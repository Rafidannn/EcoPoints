<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Riwayat Penukaran Reward</h1>
            <a href="{{ route('rewards.index') }}" class="text-xs text-ink-muted hover:text-ink transition">
                &larr; Katalog Reward
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6">
        @if (session('success'))
            <div class="mb-4 p-3 bg-surface border border-organik/40 text-organik text-xs font-mono">
                [OK] {{ session('success') }}
            </div>
        @endif

        <div class="border border-border bg-surface overflow-hidden">
            <div class="p-4 border-b border-border flex items-center justify-between bg-bg/50">
                <div>
                    <h2 class="text-xs uppercase tracking-wider font-mono font-semibold text-ink">Log Penukaran</h2>
                    <p class="text-xs text-ink-faint">Daftar klaim reward dengan EcoPoints Anda.</p>
                </div>
                <span class="text-xs font-mono text-ink-faint">{{ $redemptions->total() }} transaksi</span>
            </div>

            @if ($redemptions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-border bg-bg/30 text-ink-muted uppercase font-mono text-[11px]">
                            <tr>
                                <th class="py-2.5 px-4">Klaim # / Waktu</th>
                                <th class="py-2.5 px-4">Item Reward</th>
                                <th class="py-2.5 px-4 text-right">Poin</th>
                                <th class="py-2.5 px-4 text-center">Status</th>
                                <th class="py-2.5 px-4">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60">
                            @foreach ($redemptions as $item)
                                <tr class="hover:bg-bg/40 transition">
                                    <td class="py-3 px-4 whitespace-nowrap font-mono text-ink">
                                        <span class="font-semibold">#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-ink-faint block text-[11px]">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-ink">{{ $item->reward?->name ?? 'Reward' }}</div>
                                        @if($item->reward?->description)
                                            <div class="text-[11px] text-ink-faint line-clamp-1">{{ $item->reward->description }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-b3 whitespace-nowrap">
                                        -{{ number_format($item->points_used) }}
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        @if ($item->status === 'completed' || $item->status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 text-organik font-mono text-[11px]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-organik"></span> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-accent-poin font-mono text-[11px]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-accent-poin"></span> Proses
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-ink-muted text-[11px]">
                                        {{ $item->notes ?: 'Tersedia untuk diambil / digunakan.' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($redemptions->hasPages())
                    <div class="p-3 border-t border-border bg-bg/20">
                        {{ $redemptions->links() }}
                    </div>
                @endif
            @else
                <div class="py-12 text-center text-ink-muted text-xs">
                    <p class="font-mono text-ink-faint mb-3">-- Belum ada penukaran reward --</p>
                    <a href="{{ route('rewards.index') }}" class="btn-primary text-xs">
                        Buka Katalog Reward &rarr;
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
