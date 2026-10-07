<!DOCTYPE html>
<html lang="id" class="h-full bg-canvas-soft">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMPRO') — Sistem Informasi Monitoring Prospek</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v={{ time() }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v={{ time() }}">

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-body antialiased flex">

    <!-- Mobile Sidebar Backdrop -->
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-surface border-r border-hairline transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:z-auto">
        <!-- Brand / Logo -->
        <div class="flex h-16 shrink-0 items-center justify-between px-5 border-b border-hairline bg-white">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}?v={{ file_exists(public_path('images/logo.png')) ? filemtime(public_path('images/logo.png')) : time() }}" alt="JG Motor Logo" class="h-9 w-auto max-w-[52px] object-contain drop-shadow-sm">
                <div class="flex flex-col min-w-0">
                    <span class="font-display text-base font-extrabold text-ink tracking-tight group-hover:text-[#0A4DF3] transition leading-none">SIMPRO</span>
                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#0A4DF3] truncate mt-1">JG Motor Purwakarta</span>
                </div>
            </a>
            <button type="button" class="lg:hidden text-mute hover:text-ink" onclick="toggleSidebar()">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex flex-1 flex-col justify-between overflow-y-auto px-4 py-6">
            <nav class="space-y-1">
                <div class="px-2 pb-2 text-[11px] font-semibold uppercase tracking-wider text-mute">Menu Utama</div>

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('dashboard') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Prospects -->
                <a href="{{ route('prospects.index') }}"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('prospects.*') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('prospects.*') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span>{{ auth()->user()->isAdmin() ? 'Semua Prospek' : 'Prospek Saya' }}</span>
                </a>

                <!-- Training / Knowledge Sales -->
                <a href="{{ route('trainings.index') }}"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('trainings.*') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('trainings.*') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span>Training Sales</span>
                </a>

                <!-- Tes Online Mingguan -->
                <a href="{{ route('weekly-tests.index') }}"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('weekly-tests.*') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('weekly-tests.*') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                    <span>Tes Mingguan</span>
                </a>

                <!-- Profil Saya -->
                <a href="{{ route('profile.show') }}"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('profile.*') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('profile.*') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span>Profil Saya</span>
                </a>

                @if(auth()->user()->isAdmin())
                    <div class="pt-5 px-2 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">Master Data & Pengguna</div>

                    <!-- Master Motor -->
                    <a href="{{ route('master.motorcycles.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('master.motorcycles.*') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('master.motorcycles.*') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25V4.5m0 0H9.75M14.25 7.5H9.75" />
                        </svg>
                        <span>Data Motor</span>
                    </a>

                    <!-- Master Sumber -->
                    <a href="{{ route('master.sources.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('master.sources.*') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('master.sources.*') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" />
                        </svg>
                        <span>Sumber Prospek</span>
                    </a>

                    <!-- Master Status -->
                    <a href="{{ route('master.statuses.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('master.statuses.*') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('master.statuses.*') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Status Prospek</span>
                    </a>

                    <!-- Kelola Modul Training (Admin) -->
                    <a href="{{ route('admin.trainings.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('admin.trainings.*') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.trainings.*') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                        <span>Kelola Training</span>
                    </a>

                    <!-- Kelola Tes Mingguan (Admin) -->
                    <a href="{{ route('admin.weekly-tests.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('admin.weekly-tests.*') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.weekly-tests.*') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        <span>Kelola Tes Mingguan</span>
                    </a>

                    <!-- Pengguna -->
                    <a href="{{ route('users.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('users.*') ? 'bg-blue-50/90 text-[#0A4DF3] font-bold border-l-4 border-[#0A4DF3] shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold' }}">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('users.*') ? 'text-[#0A4DF3]' : 'text-slate-500 group-hover:text-slate-800' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span>Kelola Pengguna</span>
                    </a>
                @endif
            </nav>

            <!-- User profile footer -->
            <div class="mt-auto pt-6 border-t border-hairline">
                <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-blue-50/80 transition group border border-transparent hover:border-blue-200">
                    @if(auth()->user()->foto_url)
                        <img src="{{ auth()->user()->foto_url }}" alt="{{ auth()->user()->name }}"
                             class="h-10 w-10 shrink-0 rounded-full object-cover border-2 border-blue-200 shadow-sm">
                    @else
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-[#0A4DF3] font-bold text-sm border-2 border-blue-200 shadow-sm">
                            {{ auth()->user()->avatar_initials }}
                        </div>
                    @endif
                    <div class="flex flex-col min-w-0 flex-1">
                        <span class="text-sm font-bold text-slate-900 truncate group-hover:text-[#0A4DF3] transition">{{ auth()->user()->name }}</span>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs text-slate-500 capitalize font-medium">{{ auth()->user()->role }}</span>
                            <span class="text-[10px] text-primary font-bold opacity-0 group-hover:opacity-100 transition">&rarr; Profil</span>
                        </div>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl border-2 border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-300 shadow-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex flex-1 flex-col min-w-0 overflow-hidden">
        <!-- Top Navbar -->
        <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-hairline bg-surface/95 px-4 sm:px-6 lg:px-8 backdrop-blur shadow-sm">
            <div class="flex items-center gap-4">
                <button type="button" class="lg:hidden text-slate-700 hover:text-[#0A4DF3] p-1.5 rounded-lg border border-slate-200" onclick="toggleSidebar()">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <h1 class="text-heading-md font-bold text-slate-900">@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('prospects.create') }}" class="inline-flex h-10 items-center gap-2 rounded-xl bg-[#0A4DF3] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#0639B8] hover:shadow-md active:scale-[0.98] border-2 border-[#0A4DF3] shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span class="hidden sm:inline">Tambah Prospek</span>
                </a>

                <!-- Profile Quick Link Button -->
                <a href="{{ route('profile.show') }}" title="Profil Saya" class="flex items-center gap-2 p-1 rounded-xl hover:bg-slate-100 transition border border-slate-200">
                    @if(auth()->user()->foto_url)
                        <img src="{{ auth()->user()->foto_url }}" alt="{{ auth()->user()->name }}" class="h-8 w-8 rounded-lg object-cover border border-blue-300">
                    @else
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-[#0A4DF3] font-bold text-xs border border-blue-300">
                            {{ auth()->user()->avatar_initials }}
                        </div>
                    @endif
                    <span class="hidden md:inline text-xs font-bold text-slate-700 max-w-[120px] truncate pr-2">{{ auth()->user()->name }}</span>
                </a>
            </div>
        </header>

        <!-- Flash Messages / Toast Alerts -->
        @if(session('success'))
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                <div class="flex items-center justify-between gap-3 rounded-lg border border-green-200 bg-success-soft p-4 text-body-sm text-success-ink shadow-elev-1">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-success shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-success-ink/70 hover:text-success-ink">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                <div class="flex items-center justify-between gap-3 rounded-lg border border-blue-200 bg-info-soft p-4 text-body-sm text-info-ink shadow-elev-1">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-info shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-info-ink/70 hover:text-info-ink">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                <div class="flex items-center justify-between gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-body-sm text-red-900 shadow-elev-1">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-700/70 hover:text-red-900">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                <div class="rounded-lg border border-red-200 bg-error-soft p-4 text-body-sm text-error-ink shadow-elev-1">
                    <div class="flex items-center gap-2 font-medium">
                        <svg class="h-5 w-5 text-error shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                        <span>Mohon periksa kembali input Anda:</span>
                    </div>
                    <ul class="mt-2 list-disc list-inside space-y-1 text-caption text-error-ink">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Main Page Content -->
        <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-container">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Script to toggle mobile sidebar -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
