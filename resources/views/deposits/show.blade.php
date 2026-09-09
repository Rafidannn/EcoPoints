<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Detail Setoran Sampah #{{ $deposit->id }}</h1>
            <a href="{{ route('deposits.index') }}" class="text-xs text-ink-muted hover:text-ink transition-colors">Riwayat setoran</a>
        </div>
    </x-slot>

    <div class="max-w-2xl space-y-4">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        {{-- Nota / Receipt --}}
        <div class="card">
            {{-- Header nota --}}
            <div class="px-5 py-4 border-b border-border flex items-start justify-between">
                <div>
                    <p class="text-xs text-ink-faint font-mono">{{ $deposit->created_at->format('d M Y · H:i') }}</p>
                    <p class="text-sm font-medium text-ink mt-0.5">{{ $deposit->dropPoint?->name }}</p>
                    <p class="text-xs text-ink-faint">{{ $deposit->dropPoint?->address }}</p>
                </div>
                <div class="text-right">
                    @if($deposit->status === 'verified')
                        <span class="status-verified">
                            <span class="w-1.5 h-1.5 rounded-full bg-organik inline-block"></span>
                            Terverifikasi
                        </span>
                    @elseif($deposit->status === 'rejected')
                        <span class="status-rejected">
                            <span class="w-1.5 h-1.5 rounded-full bg-b3 inline-block"></span>
                            Ditolak
                        </span>
                    @else
                        <span class="status-pending">
                            <span class="w-1.5 h-1.5 rounded-full bg-poin inline-block"></span>
                            Menunggu verifikasi
                        </span>
                    @endif
                </div>
            </div>

            {{-- Nota body --}}
            <div class="px-5 py-4 font-mono space-y-0.5">
                <div class="receipt-row">
                    <span class="receipt-label">Jenis sampah</span>
                    <span class="receipt-dots"></span>
                    <span class="receipt-value">{{ $deposit->wasteType?->name ?? '—' }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Berat ditimbang</span>
                    <span class="receipt-dots"></span>
                    <span class="receipt-value">{{ number_format($deposit->weight_kg, 2) }} kg</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Tarif poin</span>
                    <span class="receipt-dots"></span>
                    <span class="receipt-value">{{ number_format($deposit->wasteType?->points_per_kg ?? 0) }} pts/kg</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Nilai ekonomi</span>
                    <span class="receipt-dots"></span>
                    <span class="receipt-value">Rp {{ number_format(floor($deposit->weight_kg * ($deposit->wasteType?->unit_price_per_kg ?? 0))) }}</span>
                </div>

                @if($deposit->notes)
                    <div class="receipt-row">
                        <span class="receipt-label">Catatan</span>
                        <span class="receipt-dots"></span>
                        <span class="receipt-value text-ink-muted">{{ $deposit->notes }}</span>
                    </div>
                @endif

                {{-- Total --}}
                <div class="receipt-total-row mt-3">
                    <span class="receipt-total-label">Total EcoPoints</span>
                    <span class="flex-1 border-0"></span>
                    <span class="font-mono text-2xl font-bold {{ $deposit->status === 'verified' ? 'text-poin' : 'text-ink-faint' }} tabular-nums">
                        {{ $deposit->status === 'verified' ? '+' : '~' }}{{ number_format(floor($deposit->weight_kg * ($deposit->wasteType?->points_per_kg ?? 0))) }}
                        <span class="text-sm font-normal ml-0.5">pts</span>
                    </span>
                </div>
            </div>

            {{-- Verification info --}}
            @if($deposit->status !== 'pending')
                <div class="px-5 py-3.5 border-t border-border bg-surface text-xs text-ink-muted font-mono">
                    @if($deposit->status === 'verified')
                        Diverifikasi oleh {{ $deposit->verifier?->name ?? 'Petugas' }}
                        pada {{ $deposit->verified_at?->format('d M Y · H:i') }}
                    @else
                        Alasan penolakan: {{ $deposit->notes ?? 'Tidak memenuhi kriteria.' }}
                    @endif
                </div>
            @else
                <div class="px-5 py-3.5 border-t border-border bg-surface text-xs text-ink-faint">
                    Bawa sampah ini ke drop point untuk penimbangan fisik oleh petugas.
                </div>
            @endif
        </div>

        {{-- Foto bukti --}}
        @if($deposit->photo)
            <div class="card overflow-hidden">
                <div class="card-header">
                    <span class="card-title">Foto bukti</span>
                </div>
                <img src="{{ asset('storage/' . $deposit->photo) }}"
                     alt="Foto bukti sampah"
                     class="w-full max-h-72 object-cover">
            </div>
        @endif

        {{-- Point transaction --}}
        @if($deposit->pointTransaction)
            <div class="card p-5">
                <p class="text-xs text-ink-faint mb-1">Transaksi poin</p>
                <p class="text-sm text-ink">{{ $deposit->pointTransaction->description }}</p>
                <p class="pts-display text-xl font-semibold mt-1">+{{ number_format($deposit->pointTransaction->amount) }} <span class="text-sm font-mono font-normal text-ink-faint">pts</span></p>
            </div>
        @endif

    </div>
</x-app-layout>
