<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Antrean Verifikasi Setoran</h1>
            @if ($pendingCount > 0)
                <span class="text-xs font-mono text-accent-poin font-semibold">
                    [{{ $pendingCount }} antrean menunggu]
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 space-y-4">
        @if (session('success'))
            <div class="p-3 bg-surface border border-organik/40 text-organik text-xs font-mono">
                [OK] {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="p-3 bg-surface border border-b3/40 text-b3 text-xs font-mono">
                [PERINGATAN] {{ session('warning') }}
            </div>
        @endif

        <!-- Filter bar -->
        <div class="border border-border bg-surface p-3 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center gap-4 text-xs font-mono overflow-x-auto pb-1">
                <a href="{{ route('petugas.deposits.index', ['status' => 'pending', 'drop_point_id' => request('drop_point_id')]) }}"
                   class="pb-1 transition whitespace-nowrap {{ $status === 'pending' ? 'border-b-2 border-ink text-ink font-bold' : 'text-ink-muted hover:text-ink' }}">
                    Menunggu ({{ $pendingCount }})
                </a>
                <a href="{{ route('petugas.deposits.index', ['status' => 'verified', 'drop_point_id' => request('drop_point_id')]) }}"
                   class="pb-1 transition whitespace-nowrap {{ $status === 'verified' ? 'border-b-2 border-ink text-ink font-bold' : 'text-ink-muted hover:text-ink' }}">
                    Terverifikasi
                </a>
                <a href="{{ route('petugas.deposits.index', ['status' => 'rejected', 'drop_point_id' => request('drop_point_id')]) }}"
                   class="pb-1 transition whitespace-nowrap {{ $status === 'rejected' ? 'border-b-2 border-ink text-ink font-bold' : 'text-ink-muted hover:text-ink' }}">
                    Ditolak
                </a>
                <a href="{{ route('petugas.deposits.index', ['status' => 'all', 'drop_point_id' => request('drop_point_id')]) }}"
                   class="pb-1 transition whitespace-nowrap {{ $status === 'all' ? 'border-b-2 border-ink text-ink font-bold' : 'text-ink-muted hover:text-ink' }}">
                    Semua
                </a>
            </div>

            <!-- Drop point filter -->
            <form method="GET" action="{{ route('petugas.deposits.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ $status }}">
                <select name="drop_point_id" onchange="this.form.submit()" class="text-xs bg-bg border border-border text-ink rounded-none py-1 px-2 focus:ring-1 focus:ring-ink focus:border-ink font-mono">
                    <option value="">-- Semua Drop Point --</option>
                    @foreach ($dropPoints as $point)
                        <option value="{{ $point->id }}" {{ request('drop_point_id') == $point->id ? 'selected' : '' }}>
                            {{ $point->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Table -->
        <div class="border border-border bg-surface overflow-hidden">
            @if ($deposits->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-border bg-bg/40 text-ink-muted uppercase font-mono text-[11px]">
                            <tr>
                                <th class="py-2.5 px-4">ID / Waktu</th>
                                <th class="py-2.5 px-4">Nasabah</th>
                                <th class="py-2.5 px-4">Kategori Sampah</th>
                                <th class="py-2.5 px-4">Drop Point</th>
                                <th class="py-2.5 px-4 text-right">Berat (Kg)</th>
                                <th class="py-2.5 px-4 text-center">Status</th>
                                <th class="py-2.5 px-4 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60">
                            @foreach ($deposits as $item)
                                @php
                                    $catName = strtolower($item->wasteType?->name ?? '');
                                    $dotClass = str_contains($catName, 'organik') && !str_contains($catName, 'anorganik') 
                                        ? 'bg-organik' 
                                        : (str_contains($catName, 'b3') || str_contains($catName, 'elektronik') ? 'bg-b3' : 'bg-anorganik');
                                @endphp
                                <tr class="hover:bg-bg/40 transition">
                                    <td class="py-3 px-4 whitespace-nowrap font-mono text-ink">
                                        <span class="font-semibold">#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-ink-faint block text-[11px]">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-ink">{{ $item->user?->name }}</div>
                                        <div class="text-[11px] font-mono text-ink-faint">{{ $item->user?->email }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-1.5 font-medium text-ink">
                                            <span class="w-2 h-2 rounded-full {{ $dotClass }}"></span>
                                            <span>{{ $item->wasteType?->name }}</span>
                                        </div>
                                        <span class="text-[11px] font-mono text-ink-faint">{{ number_format($item->wasteType?->points_per_kg) }} pt/kg</span>
                                    </td>
                                    <td class="py-3 px-4 text-ink-muted text-[11px]">
                                        {{ $item->dropPoint?->name }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-ink whitespace-nowrap">
                                        {{ number_format($item->weight_kg, 2) }} kg
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        @if ($item->status === 'verified')
                                            <span class="inline-flex items-center gap-1 text-organik font-mono text-[11px]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-organik"></span> Valid
                                            </span>
                                        @elseif ($item->status === 'rejected')
                                            <span class="inline-flex items-center gap-1 text-b3 font-mono text-[11px]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-b3"></span> Tolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-accent-poin font-mono text-[11px]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-accent-poin"></span> Antre
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <a href="{{ route('petugas.deposits.show', $item) }}"
                                           class="{{ $item->status === 'pending' ? 'btn-primary text-xs py-1 px-3' : 'text-xs text-ink-muted hover:text-ink font-mono underline' }}">
                                            {{ $item->status === 'pending' ? 'Timbang' : 'Detail' }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($deposits->hasPages())
                    <div class="p-3 border-t border-border bg-bg/20">
                        {{ $deposits->links() }}
                    </div>
                @endif
            @else
                <div class="py-12 text-center text-ink-faint font-mono text-xs">
                    -- Tidak ada antrean setoran sampah untuk filter ini --
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
