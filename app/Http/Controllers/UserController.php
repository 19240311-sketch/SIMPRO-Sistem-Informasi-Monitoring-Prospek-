<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount('prospects')->orderBy('peran')->orderBy('nama')->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,sales'],
        ]);

        User::create([
            'nama' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'peran' => $validated['role'],
            'status_aktif' => true,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna baru berhasil didaftarkan!');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,sales'],
            'is_active' => ['required', 'boolean'],
        ]);

        $data = [
            'nama' => $validated['name'],
            'email' => $validated['email'],
            'peran' => $validated['role'],
            'status_aktif' => (bool) $validated['is_active'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui!');
    }
}
