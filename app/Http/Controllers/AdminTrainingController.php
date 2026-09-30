<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingMaterial;
use App\Models\TrainingQuiz;
use App\Models\User;
use App\Models\UserQuizResult;
use App\Models\UserTrainingProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminTrainingController extends Controller
{
    /**
     * Admin index: List all trainings & sales completion overview.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'modules'); // 'modules' or 'sales_report'

        $trainings = Training::withCount(['materials', 'quizzes', 'userProgresses'])
            ->orderBy('urutan', 'asc')
            ->get();

        // Sales Report Data
        $salesUsers = User::where('peran', 'sales')
            ->with(['trainingProgresses.training', 'quizResults'])
            ->get();

        $salesProgressData = $salesUsers->map(function ($sales) use ($trainings) {
            $completedCount = $sales->trainingProgresses->where('status', 'selesai')->count();
            $inProgressCount = $sales->trainingProgresses->where('status', 'sedang_berjalan')->count();
            $totalTrainings = max(1, $trainings->count());
            $completionRate = round(($completedCount / $totalTrainings) * 100);

            $latestQuiz = $sales->quizResults->sortByDesc('waktu_selesai')->first();
            $avgQuizScore = $sales->quizResults->count() > 0 ? round($sales->quizResults->avg('nilai')) : 0;

            return [
                'user' => $sales,
                'completed_count' => $completedCount,
                'in_progress_count' => $inProgressCount,
                'completion_rate' => $completionRate,
                'latest_quiz' => $latestQuiz,
                'avg_quiz_score' => $avgQuizScore,
                'progresses' => $sales->trainingProgresses->keyBy('training_id'),
            ];
        });

        $totalTrainingsCount = $trainings->count();
        $totalMaterialsCount = TrainingMaterial::count();
        $totalQuizzesCount = TrainingQuiz::count();
        $totalCompletions = UserTrainingProgress::where('status', 'selesai')->count();

        return view('admin.trainings.index', compact(
            'trainings',
            'salesProgressData',
            'activeTab',
            'totalTrainingsCount',
            'totalMaterialsCount',
            'totalQuizzesCount',
            'totalCompletions'
        ));
    }

    /**
     * Store new training.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_training' => 'required|string|max:255',
            'kategori' => 'required|in:product_knowledge,sales_skill',
            'deskripsi' => 'required|string',
            'estimasi_waktu' => 'nullable|string|max:50',
            'status' => 'required|in:published,draft',
        ]);

        $maxUrutan = Training::max('urutan') ?? 0;
        $validated['urutan'] = $maxUrutan + 1;
        $validated['slug'] = Str::slug($validated['nama_training']) . '-' . Str::random(4);

        $training = Training::create($validated);

        return redirect()->route('admin.trainings.manage', $training->id)
            ->with('success', 'Modul Training baru berhasil ditambahkan! Silakan tambahkan materi dan kuis.');
    }

    /**
     * Show Manage page (Materials & Quizzes) for a training.
     */
    public function manage(Training $training): View
    {
        $training->load(['materials', 'quizzes']);
        return view('admin.trainings.manage', compact('training'));
    }

    /**
     * Update training basic info.
     */
    public function update(Request $request, Training $training): RedirectResponse
    {
        $validated = $request->validate([
            'nama_training' => 'required|string|max:255',
            'kategori' => 'required|in:product_knowledge,sales_skill',
            'deskripsi' => 'required|string',
            'estimasi_waktu' => 'nullable|string|max:50',
            'status' => 'required|in:published,draft',
        ]);

        $training->update($validated);

        return back()->with('success', 'Informasi modul training berhasil diperbarui.');
    }

    /**
     * Delete training.
     */
    public function destroy(Training $training): RedirectResponse
    {
        $nama = $training->nama_training;
        $training->delete();

        return redirect()->route('admin.trainings.index')
            ->with('success', "Modul training \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Add material to a training.
     */
    public function storeMaterial(Request $request, Training $training): RedirectResponse
    {
        $validated = $request->validate([
            'judul_materi' => 'required|string|max:255',
            'isi_materi' => 'required|string',
        ]);

        $maxUrutan = $training->materials()->max('urutan') ?? 0;
        $validated['urutan'] = $maxUrutan + 1;
        $validated['training_id'] = $training->id;

        TrainingMaterial::create($validated);

        return back()->with('success', 'Materi training berhasil ditambahkan!');
    }

    /**
     * Update material.
     */
    public function updateMaterial(Request $request, Training $training, TrainingMaterial $material): RedirectResponse
    {
        $validated = $request->validate([
            'judul_materi' => 'required|string|max:255',
            'isi_materi' => 'required|string',
        ]);

        $material->update($validated);

        return back()->with('success', 'Materi training berhasil diperbarui!');
    }

    /**
     * Delete material.
     */
    public function destroyMaterial(Training $training, TrainingMaterial $material): RedirectResponse
    {
        $material->delete();
        return back()->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Add quiz question to a training.
     */
    public function storeQuiz(Request $request, Training $training): RedirectResponse
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban_benar' => 'required|string',
            'penjelasan' => 'nullable|string',
        ]);

        $choices = [
            $validated['pilihan_a'],
            $validated['pilihan_b'],
            $validated['pilihan_c'],
            $validated['pilihan_d'],
        ];

        $correctAnswer = match ($validated['jawaban_benar']) {
            'A' => $validated['pilihan_a'],
            'B' => $validated['pilihan_b'],
            'C' => $validated['pilihan_c'],
            'D' => $validated['pilihan_d'],
            default => $validated['pilihan_a'],
        };

        $maxUrutan = $training->quizzes()->max('urutan') ?? 0;

        TrainingQuiz::create([
            'training_id' => $training->id,
            'pertanyaan' => $validated['pertanyaan'],
            'pilihan_jawaban' => $choices,
            'jawaban_benar' => $correctAnswer,
            'penjelasan' => $validated['penjelasan'] ?? null,
            'urutan' => $maxUrutan + 1,
        ]);

        return back()->with('success', 'Pertanyaan Quiz berhasil ditambahkan!');
    }

    /**
     * Delete quiz question.
     */
    public function destroyQuiz(Training $training, TrainingQuiz $quiz): RedirectResponse
    {
        $quiz->delete();
        return back()->with('success', 'Soal Quiz berhasil dihapus.');
    }
}
