@extends('layouts.app')

@section('title', 'Kelola Training & Laporan Sales — SIMPRO Admin')
@section('page-title', 'Kelola Training & Laporan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-heading-md font-extrabold text-slate-900">Manajemen Modul Training & Laporan Sales</h2>
            <p class="text-xs text-slate-500">Kelola silabus, materi produk Yamaha, soal kuis, serta pantau kemajuan belajar sales.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('trainings.index') }}" class="btn-secondary" target="_blank">
                <svg class="h-4 w-4 text-[#1E3A8A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>Lihat Tampilan Sales</span>
            </a>

            <button type="button" onclick="document.getElementById('modalCreateTraining').classList.remove('hidden')" class="btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Modul Training</span>
            </button>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500">Total Modul Training</div>
            <div class="text-2xl font-black text-[#1E3A8A] mt-1">{{ $totalTrainingsCount }}</div>
        </div>
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500">Total Materi Topik</div>
            <div class="text-2xl font-black text-slate-800 mt-1">{{ $totalMaterialsCount }}</div>
        </div>
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500">Total Soal Kuis</div>
            <div class="text-2xl font-black text-slate-800 mt-1">{{ $totalQuizzesCount }}</div>
        </div>
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500">Penyelesaian Modul</div>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $totalCompletions }} <span class="text-xs text-slate-500 font-semibold">kali</span></div>
        </div>
    </div>

    <!-- Tab Switcher (Daftar Modul vs Laporan Sales) -->
    <div class="flex items-center gap-2 border-b-2 border-slate-200 pb-2">
        <a href="{{ route('admin.trainings.index', ['tab' => 'modules']) }}"
           class="rounded-xl px-5 py-2.5 text-xs font-bold transition {{ $activeTab === 'modules' ? 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] shadow-sm' : 'bg-white text-slate-700 border-2 border-slate-300 hover:bg-slate-50' }}">
            Daftar Modul Training ({{ $trainings->count() }})
        </a>
        <a href="{{ route('admin.trainings.index', ['tab' => 'sales_report']) }}"
           class="rounded-xl px-5 py-2.5 text-xs font-bold transition {{ $activeTab === 'sales_report' ? 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] shadow-sm' : 'bg-white text-slate-700 border-2 border-slate-300 hover:bg-slate-50' }}">
            Laporan Kemajuan Belajar Sales ({{ $salesProgressData->count() }})
        </a>
    </div>

    @if($activeTab === 'modules')
        <!-- TAB 1: DAFTAR MODUL TRAINING -->
        <div class="rounded-2xl bg-white border-2 border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">No</th>
                            <th class="py-3.5 px-4">Nama Modul Training</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Materi</th>
                            <th class="py-3.5 px-4">Kuis</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($trainings as $idx => $t)
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-500">{{ $idx + 1 }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $t->nama_training }}</div>
                                    <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $t->deskripsi }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="rounded-lg px-2.5 py-0.5 text-[11px] font-bold border {{ $t->category_color_classes }}">
                                        {{ $t->category_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-700">
                                    {{ $t->materials_count }} Materi
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-700">
                                    {{ $t->quizzes_count }} Soal
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $t->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.trainings.manage', $t->id) }}" class="rounded-lg px-3 py-1.5 text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900 transition">
                                            Kelola Materi & Kuis
                                        </a>

                                        <form action="{{ route('admin.trainings.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus modul ini?')">
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
                                <td colspan="7" class="py-8 text-center text-slate-500 font-semibold">
                                    Belum ada modul training yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- TAB 2: LAPORAN KEMAJUAN BELAJAR SALES -->
        <div class="rounded-2xl bg-white border-2 border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Sales Representative</th>
                            <th class="py-3.5 px-4">Email</th>
                            <th class="py-3.5 px-4">Modul Selesai</th>
                            <th class="py-3.5 px-4">Tingkat Penyelesaian</th>
                            <th class="py-3.5 px-4">Rata-rata Nilai Quiz</th>
                            <th class="py-3.5 px-4">Aktivitas Terakhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($salesProgressData as $salesItem)
                            @php
                                $user = $salesItem['user'];
                                $rate = $salesItem['completion_rate'];
                            @endphp
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-[#1E3A8A] font-bold text-xs">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 font-medium">
                                    {{ $user->email }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-900">{{ $salesItem['completed_count'] }}</span>
                                    <span class="text-slate-500">/ {{ $trainings->count() }} Modul</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="space-y-1 w-36">
                                        <div class="flex items-center justify-between font-bold text-[11px]">
                                            <span class="text-slate-600">{{ $rate }}%</span>
                                        </div>
                                        <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                                            <div class="h-full rounded-full bg-blue-600" style="width: {{ $rate }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-bold {{ $salesItem['avg_quiz_score'] >= 70 ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $salesItem['avg_quiz_score'] }}/100
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">
                                    {{ $salesItem['latest_quiz'] ? $salesItem['latest_quiz']->waktu_selesai->format('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500 font-semibold">
                                    Belum ada data sales.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- MODAL CREATE TRAINING -->
    <div id="modalCreateTraining" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 hidden">
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border-2 border-slate-200 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Tambah Modul Training Baru</h3>
                <button type="button" onclick="document.getElementById('modalCreateTraining').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">
                    ✕
                </button>
            </div>

            <form action="{{ route('admin.trainings.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Modul Training <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_training" required placeholder="Contoh: Yamaha NMAX Turbo — Product Knowledge"
                           class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                        <select name="kategori" required class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="product_knowledge">Product Knowledge</option>
                            <option value="sales_skill">Sales Skill</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estimasi Waktu</label>
                        <input type="text" name="estimasi_waktu" value="15 Menit" placeholder="15 Menit"
                               class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat <span class="text-rose-500">*</span></label>
                    <textarea name="deskripsi" rows="3" required placeholder="Jelaskan ringkasan materi yang akan dipelajari oleh Sales..."
                              class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Publikasi</label>
                    <select name="status" class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                        <option value="published">Published (Dapat diakses Sales)</option>
                        <option value="draft">Draft (Disembunyikan)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modalCreateTraining').classList.add('hidden')" class="rounded-xl px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="rounded-xl px-5 py-2 text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900">
                        Simpan & Kelola Materi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
