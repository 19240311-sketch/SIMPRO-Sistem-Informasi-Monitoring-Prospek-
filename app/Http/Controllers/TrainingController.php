<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingMaterial;
use App\Models\TrainingQuiz;
use App\Models\UserQuizResult;
use App\Models\UserTrainingProgress;
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

        // Query all published trainings
        $trainingsQuery = Training::with(['materials', 'quizzes'])
            ->where('status', 'published')
            ->orderBy('urutan', 'asc');

        if ($tab === 'product_knowledge') {
            $trainingsQuery->where('kategori', 'product_knowledge');
        } elseif ($tab === 'sales_skill') {
            $trainingsQuery->where('kategori', 'sales_skill');
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

        // "Kursus Saya": Trainings that user has interacted with (sedang_berjalan or selesai)
        $myCourses = Training::with(['materials', 'quizzes'])
            ->whereIn('id', $userProgresses->pluck('training_id'))
            ->get()
            ->map(function ($training) use ($userProgresses, $userQuizResults) {
                $training->progress = $userProgresses->get($training->id);
                $training->latest_quiz = $userQuizResults->get($training->id);
                return $training;
            })
            ->sortByDesc(fn($t) => $t->progress->updated_at ?? now());

        // Attach progress and latest quiz to all trainings
        $allTrainings->transform(function ($training) use ($userProgresses, $userQuizResults) {
            $training->progress = $userProgresses->get($training->id);
            $training->latest_quiz = $userQuizResults->get($training->id);
            return $training;
        });

        // Summary Stats for user
        $totalCompleted = $userProgresses->where('status', 'selesai')->count();
        $totalInProgress = $userProgresses->where('status', 'sedang_berjalan')->count();
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
     * Show training detail and reader for materials.
     */
    public function show(Training $training, Request $request): View
    {
        $user = Auth::user();

        $training->load(['materials', 'quizzes']);

        // Get or initialize progress
        $progress = UserTrainingProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'training_id' => $training->id,
            ],
            [
                'completed_material_ids' => [],
                'progress_persen' => 0,
                'status' => 'sedang_berjalan',
                'waktu_mulai' => now(),
            ]
        );

        if ($progress->status === 'belum_mulai') {
            $progress->update([
                'status' => 'sedang_berjalan',
                'waktu_mulai' => $progress->waktu_mulai ?? now(),
            ]);
        }

        $completedIds = $progress->completed_material_ids ?? [];

        // Determine active material
        $materialId = $request->query('material_id');
        $activeMaterial = null;

        if ($materialId) {
            $activeMaterial = $training->materials->firstWhere('id', (int)$materialId);
        }

        if (!$activeMaterial) {
            // Find first incomplete material, or default to first material
            $activeMaterial = $training->materials->first(fn($m) => !in_array($m->id, $completedIds))
                ?? $training->materials->first();
        }

        // Get previous & next material for reader navigation
        $prevMaterial = null;
        $nextMaterial = null;

        if ($activeMaterial) {
            $currentIndex = $training->materials->search(fn($m) => $m->id === $activeMaterial->id);
            if ($currentIndex !== false) {
                $prevMaterial = $currentIndex > 0 ? $training->materials->get($currentIndex - 1) : null;
                $nextMaterial = $currentIndex < ($training->materials->count() - 1) ? $training->materials->get($currentIndex + 1) : null;
            }
        }

        $latestQuiz = UserQuizResult::where('user_id', $user->id)
            ->where('training_id', $training->id)
            ->latest('waktu_selesai')
            ->first();

        return view('trainings.show', compact(
            'training',
            'progress',
            'completedIds',
            'activeMaterial',
            'prevMaterial',
            'nextMaterial',
            'latestQuiz'
        ));
    }

    /**
     * Mark a material as complete and recalculate progress.
     */
    public function completeMaterial(Training $training, TrainingMaterial $material, Request $request): RedirectResponse
    {
        $user = Auth::user();

        $progress = UserTrainingProgress::firstOrCreate(
            ['user_id' => $user->id, 'training_id' => $training->id],
            ['completed_material_ids' => [], 'status' => 'sedang_berjalan', 'waktu_mulai' => now()]
        );

        $completed = $progress->completed_material_ids ?? [];
        if (!in_array($material->id, $completed)) {
            $completed[] = $material->id;
        }

        $totalMaterials = max(1, $training->materials()->count());
        $progressPercentage = min(100, round((count($completed) / $totalMaterials) * 100));

        $hasQuizzes = $training->quizzes()->count() > 0;
        $isCompleted = ($progressPercentage >= 100);

        $progress->update([
            'completed_material_ids' => array_values($completed),
            'progress_persen' => $progressPercentage,
            'last_material_id' => $material->id,
            'status' => ($isCompleted && !$hasQuizzes) ? 'selesai' : ($progress->status === 'selesai' ? 'selesai' : 'sedang_berjalan'),
            'waktu_selesai' => ($isCompleted && !$hasQuizzes && !$progress->waktu_selesai) ? now() : $progress->waktu_selesai,
        ]);

        // Find next material or go to quiz
        $allMaterials = $training->materials()->orderBy('urutan', 'asc')->get();
        $nextMat = $allMaterials->first(fn($m) => !in_array($m->id, $completed));

        if ($nextMat) {
            return redirect()->route('trainings.show', [$training->id, 'material_id' => $nextMat->id])
                ->with('success', 'Materi "' . $material->judul_materi . '" selesai dipelajari! Lanjut ke materi berikutnya.');
        }

        if ($hasQuizzes) {
            return redirect()->route('trainings.quiz', $training->id)
                ->with('success', 'Selamat! Semua materi telah selesai. Mari uji pemahaman Anda dengan Quiz!');
        }

        return redirect()->route('trainings.show', $training->id)
            ->with('success', 'Selamat! Anda telah menyelesaikan seluruh materi kursus ini.');
    }

    /**
     * Show quiz page for a training.
     */
    public function quiz(Training $training): View|RedirectResponse
    {
        $user = Auth::user();
        $training->load(['quizzes', 'materials']);

        if ($training->quizzes->isEmpty()) {
            return redirect()->route('trainings.show', $training->id)
                ->with('info', 'Belum ada quiz yang tersedia untuk training ini.');
        }

        $progress = UserTrainingProgress::where('user_id', $user->id)
            ->where('training_id', $training->id)
            ->first();

        $latestResult = UserQuizResult::where('user_id', $user->id)
            ->where('training_id', $training->id)
            ->latest('waktu_selesai')
            ->first();

        return view('trainings.quiz', compact('training', 'progress', 'latestResult'));
    }

    /**
     * Submit and grade the quiz.
     */
    public function submitQuiz(Training $training, Request $request): RedirectResponse
    {
        $user = Auth::user();
        $training->load('quizzes');

        $answers = $request->input('answers', []);
        $totalQuestions = $training->quizzes->count();
        $correctCount = 0;
        $gradedAnswers = [];

        foreach ($training->quizzes as $quiz) {
            $userAns = $answers[$quiz->id] ?? null;
            $isCorrect = false;

            if ($userAns !== null && trim($userAns) === trim($quiz->jawaban_benar)) {
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
        $passed = ($score >= 70);

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

        // If passed, mark training progress as 'selesai'
        if ($passed) {
            $progress = UserTrainingProgress::firstOrCreate(
                ['user_id' => $user->id, 'training_id' => $training->id],
                ['completed_material_ids' => $training->materials->pluck('id')->toArray(), 'status' => 'selesai']
            );

            $progress->update([
                'progress_persen' => 100,
                'status' => 'selesai',
                'waktu_selesai' => now(),
            ]);
        }

        $message = $passed
            ? "Lulus! Nilai Quiz Anda: {$score}/100 ({$correctCount} Benar dari {$totalQuestions} Soal). Selamat!"
            : "Nilai Quiz Anda: {$score}/100. Nilai kelulusan minimal 70. Anda dapat membaca ulang materi dan mencoba kembali.";

        return redirect()->route('trainings.quiz', $training->id)
            ->with($passed ? 'success' : 'warning', $message);
    }
}
