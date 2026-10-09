@extends('layouts.app')

@section('title', 'Master Data Sepeda Motor')
@section('page-title', 'Kelola Master Data Motor')

@section('content')
<div class="space-y-6">

    <!-- Top Summary & Actions Banner -->
    <div class="rounded-2xl bg-white p-5 shadow-sm border-2 border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-heading-sm font-bold text-slate-900">Master Data Sepeda Motor Yamaha</h2>
            <p class="text-xs font-medium text-slate-500">Pusat data model, varian, pilihan warna resmi, harga OTR, dan simulasi skema angsuran/tenor.</p>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <!-- Tombol Buka Modal Tambah Motor Manual -->
            <button type="button" onclick="openCreateMotorModal()" class="inline-flex items-center gap-1.5 rounded-xl bg-[#1E3A8A] px-4 py-2 text-xs font-bold text-white hover:bg-blue-900 transition shadow-sm border-2 border-[#1E3A8A] cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Motor Baru</span>
            </button>

            <!-- Tombol Buka Modal Drop File -->
            <button type="button" onclick="openImportModal()" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition shadow-sm border-2 border-emerald-600 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                <span>Drop / Impor File Data Motor</span>
            </button>

            <!-- Unduh Template CSV -->
            <a href="{{ route('master.motorcycles.template') }}" class="inline-flex items-center gap-1.5 rounded-xl border-2 border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition shadow-sm" title="Unduh Contoh Template CSV">
                <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Unduh Template CSV</span>
            </a>

            <!-- Tombol Kosongkan / Hapus Semua Motor -->
            @if($motorcycles->count() > 0)
                <button type="button" onclick="openDeleteAllModal()" class="inline-flex items-center gap-1.5 rounded-xl border-2 border-red-200 bg-red-50 px-3.5 py-2 text-xs font-bold text-red-700 hover:bg-red-100 hover:border-red-300 transition shadow-sm cursor-pointer" title="Hapus seluruh master data motor">
                    <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span>Kosongkan Semua</span>
                </button>
            @endif

            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-[#1E3A8A] border border-blue-200">
                <span class="h-2 w-2 rounded-full bg-[#1E3A8A]"></span>
                Total {{ $motorcycles->count() }} Unit
            </span>
        </div>
    </div>

    <!-- Tabel Daftar Master Data Motor Full Width -->
    <div class="rounded-2xl bg-white border-2 border-slate-200 shadow-sm overflow-hidden">
        <!-- Header Table with Filter & Search -->
        <div class="p-5 border-b-2 border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-heading-sm font-bold text-slate-900">Daftar Master Sepeda Motor</h2>
                <p class="text-xs font-medium text-slate-500">Centang checkbox untuk menghapus data massal sekaligus tanpa satu-satu.</p>
            </div>

                <form method="GET" action="{{ route('master.motorcycles.index') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tipe/varian..."
                           class="h-9 w-44 sm:w-56 rounded-xl border-2 border-slate-300 bg-white px-3 text-xs font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    @if(request('search'))
                        <a href="{{ route('master.motorcycles.index') }}" class="h-9 px-2.5 flex items-center justify-center rounded-xl bg-slate-100 text-xs font-bold text-slate-600 hover:bg-slate-200">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Floating / Inline Bulk Action Bar (muncul jika ada yang dicentang) -->
            <form id="bulkDeleteForm" action="{{ route('master.motorcycles.bulk-delete') }}" method="POST">
                @csrf
                <div id="bulkActionBar" class="hidden bg-amber-50 border-y-2 border-amber-200 px-5 py-3 flex items-center justify-between transition-all">
                    <div class="flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-500 text-white text-xs font-extrabold" id="selectedCountBadge">
                            0
                        </span>
                        <span class="text-xs font-bold text-amber-900" id="selectedCountText">
                            0 data motor dipilih
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="cancelSelection()" class="rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-100 border border-slate-300 transition">
                            Batal Pilih
                        </button>
                        <button type="button" onclick="confirmBulkDelete()" class="rounded-lg bg-red-600 px-3.5 py-1.5 text-xs font-bold text-white hover:bg-red-700 shadow-sm transition inline-flex items-center gap-1.5 cursor-pointer">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            <span>Hapus Motor Terpilih</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b-2 border-slate-200 bg-slate-50 text-[11px] font-extrabold uppercase tracking-wider text-slate-600">
                                <th class="px-4 py-3.5 w-10 text-center">
                                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)"
                                           class="h-4 w-4 rounded border-slate-300 text-[#1E3A8A] focus:ring-blue-500 cursor-pointer" title="Pilih Semua">
                                </th>
                                <th class="px-5 py-3.5">Tipe & Varian Motor</th>
                                <th class="px-5 py-3.5">Harga OTR</th>
                                <th class="px-5 py-3.5">Pilihan Warna</th>
                                <th class="px-5 py-3.5">Tenor & Angsuran</th>
                                <th class="px-5 py-3.5 text-center">Status</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-100 font-medium">
                            @forelse($motorcycles as $m)
                                <tr class="hover:bg-blue-50/40 transition" id="row-motor-{{ $m->id }}">
                                    <!-- Checkbox Select -->
                                    <td class="px-4 py-4 text-center">
                                        <input type="checkbox" name="selected_ids[]" value="{{ $m->id }}"
                                               onchange="handleRowCheckboxChange(this, 'row-motor-{{ $m->id }}')"
                                               class="motor-checkbox h-4 w-4 rounded border-slate-300 text-[#1E3A8A] focus:ring-blue-500 cursor-pointer">
                                    </td>

                                    <!-- Model & Varian -->
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-900 text-sm">
                                            {{ $m->merk }} {{ $m->nama_model }}
                                        </div>
                                        <div class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700 mt-1">
                                            Varian: {{ $m->varian ?: 'Standard' }}
                                        </div>
                                    </td>

                                    <!-- Harga OTR -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="font-black text-sm text-[#1E3A8A]">
                                            Rp {{ number_format($m->harga_otr, 0, ',', '.') }}
                                        </div>
                                        <span class="text-[10px] text-slate-500 font-medium">OTR Resmi</span>
                                    </td>

                                    <!-- Warna -->
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                            @forelse($m->colors as $col)
                                                <span class="inline-block rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-[#1E3A8A] border border-blue-200">
                                                    {{ $col->nama_warna }}
                                                </span>
                                            @empty
                                                <span class="text-slate-400 italic text-[11px]">Belum diisi</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <!-- Tenor & Angsuran -->
                                    <td class="px-5 py-4">
                                        <div class="space-y-1 max-w-xs">
                                            @forelse($m->installments as $ins)
                                                <div class="inline-flex items-center gap-1.5 mr-1 mb-1 rounded bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-800 border border-slate-200">
                                                    <span class="font-bold text-[#1E3A8A]">{{ $ins->tenor_bulan }} bln:</span>
                                                    <span>Rp {{ number_format($ins->nominal_angsuran, 0, ',', '.') }}</span>
                                                </div>
                                            @empty
                                                <span class="text-slate-400 italic text-[11px]">Belum diisi</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-5 py-4 text-center whitespace-nowrap">
                                        @if($m->status_aktif)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span> Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600 border border-slate-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Ubah Button -->
                                            <button type="button"
                                                    onclick="openEditMotorModal({{ json_encode([
                                                        'id' => $m->id,
                                                        'merk' => $m->merk,
                                                        'nama_model' => $m->nama_model,
                                                        'varian' => $m->varian,
                                                        'harga_otr' => $m->harga_otr,
                                                        'status_aktif' => $m->status_aktif ? 1 : 0,
                                                        'warna' => $m->colors->pluck('nama_warna')->implode(', '),
                                                        'installments' => $m->installments->map(fn($i) => ['tenor' => $i->tenor_bulan, 'nominal' => $i->nominal_angsuran]),
                                                    ]) }})"
                                                    class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-[#1E3A8A] hover:bg-blue-100 border border-blue-200 transition inline-flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                </svg>
                                                Ubah
                                            </button>

                                            <!-- Toggle Status Button -->
                                            <button type="button" onclick="submitToggleStatus('{{ route('master.motorcycles.toggle', $m->id) }}')" class="rounded-lg {{ $m->status_aktif ? 'bg-slate-100 text-slate-700 hover:bg-slate-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} px-2.5 py-1.5 text-xs font-bold border border-slate-200 transition">
                                                {{ $m->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>

                                            <!-- Single Delete Button -->
                                            <button type="button" onclick="confirmSingleDelete('{{ route('master.motorcycles.destroy', $m->id) }}', '{{ $m->nama_model }} ({{ $m->varian }})')" class="rounded-lg bg-red-50 text-red-700 hover:bg-red-100 p-1.5 text-xs font-bold border border-red-200 transition" title="Hapus motor ini">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400 font-medium">Belum ada master data motor terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Single Delete Hidden -->
<form id="singleDeleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<!-- Form Toggle Status Hidden -->
<form id="toggleStatusForm" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>

<!-- Modal Konfirmasi Hapus Semua Data Motor -->
<div id="deleteAllModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border-2 border-red-200 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-100 text-red-600 border-2 border-red-200">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-heading-sm font-bold text-slate-900 mb-2">Kosongkan Semua Master Motor?</h3>
        <p class="text-xs text-slate-600 mb-6 leading-relaxed">
            Apakah Anda yakin ingin menghapus seluruh data motor dalam daftar ini? Data motor yang sudah memiliki data prospek akan tetap dipertahankan demi keamanan sistem.
        </p>
        <form action="{{ route('master.motorcycles.delete-all') }}" method="POST" class="flex items-center justify-center gap-3">
            @csrf
            <button type="button" onclick="closeDeleteAllModal()" class="w-1/2 rounded-xl border-2 border-slate-300 bg-slate-100 hover:bg-slate-200 px-5 py-3 text-sm font-bold text-slate-800 transition cursor-pointer">
                Batal
            </button>
            <button type="submit" style="background-color: #dc2626 !important; color: #ffffff !important;" class="w-1/2 rounded-xl bg-red-600 hover:bg-red-700 px-5 py-3 text-sm font-bold text-white transition shadow-md border-2 border-red-600 cursor-pointer">
                Ya, Kosongkan Semua
            </button>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus 1 Motor -->
<div id="singleDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl border-2 border-red-200 text-center animate-in">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-100 text-red-600 border-2 border-red-200">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <h3 class="text-heading-sm font-bold text-slate-900 mb-1">Hapus Data Motor?</h3>
        <p class="text-xs text-slate-600 mb-2 leading-relaxed">
            Apakah Anda yakin ingin menghapus motor:
        </p>
        <p class="text-sm font-extrabold text-red-700 mb-5 bg-red-50 rounded-lg py-2 px-3 border border-red-200" id="singleDeleteMotorName">
            —
        </p>
        <div class="flex items-center justify-center gap-3 mt-1">
            <button type="button" onclick="closeSingleDeleteModal()" class="w-1/2 rounded-xl border-2 border-slate-300 bg-slate-100 hover:bg-slate-200 px-5 py-3 text-sm font-bold text-slate-800 transition cursor-pointer">
                Batal
            </button>
            <button type="button" onclick="executeSingleDelete()" style="background-color: #dc2626 !important; color: #ffffff !important;" class="w-1/2 rounded-xl bg-red-600 hover:bg-red-700 px-5 py-3 text-sm font-bold text-white transition shadow-md border-2 border-red-600 cursor-pointer inline-flex items-center justify-center gap-2">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                </svg>
                <span>Ya, Hapus</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Motor Terpilih (Bulk) -->
<div id="bulkDeleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl border-2 border-amber-200 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 border-2 border-amber-200">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-heading-sm font-bold text-slate-900 mb-1">Hapus Motor Terpilih?</h3>
        <p class="text-xs text-slate-600 mb-2 leading-relaxed">
            Anda akan menghapus:
        </p>
        <p class="text-sm font-extrabold text-amber-800 mb-5 bg-amber-50 rounded-lg py-2 px-3 border border-amber-200" id="bulkDeleteCountText">
            0 data motor
        </p>
        <div class="flex items-center justify-center gap-3 mt-1">
            <button type="button" onclick="closeBulkDeleteModal()" class="w-1/2 rounded-xl border-2 border-slate-300 bg-slate-100 hover:bg-slate-200 px-5 py-3 text-sm font-bold text-slate-800 transition cursor-pointer">
                Batal
            </button>
            <button type="button" onclick="executeBulkDelete()" style="background-color: #dc2626 !important; color: #ffffff !important;" class="w-1/2 rounded-xl bg-red-600 hover:bg-red-700 px-5 py-3 text-sm font-bold text-white transition shadow-md border-2 border-red-600 cursor-pointer inline-flex items-center justify-center gap-2">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                </svg>
                <span>Ya, Hapus Semua</span>
            </button>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL DROP FILE / IMPOR DATA MOTOR                                        -->
<!-- ========================================================================= -->
<div id="importMotorModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border-2 border-slate-200 transition-all">
        <div class="flex items-center justify-between border-b-2 border-slate-100 pb-4 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 font-extrabold text-base border-2 border-emerald-200">
                    📂
                </div>
                <div>
                    <h3 class="text-heading-sm font-bold text-slate-900">Drop / Impor File Data Motor</h3>
                    <p class="text-xs text-slate-500">Upload file CSV atau JSON data motor & angsuran</p>
                </div>
            </div>
            <button type="button" onclick="closeImportModal()" class="text-slate-400 hover:text-slate-700 p-1">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form action="{{ route('master.motorcycles.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Drag & Drop Zone -->
            <div id="dropzoneContainer"
                 ondragover="handleDragOver(event)"
                 ondragleave="handleDragLeave(event)"
                 ondrop="handleFileDrop(event)"
                 onclick="document.getElementById('file_motor').click()"
                 class="group relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50/80 p-8 text-center hover:bg-blue-50/50 hover:border-blue-400 transition cursor-pointer">
                
                <input type="file" name="file_motor" id="file_motor" accept=".xlsx,.xls,.csv,.json,.txt" class="hidden" onchange="handleFileSelected(this)">

                <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#1E3A8A] shadow-sm border-2 border-slate-200 group-hover:border-blue-400 group-hover:scale-105 transition">
                    <svg class="h-7 w-7 text-[#1E3A8A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>

                <h4 class="text-sm font-bold text-slate-800" id="dropzoneTextTitle">
                    Tarik & Letakkan (Drop) File Excel atau CSV di Sini
                </h4>
                <p class="text-xs text-slate-500 mt-1" id="dropzoneTextSubtitle">
                    atau <span class="font-bold text-[#1E3A8A] underline">klik untuk memilih file</span> (Mendukung .XLSX Brosur Dealer, .CSV, .JSON)
                </p>

                <div id="fileSelectedBadge" class="mt-3 hidden inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 border border-emerald-300">
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span id="selectedFileName">file.xlsx</span>
                </div>
            </div>

            <!-- Petunjuk Format File Excel & CSV -->
            <div class="rounded-xl bg-blue-50/80 p-3.5 border border-blue-200 text-xs text-blue-900 space-y-2">
                <div class="font-bold flex items-center gap-1.5 text-[#1E3A8A]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Dukungan Format File:
                </div>
                <ul class="text-[11px] leading-relaxed list-disc list-inside space-y-1 text-slate-700">
                    <li><strong class="text-emerald-700">File Excel Brosur Dealer (.xlsx / .xls):</strong> Otomatis membaca semua sheet/tab motor (FAZZIO, FILANO, NMAX, AEROX, dll.), mendeteksi blok tabel, nama motor, OTR, tenor (11, 17, 23, 29, 35, dst.), dan angsurannya secara cerdas!</li>
                    <li><strong class="text-blue-700">File CSV / Template Master (.csv):</strong> Kolom otomatis terdeteksi: <code>Brand</code>, <code>Nama Model</code>, <code>Varian</code>, <code>Harga OTR</code>, <code>Warna</code>, dan kolom tenor.</li>
                </ul>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-slate-100">
                <button type="button" onclick="closeImportModal()" class="rounded-xl border-2 border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-emerald-700 shadow-md transition inline-flex items-center gap-1.5 border-2 border-emerald-600 cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                    Mulai Impor File
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- CREATE MOTOR MODAL DIALOG                                                 -->
<!-- ========================================================================= -->
<div id="createMotorModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl border-2 border-slate-200 transition-all">
        <div class="flex items-center justify-between border-b-2 border-slate-100 pb-4 mb-4">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                    +
                </div>
                <div>
                    <h3 class="text-heading-sm font-bold text-slate-900">Tambah Master Motor Baru</h3>
                    <p class="text-xs text-slate-500">Input model, varian, warna, OTR & skema angsuran manual</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateMotorModal()" class="text-slate-400 hover:text-slate-700 p-1">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form action="{{ route('master.motorcycles.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Brand / Merk -->
                <div>
                    <label for="modal_brand" class="mb-1 block text-body-sm font-bold text-slate-900">Brand / Merk <span class="text-red-600">*</span></label>
                    <input id="modal_brand" name="merk" type="text" value="{{ old('merk', 'Yamaha') }}" required
                           placeholder="Yamaha"
                           class="h-10 w-full rounded-xl border-2 border-slate-300 bg-white px-3 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <!-- Nama Model -->
                <div>
                    <label for="modal_name" class="mb-1 block text-body-sm font-bold text-slate-900">Nama Motor / Model <span class="text-red-600">*</span></label>
                    <input id="modal_name" name="nama_model" type="text" value="{{ old('nama_model') }}" required
                           placeholder="Contoh: Fazzio Hybrid, NMAX Turbo 155"
                           class="h-10 w-full rounded-xl border-2 border-slate-300 bg-white px-3 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <!-- Varian -->
                <div>
                    <label for="modal_varian" class="mb-1 block text-body-sm font-bold text-slate-900">Tipe / Varian <span class="text-red-600">*</span></label>
                    <input id="modal_varian" name="varian" type="text" value="{{ old('varian', 'Standard') }}" required
                           placeholder="Contoh: Neo, Lux, Tech MAX Ultimate, Standard"
                           class="h-10 w-full rounded-xl border-2 border-slate-300 bg-white px-3 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <!-- Harga OTR -->
                <div>
                    <label for="modal_harga_otr" class="mb-1 block text-body-sm font-bold text-slate-900">Harga OTR (Rp) <span class="text-red-600">*</span></label>
                    <input id="modal_harga_otr" name="harga_otr" type="number" min="0" value="{{ old('harga_otr') }}" required
                           placeholder="Contoh: 22700000"
                           class="h-10 w-full rounded-xl border-2 border-slate-300 bg-white px-3 text-sm font-bold text-[#1E3A8A] focus:border-[#1E3A8A] focus:outline-none">
                </div>
            </div>

            <!-- Daftar Warna -->
            <div>
                <label for="modal_warna" class="mb-1 block text-body-sm font-bold text-slate-900">Pilihan Warna yang Tersedia</label>
                <textarea id="modal_warna" name="warna" rows="2"
                          placeholder="Pisahkan dengan koma. Contoh: Neo Silver, Neo Dull Blue, Neo Red, Neo Mint"
                          class="w-full rounded-xl border-2 border-slate-300 bg-white p-2.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">{{ old('warna') }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Warna akan otomatis menjadi dropdown pilihan setelah tipe motor dipilih.</p>
            </div>

            <!-- Pilihan Tenor & Angsuran Dinamis -->
            <div class="rounded-xl bg-slate-50 p-3.5 border-2 border-slate-200 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Pilihan Tenor & Angsuran</label>
                    <button type="button" onclick="addTenorRow('containerModalAddTenors')" class="inline-flex items-center gap-1 rounded-lg bg-blue-100 px-2.5 py-1 text-xs font-bold text-[#1E3A8A] hover:bg-blue-200 transition">
                        + Tambah Tenor
                    </button>
                </div>

                <div id="containerModalAddTenors" class="space-y-2">
                    <div class="flex items-center gap-2">
                        <div class="w-24 shrink-0">
                            <select name="tenor_bulan[]" class="h-9 w-full rounded-lg border-2 border-slate-300 bg-white px-2 text-xs font-bold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                                <option value="11">11 Bulan</option>
                                <option value="23">23 Bulan</option>
                                <option value="35">35 Bulan</option>
                                <option value="47">47 Bulan</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <input type="number" name="nominal_angsuran[]" placeholder="Angsuran (Rp)" min="0"
                                   class="h-9 w-full rounded-lg border-2 border-slate-300 bg-white px-2.5 text-xs font-bold text-[#1E3A8A] focus:border-[#1E3A8A] focus:outline-none">
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-red-600 p-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-24 shrink-0">
                            <select name="tenor_bulan[]" class="h-9 w-full rounded-lg border-2 border-slate-300 bg-white px-2 text-xs font-bold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                                <option value="11">11 Bulan</option>
                                <option value="23" selected>23 Bulan</option>
                                <option value="35">35 Bulan</option>
                                <option value="47">47 Bulan</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <input type="number" name="nominal_angsuran[]" placeholder="Angsuran (Rp)" min="0"
                                   class="h-9 w-full rounded-lg border-2 border-slate-300 bg-white px-2.5 text-xs font-bold text-[#1E3A8A] focus:border-[#1E3A8A] focus:outline-none">
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-red-600 p-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-slate-100">
                <button type="button" onclick="closeCreateMotorModal()" class="rounded-xl border-2 border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-[#1E3A8A] px-5 py-2.5 text-xs font-bold text-white hover:bg-blue-900 shadow-md transition inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Simpan Master Motor
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- EDIT MOTOR MODAL DIALOG                                                   -->
<!-- ========================================================================= -->
<div id="editMotorModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl border-2 border-slate-200 transition-all">
        <div class="flex items-center justify-between border-b-2 border-slate-100 pb-4 mb-4">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                    ✎
                </div>
                <h3 class="text-heading-sm font-bold text-slate-900">Ubah Master Data Sepeda Motor</h3>
            </div>
            <button type="button" onclick="closeEditMotorModal()" class="text-slate-400 hover:text-slate-700 p-1">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form id="editMotorForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_brand" class="mb-1 block text-body-sm font-bold text-slate-900">Brand / Merk <span class="text-red-600">*</span></label>
                    <input id="edit_brand" name="merk" type="text" required
                           class="h-10 w-full rounded-xl border-2 border-slate-300 bg-white px-3 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <div>
                    <label for="edit_name" class="mb-1 block text-body-sm font-bold text-slate-900">Nama Tipe / Model <span class="text-red-600">*</span></label>
                    <input id="edit_name" name="nama_model" type="text" required
                           class="h-10 w-full rounded-xl border-2 border-slate-300 bg-white px-3 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <div>
                    <label for="edit_varian" class="mb-1 block text-body-sm font-bold text-slate-900">Varian <span class="text-red-600">*</span></label>
                    <input id="edit_varian" name="varian" type="text" required
                           class="h-10 w-full rounded-xl border-2 border-slate-300 bg-white px-3 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <div>
                    <label for="edit_harga_otr" class="mb-1 block text-body-sm font-bold text-slate-900">Harga OTR (Rp) <span class="text-red-600">*</span></label>
                    <input id="edit_harga_otr" name="harga_otr" type="number" min="0" required
                           class="h-10 w-full rounded-xl border-2 border-slate-300 bg-white px-3 text-sm font-bold text-[#1E3A8A] focus:border-[#1E3A8A] focus:outline-none">
                </div>
            </div>

            <div>
                <label for="edit_is_active" class="mb-1 block text-body-sm font-bold text-slate-900">Status Aktif</label>
                <select id="edit_is_active" name="status_aktif" class="h-10 w-full rounded-xl border-2 border-slate-300 bg-white px-3 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    <option value="1">Aktif (Tampil pada Pilihan Prospek)</option>
                    <option value="0">Nonaktif (Disembunyikan)</option>
                </select>
            </div>

            <div>
                <label for="edit_warna" class="mb-1 block text-body-sm font-bold text-slate-900">Pilihan Warna Motor (Pisahkan Koma)</label>
                <textarea id="edit_warna" name="warna" rows="2"
                          class="w-full rounded-xl border-2 border-slate-300 bg-white p-2.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none"></textarea>
            </div>

            <!-- Edit Tenor & Installments Dinamis -->
            <div class="rounded-xl bg-slate-50 p-3.5 border-2 border-slate-200 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Pilihan Tenor & Angsuran</label>
                    <button type="button" onclick="addTenorRow('containerEditTenors')" class="inline-flex items-center gap-1 rounded-lg bg-blue-100 px-2.5 py-1 text-xs font-bold text-[#1E3A8A] hover:bg-blue-200 transition">
                        + Tambah Tenor
                    </button>
                </div>

                <div id="containerEditTenors" class="space-y-2">
                    <!-- Populated via Javascript -->
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t-2 border-slate-100">
                <button type="button" onclick="closeEditMotorModal()" class="rounded-xl border-2 border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-[#1E3A8A] px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-900 shadow-md transition inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function addTenorRow(containerId, tenorVal = 11, nominalVal = '') {
        const container = document.getElementById(containerId);
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2';

        const tenors = [11, 23, 35, 47, 12, 24, 36];
        let optionsHtml = '';
        tenors.forEach(t => {
            optionsHtml += `<option value="${t}" ${t == tenorVal ? 'selected' : ''}>${t} Bulan</option>`;
        });

        row.innerHTML = `
            <div class="w-24 shrink-0">
                <select name="tenor_bulan[]" class="h-9 w-full rounded-lg border-2 border-slate-300 bg-white px-2 text-xs font-bold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    ${optionsHtml}
                </select>
            </div>
            <div class="flex-1">
                <input type="number" name="nominal_angsuran[]" value="${nominalVal}" placeholder="Angsuran (Rp)" min="0" required
                       class="h-9 w-full rounded-lg border-2 border-slate-300 bg-white px-2.5 text-xs font-bold text-[#1E3A8A] focus:border-[#1E3A8A] focus:outline-none">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-red-600 p-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
        `;
        container.appendChild(row);
    }

    function openEditMotorModal(data) {
        const modal = document.getElementById('editMotorModal');
        const form = document.getElementById('editMotorForm');
        form.action = `/master/motorcycles/${data.id}`;

        document.getElementById('edit_brand').value = data.merk;
        document.getElementById('edit_name').value = data.nama_model;
        document.getElementById('edit_varian').value = data.varian || 'Standard';
        document.getElementById('edit_harga_otr').value = data.harga_otr || 0;
        document.getElementById('edit_is_active').value = data.status_aktif;
        document.getElementById('edit_warna').value = data.warna || '';

        // Populate installments rows
        const container = document.getElementById('containerEditTenors');
        container.innerHTML = '';
        if (data.installments && data.installments.length > 0) {
            data.installments.forEach(ins => {
                addTenorRow('containerEditTenors', ins.tenor, ins.nominal);
            });
        } else {
            [11, 23, 35, 47].forEach(t => {
                addTenorRow('containerEditTenors', t, '');
            });
        }

        modal.classList.remove('hidden');
    }

    function closeEditMotorModal() {
        document.getElementById('editMotorModal').classList.add('hidden');
    }

    // Functions to handle Create Motor Modal
    function openCreateMotorModal() {
        document.getElementById('createMotorModal').classList.remove('hidden');
    }

    function closeCreateMotorModal() {
        document.getElementById('createMotorModal').classList.add('hidden');
    }



    // Drag & Drop Modal Functions
    function openImportModal() {
        document.getElementById('importMotorModal').classList.remove('hidden');
    }

    function closeImportModal() {
        document.getElementById('importMotorModal').classList.add('hidden');
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropzoneContainer').classList.add('border-blue-500', 'bg-blue-50');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropzoneContainer').classList.remove('border-blue-500', 'bg-blue-50');
    }

    function handleFileDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropzoneContainer').classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('file_motor').files = files;
            showSelectedFile(files[0]);
        }
    }

    function handleFileSelected(input) {
        if (input.files.length > 0) {
            showSelectedFile(input.files[0]);
        }
    }

    function showSelectedFile(file) {
        document.getElementById('selectedFileName').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        document.getElementById('fileSelectedBadge').classList.remove('hidden');
        document.getElementById('dropzoneTextTitle').textContent = 'File Siap Diimpor:';
        document.getElementById('dropzoneTextSubtitle').innerHTML = '<span class="text-emerald-700 font-bold">' + file.name + '</span> (Klik untuk mengganti)';
    }

    // =========================================================================
    // CHECKBOX & BULK DELETE FUNCTIONS
    // =========================================================================
    function toggleSelectAll(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.motor-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
            const rowId = 'row-motor-' + cb.value;
            const row = document.getElementById(rowId);
            if (row) {
                if (cb.checked) {
                    row.classList.add('bg-blue-50/70');
                } else {
                    row.classList.remove('bg-blue-50/70');
                }
            }
        });
        updateSelectedCount();
    }

    function handleRowCheckboxChange(cb, rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            if (cb.checked) {
                row.classList.add('bg-blue-50/70');
            } else {
                row.classList.remove('bg-blue-50/70');
            }
        }

        const checkboxes = document.querySelectorAll('.motor-checkbox');
        const selectAllCb = document.getElementById('selectAllCheckbox');
        const allChecked = Array.from(checkboxes).every(c => c.checked);
        if (selectAllCb) {
            selectAllCb.checked = allChecked && checkboxes.length > 0;
        }

        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.motor-checkbox:checked');
        const count = checkedBoxes.length;
        const bar = document.getElementById('bulkActionBar');
        const badge = document.getElementById('selectedCountBadge');
        const text = document.getElementById('selectedCountText');

        if (count > 0) {
            bar.classList.remove('hidden');
            badge.textContent = count;
            text.textContent = count + ' data motor dipilih';
        } else {
            bar.classList.add('hidden');
        }
    }

    function cancelSelection() {
        const checkboxes = document.querySelectorAll('.motor-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = false;
            const rowId = 'row-motor-' + cb.value;
            const row = document.getElementById(rowId);
            if (row) row.classList.remove('bg-blue-50/70');
        });
        const selectAllCb = document.getElementById('selectAllCheckbox');
        if (selectAllCb) selectAllCb.checked = false;
        updateSelectedCount();
    }

    function confirmBulkDelete() {
        const checkedBoxes = document.querySelectorAll('.motor-checkbox:checked');
        const count = checkedBoxes.length;
        if (count === 0) {
            alert('Silakan pilih minimal 1 data motor terlebih dahulu.');
            return;
        }

        document.getElementById('bulkDeleteCountText').textContent = count + ' data motor';
        document.getElementById('bulkDeleteConfirmModal').classList.remove('hidden');
    }

    function closeBulkDeleteModal() {
        document.getElementById('bulkDeleteConfirmModal').classList.add('hidden');
    }

    function executeBulkDelete() {
        document.getElementById('bulkDeleteForm').submit();
    }

    // =========================================================================
    // SINGLE DELETE MODAL FUNCTIONS
    // =========================================================================
    let pendingSingleDeleteUrl = null;

    function confirmSingleDelete(url, name) {
        pendingSingleDeleteUrl = url;
        document.getElementById('singleDeleteMotorName').textContent = name;
        document.getElementById('singleDeleteModal').classList.remove('hidden');
    }

    function closeSingleDeleteModal() {
        document.getElementById('singleDeleteModal').classList.add('hidden');
        pendingSingleDeleteUrl = null;
    }

    function executeSingleDelete() {
        if (pendingSingleDeleteUrl) {
            const form = document.getElementById('singleDeleteForm');
            form.action = pendingSingleDeleteUrl;
            form.submit();
        }
    }

    function submitToggleStatus(url) {
        const form = document.getElementById('toggleStatusForm');
        form.action = url;
        form.submit();
    }

    function openDeleteAllModal() {
        document.getElementById('deleteAllModal').classList.remove('hidden');
    }

    function closeDeleteAllModal() {
        document.getElementById('deleteAllModal').classList.add('hidden');
    }
</script>
@endsection
