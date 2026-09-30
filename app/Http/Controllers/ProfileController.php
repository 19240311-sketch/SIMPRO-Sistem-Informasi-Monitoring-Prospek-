<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Models\ProspectActivity;
use App\Models\Training;
use App\Models\User;
use App\Models\WeeklyAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display user profile and activity summary.
     */
    public function show(): View
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. Activity Summary based on user role & relations
        if ($user->isAdmin()) {
            $totalProspects = Prospect::count();
            $goToDealCount = Prospect::where('tahap_data', 'deal')->count();
            $beliCount = Prospect::whereHas('status', function ($q) {
                $q->where('kode', 'deal');
            })->count();
            $phoneCount = ProspectActivity::where('jenis_aktivitas', 'phone')->count();
            $visitCount = ProspectActivity::where('jenis_aktivitas', 'visit')->count();
        } else {
            $totalProspects = $user->prospects()->count();
            $goToDealCount = $user->prospects()->where('tahap_data', 'deal')->count();
            $beliCount = $user->prospects()->whereHas('status', function ($q) {
                $q->where('kode', 'deal');
            })->count();
            $phoneCount = $user->prospectActivities()->where('jenis_aktivitas', 'phone')->count();
            $visitCount = $user->prospectActivities()->where('jenis_aktivitas', 'visit')->count();
        }

        // 2. Training & Weekly Test Summary
        $totalTrainingCount = Training::where('status', 'published')->count();
        $completedTrainingsCount = $user->trainingProgresses()->where('status', 'selesai')->count();

        $completedWeeklyAttempts = $user->weeklyAttempts()
            ->whereIn('status', ['selesai', 'waktu_habis'])
            ->get();
        $weeklyTestDoneCount = $completedWeeklyAttempts->count();
        $avgWeeklyScore = $weeklyTestDoneCount > 0 ? round($completedWeeklyAttempts->avg('nilai')) : null;

        return view('profile.show', compact(
            'user',
            'totalProspects',
            'goToDealCount',
            'beliCount',
            'phoneCount',
            'visitCount',
            'totalTrainingCount',
            'completedTrainingsCount',
            'weeklyTestDoneCount',
            'avgWeeklyScore'
        ));
    }

    /**
     * Update user profile information & avatar photo.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'no_hp' => ['nullable', 'string', 'max:25'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'foto_profil.image' => 'File foto profil harus berupa gambar.',
            'foto_profil.mimes' => 'Format foto profil yang didukung: JPG, JPEG, PNG.',
            'foto_profil.max' => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        // Handle Avatar Upload
        if ($request->hasFile('foto_profil')) {
            // Delete old photo if exists
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $path = $request->file('foto_profil')->store('avatars', 'public');
            $user->foto_profil = $path;
        }

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->no_hp = $validated['no_hp'] ?? null;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Update user password securely with current password check.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('profile.show')
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Password Anda berhasil diubah.');
    }
}
