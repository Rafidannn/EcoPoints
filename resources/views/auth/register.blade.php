<x-guest-layout>
    <div class="mb-4 border-b border-border pb-3">
        <h2 class="text-sm font-mono font-bold uppercase text-ink">Registrasi Nasabah Baru</h2>
        <p class="text-xs text-ink-muted mt-0.5">Mulai kumpulkan EcoPoints dari sampah rumah tangga Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="email@domain.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full py-2.5 text-center text-xs">
                [Daftar Akun Nasabah]
            </x-primary-button>
        </div>

        <div class="pt-2 text-center text-xs font-mono text-ink-muted border-t border-border">
            Sudah terdaftar? 
            <a href="{{ route('login') }}" class="text-ink font-bold underline">Masuk di sini</a>
        </div>
    </form>
</x-guest-layout>
