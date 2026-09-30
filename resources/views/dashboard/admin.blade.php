@extends('layouts.app')

@section('title', 'Dashboard Monitoring Admin')
@section('page-title', 'Monitoring Prospek Dealer')

@section('content')
<div class="space-y-6">

    <!-- Reminder Alert Banner (Overdue Dealer Follow-ups) -->
    @if($overdueCount > 0)
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
                            Monitoring: {{ $overdueCount }} Prospek Dealer Belum Di-follow Up (&gt; 3 Hari)
                        </h3>
                        <p class="text-caption text-amber-900 mt-0.5">
                            Terdapat calon konsumen yang belum menerima aktivitas follow-up berkala dari tim Sales.
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

    <!-- Hero / Top Summary Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Prospek -->
        <div class="rounded-xl bg-surface p-5 border border-hairline shadow-elev-1 transition hover:shadow-elev-2">
            <div class="flex items-center justify-between">
                <p class="text-caption font-medium uppercase tracking-wider text-mute">Total Prospek</p>
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-soft text-primary">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 bg-brand bg-clip-text text-3xl font-extrabold tabular-nums text-transparent">{{ $totalProspects }}</p>
            <p class="mt-1 text-caption text-mute">Calon konsumen terdata</p>
        </div>

        <!-- Total Sales -->
        <div class="rounded-xl bg-surface p-5 border border-hairline shadow-elev-1 transition hover:shadow-elev-2">
            <div class="flex items-center justify-between">
                <p class="text-caption font-medium uppercase tracking-wider text-mute">Tim Sales</p>
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-3xl font-extrabold tabular-nums text-ink">{{ $totalSales }}</p>
            <p class="mt-1 text-caption text-mute">Sales aktif penanggung jawab</p>
        </div>

        <!-- Follow-up Phone -->
        <div class="rounded-xl bg-surface p-5 border border-hairline shadow-elev-1 transition hover:shadow-elev-2">
            <div class="flex items-center justify-between">
                <p class="text-caption font-medium uppercase tracking-wider text-mute">Follow-up Phone</p>
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-soft text-primary">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-3xl font-extrabold tabular-nums text-primary">{{ $totalPhone }}</p>
            <p class="mt-1 text-caption text-mute">Panggilan telepon tercatat</p>
        </div>

        <!-- Follow-up Visit -->
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
            <p class="mt-2 text-3xl font-extrabold tabular-nums text-accent-ink">{{ $totalVisit }}</p>
            <p class="mt-1 text-caption text-mute">Kunjungan langsung tercatat</p>
        </div>
    </div>

    <!-- Status Breakdown Grid -->
    <div class="rounded-xl bg-surface p-6 border border-hairline shadow-elev-1">
        <h2 class="text-heading-md font-semibold text-ink mb-4">Distribusi Status Prospek</h2>
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

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Prospects -->
        <div class="rounded-xl bg-surface border border-hairline shadow-elev-1 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-hairline">
                <h2 class="text-heading-md font-semibold text-ink">Prospek Terbaru</h2>
                <a href="{{ route('prospects.index') }}" class="text-button-md text-primary hover:text-primary-deep font-medium">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="divide-y divide-hairline">
                @forelse($recentProspects as $p)
                    <div class="p-4 flex items-center justify-between hover:bg-canvas-soft transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-soft text-primary-ink font-semibold text-xs border border-primary-softer">
                                {{ $p->initials }}
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('prospects.show', $p->id) }}" class="text-body-sm font-semibold text-ink hover:text-primary truncate block">
                                    {{ $p->name }}
                                </a>
                                <p class="text-caption text-mute flex items-center gap-1 truncate">
                                    <span>{{ $p->motorcycle->name ?? '-' }}</span>
                                    <span>&middot;</span>
                                    <span>Sales: {{ $p->user->name }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="shrink-0 pl-2">
                            <x-status-badge :status="$p->status" />
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-mute text-body-sm">
                        Belum ada data prospek yang dimasukkan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Activities Feed -->
        <div class="rounded-xl bg-surface border border-hairline shadow-elev-1 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-hairline">
                <h2 class="text-heading-md font-semibold text-ink">Aktivitas Follow-up Terkini</h2>
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
                                <span>Oleh: <strong class="text-ink font-medium">{{ $act->user->name }}</strong></span>
                                <span>&middot;</span>
                                <span class="uppercase font-semibold text-primary">{{ $act->type }}</span>
                                @if($act->status)
                                    <span>&middot;</span>
                                    <span class="text-success-ink font-medium">&rarr; {{ $act->status->name }}</span>
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
</div>
@endsection
