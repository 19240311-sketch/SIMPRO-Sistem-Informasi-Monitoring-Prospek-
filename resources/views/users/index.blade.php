@extends('layouts.app')

@section('title', 'Kelola Pengguna Sistem')
@section('page-title', 'Kelola Akun Pengguna')

@section('content')
<div class="space-y-6">

    <!-- Users List -->
    <div class="rounded-xl bg-surface border border-hairline shadow-elev-1 overflow-hidden">
        <div class="p-5 border-b border-hairline flex items-center justify-between">
            <div>
                <h2 class="text-heading-md font-semibold text-ink">Daftar Akun Pengguna</h2>
                <p class="text-caption text-mute">Total {{ $users->count() }} akun terdaftar</p>
            </div>
            <button type="button" onclick="openCreateUserModal()" class="btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Pengguna</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-body-sm">
                <thead>
                    <tr class="border-b border-hairline bg-canvas-soft text-caption font-semibold uppercase tracking-wider text-mute">
                        <th class="px-6 py-3.5">Pengguna</th>
                        <th class="px-6 py-3.5">Role</th>
                        <th class="px-6 py-3.5">Prospek Dikelola</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($users as $u)
                        <tr class="hover:bg-canvas-soft transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-soft text-primary-ink font-semibold text-xs border border-primary-softer">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-ink block">{{ $u->name }}</span>
                                        <span class="text-caption text-mute">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($u->role === 'admin')
                                    <span class="inline-flex items-center rounded-pill bg-indigo-50 px-2.5 py-0.5 text-caption font-semibold text-indigo-700 border border-indigo-200">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-pill bg-primary-soft px-2.5 py-0.5 text-caption font-semibold text-primary-ink border border-primary-softer">
                                        Sales
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold tabular-nums text-ink">
                                {{ $u->prospects_count }}
                            </td>
                            <td class="px-6 py-4">
                                @if($u->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-pill bg-success-soft px-2.5 py-0.5 text-caption font-medium text-success-ink border border-green-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-success"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-pill bg-slate-100 px-2.5 py-0.5 text-caption font-medium text-slate-700 border border-slate-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button type="button"
                                        onclick="openEditUserModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}', '{{ $u->role }}', {{ $u->is_active ? 1 : 0 }})"
                                        class="text-caption font-semibold text-primary hover:text-primary-deep mr-3 inline-flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                    Ubah
                                </button>
                                @if(auth()->id() !== $u->id)
                                    <form action="{{ route('users.update', $u->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $u->name }}">
                                        <input type="hidden" name="email" value="{{ $u->email }}">
                                        <input type="hidden" name="role" value="{{ $u->role }}">
                                        <input type="hidden" name="is_active" value="{{ $u->is_active ? 0 : 1 }}">
                                        <button type="submit" class="text-caption font-semibold {{ $u->is_active ? 'text-mute hover:text-error' : 'text-success-ink hover:underline' }}">
                                            {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-caption text-mute italic">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-mute">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create User Modal Dialog -->
<div id="createUserModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm hidden">
    <div class="w-full max-w-md rounded-2xl bg-surface p-6 shadow-elev-4 border border-hairline transition-all">
        <div class="flex items-center justify-between border-b border-hairline pb-4 mb-4">
            <div>
                <h3 class="text-heading-md font-bold text-ink">Tambah Pengguna Baru</h3>
                <p class="text-caption text-mute mt-1">Daftarkan akun Admin atau Sales penanggung jawab prospek.</p>
            </div>
            <button type="button" onclick="closeCreateUserModal()" class="text-mute hover:text-ink">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="mb-xs block text-body-sm font-medium text-ink">Nama Lengkap <span class="text-error">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                       placeholder="Contoh: Rian Pratama"
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div>
                <label for="email" class="mb-xs block text-body-sm font-medium text-ink">Alamat Email (Username) <span class="text-error">*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       placeholder="rian@simpro.com"
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div>
                <label for="password" class="mb-xs block text-body-sm font-medium text-ink">Kata Sandi <span class="text-error">*</span></label>
                <input id="password" name="password" type="password" required
                       placeholder="Minimal 6 karakter"
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div>
                <label for="role" class="mb-xs block text-body-sm font-medium text-ink">Peran (Role) <span class="text-error">*</span></label>
                <select id="role" name="role" required
                        class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
                    <option value="sales">Sales (Pengelola & Follow-up Prospek)</option>
                    <option value="admin">Admin (Monitoring & Master Data)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-hairline">
                <button type="button" onclick="closeCreateUserModal()" class="btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Daftarkan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal Dialog -->
<div id="editUserModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm hidden">
    <div class="w-full max-w-md rounded-2xl bg-surface p-6 shadow-elev-4 border border-hairline transition-all">
        <div class="flex items-center justify-between border-b border-hairline pb-4 mb-4">
            <h3 class="text-heading-md font-bold text-ink">Ubah Data Pengguna</h3>
            <button type="button" onclick="closeEditUserModal()" class="text-mute hover:text-ink">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form id="editUserForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_user_name" class="mb-xs block text-body-sm font-medium text-ink">Nama Lengkap <span class="text-error">*</span></label>
                <input id="edit_user_name" name="name" type="text" required
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div>
                <label for="edit_user_email" class="mb-xs block text-body-sm font-medium text-ink">Alamat Email <span class="text-error">*</span></label>
                <input id="edit_user_email" name="email" type="email" required
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div>
                <label for="edit_user_password" class="mb-xs block text-body-sm font-medium text-ink">Ganti Kata Sandi (Opsional)</label>
                <input id="edit_user_password" name="password" type="password"
                       placeholder="Kosongkan jika tidak ingin mengubah"
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div>
                <label for="edit_user_role" class="mb-xs block text-body-sm font-medium text-ink">Peran (Role) <span class="text-error">*</span></label>
                <select id="edit_user_role" name="role" required class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
                    <option value="sales">Sales (Pengelola & Follow-up Prospek)</option>
                    <option value="admin">Admin (Monitoring & Master Data)</option>
                </select>
            </div>

            <div>
                <label for="edit_user_is_active" class="mb-xs block text-body-sm font-medium text-ink">Status Akun</label>
                <select id="edit_user_is_active" name="is_active" class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
                    <option value="1">Aktif (Dapat Login)</option>
                    <option value="0">Nonaktif (Akses Ditangguhkan)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-hairline">
                <button type="button" onclick="closeEditUserModal()" class="btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateUserModal() {
        document.getElementById('createUserModal').classList.remove('hidden');
    }

    function closeCreateUserModal() {
        document.getElementById('createUserModal').classList.add('hidden');
    }

    function openEditUserModal(id, name, email, role, isActive) {
        const modal = document.getElementById('editUserModal');
        const form = document.getElementById('editUserForm');
        form.action = `/users/${id}`;
        document.getElementById('edit_user_name').value = name;
        document.getElementById('edit_user_email').value = email;
        document.getElementById('edit_user_password').value = '';
        document.getElementById('edit_user_role').value = role;
        document.getElementById('edit_user_is_active').value = isActive;
        modal.classList.remove('hidden');
    }

    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }
</script>
@endsection
