<?php

namespace App\Http\Controllers;

use App\Models\WeeklyAnswer;
use App\Models\WeeklyAttempt;
use App\Models\WeeklyQuestion;
use App\Models\WeeklyTest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WeeklyTestController extends Controller
{
    /**
     * Display Weekly Tests list for Sales (Available Tests & History).
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Load all published weekly tests
        $tests = WeeklyTest::with(['questions', 'attempts'])
            ->where('status', 'published')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Get current user's attempts mapped by test_id
        $userAttempts = WeeklyAttempt::where('user_id', $user->id)
            ->get()
            ->keyBy('weekly_test_id');

        // Attach attempt to each test
        $tests->transform(function ($test) use ($userAttempts) {
            $test->user_attempt = $userAttempts->get($test->id);
            return $test;
        });

        // Split into Active Available Tests vs Completed / History Tests
        $activeTests = $tests->filter(function ($test) {
            $hasCompleted = $test->user_attempt && in_array($test->user_attempt->status, ['selesai', 'waktu_habis']);
            return !$hasCompleted && $test->isActive();
        });

        $historyTests = $tests->filter(function ($test) {
            $hasCompleted = $test->user_attempt && in_array($test->user_attempt->status, ['selesai', 'waktu_habis']);
            return $hasCompleted || $test->isExpired();
        });

        // Metrics for summary header
        $totalCompleted = $userAttempts->whereIn('status', ['selesai', 'waktu_habis'])->count();
        $totalAvailable = $activeTests->count();
        $avgScore = $userAttempts->whereIn('status', ['selesai', 'waktu_habis'])->count() > 0
            ? round($userAttempts->whereIn('status', ['selesai', 'waktu_habis'])->avg('nilai'))
            : 0;

        return view('weekly_tests.index', compact(
            'tests',
            'activeTests',
            'historyTests',
            'totalCompleted',
            'totalAvailable',
            'avgScore'
        ));
    }

    /**
     * Start / Continue taking the weekly test.
     */
    public function take(WeeklyTest $weeklyTest): View|RedirectResponse
    {
        $user = Auth::user();

        // Check if test is active
        if ($weeklyTest->status !== 'published') {
            return redirect()->route('weekly-tests.index')
                ->with('error', 'Tes ini belum dipublikasikan.');
        }

        // Check if user already finished
        $existingAttempt = WeeklyAttempt::where('weekly_test_id', $weeklyTest->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingAttempt && in_array($existingAttempt->status, ['selesai', 'waktu_habis'])) {
            return redirect()->route('weekly-tests.result', $weeklyTest->id)
                ->with('info', 'Anda telah menyelesaikan tes mingguan ini.');
        }

        if ($weeklyTest->isExpired() && !$existingAttempt) {
            return redirect()->route('weekly-tests.index')
                ->with('warning', 'Periode pengerjaan tes mingguan ini telah berakhir.');
        }

        if ($weeklyTest->isUpcoming()) {
            return redirect()->route('weekly-tests.index')
                ->with('info', 'Tes mingguan ini baru akan dibuka pada ' . $weeklyTest->tanggal_mulai->format('d M Y, H:i'));
        }

        // Create or resume attempt
        $attempt = WeeklyAttempt::firstOrCreate(
            [
                'weekly_test_id' => $weeklyTest->id,
                'user_id' => $user->id,
            ],
            [
                'waktu_mulai' => now(),
                'status' => 'sedang_mengerjakan',
                'total_soal' => $weeklyTest->questions()->count(),
            ]
        );

        // Calculate remaining seconds
        $endTime = $attempt->waktu_mulai->copy()->addMinutes($weeklyTest->durasi_menit);
        $remainingSeconds = max(0, $endTime->timestamp - now()->timestamp);

        // If time already expired while in 'sedang_mengerjakan'
        if ($remainingSeconds <= 0 && $attempt->status === 'sedang_mengerjakan') {
            $this->finalizeAttempt($attempt, []);
            return redirect()->route('weekly-tests.result', $weeklyTest->id)
                ->with('warning', 'Waktu pengerjaan tes telah habis.');
        }

        $weeklyTest->load('questions');

        // Existing saved answers if any
        $savedAnswers = WeeklyAnswer::where('weekly_attempt_id', $attempt->id)
            ->pluck('jawaban_user', 'weekly_question_id')
            ->toArray();

        return view('weekly_tests.take', compact('weeklyTest', 'attempt', 'remainingSeconds', 'savedAnswers'));
    }

    /**
     * Submit weekly test answers.
     */
    public function submit(WeeklyTest $weeklyTest, Request $request): RedirectResponse
    {
        $user = Auth::user();

        $attempt = WeeklyAttempt::where('weekly_test_id', $weeklyTest->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (in_array($attempt->status, ['selesai', 'waktu_habis'])) {
            return redirect()->route('weekly-tests.result', $weeklyTest->id);
        }

        $answers = $request->input('answers', []);
        $this->finalizeAttempt($attempt, $answers);

        $passed = $attempt->status_lulus;
        $msg = $passed
            ? "Selamat! Anda LULUS dengan nilai {$attempt->nilai}/100 ({$attempt->jumlah_benar} Benar dari {$attempt->total_soal} Soal)."
            : "Tes selesai. Nilai Anda: {$attempt->nilai}/100 (Batas kelulusan: {$weeklyTest->nilai_minimum}).";

        return redirect()->route('weekly-tests.result', $weeklyTest->id)
            ->with($passed ? 'success' : 'warning', $msg);
    }

    /**
     * Display test result and answer explanations.
     */
    public function result(WeeklyTest $weeklyTest): View|RedirectResponse
    {
        $user = Auth::user();

        $attempt = WeeklyAttempt::where('weekly_test_id', $weeklyTest->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$attempt) {
            return redirect()->route('weekly-tests.index')
                ->with('error', 'Anda belum mengerjakan tes ini.');
        }

        $weeklyTest->load('questions');
        $userAnswers = WeeklyAnswer::where('weekly_attempt_id', $attempt->id)
            ->get()
            ->keyBy('weekly_question_id');

        return view('weekly_tests.result', compact('weeklyTest', 'attempt', 'userAnswers'));
    }

    /**
     * Helper to calculate score and save answers.
     */
    private function finalizeAttempt(WeeklyAttempt $attempt, array $answers): void
    {
        $test = $attempt->test;
        $questions = $test->questions;
        $totalQuestions = $questions->count();
        $correctCount = 0;

        foreach ($questions as $question) {
            $userAns = $answers[$question->id] ?? null;
            $isCorrect = ($userAns && strtoupper($userAns) === strtoupper($question->jawaban_benar));

            if ($isCorrect) {
                $correctCount++;
            }

            WeeklyAnswer::updateOrCreate(
                [
                    'weekly_attempt_id' => $attempt->id,
                    'weekly_question_id' => $question->id,
                ],
                [
                    'jawaban_user' => $userAns,
                    'is_correct' => $isCorrect,
                ]
            );
        }

        $wrongCount = $totalQuestions - $correctCount;
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $passed = ($score >= $test->nilai_minimum);

        $attempt->update([
            'waktu_selesai' => now(),
            'nilai' => $score,
            'jumlah_benar' => $correctCount,
            'jumlah_salah' => $wrongCount,
            'total_soal' => $totalQuestions,
            'status' => 'selesai',
            'status_lulus' => $passed,
        ]);
    }
}
