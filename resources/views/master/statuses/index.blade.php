@extends('layouts.app')

@section('title', 'Master Status Prospek')
@section('page-title', 'Kelola Status Perkembangan Prospek')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <!-- Add Form -->
    <div class="rounded-xl bg-surface p-6 border border-hairline shadow-elev-1 lg:col-span-1 h-fit">
        <h2 class="text-heading-md font-semibold text-ink mb-2">Tambah Status Baru</h2>
        <p class="text-caption text-mute mb-5">Atur tahapan status proses follow-up prospek dealer.</p>

        <form action="{{ route('master.statuses.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="code" class="mb-xs block text-body-sm font-medium text-ink">Kode Unik (Slug) <span class="text-error">*</span></label>
                <input id="code" name="code" type="text" value="{{ old('code') }}" required
                       placeholder="misal: spk_terbit"
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div>
                <label for="name" class="mb-xs block text-body-sm font-medium text-ink">Nama Tampilan Status <span class="text-error">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                       placeholder="misal: SPK Terbit"
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div>
                <label for="sort_order" class="mb-xs block text-body-sm font-medium text-ink">Urutan Tahapan <span class="text-error">*</span></label>
                <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', 6) }}" required min="1"
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div class="flex items-center gap-2">
                <input id="is_final" name="is_final" type="checkbox" value="1"
                       class="h-4 w-4 rounded border-hairline-strong text-primary focus:ring-primary">
                <label for="is_final" class="text-body-sm text-body">
                    Merupakan Status Akhir (Final Stage)
                </label>
            </div>

            <button type="submit" class="btn-primary w-full">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Simpan Status Baru
            </button>
        </form>
    </div>

    <!-- Statuses List -->
    <div class="rounded-xl bg-surface border border-hairline shadow-elev-1 lg:col-span-2 overflow-hidden">
        <div class="p-5 border-b border-hairline flex items-center justify-between">
            <div>
                <h2 class="text-heading-md font-semibold text-ink">Daftar Status Prospek</h2>
                <p class="text-caption text-mute">Status disusun berdasarkan nomor urutan tahapan follow-up</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-body-sm">
                <thead>
                    <tr class="border-b border-hairline bg-canvas-soft text-caption font-semibold uppercase tracking-wider text-mute">
                        <th class="px-6 py-3.5">Urutan</th>
                        <th class="px-6 py-3.5">Kode</th>
                        <th class="px-6 py-3.5">Nama Status</th>
                        <th class="px-6 py-3.5">Preview Badge</th>
                        <th class="px-6 py-3.5">Status Akhir</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($statuses as $st)
                        <tr class="hover:bg-canvas-soft transition">
                            <td class="px-6 py-4 font-bold text-ink tabular-nums">{{ $st->sort_order }}</td>
                            <td class="px-6 py-4 font-mono text-caption text-mute">{{ $st->code }}</td>
                            <td class="px-6 py-4 font-semibold text-ink">{{ $st->name }}</td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$st" />
                            </td>
                            <td class="px-6 py-4">
                                @if($st->is_final)
                                    <span class="rounded-md bg-amber-50 px-2 py-0.5 text-caption font-semibold text-amber-800 border border-amber-200">
                                        Final
                                    </span>
                                @else
                                    <span class="text-caption text-mute">Proses</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button type="button"
                                        onclick="openEditStatusModal({{ $st->id }}, '{{ addslashes($st->name) }}', {{ $st->sort_order }}, {{ $st->is_final ? 1 : 0 }})"
                                        class="text-caption font-semibold text-primary hover:text-primary-deep inline-flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                    Ubah
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-mute">Belum ada data status.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Status Modal Dialog -->
<div id="editStatusModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm hidden">
    <div class="w-full max-w-md rounded-2xl bg-surface p-6 shadow-elev-4 border border-hairline transition-all">
        <div class="flex items-center justify-between border-b border-hairline pb-4 mb-4">
            <h3 class="text-heading-md font-bold text-ink">Ubah Status Prospek</h3>
            <button type="button" onclick="closeEditStatusModal()" class="text-mute hover:text-ink">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form id="editStatusForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_status_name" class="mb-xs block text-body-sm font-medium text-ink">Nama Tampilan Status <span class="text-error">*</span></label>
                <input id="edit_status_name" name="name" type="text" required
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div>
                <label for="edit_sort_order" class="mb-xs block text-body-sm font-medium text-ink">Urutan Tahapan <span class="text-error">*</span></label>
                <input id="edit_sort_order" name="sort_order" type="number" required min="1"
                       class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
            </div>

            <div class="flex items-center gap-2">
                <input id="edit_is_final" name="is_final" type="checkbox" value="1"
                       class="h-4 w-4 rounded border-hairline-strong text-primary focus:ring-primary">
                <label for="edit_is_final" class="text-body-sm text-body">
                    Merupakan Status Akhir (Final Stage)
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-hairline">
                <button type="button" onclick="closeEditStatusModal()" class="btn-secondary">
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
    function openEditStatusModal(id, name, sortOrder, isFinal) {
        const modal = document.getElementById('editStatusModal');
        const form = document.getElementById('editStatusForm');
        form.action = `/master/statuses/${id}`;
        document.getElementById('edit_status_name').value = name;
        document.getElementById('edit_sort_order').value = sortOrder;
        document.getElementById('edit_is_final').checked = (isFinal === 1);
        modal.classList.remove('hidden');
    }

    function closeEditStatusModal() {
        document.getElementById('editStatusModal').classList.add('hidden');
    }
</script>
@endsection
