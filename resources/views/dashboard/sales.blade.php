@extends('layouts.app')

@section('title', 'Dashboard Sales')
@section('page-title', 'Dashboard Prospek Saya')

@section('content')
<div class="space-y-6">

    <!-- Reminder Alert Banner (Overdue Follow-ups) -->
    @if($myOverdueCount > 0)
        <div class="rounded-xl border border-amber-300 bg-amber-50/90 p-4 sm:p-5 shadow-elev-1">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-500 text-white font-bold">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-body-sm font-bold text-amber-950">
                            Perhatian: {{ $myOverdueCount }} Prospek Perlu Follow-up Segera
                        </h3>
                        <p class="text-caption text-amber-900 mt-0.5">
                            Prospek aktif di bawah ini belum menerima komunikasi Phone atau Visit lebih dari 3 hari.
                        </p>
                    </div>
                </div>
                <a href="{{ route('prospects.index', ['needs_follow_up' => 1]) }}"
                   class="inline-flex h-9 items-center justify-center rounded-pill bg-amber-600 px-4 text-button-md text-white hover:bg-amber-700 transition shadow-sm whitespace-nowrap">
                    Lihat Prospek Tertunda &rarr;
                </a>
            </div>
        </div>
    @endif

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <!-- Total Prospek Saya -->
        <div class="rounded-xl bg-surface p-5 border border-hairline shadow-elev-1 transition hover:shadow-elev-2">
            <div class="flex items-center justify-between">
                <p class="text-caption font-medium uppercase tracking-wider text-mute">Prospek Yang Saya Kelola</p>
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-soft text-primary">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 bg-brand bg-clip-text text-3xl font-extrabold tabular-nums text-transparent">{{ $totalMyProspects }}</p>
            <p class="mt-1 text-caption text-mute">Calon pembeli aktif</p>
        </div>

        <!-- Phone Follow-ups -->
        <div class="rounded-xl bg-surface p-5 border border-hairline shadow-elev-1 transition hover:shadow-elev-2">
            <div class="flex items-center justify-between">
                <p class="text-caption font-medium uppercase tracking-wider text-mute">Follow-up Phone</p>
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-soft text-primary">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-3xl font-extrabold tabular-nums text-primary">{{ $myPhoneCount }}</p>
            <p class="mt-1 text-caption text-mute">Panggilan yang saya lakukan</p>
        </div>

        <!-- Visit Follow-ups -->
        <div class="rounded-xl bg-surface p-5 border border-hairline shadow-elev-1 transition hover:shadow-elev-2">
            <div class="flex items-center justify-between">
                <p class="text-caption font-medium uppercase tracking-wider text-mute">Follow-up Visit</p>
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-cyan-50 text-accent-ink">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-3xl font-extrabold tabular-nums text-accent-ink">{{ $myVisitCount }}</p>
            <p class="mt-1 text-caption text-mute">Kunjungan yang saya lakukan</p>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="rounded-xl bg-surface p-6 border border-hairline shadow-elev-1">
        <h2 class="text-heading-md font-semibold text-ink mb-4">Status Prospek Saya</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($statuses as $st)
                <a href="{{ route('prospects.index', ['status_id' => $st->id]) }}"
                   class="group rounded-lg border border-hairline bg-canvas-soft p-4 transition hover:border-primary-softer hover:bg-surface hover:shadow-elev-2">
                    <div class="flex items-center justify-between">
                        <x-status-badge :status="$st" />
                    </div>
                    <p class="mt-3 text-2xl font-bold tabular-nums text-ink group-hover:text-primary">
                        {{ $statusCounts[$st->code]['count'] ?? 0 }}
                    </p>
                    <p class="mt-0.5 text-caption text-mute">prospek</p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Prospects & Timeline -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Prospects -->
        <div class="rounded-xl bg-surface border border-hairline shadow-elev-1 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-hairline">
                <h2 class="text-heading-md font-semibold text-ink">Prospek Terbaru Ditangani</h2>
                <a href="{{ route('prospects.index') }}" class="text-button-md text-primary hover:text-primary-deep font-medium">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="divide-y divide-hairline">
                @forelse($myRecentProspects as $p)
                    <div class="p-4 flex items-center justify-between hover:bg-canvas-soft transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-soft text-primary-ink font-semibold text-xs border border-primary-softer">
                                {{ $p->initials }}
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('prospects.show', $p->id) }}" class="text-body-sm font-semibold text-ink hover:text-primary truncate block">
                                    {{ $p->name }}
                                </a>
                                <p class="text-caption text-mute truncate">
                                    {{ $p->motorcycle->name ?? '-' }} &middot; {{ $p->phone }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-status-badge :status="$p->status" />
                            <a href="{{ route('prospects.show', $p->id) }}" class="rounded-pill bg-canvas-soft border border-hairline-strong px-3 py-1 text-caption text-ink hover:bg-surface hover:border-primary transition">
                                Follow-up
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-mute text-body-sm">
                        Belum ada prospek yang ditugaskan kepada Anda.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Activities Feed -->
        <div class="rounded-xl bg-surface border border-hairline shadow-elev-1 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-hairline">
                <h2 class="text-heading-md font-semibold text-ink">Riwayat Follow-up Saya</h2>
            </div>
            <div class="divide-y divide-hairline max-h-[400px] overflow-y-auto">
                @forelse($recentActivities as $act)
                    <div class="p-4 flex items-start gap-3 hover:bg-canvas-soft transition">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $act->type === 'phone' ? 'bg-primary-soft text-primary' : 'bg-cyan-50 text-accent-ink' }}">
                            @if($act->type === 'phone')
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <a href="{{ route('prospects.show', $act->prospect_id) }}" class="text-body-sm font-semibold text-ink hover:text-primary truncate">
                                    {{ $act->prospect->name ?? 'Prospek' }}
                                </a>
                                <span class="text-caption text-mute whitespace-nowrap">{{ $act->activity_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-caption text-body line-clamp-2 mt-0.5">{{ $act->notes }}</p>
                            <div class="mt-1 flex items-center gap-2 text-[11px] text-mute">
                                <span class="uppercase font-semibold text-primary">{{ $act->type }}</span>
                                @if($act->status)
                                    <span>&middot;</span>
                                    <span class="text-success-ink font-medium">&rarr; Status: {{ $act->status->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-mute text-body-sm">
                        Belum ada aktivitas follow-up yang tercatat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Supporting Widgets: Training & Weekly Test Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Widget 1: Tes Online Mingguan -->
        <div class="rounded-2xl bg-gradient-to-r from-blue-900 to-indigo-900 p-5 text-white shadow-md flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 backdrop-blur-sm text-white font-bold">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-white">TES MINGGUAN</h3>
                            @if(($pendingWeeklyTestsCount ?? 0) > 0)
                                <span class="rounded-full bg-amber-400/25 px-2 py-0.5 text-[10px] font-bold text-amber-200 border border-amber-300/30">Tersedia</span>
                            @else
                                <span class="rounded-full bg-emerald-400/25 px-2 py-0.5 text-[10px] font-bold text-emerald-200 border border-emerald-300/30">Up to date</span>
                            @endif
                        </div>
                        <p class="text-xs text-blue-200 mt-0.5">
                            Evaluasi rutin pemahaman produk &amp; sales skill mingguan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs bg-white/10 px-3 py-1.5 rounded-xl border border-white/15">
                    <div>
                        <span class="text-blue-200">Tes aktif:</span>
                        <strong class="text-white ml-1 font-bold">{{ $activeWeeklyTestsCount ?? 0 }}</strong>
                    </div>
                    <span class="text-white/30">•</span>
                    <div>
                        <span class="text-blue-200">Belum:</span>
                        <strong class="{{ ($pendingWeeklyTestsCount ?? 0) > 0 ? 'text-amber-300' : 'text-emerald-300' }} ml-1 font-bold">{{ $pendingWeeklyTestsCount ?? 0 }}</strong>
                    </div>
                    @if(isset($latestWeeklyTestAttempt) && $latestWeeklyTestAttempt)
                        <span class="text-white/30">•</span>
                        <div>
                            <span class="text-blue-200">Terakhir:</span>
                            <strong class="{{ $latestWeeklyTestAttempt->isPassed() ? 'text-emerald-300' : 'text-rose-300' }} ml-1 font-bold">{{ round($latestWeeklyTestAttempt->nilai) }}</strong>
                        </div>
                    @endif
                </div>

                @if(($pendingWeeklyTestsCount ?? 0) > 0 && isset($nextActiveTestId))
                    <a href="{{ route('weekly-tests.take', $nextActiveTestId) }}"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-amber-400 px-3.5 py-1.5 text-xs font-bold text-slate-900 hover:bg-amber-300 transition shadow-sm whitespace-nowrap">
                        <span>Mulai Tes</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('weekly-tests.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-white px-3.5 py-1.5 text-xs font-bold text-[#1E3A8A] hover:bg-blue-50 transition shadow-sm whitespace-nowrap">
                        <span>Lihat Tes</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- Widget 2: Training & Knowledge Sales -->
        <div class="rounded-2xl bg-gradient-to-r from-slate-900 to-[#1E3A8A] p-5 text-white shadow-md flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 backdrop-blur-sm text-white font-bold">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-white">TRAINING &amp; MATERI</h3>
                            <span class="rounded-full bg-blue-400/25 px-2 py-0.5 text-[10px] font-bold text-blue-200 border border-blue-300/30">Materi Sales</span>
                        </div>
                        <p class="text-xs text-blue-200 mt-0.5">
                            Pelajari product knowledge Yamaha &amp; teknik closing.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs bg-white/10 px-3 py-1.5 rounded-xl border border-white/15">
                    <div>
                        <span class="text-blue-200">Selesai:</span>
                        <strong class="text-white ml-1 font-bold">{{ $totalTrainingCompleted ?? 0 }}/{{ $totalTrainingCount ?? 0 }}</strong>
                    </div>
                    <span class="text-white/30">•</span>
                    <div>
                        <span class="text-blue-200">Berjalan:</span>
                        <strong class="text-amber-300 ml-1 font-bold">{{ $totalTrainingInProgress ?? 0 }}</strong>
                    </div>
                </div>

                <a href="{{ route('trainings.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-xl bg-white px-3.5 py-1.5 text-xs font-bold text-[#1E3A8A] hover:bg-blue-50 transition shadow-sm whitespace-nowrap">
                    <span>Buka Materi</span>
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
