@extends('layouts.guest')

@section('title', 'Masuk ke Sistem')

@section('content')
<div class="relative min-h-screen flex flex-col justify-center py-10 sm:px-6 lg:px-8 bg-gradient-to-b from-[#1c4870] via-[#14395a] to-[#0c253d] overflow-hidden">
    <!-- Subtle Ambient Glow -->
    <div class="absolute inset-0 bg-glow pointer-events-none opacity-30"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 text-center px-4">
        <!-- Logo Image: Free-floating without background box, exactly like Yamaha reference -->
        <div class="mx-auto flex items-center justify-center mb-2">
            <img src="{{ asset('images/logo.png') }}?v={{ file_exists(public_path('images/logo.png')) ? filemtime(public_path('images/logo.png')) : time() }}"
                 alt="JG Motor Purwakarta - SIMPRO Logo"
                 class="h-20 sm:h-24 w-auto max-w-[250px] object-contain drop-shadow-[0_4px_14px_rgba(0,0,0,0.35)]">
        </div>
        <h1 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
            SIMPRO
        </h1>
        <p class="mt-1 text-xs sm:text-sm text-blue-100/80 max-w-sm mx-auto font-medium">
            Sistem Informasi Monitoring Prospek &amp; Aktivitas Sales
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
        <div class="rounded-3xl bg-white p-7 sm:p-9 shadow-2xl border border-white/40">
            @if(session('info'))
                <div class="mb-5 rounded-2xl bg-blue-50 p-3.5 text-xs text-blue-900 border border-blue-200">
                    {{ session('info') }}
                </div>
            @endif

            <form class="space-y-4" action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="mb-1.5 block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="{{ old('email', 'admin@simpro.com') }}"
                               placeholder="nama@dealer.com"
                               class="h-11 w-full rounded-2xl border-2 border-slate-200 bg-white pl-10 pr-4 text-sm font-medium text-slate-900 placeholder:text-slate-400 hover:border-slate-300 focus:border-[#0A4DF3] focus:ring-4 focus:ring-blue-100 focus:outline-none transition @error('email') border-red-400 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="mb-1.5 block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               value="password"
                               placeholder="••••••••"
                               class="h-11 w-full rounded-2xl border-2 border-slate-200 bg-white pl-10 pr-4 text-sm font-medium text-slate-900 placeholder:text-slate-400 hover:border-slate-300 focus:border-[#0A4DF3] focus:ring-4 focus:ring-blue-100 focus:outline-none transition @error('password') border-red-400 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 rounded border-slate-300 text-[#0A4DF3] focus:ring-[#0A4DF3]">
                        <label for="remember" class="ml-2 block text-xs font-medium text-slate-600">
                            Ingat saya di perangkat ini
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-[#0A4DF3] px-6 text-sm font-bold text-white shadow-lg shadow-blue-600/30 transition hover:bg-[#0639B8] hover:shadow-xl active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-blue-200 cursor-pointer">
                        Masuk
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Selector -->
            <div class="mt-6 pt-5 border-t border-slate-100">
                <p class="text-[11px] font-semibold text-slate-400 text-center uppercase tracking-wider mb-2.5">Login Cepat Demo Akun:</p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                            onclick="setDemo('admin@simpro.com', 'password')"
                            class="rounded-2xl border border-slate-200 bg-slate-50/70 p-2.5 text-left hover:border-[#0A4DF3] hover:bg-blue-50/50 transition cursor-pointer">
                        <div class="text-xs font-bold text-slate-900">Admin Dealer</div>
                        <div class="text-[11px] text-slate-500 truncate">admin@simpro.com</div>
                    </button>
                    <button type="button"
                            onclick="setDemo('sales1@simpro.com', 'password')"
                            class="rounded-2xl border border-slate-200 bg-slate-50/70 p-2.5 text-left hover:border-[#0A4DF3] hover:bg-blue-50/50 transition cursor-pointer">
                        <div class="text-xs font-bold text-slate-900">Sales (Budi)</div>
                        <div class="text-[11px] text-slate-500 truncate">sales1@simpro.com</div>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center space-y-1">
            <p class="text-xs font-semibold text-blue-100/90 tracking-wide">
                JG Motor Purwakarta
            </p>
            <p class="text-[11px] text-blue-200/60">
                Versi 1.0 · SIMPRO &copy; {{ date('Y') }}
            </p>
        </div>
    </div>
</div>

<script>
    function setDemo(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
