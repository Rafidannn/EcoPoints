<x-guest-layout>
    <div class="mb-4 border-b border-border pb-3">
        <h2 class="text-sm font-mono font-bold uppercase text-ink">Verifikasi Alamat Email</h2>
        <p class="text-xs text-ink-muted mt-0.5">Terima kasih telah mendaftar! Silakan klik tautan verifikasi yang kami kirimkan ke email Anda.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 bg-bg border border-organik/40 text-organik text-xs font-mono">
            [OK] Tautan verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="text-xs">
                [Kirim Ulang Email Verifikasi]
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs font-mono text-ink-muted hover:text-ink underline">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
