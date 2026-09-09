<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Katalog Reward</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('rewards.history') }}" class="text-xs text-ink-muted hover:text-ink transition-colors">Riwayat penukaran</a>
                <span class="pts-display text-sm font-semibold">{{ number_format($user->points_balance) }}<span class="font-sans text-xs text-ink-faint ml-1 font-normal">pts</span></span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if($rewards->count() > 0)
            {{-- Rewards as clean list (not card grid) --}}
            <div class="card divide-y divide-border">
                @foreach($rewards as $item)
                    @php
                        $canRedeem = $item->stock > 0 && $user->points_balance >= $item->point_cost;
                        $noStock   = $item->stock < 1;
                        $shortfall = $item->point_cost - $user->points_balance;
                    @endphp
                    <div class="flex items-start gap-4 px-5 py-4 {{ $noStock ? 'opacity-50' : '' }}">
                        {{-- Image --}}
                        <div class="w-14 h-14 rounded border border-border bg-surface flex items-center justify-center shrink-0 overflow-hidden">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-border" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm font-medium text-ink">{{ $item->name }}</p>
                                    @if($item->description)
                                        <p class="text-xs text-ink-muted mt-0.5 line-clamp-1">{{ $item->description }}</p>
                                    @endif
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="pts-display text-base font-semibold">{{ number_format($item->point_cost) }}<span class="font-sans text-xs text-ink-faint ml-0.5 font-normal">pts</span></p>
                                    <p class="text-[10px] text-ink-faint mt-0.5">sisa {{ $item->stock }}</p>
                                </div>
                            </div>

                            <div class="mt-2.5">
                                @if($noStock)
                                    <span class="text-xs text-ink-faint">Stok habis</span>
                                @elseif(!$canRedeem)
                                    <span class="text-xs text-b3">Kurang {{ number_format($shortfall) }} pts</span>
                                @else
                                    <form action="{{ route('rewards.redeem', $item) }}" method="POST"
                                          onsubmit="return confirm('Tukar {{ number_format($item->point_cost) }} pts untuk {{ $item->name }}?')">
                                        @csrf
                                        <button type="submit" class="btn-primary text-xs py-1.5 px-3">Tukar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card px-5 py-10 text-center">
                <p class="text-sm text-ink-muted">Belum ada reward aktif.</p>
            </div>
        @endif

    </div>
</x-app-layout>
