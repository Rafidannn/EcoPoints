<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Master Katalog Reward</h1>
            <a href="{{ route('admin.rewards.create') }}" class="btn-primary text-xs">
                + Tambah Item Reward
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
            <form method="GET" action="{{ route('admin.rewards.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama item reward..."
                       class="text-xs bg-bg border border-border text-ink rounded-none py-1.5 px-3 flex-1 min-w-48 font-mono focus:ring-1 focus:ring-ink focus:border-ink">
                <select name="status" class="text-xs bg-bg border border-border text-ink rounded-none py-1.5 px-2 font-mono focus:ring-1 focus:ring-ink focus:border-ink">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                </select>
                <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Filter</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.rewards.index') }}" class="text-xs text-ink-muted hover:text-ink font-mono underline">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="border border-border bg-surface overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-border bg-bg/40 text-ink-muted uppercase font-mono text-[11px]">
                        <tr>
                            <th class="py-2.5 px-4">Item Reward</th>
                            <th class="py-2.5 px-4 text-right">Biaya Poin</th>
                            <th class="py-2.5 px-4 text-right">Stok</th>
                            <th class="py-2.5 px-4 text-right">Ditukar</th>
                            <th class="py-2.5 px-4 text-center">Status</th>
                            <th class="py-2.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($rewards as $reward)
                            <tr class="hover:bg-bg/40 transition">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($reward->image)
                                            <img src="{{ Storage::url($reward->image) }}" alt="{{ $reward->name }}"
                                                 class="w-8 h-8 object-cover border border-border bg-bg shrink-0">
                                        @else
                                            <div class="w-8 h-8 border border-border bg-bg flex items-center justify-center font-mono text-ink-faint text-[10px] shrink-0">
                                                ITEM
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-ink">{{ $reward->name }}</p>
                                            @if($reward->description)
                                                <p class="text-[11px] text-ink-faint line-clamp-1 max-w-xs">{{ $reward->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-accent-poin whitespace-nowrap">
                                    {{ number_format($reward->point_cost) }} pt
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold whitespace-nowrap {{ $reward->stock === 0 ? 'text-b3' : 'text-ink' }}">
                                    {{ number_format($reward->stock) }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono text-ink-muted whitespace-nowrap">
                                    {{ number_format($reward->redemptions_count) }}x
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    @if($reward->is_active)
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
                                    <a href="{{ route('admin.rewards.edit', $reward) }}" class="text-ink hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.rewards.destroy', $reward) }}" class="inline"
                                          onsubmit="return confirm('Hapus reward {{ $reward->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-b3 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-ink-faint font-mono text-xs">
                                    -- Belum ada reward terdaftar --
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($rewards->hasPages())
                <div class="p-3 border-t border-border bg-bg/20">
                    {{ $rewards->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
