<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-4 border-b border-border pb-3">
        <h2 class="text-sm font-mono font-bold uppercase text-ink">Masuk Akun Nasabah / Staf</h2>
        <p class="text-xs text-ink-muted mt-0.5">Akses saldo EcoPoints, riwayat timbangan, dan transaksi.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nasabah@ecopoints.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs font-mono">
            <label for="remember_me" class="inline-flex items-center gap-1.5 cursor-pointer text-ink">
                <input id="remember_me" type="checkbox" class="rounded-none border-border bg-bg text-ink focus:ring-0" name="remember">
                <span>Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-ink-muted hover:text-ink underline text-[11px]" href="{{ route('password.request') }}">
                    Lupa sandi?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full py-2.5 text-center text-xs">
                [Masuk Sekarang]
            </x-primary-button>
        </div>

        <div class="pt-2 text-center text-xs font-mono text-ink-muted border-t border-border">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-ink font-bold underline">Daftar Nasabah</a>
        </div>
    </form>
</x-guest-layout>
