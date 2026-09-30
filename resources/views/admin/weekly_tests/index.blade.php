@extends('layouts.app')

@section('title', 'Manajemen Tes Online Mingguan — SIMPRO Admin')
@section('page-title', 'Kelola Tes Online Mingguan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-heading-md font-extrabold text-slate-900">Manajemen Tes Online Mingguan Sales</h2>
            <p class="text-xs text-slate-500">Buat jadwal tes berkala, susun pertanyaan pilihan ganda, dan pantau hasil evaluasi mingguan seluruh tim sales.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('weekly-tests.index') }}" class="btn-secondary" target="_blank">
                <svg class="h-4 w-4 text-[#1E3A8A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>Lihat Tampilan Sales</span>
            </a>

            <button type="button" onclick="document.getElementById('modalCreateTest').classList.remove('hidden')" class="btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Tes Mingguan Baru</span>
            </button>
        </div>
    </div>

    <!-- Tests List Table -->
    <div class="rounded-2xl bg-white border-2 border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Nama Tes &amp; Kategori</th>
                        <th class="py-3.5 px-4">Periode Pengerjaan</th>
                        <th class="py-3.5 px-4">Durasi &amp; Min</th>
                        <th class="py-3.5 px-4">Partisipasi Sales</th>
                        <th class="py-3.5 px-4 text-center">Rata-rata</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tests as $idx => $t)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-500">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $t->nama_tes }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">{{ $t->kategori }} • {{ $t->questions->count() }} Soal</div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 font-medium">
                                {{ $t->tanggal_mulai->format('d M') }} — {{ $t->tanggal_selesai->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900">{{ $t->durasi_menit }} Menit</div>
                                <div class="text-[11px] text-slate-500">Min: {{ $t->nilai_minimum }}/100</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">
                                    {{ $t->completed_count }} <span class="text-slate-500 font-normal">/ {{ $t->total_sales }} Sales</span>
                                </div>
                                <div class="text-[10px] text-slate-500 mt-0.5">
                                    <span class="text-emerald-600 font-bold">{{ $t->passed_count }} Lulus</span> •
                                    <span class="text-rose-600 font-bold">{{ $t->failed_count }} Gagal</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-black text-sm {{ $t->avg_score >= $t->nilai_minimum ? 'text-emerald-600' : 'text-slate-700' }}">
                                    {{ $t->avg_score }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $t->status_badge_classes }}">
                                    {{ $t->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.weekly-tests.manage', $t->id) }}"
                                       class="rounded-lg px-3 py-1.5 text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900 transition">
                                        Kelola Soal &amp; Hasil
                                    </a>

                                    <form action="{{ route('admin.weekly-tests.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tes mingguan ini beserta seluruh riwayat nilai?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition" title="Hapus">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500 font-medium">
                                Belum ada tes mingguan yang dibuat. Klik tombol Buat Tes Mingguan Baru di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL CREATE WEEKLY TEST -->
    <div id="modalCreateTest" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 hidden">
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border-2 border-slate-200 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Buat Tes Online Mingguan Baru</h3>
                <button type="button" onclick="document.getElementById('modalCreateTest').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('admin.weekly-tests.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Tes Mingguan <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_tes" required placeholder="Contoh: Tes Mingguan 03 — Product Knowledge & Handling"
                           class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori / Topik Tes</label>
                    <input type="text" name="kategori" value="Product Knowledge & Sales Skill" required
                           class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="tanggal_mulai" required value="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="tanggal_selesai" required value="{{ now()->addDays(7)->format('Y-m-d\TH:i') }}"
                               class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Durasi Tes (Menit) <span class="text-rose-500">*</span></label>
                        <input type="number" name="durasi_menit" value="15" min="1" max="120" required
                               class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nilai Minimum Lulus (0-100) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nilai_minimum" value="70" min="1" max="100" required
                               class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="2" required placeholder="Jelaskan cakupan materi yang diujikan..."
                              class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Publikasi</label>
                    <select name="status" class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                        <option value="published">Published (Aktif sesuai jadwal)</option>
                        <option value="draft">Draft (Disembunyikan)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modalCreateTest').classList.add('hidden')" class="rounded-xl px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="rounded-xl px-5 py-2 text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900">
                        Simpan &amp; Input Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
