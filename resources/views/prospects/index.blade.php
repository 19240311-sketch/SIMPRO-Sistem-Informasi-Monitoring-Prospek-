@extends('layouts.app')

@section('title', 'Daftar Prospek Konsumen')
@section('page-title', auth()->user()->isAdmin() ? 'Semua Prospek Konsumen' : 'Prospek Saya')

@section('content')
<div class="space-y-6">

    <!-- Header / Actions Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-heading-md font-bold text-ink">{{ auth()->user()->isAdmin() ? 'Monitoring Prospek Konsumen' : 'Daftar Prospek Saya' }}</h2>
            <p class="text-caption text-mute">Total {{ $prospects->total() }} data ditemukan</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Export CSV Button -->
            <a href="{{ route('prospects.export', request()->query()) }}" class="btn-secondary">
                <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Unduh CSV</span>
            </a>

            <a href="{{ route('prospects.create', ['mode' => 'prospect']) }}" class="btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Prospek Baru</span>
            </a>

            <a href="{{ route('prospects.create', ['mode' => 'deal']) }}" class="btn-success">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
                <span>Input Deal</span>
            </a>
        </div>
    </div>

    <!-- Segmented Tab Navigation: Semua vs Prospect (Belum Deal) vs Deal -->
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('prospects.index', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}"
           class="{{ ($tab ?? 'all') === 'all' ? 'btn-tab-active' : 'btn-tab-inactive' }}">
            <span>Semua Data</span>
            <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ ($tab ?? 'all') === 'all' ? 'bg-white/25 text-white' : 'bg-slate-200 text-slate-600' }}">{{ $countAll ?? 0 }}</span>
        </a>

        <a href="{{ route('prospects.index', array_merge(request()->except('tab', 'page'), ['tab' => 'prospect'])) }}"
           class="{{ ($tab ?? 'all') === 'prospect' ? 'btn-tab-active' : 'btn-tab-inactive' }}">
            <span>Prospek Berjalan</span>
            <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ ($tab ?? 'all') === 'prospect' ? 'bg-white/25 text-white' : 'bg-slate-200 text-slate-600' }}">{{ $countProspect ?? 0 }}</span>
        </a>

        <a href="{{ route('prospects.index', array_merge(request()->except('tab', 'page'), ['tab' => 'deal'])) }}"
           class="{{ ($tab ?? 'all') === 'deal' ? 'inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm bg-emerald-600 text-white border-2 border-emerald-600 shadow-md transition cursor-pointer select-none' : 'btn-tab-inactive' }}">
            <span>Prospek Deal (Closing)</span>
            <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ ($tab ?? 'all') === 'deal' ? 'bg-white/25 text-white' : 'bg-slate-200 text-slate-600' }}">{{ $countDeal ?? 0 }}</span>
        </a>
    </div>

    <!-- Filters & Search Bar Card -->
    <div class="rounded-xl bg-surface p-5 border border-hairline shadow-elev-1">
        <form method="GET" action="{{ route('prospects.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 {{ auth()->user()->isAdmin() ? 'lg:grid-cols-6' : 'lg:grid-cols-5' }} items-end">
            <!-- Search Input -->
            <div class="{{ auth()->user()->isAdmin() ? 'lg:col-span-2' : 'lg:col-span-2' }}">
                <label for="search" class="mb-xs block text-body-sm font-medium text-ink">Cari Nama / Telepon</label>
                <div class="relative">
                    <input id="search" name="search" type="text" value="{{ request('search') }}"
                           placeholder="Ketik nama atau nomor..."
                           class="h-10 w-full rounded-md border border-hairline-strong bg-surface pl-10 pr-3 text-body-sm text-ink placeholder:text-mute focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-mute">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status_id" class="mb-xs block text-body-sm font-medium text-ink">Status</label>
                <select id="status_id" name="status_id"
                        class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st->id }}" {{ request('status_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Source Filter -->
            <div>
                <label for="source_id" class="mb-xs block text-body-sm font-medium text-ink">Sumber</label>
                <select id="source_id" name="source_id"
                        class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
                    <option value="">Semua Sumber</option>
                    @foreach($sources as $src)
                        <option value="{{ $src->id }}" {{ request('source_id') == $src->id ? 'selected' : '' }}>
                            {{ $src->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sales Filter (Admin Only) -->
            @if(auth()->user()->isAdmin())
                <div>
                    <label for="user_id" class="mb-xs block text-body-sm font-medium text-ink">Sales PJ</label>
                    <select id="user_id" name="user_id"
                            class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
                        <option value="">Semua Sales</option>
                        @foreach($salesUsers as $sUser)
                            <option value="{{ $sUser->id }}" {{ request('user_id') == $sUser->id ? 'selected' : '' }}>
                                {{ $sUser->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex w-full items-center gap-2">
                <button type="submit" 
                        class="flex-1 inline-flex h-10 items-center justify-center gap-1.5 rounded-md bg-[#0A4DF3] px-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#0639B8] hover:shadow active:scale-[0.98] border-2 border-[#0A4DF3] cursor-pointer">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.539.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.378-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'status_id', 'source_id', 'user_id', 'motorcycle_id', 'needs_follow_up']))
                    <a href="{{ route('prospects.index') }}" 
                       class="flex-1 inline-flex h-10 items-center justify-center gap-1.5 rounded-md border-2 border-slate-200 bg-white px-3 text-sm font-bold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 active:scale-[0.98] cursor-pointer"
                       title="Reset filter ke kondisi awal">
                        <svg class="h-4 w-4 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>

        <!-- Quick Filter Pills -->
        <div class="mt-4 pt-3 border-t border-hairline flex items-center gap-2 flex-wrap text-caption">
            <span class="text-mute font-medium">Filter Cepat:</span>
            <a href="{{ request()->fullUrlWithQuery(['needs_follow_up' => request('needs_follow_up') ? null : 1]) }}"
               class="inline-flex items-center gap-1.5 rounded-pill px-3 py-1 font-semibold transition border {{ request('needs_follow_up') ? 'bg-amber-500 text-white border-amber-600 shadow-sm' : 'bg-canvas-soft text-amber-900 border-amber-200 hover:bg-amber-100' }}">
                <span class="h-2 w-2 rounded-full {{ request('needs_follow_up') ? 'bg-white' : 'bg-amber-500' }}"></span>
                ⚠️ Perlu Follow-up (&gt; 3 Hari)
            </a>
        </div>
    </div>

    <!-- Prospects List / Table Container -->
    <div class="rounded-xl bg-surface border border-hairline shadow-elev-1 overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-hairline bg-canvas-soft text-caption font-semibold uppercase tracking-wider text-mute">
                        <th class="px-6 py-3.5">Konsumen</th>
                        <th class="px-6 py-3.5">Motor Diminati</th>
                        <th class="px-6 py-3.5">Sumber</th>
                        <th class="px-6 py-3.5">Status</th>
                        @if(auth()->user()->isAdmin())
                            <th class="px-6 py-3.5">Sales</th>
                        @endif
                        <th class="px-6 py-3.5">Aktivitas Terakhir</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline text-body-sm">
                    @forelse($prospects as $p)
                        <tr class="hover:bg-canvas-soft transition">
                            <!-- Consumer Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-soft text-primary-ink font-semibold text-xs border border-primary-softer">
                                        {{ $p->initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('prospects.show', $p->id) }}" class="font-semibold text-ink hover:text-primary block">
                                            {{ $p->name }}
                                        </a>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-caption text-mute">{{ $p->phone }}</span>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->phone) }}" target="_blank" title="Kirim Pesan WhatsApp"
                                               class="inline-flex items-center text-[11px] font-medium text-success hover:underline">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                                </svg>
                                                <span>WA</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Motorcycle -->
                            <td class="px-6 py-4 text-ink">
                                <div class="font-bold text-slate-900">{{ $p->motorcycle->full_display_name ?? ($p->motorcycle->nama_model ?? '-') }}</div>
                                @if($p->warna_motor_diminati)
                                    <span class="text-[11px] text-primary font-semibold block">{{ $p->warna_motor_diminati }}</span>
                                @endif
                            </td>

                            <!-- Source -->
                            <td class="px-6 py-4 text-mute">
                                <span class="rounded-md bg-canvas-soft px-2 py-1 text-caption border border-hairline">
                                    {{ $p->source->name ?? '-' }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <x-status-badge :status="$p->status" />
                            </td>

                            <!-- Sales (if admin) -->
                            @if(auth()->user()->isAdmin())
                                <td class="px-6 py-4 text-mute">
                                    <span class="font-medium text-ink">{{ $p->user->name ?? '-' }}</span>
                                </td>
                            @endif

                            <!-- Last Activity & Overdue Warning -->
                            <td class="px-6 py-4 text-caption">
                                @if($p->needs_follow_up)
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center gap-1 rounded-pill bg-amber-50 px-2 py-0.5 text-[11px] font-bold text-amber-800 border border-amber-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Perlu Follow-up ({{ $p->days_since_last_activity }} hr)
                                        </span>
                                        <p class="text-[11px] text-mute">
                                            @if($p->latestActivity)
                                                Terakhir: {{ $p->latestActivity->activity_at->diffForHumans() }}
                                            @else
                                                Belum pernah dihubungi
                                            @endif
                                        </p>
                                    </div>
                                @elseif($p->latestActivity)
                                    <span class="font-semibold uppercase text-primary">{{ $p->latestActivity->type }}</span> &middot; <span class="text-mute">{{ $p->latestActivity->activity_at->diffForHumans() }}</span>
                                @else
                                    <span class="italic text-slate-400">Belum ada</span>
                                @endif
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('prospects.show', $p->id) }}"
                                   class="inline-flex items-center gap-1 rounded-pill bg-primary-soft px-3 py-1 text-caption font-semibold text-primary-ink hover:bg-primary-softer transition">
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="p-12 text-center text-mute">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-canvas-soft text-mute mb-3">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                </div>
                                <p class="text-heading-md font-semibold text-ink">Tidak ada data prospek</p>
                                <p class="text-body-sm text-mute mt-1">Coba sesuaikan filter pencarian atau tambahkan data prospek baru.</p>
                                <div class="mt-4">
                                    <a href="{{ route('prospects.create') }}" class="inline-flex h-9 items-center rounded-pill bg-primary px-4 text-button-md text-on-primary hover:bg-primary-deep">
                                        Tambah Prospek Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View (< 640px) -->
        <div class="block sm:hidden divide-y divide-hairline">
            @forelse($prospects as $p)
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-soft text-primary-ink font-semibold text-xs">
                                {{ $p->initials }}
                            </div>
                            <div>
                                <a href="{{ route('prospects.show', $p->id) }}" class="font-semibold text-ink hover:text-primary">
                                    {{ $p->name }}
                                </a>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="text-caption text-mute">{{ $p->phone }}</span>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->phone) }}" target="_blank" class="text-[11px] font-medium text-success hover:underline">
                                        WA
                                    </a>
                                </div>
                            </div>
                        </div>
                        <x-status-badge :status="$p->status" />
                    </div>

                    @if($p->needs_follow_up)
                        <div class="rounded-md bg-amber-50 p-2 border border-amber-200 text-caption text-amber-900 flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-amber-500 shrink-0 animate-pulse"></span>
                            <span>Perlu follow-up segera (sudah {{ $p->days_since_last_activity }} hari tidak ada interaksi)</span>
                        </div>
                    @endif

                    <div class="text-body-sm text-body flex items-center justify-between pt-1">
                        <span class="font-medium text-ink">{{ $p->motorcycle->name ?? '-' }}</span>
                        <span class="text-caption text-mute">{{ $p->source->name ?? '-' }}</span>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-hairline text-caption text-mute">
                        <span>
                            @if($p->latestActivity)
                                <strong class="uppercase text-primary">{{ $p->latestActivity->type }}</strong> · {{ $p->latestActivity->activity_at->diffForHumans() }}
                            @else
                                Belum ada aktivitas
                            @endif
                        </span>
                        <a href="{{ route('prospects.show', $p->id) }}" class="font-semibold text-primary hover:underline">
                            Detail &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-mute text-body-sm">
                    Tidak ada data prospek ditemukan.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($prospects->hasPages())
            <div class="p-4 border-t border-hairline bg-surface">
                {{ $prospects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
