<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingMaterial;
use App\Models\TrainingQuiz;
use App\Models\UserQuizResult;
use App\Models\UserTrainingProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TrainingController extends Controller
{
    /**
     * Display the Training Catalog & "Kursus Saya" for Sales / User.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tab = $request->query('kategori', 'all');
        $search = $request->query('q', '');

        // Query all active and published trainings
        $trainingsQuery = Training::with(['quizzes'])
            ->where('status', 'published')
            ->where('is_active', true)
            ->orderBy('urutan', 'asc');

        if ($tab !== 'all' && !empty($tab)) {
            $trainingsQuery->where('kategori', $tab);
        }

        if (!empty($search)) {
            $trainingsQuery->where(function ($q) use ($search) {
                $q->where('nama_training', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $allTrainings = $trainingsQuery->get();

        // Get user's progress mapped by training_id
        $userProgresses = UserTrainingProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('training_id');

        // Get user's latest quiz results mapped by training_id
        $userQuizResults = UserQuizResult::where('user_id', $user->id)
            ->orderBy('waktu_selesai', 'desc')
            ->get()
            ->groupBy('training_id')
            ->map(fn($group) => $group->first());

        // Attach progress and latest quiz to all trainings
        $allTrainings->transform(function ($training) use ($userProgresses, $userQuizResults) {
            $training->progress = $userProgresses->get($training->id);
            $training->latest_quiz = $userQuizResults->get($training->id);
            return $training;
        });

        // "Kursus Saya": Trainings that user has interacted with
        $myCourses = Training::with(['quizzes'])
            ->whereIn('id', $userProgresses->pluck('training_id'))
            ->get()
            ->map(function ($training) use ($userProgresses, $userQuizResults) {
                $training->progress = $userProgresses->get($training->id);
                $training->latest_quiz = $userQuizResults->get($training->id);
                return $training;
            })
            ->sortByDesc(fn($t) => $t->progress->updated_at ?? now());

        // Summary Stats for user
        $totalCompleted = $userProgresses->where('status_lulus', true)->count();
        $totalInProgress = $userProgresses->where('video_progress', '>', 0)->where('status_lulus', false)->count();
        $avgScore = $userQuizResults->count() > 0
            ? round($userQuizResults->avg('nilai'))
            : 0;

        return view('trainings.index', compact(
            'allTrainings',
            'myCourses',
            'tab',
            'search',
            'totalCompleted',
            'totalInProgress',
            'avgScore'
        ));
    }

    /**
     * Show Video-based learning room for a training module.
     */
    public function show(Training $training, Request $request): View
    {
        $user = Auth::user();
        $training->load(['quizzes']);

        // Get or initialize progress
        $progress = UserTrainingProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'training_id' => $training->id,
            ],
            [
                'video_progress' => 0,
                'video_completed' => false,
                'waktu_mulai_video' => now(),
                'status' => 'sedang_berjalan',
                'tanggal_terakhir_belajar' => now(),
            ]
        );

        if (!$progress->waktu_mulai_video) {
            $progress->update([
                'waktu_mulai_video' => now(),
                'tanggal_terakhir_belajar' => now(),
            ]);
        }

        $latestQuiz = UserQuizResult::where('user_id', $user->id)
            ->where('training_id', $training->id)
            ->latest('waktu_selesai')
            ->first();

        return view('trainings.show', compact(
            'training',
            'progress',
            'latestQuiz'
        ));
    }

    /**
     * Realtime AJAX progress tracker from YouTube IFrame Player API.
     */
    public function updateVideoProgress(Training $training, Request $request): JsonResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'progress' => 'required|numeric|min:0|max:100',
            'completed' => 'nullable|boolean',
            'current_time' => 'nullable|numeric',
            'duration' => 'nullable|numeric',
        ]);

        $incomingProgress = round($validated['progress']);
        $isCompleted = !empty($validated['completed']) || ($incomingProgress >= 100);

        $progress = UserTrainingProgress::firstOrCreate(
            ['user_id' => $user->id, 'training_id' => $training->id],
            [
                'waktu_mulai_video' => now(),
                'status' => 'sedang_berjalan',
            ]
        );

        // Keep highest progress watched
        $newProgress = max($progress->video_progress ?? 0, $incomingProgress);
        $finalCompleted = ($progress->video_completed || $isCompleted || $newProgress >= 100);

        $updates = [
            'video_progress' => $newProgress,
            'progress_persen' => $newProgress,
            'tanggal_terakhir_belajar' => now(),
        ];

        if ($finalCompleted && !$progress->video_completed) {
            $updates['video_completed'] = true;
            $updates['waktu_selesai_video'] = now();
            if (!$progress->status_lulus) {
                $updates['status'] = 'video_selesai';
            }
        }

        $progress->update($updates);

        return response()->json([
            'success' => true,
            'video_progress' => $newProgress,
            'video_completed' => $finalCompleted,
            'can_take_quiz' => $finalCompleted,
        ]);
    }

    /**
     * Backward compatibility: Mark a material as complete.
     */
    public function completeMaterial(Training $training, TrainingMaterial $material, Request $request): RedirectResponse
    {
        return redirect()->route('trainings.show', $training->id);
    }

    /**
     * Show quiz page for a training module (Security check: Video must be 100% completed).
     */
    public function quiz(Training $training): View|RedirectResponse
    {
        $user = Auth::user();
        $training->load(['quizzes']);

        if ($training->quizzes->isEmpty()) {
            return redirect()->route('trainings.show', $training->id)
                ->with('info', 'Belum ada quiz yang tersedia untuk materi ini.');
        }

        $progress = UserTrainingProgress::where('user_id', $user->id)
            ->where('training_id', $training->id)
            ->first();

        // Security check: sales must watch 100% video before taking quiz
        if (!$progress || (!$progress->video_completed && ($progress->video_progress < 100))) {
            return redirect()->route('trainings.show', $training->id)
                ->with('warning', 'Harap selesaikan menonton video hingga 100% terlebih dahulu sebelum mengerjakan kuis pemahaman.');
        }

        $latestResult = UserQuizResult::where('user_id', $user->id)
            ->where('training_id', $training->id)
            ->latest('waktu_selesai')
            ->first();

        return view('trainings.quiz', compact('training', 'progress', 'latestResult'));
    }

    /**
     * Submit and grade the quiz against passing grade.
     */
    public function submitQuiz(Training $training, Request $request): RedirectResponse
    {
        $user = Auth::user();
        $training->load('quizzes');

        // Security check: video must be completed
        $progress = UserTrainingProgress::where('user_id', $user->id)
            ->where('training_id', $training->id)
            ->first();

        if (!$progress || (!$progress->video_completed && ($progress->video_progress < 100))) {
            return redirect()->route('trainings.show', $training->id)
                ->with('warning', 'Akses kuis ditolak: Video pembelajaran belum diselesaikan 100%.');
        }

        $answers = $request->input('answers', []);
        $totalQuestions = $training->quizzes->count();
        $correctCount = 0;
        $gradedAnswers = [];

        foreach ($training->quizzes as $quiz) {
            $userAns = $answers[$quiz->id] ?? null;
            $isCorrect = false;

            if ($userAns !== null && trim((string)$userAns) === trim((string)$quiz->jawaban_benar)) {
                $isCorrect = true;
                $correctCount++;
            }

            $gradedAnswers[$quiz->id] = [
                'user_answer' => $userAns,
                'correct_answer' => $quiz->jawaban_benar,
                'is_correct' => $isCorrect,
                'explanation' => $quiz->penjelasan,
            ];
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 100;
        $passingGrade = $training->passing_grade ?: 80;
        $passed = ($score >= $passingGrade);

        // Save Quiz Result
        $result = UserQuizResult::create([
            'user_id' => $user->id,
            'training_id' => $training->id,
            'nilai' => $score,
            'jumlah_benar' => $correctCount,
            'total_soal' => $totalQuestions,
            'status_lulus' => $passed,
            'jawaban_user' => $gradedAnswers,
            'waktu_selesai' => now(),
        ]);

        // Update progress in database
        $newAttempts = ($progress->jumlah_percobaan_kuis ?? 0) + 1;
        $updates = [
            'nilai_kuis' => $score,
            'jumlah_percobaan_kuis' => $newAttempts,
            'tanggal_terakhir_belajar' => now(),
        ];

        if ($passed) {
            $updates['status_lulus'] = true;
            $updates['status'] = 'selesai';
            $updates['tanggal_lulus'] = now();
            $updates['waktu_selesai'] = now();
            $updates['progress_persen'] = 100;
        } else {
            $updates['status'] = 'belum_lulus';
        }

        $progress->update($updates);

        $flashType = $passed ? 'quiz_passed' : 'quiz_failed';
        $message = $passed
            ? "Selamat! Anda dinyatakan LULUS dengan nilai {$score}% (Passing Grade: {$passingGrade}%)."
            : "Nilai Anda {$score}% belum mencapai batas kelulusan {$passingGrade}%. Silakan ulangi kuis.";

        return redirect()->route('trainings.quiz', $training->id)
            ->with($flashType, true)
            ->with('score', $score)
            ->with('correct_count', $correctCount)
            ->with('total_questions', $totalQuestions)
            ->with('passing_grade', $passingGrade)
            ->with('message', $message);
    }
}
