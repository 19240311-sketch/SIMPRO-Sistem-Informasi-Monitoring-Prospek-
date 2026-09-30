@extends('layouts.app')

@section('title', $training->nama_training . ' — Training SIMPRO')
@section('page-title', 'Detail Modul Training')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 pb-12">

    <!-- Top Navigation Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('trainings.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-[#1E3A8A] transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Katalog Training</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="rounded-lg px-3 py-1 text-xs font-bold border {{ $training->category_color_classes }}">
                {{ $training->category_label }}
            </span>
            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $progress->status_badge_classes }}">
                {{ $progress->status_label }}
            </span>
        </div>
    </div>

    <!-- Training Course Header Card -->
    <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">
                    {{ $training->nama_training }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-3xl">
                    {{ $training->deskripsi }}
                </p>
            </div>

            @if($training->quizzes->isNotEmpty())
                <a href="{{ route('trainings.quiz', $training->id) }}"
                   class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white border-2 border-emerald-600 shadow-sm hover:bg-emerald-700 transition active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Mulai Uji Pemahaman (Quiz)</span>
                </a>
            @endif
        </div>

        <!-- Overall Progress Bar -->
        <div class="space-y-1.5 pt-2 border-t border-slate-100">
            <div class="flex items-center justify-between text-xs font-bold">
                <span class="text-slate-600">
                    Kemajuan Modul: {{ count($completedIds) }} dari {{ $training->materials->count() }} Materi Selesai
                </span>
                <span class="{{ $progress->progress_persen >= 100 ? 'text-emerald-600' : 'text-[#1E3A8A]' }}">
                    {{ $progress->progress_persen }}%
                </span>
            </div>
            <div class="h-2.5 w-full rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                <div class="h-full rounded-full transition-all duration-500 {{ $progress->progress_persen >= 100 ? 'bg-emerald-500' : 'bg-[#1E3A8A]' }}" style="width: {{ $progress->progress_persen }}%"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Layout: Sidebar Material List + Material Content Viewer -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Column: Syllabus / Material Outline (4 cols) -->
        <div class="lg:col-span-4 rounded-2xl bg-white p-5 border-2 border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="h-4 w-4 text-[#1E3A8A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>Daftar Materi Modul</span>
                </h3>
                <span class="text-xs font-bold text-slate-500">{{ $training->materials->count() }} Topik</span>
            </div>

            <div class="space-y-2">
                @foreach($training->materials as $idx => $m)
                    @php
                        $isCompleted = in_array($m->id, $completedIds);
                        $isActive = $activeMaterial && $activeMaterial->id === $m->id;
                    @endphp
                    <a href="{{ route('trainings.show', [$training->id, 'material_id' => $m->id]) }}"
                       class="flex items-start gap-3 rounded-xl p-3 text-xs transition border-2 {{ $isActive ? 'bg-blue-50 border-[#1E3A8A] text-[#1E3A8A] font-bold shadow-sm' : ($isCompleted ? 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50') }}">
                        <!-- Indicator Icon -->
                        <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-extrabold {{ $isCompleted ? 'bg-emerald-500 text-white' : ($isActive ? 'bg-[#1E3A8A] text-white' : 'bg-slate-200 text-slate-600') }}">
                            @if($isCompleted)
                                ✓
                            @else
                                {{ $idx + 1 }}
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="truncate {{ $isActive ? 'font-bold text-[#1E3A8A]' : 'font-semibold text-slate-800' }}">
                                {{ $m->judul_materi }}
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                {{ $isCompleted ? 'Sudah Selesai' : ($isActive ? 'Sedang Dibaca' : 'Belum Selesai') }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Quiz Link in Syllabus -->
            @if($training->quizzes->isNotEmpty())
                <div class="pt-3 border-t border-slate-100">
                    <a href="{{ route('trainings.quiz', $training->id) }}"
                       class="flex items-center justify-between rounded-xl p-3 text-xs font-bold border-2 transition {{ $latestQuiz && $latestQuiz->status_lulus ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : 'bg-amber-50 border-amber-300 text-amber-900 hover:bg-amber-100' }}">
                        <div class="flex items-center gap-2">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full {{ $latestQuiz && $latestQuiz->status_lulus ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }} text-[10px]">
                                📝
                            </span>
                            <span>Quiz Evaluasi Pemahaman</span>
                        </div>
                        <span class="text-[11px]">
                            @if($latestQuiz)
                                {{ $latestQuiz->nilai }}/100
                            @else
                                {{ $training->quizzes->count() }} Soal
                            @endif
                        </span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Right Column: Active Material Reader (8 cols) -->
        <div class="lg:col-span-8 rounded-2xl bg-white p-6 sm:p-8 border-2 border-slate-200 shadow-sm space-y-6">
            @if($activeMaterial)
                <!-- Material Header -->
                <div class="border-b-2 border-slate-100 pb-4 space-y-1">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#1E3A8A]">
                        <span>Materi {{ $training->materials->search(fn($m) => $m->id === $activeMaterial->id) + 1 }} dari {{ $training->materials->count() }}</span>
                        @if(in_array($activeMaterial->id, $completedIds))
                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-800">✓ Selesai</span>
                        @endif
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                        {{ $activeMaterial->judul_materi }}
                    </h2>
                </div>

                <!-- Material Content Body (Formatted Markdown / Clean Text) -->
                <div class="prose prose-slate max-w-none text-slate-800 text-sm leading-relaxed space-y-4">
                    {!! nl2br(e($activeMaterial->isi_materi)) !!}
                </div>

                <!-- Material Actions & Bottom Navigation -->
                <div class="border-t-2 border-slate-100 pt-6 mt-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <!-- Previous Button -->
                    @if($prevMaterial)
                        <a href="{{ route('trainings.show', [$training->id, 'material_id' => $prevMaterial->id]) }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 bg-slate-100 border border-slate-300 hover:bg-slate-200 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Materi Sebelumnya</span>
                        </a>
                    @else
                        <div></div>
                    @endif

                    <!-- Mark Complete & Next Button -->
                    <form action="{{ route('trainings.materials.complete', [$training->id, $activeMaterial->id]) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-[#1E3A8A] px-6 py-2.5 text-xs font-bold text-white border-2 border-[#1E3A8A] shadow-md hover:bg-blue-900 transition active:scale-95 cursor-pointer">
                            <span>Tandai Selesai & Lanjut</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </form>
                </div>
            @else
                <div class="text-center py-12 space-y-3">
                    <p class="text-sm font-semibold text-slate-600">Belum ada materi pada modul ini.</p>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
