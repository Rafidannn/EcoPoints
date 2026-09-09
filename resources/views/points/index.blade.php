<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">{{ 'Poin Saya & Mutasi Saldo' }}</h1>
            <a href="{{ route('rewards.index') }}" class="text-xs text-ink-muted hover:text-ink transition-colors">Tukar poin</a>
        </div>
    </x-slot>

    <div class="space-y-5">

        {{-- Balance summary (receipt header style) --}}
        <div class="card p-5 font-mono">
            <div class="receipt-row">
                <span class="receipt-label text-ink-faint text-xs">Total poin masuk</span>
                <span class="receipt-dots"></span>
                <span class="receipt-value text-organik">+{{ number_format($totalCredit) }}</span>
            </div>
            <div class="receipt-row">
                <span class="receipt-label text-ink-faint text-xs">Total poin keluar</span>
                <span class="receipt-dots"></span>
                <span class="receipt-value text-b3">-{{ number_format($totalDebit) }}</span>
            </div>
            <div class="receipt-total-row">
                <span class="receipt-total-label">Saldo saat ini</span>
                <span class="receipt-dots border-0"></span>
                <span class="font-mono text-2xl font-bold text-poin">{{ number_format(Auth::user()->points_balance) }}<span class="text-sm font-normal text-ink-faint ml-1">pts</span></span>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('points.index') }}" class="flex flex-wrap gap-2 items-end">
            <div>
                <label class="field-label text-xs">Jenis</label>
                <select name="type" class="field-input text-xs py-1.5">
                    <option value="">Semua</option>
                    <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Masuk</option>
                    <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>
            <div>
                <label class="field-label text-xs">Dari</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="field-input text-xs py-1.5">
            </div>
            <div>
                <label class="field-label text-xs">Sampai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="field-input text-xs py-1.5">
            </div>
            <button type="submit" class="btn-ghost text-xs py-1.5">Filter</button>
            @if(request()->hasAny(['type','start_date','end_date']))
                <a href="{{ route('points.index') }}" class="text-xs text-ink-muted hover:text-ink py-1.5">Reset</a>
            @endif
        </form>

        {{-- Ledger list --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Riwayat transaksi</span>
                <span class="text-xs text-ink-faint">{{ $transactions->total() }} entri</span>
            </div>

            @if($transactions->count() > 0)
                <div class="divide-y divide-border">
                    @foreach($transactions as $tx)
                        <div class="px-5 py-3.5 flex items-start gap-3">
                            {{-- Type indicator --}}
                            <div class="mt-0.5 shrink-0">
                                @if($tx->type === 'credit')
                                    <span class="inline-block w-1 h-1 rounded-full bg-organik mt-1.5"></span>
                                @else
                                    <span class="inline-block w-1 h-1 rounded-full bg-b3 mt-1.5"></span>
                                @endif
                            </div>

                            {{-- Description --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-ink leading-snug">{{ $tx->description }}</p>
                                <p class="text-xs text-ink-faint mt-0.5 font-mono">{{ $tx->created_at->format('d M Y · H:i') }}</p>
                            </div>

                            {{-- Amount --}}
                            <div class="font-mono text-sm font-semibold tabular-nums shrink-0">
                                @if($tx->type === 'credit')
                                    <span class="text-organik">+{{ number_format($tx->amount) }}</span>
                                @else
                                    <span class="text-b3">-{{ number_format($tx->amount) }}</span>
                                @endif
                                <span class="text-ink-faint text-xs ml-0.5">pts</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($transactions->hasPages())
                    <div class="px-5 py-3 border-t border-border">
                        {{ $transactions->links() }}
                    </div>
                @endif
            @else
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-ink-faint">Belum ada transaksi poin.</p>
                    <a href="{{ route('deposits.create') }}" class="text-sm text-primary hover:underline mt-1 inline-block">Setor sampah sekarang</a>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
