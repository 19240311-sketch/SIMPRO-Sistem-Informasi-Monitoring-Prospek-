@extends('layouts.app')

@section('title', 'Master Sumber Prospek')
@section('page-title', 'Kelola Sumber Asal Prospek')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <!-- Add Form -->
    <div class="rounded-xl bg-surface p-6 border border-hairline shadow-elev-1 lg:col-span-1 h-fit">
        <h2 class="text-heading-md font-semibold text-ink mb-2">Tambah Sumber Baru</h2>
        <p class="text-caption text-mute mb-5">Tambahkan media/sumber masuknya data prospek konsumen.</p>

        <form action="{{ route('master.sources.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="mb-xs block text-body-sm font-medium text-ink">Nama Sumber <span class="text-error">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                       placeholder="Contoh: TikTok Ads"
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <button type="submit" class="btn-primary w-full">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Simpan Sumber Baru
            </button>
        </form>
    </div>

    <!-- Sources List -->
    <div class="rounded-xl bg-surface border border-hairline shadow-elev-1 lg:col-span-2 overflow-hidden">
        <div class="p-5 border-b border-hairline flex items-center justify-between">
            <div>
                <h2 class="text-heading-md font-semibold text-ink">Daftar Sumber Prospek</h2>
                <p class="text-caption text-mute">Total {{ $sources->count() }} sumber terdaftar</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-body-sm">
                <thead>
                    <tr class="border-b border-hairline bg-canvas-soft text-caption font-semibold uppercase tracking-wider text-mute">
                        <th class="px-6 py-3.5">Nama Sumber</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($sources as $s)
                        <tr class="hover:bg-canvas-soft transition">
                            <td class="px-6 py-4 font-semibold text-ink">{{ $s->name }}</td>
                            <td class="px-6 py-4">
                                @if($s->is_active)
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
                                        onclick="openEditSourceModal({{ $s->id }}, '{{ addslashes($s->name) }}', {{ $s->is_active ? 1 : 0 }})"
                                        class="text-caption font-semibold text-primary hover:text-primary-deep mr-3 inline-flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                    Ubah
                                </button>
                                <form action="{{ route('master.sources.update', $s->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $s->name }}">
                                    <input type="hidden" name="is_active" value="{{ $s->is_active ? 0 : 1 }}">
                                    <button type="submit" class="text-caption font-semibold {{ $s->is_active ? 'text-mute hover:text-error' : 'text-success-ink hover:underline' }}">
                                        {{ $s->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-mute">Belum ada data sumber.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Source Modal Dialog -->
<div id="editSourceModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm hidden">
    <div class="w-full max-w-md rounded-2xl bg-surface p-6 shadow-elev-4 border border-hairline transition-all">
        <div class="flex items-center justify-between border-b border-hairline pb-4 mb-4">
            <h3 class="text-heading-md font-bold text-ink">Ubah Data Sumber Prospek</h3>
            <button type="button" onclick="closeEditSourceModal()" class="text-mute hover:text-ink">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form id="editSourceForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_source_name" class="mb-xs block text-body-sm font-medium text-ink">Nama Sumber <span class="text-error">*</span></label>
                <input id="edit_source_name" name="name" type="text" required
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div>
                <label for="edit_source_is_active" class="mb-xs block text-body-sm font-medium text-ink">Status Aktif</label>
                <select id="edit_source_is_active" name="is_active" class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
                    <option value="1">Aktif (Tampil pada Pilihan Prospek)</option>
                    <option value="0">Nonaktif (Disembunyikan)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-hairline">
                <button type="button" onclick="closeEditSourceModal()" class="btn-secondary">
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
    function openEditSourceModal(id, name, isActive) {
        const modal = document.getElementById('editSourceModal');
        const form = document.getElementById('editSourceForm');
        form.action = `/master/sources/${id}`;
        document.getElementById('edit_source_name').value = name;
        document.getElementById('edit_source_is_active').value = isActive;
        modal.classList.remove('hidden');
    }

    function closeEditSourceModal() {
        document.getElementById('editSourceModal').classList.add('hidden');
    }
</script>
@endsection
