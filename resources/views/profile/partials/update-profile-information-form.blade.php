<section>
    <header class="border-b border-border pb-3 mb-4">
        <h2 class="text-xs font-mono uppercase font-bold text-ink">
            Informasi Profil
        </h2>
        <p class="mt-0.5 text-xs text-ink-muted">
            Perbarui nama lengkap dan alamat email akun Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama Lengkap *" />
            <x-text-input id="name" name="name" type="text" class="block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Alamat Email *" />
            <x-text-input id="email" name="email" type="email" class="block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-xs text-ink-muted">
                    <p>
                        Email belum diverifikasi.
                        <button form="send-verification" class="text-ink font-bold underline font-mono">
                            Kirim ulang email verifikasi
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-xs font-mono text-organik">
                            [OK] Tautan verifikasi baru telah dikirim ke email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 pt-2">
            <x-primary-button>[Simpan Perubahan]</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-mono text-organik"
                >[Tersimpan]</p>
            @endif
        </div>
    </form>
</section>
