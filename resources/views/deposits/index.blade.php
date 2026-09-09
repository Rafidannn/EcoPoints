<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Riwayat Setoran Sampah</h1>
            <a href="{{ route('deposits.create') }}" class="btn-primary text-xs py-1.5 px-3">Setor sampah</a>
        </div>
    </x-slot>

    <div class="space-y-4">

        @if(session('success'))
            <div class="alert-success">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Status filter tabs --}}
        <div class="flex items-center gap-1 border-b border-border">
            @php
                $tabs = [
                    [null,       'Semua'],
                    ['pending',  'Menunggu'],
                    ['verified', 'Terverifikasi'],
                    ['rejected', 'Ditolak'],
                ];
            @endphp
            @foreach($tabs as [$val, $label])
                <a href="{{ route('deposits.index', $val ? ['status' => $val] : []) }}"
                   class="px-3 py-2.5 text-xs transition-colors border-b-2 -mb-px
                       {{ request('status') === $val ? 'border-primary text-ink font-medium' : 'border-transparent text-ink-muted hover:text-ink' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- List --}}
        <div class="card">
            @if($deposits->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis sampah</th>
                            <th>Drop point</th>
                            <th class="text-right">Berat</th>
                            <th class="text-right">Poin</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deposits as $item)
                            @php
                                $estPts = floor($item->weight_kg * ($item->wasteType?->points_per_kg ?? 0));
                            @endphp
                            <tr>
                                <td class="text-xs text-ink-muted whitespace-nowrap font-mono">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                                <td>{{ $item->wasteType?->name ?? '—' }}</td>
                                <td class="text-ink-muted text-xs">{{ $item->dropPoint?->name ?? '—' }}</td>
                                <td class="text-right font-mono tabular-nums">{{ number_format($item->weight_kg, 2) }} kg</td>
                                <td class="text-right font-mono tabular-nums">
                                    @if($item->status === 'verified')
                                        <span class="text-poin font-semibold">+{{ number_format($estPts) }}</span>
                                    @else
                                        <span class="text-ink-faint text-xs">~{{ number_format($estPts) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status === 'verified')
                                        <span class="status-verified">
                                            <span class="w-1.5 h-1.5 rounded-full bg-organik inline-block"></span>
                                            Terverifikasi
                                        </span>
                                    @elseif($item->status === 'rejected')
                                        <span class="status-rejected">
                                            <span class="w-1.5 h-1.5 rounded-full bg-b3 inline-block"></span>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="status-pending">
                                            <span class="w-1.5 h-1.5 rounded-full bg-poin inline-block"></span>
                                            Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('deposits.show', $item) }}" class="text-xs text-ink-muted hover:text-ink transition-colors">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($deposits->hasPages())
                    <div class="px-5 py-3 border-t border-border">{{ $deposits->links() }}</div>
                @endif
            @else
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-ink-muted">Belum ada setoran.</p>
                    <a href="{{ route('deposits.create') }}" class="text-sm text-primary hover:underline mt-1 inline-block">Setor sampah sekarang</a>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
