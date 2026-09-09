<x-guest-layout>
    <div class="mb-4 border-b border-border pb-3">
        <h2 class="text-sm font-mono font-bold uppercase text-ink">Konfirmasi Keamanan</h2>
        <p class="text-xs text-ink-muted mt-0.5">Area ini memerlukan konfirmasi kata sandi sebelum melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi *" />
            <x-text-input id="password" class="block w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password"
                          placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full py-2.5 text-center text-xs">
                [Konfirmasi Akses]
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
