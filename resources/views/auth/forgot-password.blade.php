<x-guest-layout>
    <div class="mb-4 border-b border-border pb-3">
        <h2 class="text-sm font-mono font-bold uppercase text-ink">Reset Kata Sandi</h2>
        <p class="text-xs text-ink-muted mt-0.5">Masukkan alamat email Anda untuk menerima tautan pemulihan kata sandi.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email *" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="email@domain.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full py-2.5 text-center text-xs">
                [Kirim Tautan Reset Sandi]
            </x-primary-button>
        </div>

        <div class="pt-2 text-center text-xs font-mono text-ink-muted border-t border-border">
            <a href="{{ route('login') }}" class="text-ink font-bold underline">&larr; Kembali ke Login</a>
        </div>
    </form>
</x-guest-layout>
