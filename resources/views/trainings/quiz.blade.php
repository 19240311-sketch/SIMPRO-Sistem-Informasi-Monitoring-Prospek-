@extends('layouts.app')

@section('title', 'Quiz: ' . $training->nama_training . ' — SIMPRO')
@section('page-title', 'Quiz Evaluasi Pemahaman')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    <!-- Top Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('trainings.show', $training->id) }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-[#1E3A8A] transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Modul Training</span>
        </a>
        <span class="rounded-lg px-3 py-1 text-xs font-bold border {{ $training->category_color_classes }}">
            {{ $training->category_label }}
        </span>
    </div>

    <!-- Latest Result Banner if exists -->
    @if($latestResult)
        <div class="rounded-2xl p-6 border-2 {{ $latestResult->status_lulus ? 'bg-emerald-50 border-emerald-300 text-emerald-950' : 'bg-amber-50 border-amber-300 text-amber-950' }} shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full {{ $latestResult->status_lulus ? 'bg-emerald-600' : 'bg-amber-600' }} text-white text-sm font-bold">
                            {{ $latestResult->status_lulus ? '✓' : '!' }}
                        </span>
                        <h3 class="text-lg font-extrabold">
                            {{ $latestResult->status_lulus ? 'Quiz Selesai — Anda LULUS!' : 'Quiz Selesai — BELUM LULUS' }}
                        </h3>
                    </div>
                    <p class="text-xs {{ $latestResult->status_lulus ? 'text-emerald-800' : 'text-amber-800' }}">
                        Dikerjakan pada: {{ $latestResult->waktu_selesai ? $latestResult->waktu_selesai->format('d M Y, H:i') : '-' }} • Standar kelulusan: 70/100
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="text-3xl font-black {{ $latestResult->status_lulus ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $latestResult->nilai }}<span class="text-base text-slate-500 font-bold">/100</span>
                        </div>
                        <div class="text-xs font-bold text-slate-600">
                            {{ $latestResult->jumlah_benar }} Benar • {{ $latestResult->total_soal - $latestResult->jumlah_benar }} Salah
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quiz Header Card -->
    <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-2">
        <h1 class="text-xl font-bold text-slate-900">
            Uji Pemahaman: {{ $training->nama_training }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Pilihlah salah satu jawaban yang paling tepat dari setiap pertanyaan di bawah ini untuk menguji pemahaman Anda terhadap materi modul ini.
        </p>
    </div>

    <!-- Quiz Questions Form -->
    <form action="{{ route('trainings.quiz.submit', $training->id) }}" method="POST" class="space-y-6">
        @csrf

        @foreach($training->quizzes as $index => $quiz)
            @php
                $savedAnswer = $latestResult && isset($latestResult->jawaban_user[$quiz->id]) ? $latestResult->jawaban_user[$quiz->id] : null;
            @endphp
            <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
                <!-- Question Title -->
                <div class="flex items-start gap-3">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-bold text-xs border-2 border-blue-200">
                        {{ $index + 1 }}
                    </span>
                    <div class="space-y-1 pt-0.5">
                        <h3 class="text-sm font-bold text-slate-900 leading-snug">
                            {{ $quiz->pertanyaan }}
                        </h3>
                    </div>
                </div>

                <!-- Multiple Choices -->
                <div class="space-y-2.5 pt-1 pl-10">
                    @foreach($quiz->pilihan_jawaban as $optIndex => $option)
                        @php
                            $isSelected = $savedAnswer && ($savedAnswer['user_answer'] ?? '') === $option;
                            $isCorrectOpt = $savedAnswer && $savedAnswer['is_correct'] && $isSelected;
                            $isWrongOpt = $savedAnswer && !$savedAnswer['is_correct'] && $isSelected;
                        @endphp
                        <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition {{ $isSelected ? ($isCorrectOpt ? 'bg-emerald-50 border-emerald-500 text-emerald-950 font-semibold' : ($isWrongOpt ? 'bg-rose-50 border-rose-400 text-rose-950' : 'bg-blue-50 border-[#1E3A8A] text-[#1E3A8A] font-semibold')) : 'bg-slate-50 border-slate-200 hover:border-blue-300 hover:bg-white text-slate-700' }}">
                            <input type="radio"
                                   name="answers[{{ $quiz->id }}]"
                                   value="{{ $option }}"
                                   required
                                   {{ $isSelected ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#1E3A8A] focus:ring-[#1E3A8A] border-slate-300">
                            <span class="text-xs leading-relaxed">{{ $option }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Explanation if previously answered -->
                @if($savedAnswer && !empty($quiz->penjelasan))
                    <div class="ml-10 rounded-xl bg-slate-50 p-3.5 border border-slate-200 text-xs text-slate-600 space-y-1">
                        <span class="font-bold text-slate-900 block">💡 Pembahasan:</span>
                        <span>{{ $quiz->penjelasan }}</span>
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Submit Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
            <a href="{{ route('trainings.show', $training->id) }}" class="inline-flex items-center justify-center rounded-xl px-5 py-3 text-xs font-bold text-slate-700 bg-slate-100 border-2 border-slate-300 hover:bg-slate-200 transition">
                Buka Ulang Materi Modul
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-8 py-3 text-sm font-bold text-white border-2 border-emerald-600 shadow-md hover:bg-emerald-700 transition active:scale-95 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ $latestResult ? 'Kirim Ulang Jawaban Quiz' : 'Selesaikan & Kirim Jawaban Quiz' }}</span>
            </button>
        </div>
    </form>

</div>
@endsection
