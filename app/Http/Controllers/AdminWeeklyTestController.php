<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WeeklyAnswer;
use App\Models\WeeklyAttempt;
use App\Models\WeeklyQuestion;
use App\Models\WeeklyTest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminWeeklyTestController extends Controller
{
    /**
     * Admin index: List all weekly tests with participation summary.
     */
    public function index(Request $request): View
    {
        $tests = WeeklyTest::with(['questions', 'attempts.user'])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $salesCount = User::where('peran', 'sales')->where('status_aktif', true)->count();

        // Calculate statistics for each test
        $tests->transform(function ($test) use ($salesCount) {
            $completedAttempts = $test->attempts->whereIn('status', ['selesai', 'waktu_habis']);
            $test->total_sales = $salesCount;
            $test->completed_count = $completedAttempts->count();
            $test->pending_count = max(0, $salesCount - $completedAttempts->count());
            $test->avg_score = $completedAttempts->count() > 0 ? round($completedAttempts->avg('nilai')) : 0;
            $test->passed_count = $completedAttempts->where('status_lulus', true)->count();
            $test->failed_count = $completedAttempts->where('status_lulus', false)->count();
            return $test;
        });

        return view('admin.weekly_tests.index', compact('tests', 'salesCount'));
    }

    /**
     * Store new weekly test.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_tes' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'durasi_menit' => 'required|integer|min:1|max:120',
            'nilai_minimum' => 'required|integer|min:1|max:100',
            'status' => 'required|in:published,draft,archived',
        ]);

        $validated['slug'] = Str::slug($validated['nama_tes']) . '-' . Str::random(4);

        $test = WeeklyTest::create($validated);

        return redirect()->route('admin.weekly-tests.manage', $test->id)
            ->with('success', 'Tes Mingguan baru berhasil dibuat! Silakan tambahkan soal-soal tes.');
    }

    /**
     * Manage questions & edit test details.
     */
    public function manage(WeeklyTest $weeklyTest): View
    {
        $weeklyTest->load(['questions', 'attempts.user', 'attempts.answers']);

        $salesUsers = User::where('peran', 'sales')->where('status_aktif', true)->get();
        $attemptsMap = $weeklyTest->attempts->keyBy('user_id');

        $salesMonitoring = $salesUsers->map(function ($sales) use ($attemptsMap, $weeklyTest) {
            $attempt = $attemptsMap->get($sales->id);
            return [
                'user' => $sales,
                'attempt' => $attempt,
                'status' => $attempt ? $attempt->status_label : ($weeklyTest->isExpired() ? 'Tidak Mengerjakan' : 'Belum Mengerjakan'),
                'score' => $attempt ? $attempt->nilai : null,
                'duration' => $attempt ? $attempt->duration_spent : '-',
                'submitted_at' => $attempt ? $attempt->waktu_selesai : null,
            ];
        });

        return view('admin.weekly_tests.manage', compact('weeklyTest', 'salesMonitoring'));
    }

    /**
     * Update weekly test basic settings.
     */
    public function update(Request $request, WeeklyTest $weeklyTest): RedirectResponse
    {
        $validated = $request->validate([
            'nama_tes' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'durasi_menit' => 'required|integer|min:1|max:120',
            'nilai_minimum' => 'required|integer|min:1|max:100',
            'status' => 'required|in:published,draft,archived',
        ]);

        $weeklyTest->update($validated);

        return back()->with('success', 'Pengaturan Tes Mingguan berhasil diperbarui.');
    }

    /**
     * Delete weekly test.
     */
    public function destroy(WeeklyTest $weeklyTest): RedirectResponse
    {
        $nama = $weeklyTest->nama_tes;
        $weeklyTest->delete();

        return redirect()->route('admin.weekly-tests.index')
            ->with('success', "Tes Mingguan \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Add question to weekly test.
     */
    public function storeQuestion(Request $request, WeeklyTest $weeklyTest): RedirectResponse
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'penjelasan' => 'nullable|string',
        ]);

        $maxUrutan = $weeklyTest->questions()->max('urutan') ?? 0;
        $validated['urutan'] = $maxUrutan + 1;
        $validated['weekly_test_id'] = $weeklyTest->id;

        WeeklyQuestion::create($validated);

        return back()->with('success', 'Soal Tes Mingguan berhasil ditambahkan!');
    }

    /**
     * Delete question.
     */
    public function destroyQuestion(WeeklyTest $weeklyTest, WeeklyQuestion $question): RedirectResponse
    {
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }
}
