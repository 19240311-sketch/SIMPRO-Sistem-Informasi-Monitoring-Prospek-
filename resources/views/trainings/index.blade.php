@extends('layouts.app')

@section('title', 'Training & Knowledge Sales — SIMPRO Yamaha')
@section('page-title', 'Training & Knowledge Sales')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto pb-12">

    <!-- Header Banner / Intro -->
    <div class="rounded-2xl bg-gradient-to-r from-[#1E3A8A] to-blue-800 p-6 sm:p-8 text-white shadow-md">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold backdrop-blur-sm">
                    <svg class="h-3.5 w-3.5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                    <span>Modul Pembelajaran Sales</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Training & Knowledge Sales
                </h2>
                <p class="text-blue-100 text-sm max-w-2xl font-normal leading-relaxed">
                    Pelajari keunggulan produk sepeda motor Yamaha dan tingkatkan kemampuan komunikasi, handling objection, serta teknik closing transaksi Anda.
                </p>
            </div>

            <!-- Mini Sales Training Summary Metrics -->
            <div class="grid grid-cols-3 gap-3 shrink-0 bg-white/10 p-3.5 rounded-xl backdrop-blur-md border border-white/20">
                <div class="text-center px-2">
                    <div class="text-2xl font-black text-white">{{ $totalCompleted }}</div>
                    <div class="text-[11px] font-medium text-blue-200">Selesai</div>
                </div>
                <div class="text-center px-2 border-x border-white/20">
                    <div class="text-2xl font-black text-amber-300">{{ $totalInProgress }}</div>
                    <div class="text-[11px] font-medium text-blue-200">Berjalan</div>
                </div>
                <div class="text-center px-2">
                    <div class="text-2xl font-black text-emerald-300">{{ $avgScore }}</div>
                    <div class="text-[11px] font-medium text-blue-200">Avg Quiz</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SECTION 1: KURSUS SAYA                    -->
    <!-- ========================================== -->
    @if($myCourses->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-[#1E3A8A] text-xs font-black">✓</span>
                        Kursus Saya
                    </h3>
                    <p class="text-xs text-slate-500">Daftar training yang sedang atau telah Anda pelajari</p>
                </div>
                <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                    {{ $myCourses->count() }} Training Aktif
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($myCourses as $course)
                    @php
                        $prog = $course->progress;
                        $pct = $prog->progress_persen ?? 0;
                        $isDone = ($prog->status ?? '') === 'selesai';
                        $matCount = $course->materials->count();
                        $quizCount = $course->quizzes->count();
                    @endphp
                    <div class="flex flex-col justify-between rounded-2xl bg-white p-5 border-2 border-slate-200 shadow-sm hover:border-blue-400 hover:shadow-md transition group">
                        <div class="space-y-3">
                            <!-- Category Badge & Status -->
                            <div class="flex items-center justify-between gap-2">
                                <span class="rounded-lg px-2.5 py-1 text-xs font-bold border {{ $course->category_color_classes }}">
                                    {{ $course->category_label }}
                                </span>
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $prog ? $prog->status_badge_classes : 'bg-slate-100 text-slate-600' }}">
                                    {{ $prog ? $prog->status_label : 'Belum Mulai' }}
                                </span>
                            </div>

                            <!-- Course Title & Description -->
                            <div>
                                <h4 class="text-base font-bold text-slate-900 group-hover:text-[#1E3A8A] transition line-clamp-1">
                                    {{ $course->nama_training }}
                                </h4>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                    {{ $course->deskripsi }}
                                </p>
                            </div>

                            <!-- Meta Info -->
                            <div class="flex items-center gap-3 text-xs font-medium text-slate-500 pt-1">
                                <span class="flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    {{ $matCount }} Materi
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $quizCount }} Quiz
                                </span>
                                <span>•</span>
                                <span>{{ $course->estimasi_waktu }}</span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="space-y-1.5 pt-2">
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <span class="text-slate-600">Progress Belajar</span>
                                    <span class="{{ $isDone ? 'text-emerald-600' : 'text-[#1E3A8A]' }}">{{ $pct }}%</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                                    <div class="h-full rounded-full transition-all duration-500 {{ $isDone ? 'bg-emerald-500' : 'bg-[#1E3A8A]' }}" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-5 mt-auto">
                            <a href="{{ route('trainings.show', $course->id) }}"
                               class="w-full flex items-center justify-center gap-2 rounded-xl py-2.5 text-xs font-bold transition shadow-sm {{ $isDone ? 'bg-emerald-50 text-emerald-700 border-2 border-emerald-300 hover:bg-emerald-100' : 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] hover:bg-blue-900' }}">
                                @if($isDone)
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Buka Ulang Materi</span>
                                @elseif($pct > 0)
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                    <span>Lanjutkan ({{ $pct }}%)</span>
                                @else
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Mulai Belajar</span>
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- SECTION 2: KATALOG SEMUA TRAINING          -->
    <!-- ========================================== -->
    <div class="space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-2 border-slate-200 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">
                    Katalog Materi Training
                </h3>
                <p class="text-xs text-slate-500">Pilih kategori modul untuk memperdalam wawasan produk & skill penjualan</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('trainings.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="kategori" value="{{ $tab }}">
                <div class="relative">
                    <input type="text" name="q" value="{{ $search }}"
                           placeholder="Cari materi atau motor..."
                           class="w-48 sm:w-64 rounded-xl border-2 border-slate-300 bg-white py-2 pl-9 pr-3 text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:outline-none">
                    <svg class="absolute left-3 top-2.5 h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                @if(!empty($search))
                    <a href="{{ route('trainings.index', ['kategori' => $tab]) }}" class="rounded-xl border border-slate-300 p-2 text-slate-500 hover:bg-slate-100" title="Reset Pencarian">
                        ✕
                    </a>
                @endif
            </form>
        </div>

        <!-- Filter Segmented Tabs -->
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('trainings.index', ['kategori' => 'all', 'q' => $search]) }}"
               class="rounded-xl px-4 py-2 text-xs font-bold transition border-2 {{ $tab === 'all' ? 'bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}">
                Semua Materi
            </a>
            <a href="{{ route('trainings.index', ['kategori' => 'product_knowledge', 'q' => $search]) }}"
               class="flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-bold transition border-2 {{ $tab === 'product_knowledge' ? 'bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}">
                <span>🏍️ Product Knowledge</span>
            </a>
            <a href="{{ route('trainings.index', ['kategori' => 'sales_skill', 'q' => $search]) }}"
               class="flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-bold transition border-2 {{ $tab === 'sales_skill' ? 'bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}">
                <span>🎯 Sales Skill</span>
            </a>
        </div>

        <!-- Training Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($allTrainings as $training)
                @php
                    $prog = $training->progress;
                    $pct = $prog->progress_persen ?? 0;
                    $isDone = ($prog->status ?? '') === 'selesai';
                    $matCount = $training->materials->count();
                    $quizCount = $training->quizzes->count();
                @endphp
                <div class="flex flex-col justify-between rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm hover:border-blue-400 hover:shadow-md transition group">
                    <div class="space-y-4">
                        <!-- Top Badges -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="rounded-lg px-2.5 py-1 text-xs font-bold border {{ $training->category_color_classes }}">
                                {{ $training->category_label }}
                            </span>
                            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200">
                                ⏱️ {{ $training->estimasi_waktu }}
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <div>
                            <h4 class="text-base font-bold text-slate-900 group-hover:text-[#1E3A8A] transition leading-snug">
                                {{ $training->nama_training }}
                            </h4>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                                {{ $training->deskripsi }}
                            </p>
                        </div>

                        <!-- Specs / Outline Details -->
                        <div class="rounded-xl bg-slate-50 p-3 border border-slate-200 space-y-1.5">
                            <div class="flex items-center justify-between text-xs text-slate-600">
                                <span class="font-medium">Jumlah Materi:</span>
                                <span class="font-bold text-slate-900">{{ $matCount }} Topik</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-600">
                                <span class="font-medium">Evaluasi Quiz:</span>
                                <span class="font-bold text-slate-900">{{ $quizCount }} Soal Pilihan Ganda</span>
                            </div>
                            @if($training->latest_quiz)
                                <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-200">
                                    <span class="font-medium text-slate-600">Nilai Quiz Terakhir:</span>
                                    <span class="font-bold {{ $training->latest_quiz->status_lulus ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $training->latest_quiz->nilai }}/100 ({{ $training->latest_quiz->status_lulus ? 'Lulus' : 'Belum Lulus' }})
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Progress indicator if started -->
                        @if($prog)
                            <div class="space-y-1 pt-1">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-600">
                                    <span>Status: <strong class="{{ $isDone ? 'text-emerald-600' : 'text-blue-700' }}">{{ $prog->status_label }}</strong></span>
                                    <span>{{ $pct }}%</span>
                                </div>
                                <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                                    <div class="h-full rounded-full {{ $isDone ? 'bg-emerald-500' : 'bg-[#1E3A8A]' }}" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Link Button -->
                    <div class="pt-6 mt-auto">
                        <a href="{{ route('trainings.show', $training->id) }}"
                           class="w-full flex items-center justify-center gap-2 rounded-xl py-3 text-sm font-bold transition shadow-sm {{ $isDone ? 'bg-emerald-50 text-emerald-700 border-2 border-emerald-300 hover:bg-emerald-100' : 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] hover:bg-blue-900 hover:shadow-md' }}">
                            @if($isDone)
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Lihat Materi & Quiz</span>
                            @elseif($pct > 0)
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                                <span>Lanjutkan Belajar ({{ $pct }}%)</span>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span>Mulai Pelajari Modul</span>
                            @endif
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-12 text-center border-2 border-dashed border-slate-200">
                    <p class="text-sm font-semibold text-slate-600">Tidak ada modul training yang cocok dengan pencarian.</p>
                    <a href="{{ route('trainings.index') }}" class="mt-3 inline-block text-xs font-bold text-[#1E3A8A] hover:underline">
                        Lihat Semua Modul Training
                    </a>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
