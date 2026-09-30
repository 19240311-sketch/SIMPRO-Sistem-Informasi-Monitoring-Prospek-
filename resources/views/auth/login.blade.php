@extends('layouts.guest')

@section('title', 'Masuk ke Sistem')

@section('content')
<div class="relative min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-canvas overflow-hidden">
    <!-- Glow Background Effect -->
    <div class="absolute inset-0 bg-glow pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 text-center">
        <!-- Logo Image -->
        <div class="mx-auto flex items-center justify-center mb-2">
            <img src="{{ asset('images/logo.png') }}" alt="SIMPRO Logo" class="h-20 w-20 object-contain drop-shadow-lg">
        </div>
        <h1 class="mt-2 font-display text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
            SIMPRO
        </h1>
        <p class="mt-2 text-body-sm text-mute max-w-sm mx-auto">
            Sistem Informasi Monitoring Prospek &amp; Aktivitas Follow-up Dealer Sepeda Motor
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
        <div class="rounded-2xl bg-surface p-8 shadow-elev-3 border border-hairline sm:px-10">
            @if(session('info'))
                <div class="mb-6 rounded-lg bg-info-soft p-3 text-body-sm text-info-ink border border-blue-200">
                    {{ session('info') }}
                </div>
            @endif

            <form class="space-y-5" action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="mb-xs block text-body-sm font-medium text-ink">
                        Alamat Email <span class="text-error">*</span>
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email', 'admin@simpro.com') }}"
                           placeholder="nama@dealer.com"
                           class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-md text-ink placeholder:text-mute hover:border-mute focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0 @error('email') border-error focus:border-error focus:shadow-focus-error @enderror">
                    @error('email')
                        <p class="mt-xs text-caption text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-xs">
                        <label for="password" class="block text-body-sm font-medium text-ink">
                            Kata Sandi <span class="text-error">*</span>
                        </label>
                    </div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           value="password"
                           placeholder="••••••••"
                           class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-md text-ink placeholder:text-mute hover:border-mute focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0 @error('password') border-error focus:border-error focus:shadow-focus-error @enderror">
                    @error('password')
                        <p class="mt-xs text-caption text-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 rounded border-hairline-strong text-primary focus:ring-primary">
                        <label for="remember" class="ml-2 block text-body-sm text-body">
                            Ingat saya di perangkat ini
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                            class="w-full inline-flex h-11 items-center justify-center gap-2 rounded-pill bg-primary px-6 text-button-md text-on-primary transition hover:bg-primary-deep hover:shadow-elev-2 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                        Masuk ke Dashboard
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Selector -->
            <div class="mt-6 pt-6 border-t border-hairline">
                <p class="text-caption text-mute text-center mb-3">Login Cepat Demo Akun:</p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                            onclick="setDemo('admin@simpro.com', 'password')"
                            class="rounded-lg border border-hairline-strong bg-canvas-soft px-3 py-2 text-left hover:border-primary hover:bg-primary-soft transition">
                        <div class="text-xs font-semibold text-ink">Admin Dealer</div>
                        <div class="text-[11px] text-mute truncate">admin@simpro.com</div>
                    </button>
                    <button type="button"
                            onclick="setDemo('sales1@simpro.com', 'password')"
                            class="rounded-lg border border-hairline-strong bg-canvas-soft px-3 py-2 text-left hover:border-primary hover:bg-primary-soft transition">
                        <div class="text-xs font-semibold text-ink">Sales (Budi)</div>
                        <div class="text-[11px] text-mute truncate">sales1@simpro.com</div>
                    </button>
                </div>
            </div>
        </div>

        <p class="mt-8 text-center text-caption text-mute">
            &copy; {{ date('Y') }} SIMPRO · Yamaha Dealer Prospect Management
        </p>
    </div>
</div>

<script>
    function setDemo(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
