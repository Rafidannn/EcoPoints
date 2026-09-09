<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Master Drop Point</h1>
            <a href="{{ route('admin.drop-points.create') }}" class="btn-primary text-xs">
                + Tambah Drop Point
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 space-y-4">
        @if(session('success'))
            <div class="p-3 bg-surface border border-organik/40 text-organik text-xs font-mono">
                [OK] {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-3 bg-surface border border-b3/40 text-b3 text-xs font-mono">
                [ERROR] {{ session('error') }}
            </div>
        @endif

        <!-- Filter bar -->
        <div class="border border-border bg-surface p-3">
            <form method="GET" action="{{ route('admin.drop-points.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau alamat pos drop point..."
                       class="text-xs bg-bg border border-border text-ink rounded-none py-1.5 px-3 flex-1 min-w-48 font-mono focus:ring-1 focus:ring-ink focus:border-ink">
                <select name="status" class="text-xs bg-bg border border-border text-ink rounded-none py-1.5 px-2 font-mono focus:ring-1 focus:ring-ink focus:border-ink">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                </select>
                <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Filter</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.drop-points.index') }}" class="text-xs text-ink-muted hover:text-ink font-mono underline">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="border border-border bg-surface overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-border bg-bg/40 text-ink-muted uppercase font-mono text-[11px]">
                        <tr>
                            <th class="py-2.5 px-4">Nama Drop Point</th>
                            <th class="py-2.5 px-4">Alamat Lokasi</th>
                            <th class="py-2.5 px-4 font-mono">Koordinat GPS</th>
                            <th class="py-2.5 px-4 text-right">Total Setoran</th>
                            <th class="py-2.5 px-4 text-center">Status</th>
                            <th class="py-2.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($dropPoints as $dropPoint)
                            <tr class="hover:bg-bg/40 transition">
                                <td class="py-3 px-4 font-semibold text-ink whitespace-nowrap">
                                    {{ $dropPoint->name }}
                                </td>
                                <td class="py-3 px-4 text-ink-muted text-[11px] max-w-xs">
                                    {{ $dropPoint->address }}
                                </td>
                                <td class="py-3 px-4 font-mono text-[11px] text-ink-faint whitespace-nowrap">
                                    @if($dropPoint->latitude && $dropPoint->longitude)
                                        {{ $dropPoint->latitude }}, {{ $dropPoint->longitude }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold text-ink">
                                    {{ number_format($dropPoint->waste_deposits_count) }}
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    @if($dropPoint->is_active)
                                        <span class="inline-flex items-center gap-1 text-organik font-mono text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-organik"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-ink-faint font-mono text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-ink-faint"></span> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right space-x-2 whitespace-nowrap font-mono">
                                    <a href="{{ route('admin.drop-points.edit', $dropPoint) }}" class="text-ink hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.drop-points.destroy', $dropPoint) }}" class="inline"
                                          onsubmit="return confirm('Hapus drop point {{ $dropPoint->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-b3 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-ink-faint font-mono text-xs">
                                    -- Belum ada drop point terdaftar --
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($dropPoints->hasPages())
                <div class="p-3 border-t border-border bg-bg/20">
                    {{ $dropPoints->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
