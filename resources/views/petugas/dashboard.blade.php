<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Dashboard Petugas Drop Point</h1>
            <span class="text-xs font-mono text-ink-faint">Pos Penimbangan & Verifikasi</span>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 space-y-6">
        <!-- Scale Operator Banner -->
        <div class="border border-border bg-surface p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-organik"></span>
                    <span class="text-xs uppercase font-mono tracking-wider font-semibold text-ink">Station Petugas Aktif</span>
                </div>
                <h2 class="text-lg font-bold text-ink">{{ Auth::user()->name }}</h2>
                <p class="text-xs text-ink-muted mt-0.5">Timbang fisik sampah daur ulang nasabah, sesuaikan berat riil, dan validasi kredit poin.</p>
            </div>
            <a href="{{ route('petugas.deposits.index') }}" class="btn-primary whitespace-nowrap text-xs text-center">
                Buka Antrean Setoran &rarr;
            </a>
        </div>

        <!-- Quick Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('petugas.deposits.index', ['status' => 'pending']) }}" class="border border-border bg-surface p-4 hover:border-ink transition group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono text-ink-faint uppercase tracking-wider">Antrean Verifikasi</span>
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-accent-poin"></span>
                </div>
                <div class="mt-2 text-2xl font-mono font-bold text-ink group-hover:text-primary transition">
                    Antrean Menunggu &rarr;
                </div>
                <p class="text-xs text-ink-muted mt-1">Periksa fisik dan timbang ulang sampah yang disetor warga.</p>
            </a>

            <a href="{{ route('petugas.deposits.index', ['status' => 'verified']) }}" class="border border-border bg-surface p-4 hover:border-ink transition group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono text-ink-faint uppercase tracking-wider">Riwayat Validasi</span>
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-organik"></span>
                </div>
                <div class="mt-2 text-2xl font-mono font-bold text-ink group-hover:text-primary transition">
                    Setoran Selesai &rarr;
                </div>
                <p class="text-xs text-ink-muted mt-1">Daftar setoran yang telah sukses ditimbang dan dikreditkan poinnya.</p>
            </a>
        </div>
    </div>
</x-app-layout>
