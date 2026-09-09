<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Dashboard Nasabah</h1>
            <span class="text-xs text-ink-faint">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Saldo Poin --}}
        <div class="card">
            <div class="p-5 flex items-start justify-between">
                <div>
                    <p class="text-xs text-ink-faint mb-1">Saldo Poin Anda</p>
                    <p class="font-mono text-5xl font-bold tabular-nums text-poin leading-none">
                        {{ number_format(Auth::user()->points_balance) }}
                    </p>
                    <p class="text-xs text-ink-faint mt-2">poin aktif · bisa ditukar sekarang</p>
                </div>
                <a href="{{ route('deposits.create') }}" class="btn-primary mt-1">
                    Setor Sampah
                </a>
            </div>
        </div>

        {{-- Quick links --}}
        <div class="grid grid-cols-3 gap-3">
            @php
                $links = [
                    ['label' => 'Riwayat Setoran', 'route' => 'deposits.index', 'desc' => 'Pantau status'],
                    ['label' => 'Mutasi Poin',     'route' => 'points.index',   'desc' => 'Keluar & masuk'],
                    ['label' => 'Katalog Reward',  'route' => 'rewards.index',  'desc' => 'Tukar poin'],
                ];
            @endphp
            @foreach($links as $link)
                <a href="{{ route($link['route']) }}" class="card p-4 hover:bg-surface transition-colors group">
                    <p class="text-sm font-medium text-ink group-hover:text-primary transition-colors">{{ $link['label'] }}</p>
                    <p class="text-xs text-ink-faint mt-0.5">{{ $link['desc'] }}</p>
                </a>
            @endforeach
        </div>

        {{-- Info --}}
        <div class="card p-5">
            <p class="text-sm font-medium text-ink mb-1">Cara kerja EcoPoints</p>
            <ol class="text-sm text-ink-muted space-y-1 list-decimal list-inside">
                <li>Bawa sampah daur ulang ke drop point terdekat</li>
                <li>Petugas menimbang dan memverifikasi setoran</li>
                <li>Poin masuk otomatis ke saldo kamu</li>
                <li>Tukar poin dengan reward pilihan</li>
            </ol>
        </div>

    </div>
</x-app-layout>
