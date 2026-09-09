<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Dashboard Administrator</h1>
            <span class="text-xs font-mono text-ink-faint">Superadmin Console</span>
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 space-y-6">
        <!-- Admin Operator Header -->
        <div class="border border-border bg-surface p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-organik"></span>
                    <span class="text-xs uppercase font-mono tracking-wider font-semibold text-ink">Master Controller // EcoPoints</span>
                </div>
                <h2 class="text-lg font-bold text-ink">Selamat Datang, {{ Auth::user()->name }}</h2>
                <p class="text-xs text-ink-muted mt-0.5">Kelola master data klasifikasi sampah, jaringan drop point, inventaris reward, hak akses, dan audit analitik.</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="btn-primary whitespace-nowrap text-xs text-center">
                Buka Laporan & Analitik &rarr;
            </a>
        </div>

        <!-- Section: Master Management Navigation -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs uppercase font-mono tracking-wider text-ink-faint font-semibold">Modul Manajemen</span>
                <span class="text-[11px] font-mono text-ink-faint">5 modul aktif</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <a href="{{ route('admin.waste-types.index') }}" class="border border-border bg-surface p-4 hover:border-ink transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono text-ink-faint uppercase">[01] Klasifikasi</span>
                        <span class="text-xs font-mono text-organik font-bold">RATE</span>
                    </div>
                    <div class="mt-2 text-sm font-bold text-ink group-hover:text-primary transition">Jenis Sampah & Poin &rarr;</div>
                    <p class="text-xs text-ink-muted mt-1">Konfigurasi tarif rupiah dan nilai poin per kilogram sampah daur ulang.</p>
                </a>

                <a href="{{ route('admin.drop-points.index') }}" class="border border-border bg-surface p-4 hover:border-ink transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono text-ink-faint uppercase">[02] Lokasi</span>
                        <span class="text-xs font-mono text-anorganik font-bold">NODE</span>
                    </div>
                    <div class="mt-2 text-sm font-bold text-ink group-hover:text-primary transition">Drop Point Mitra &rarr;</div>
                    <p class="text-xs text-ink-muted mt-1">Kelola pos penimbangan, titik pengumpulan, koordinat, dan status mitra.</p>
                </a>

                <a href="{{ route('admin.rewards.index') }}" class="border border-border bg-surface p-4 hover:border-ink transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono text-ink-faint uppercase">[03] Inventaris</span>
                        <span class="text-xs font-mono text-accent-poin font-bold">STOCK</span>
                    </div>
                    <div class="mt-2 text-sm font-bold text-ink group-hover:text-primary transition">Katalog Reward &rarr;</div>
                    <p class="text-xs text-ink-muted mt-1">Kelola stok hadiah, voucher, sembako, dan biaya penukaran poin nasabah.</p>
                </a>

                <a href="{{ route('admin.users.index') }}" class="border border-border bg-surface p-4 hover:border-ink transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono text-ink-faint uppercase">[04] Otoritas</span>
                        <span class="text-xs font-mono text-ink font-bold">AUTH</span>
                    </div>
                    <div class="mt-2 text-sm font-bold text-ink group-hover:text-primary transition">Pengguna & Role &rarr;</div>
                    <p class="text-xs text-ink-muted mt-1">Atur hak akses staf/petugas drop point, admin, dan monitoring nasabah.</p>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="border border-border bg-surface p-4 hover:border-ink transition group sm:col-span-2 lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono text-ink-faint uppercase">[05] Statistik</span>
                        <span class="text-xs font-mono text-organik font-bold">AUDIT</span>
                    </div>
                    <div class="mt-2 text-sm font-bold text-ink group-hover:text-primary transition">Laporan & Sirkulasi Poin &rarr;</div>
                    <p class="text-xs text-ink-muted mt-1">Analitik volume sampah masuk, perputaran EcoPoints, dan nasabah paling aktif.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
