<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Laporan & Audit Statistik</h1>
            <!-- Period Filter -->
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-2">
                <span class="text-xs font-mono text-ink-faint uppercase">Periode:</span>
                <select name="period" onchange="this.form.submit()"
                        class="text-xs bg-bg border border-border text-ink rounded-none py-1 px-2 focus:ring-1 focus:ring-ink focus:border-ink font-mono">
                    <option value="7" @selected($period == '7')>7 Hari Terakhir</option>
                    <option value="30" @selected($period == '30')>30 Hari Terakhir</option>
                    <option value="90" @selected($period == '90')>90 Hari Terakhir</option>
                    <option value="365" @selected($period == '365')>1 Tahun Terakhir</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4 sm:px-6 space-y-6">

        <!-- ===== Overview Ledger Stats ===== -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="border border-border bg-surface p-4">
                <span class="text-[11px] font-mono text-ink-faint uppercase tracking-wider block">Total Nasabah</span>
                <p class="text-2xl font-mono font-bold text-ink mt-1">{{ number_format($totalUsers) }}</p>
                <span class="text-[11px] text-ink-muted mt-1 block">Akun terdaftar</span>
            </div>
            <div class="border border-border bg-surface p-4">
                <span class="text-[11px] font-mono text-ink-faint uppercase tracking-wider block">Total Setoran</span>
                <p class="text-2xl font-mono font-bold text-ink mt-1">{{ number_format($totalDeposits) }}</p>
                <span class="text-[11px] text-ink-muted mt-1 block">Transaksi tercatat</span>
            </div>
            <div class="border border-border bg-surface p-4">
                <span class="text-[11px] font-mono text-ink-faint uppercase tracking-wider block">Sampah Terverifikasi</span>
                <p class="text-2xl font-mono font-bold text-organik mt-1">{{ number_format($totalWeightKg, 1) }} <span class="text-sm font-normal text-ink-muted">KG</span></p>
                <span class="text-[11px] text-ink-muted mt-1 block">Telah ditimbang</span>
            </div>
            <div class="border border-border bg-surface p-4">
                <span class="text-[11px] font-mono text-ink-faint uppercase tracking-wider block">Penukaran Hadiah</span>
                <p class="text-2xl font-mono font-bold text-accent-poin mt-1">{{ number_format($totalRedemptions) }}<span class="text-sm font-normal text-ink-muted">X</span></p>
                <span class="text-[11px] text-ink-muted mt-1 block">Klaim reward</span>
            </div>
        </div>

        <!-- ===== Period Metrics ===== -->
        <div class="border border-border bg-surface p-5">
            <div class="flex items-center justify-between border-b border-border pb-3 mb-4">
                <span class="text-xs uppercase font-mono font-bold text-ink tracking-wider">Aktivitas {{ $period }} Hari Terakhir</span>
                <span class="text-xs font-mono text-ink-faint">Data berkala</span>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="border-l-2 border-ink pl-3">
                    <span class="text-[11px] font-mono text-ink-faint uppercase block">Setoran Masuk</span>
                    <p class="text-lg font-mono font-bold text-ink mt-0.5">{{ number_format($depositsInPeriod) }}</p>
                    <span class="text-[11px] text-ink-muted block">transaksi diajukan</span>
                </div>
                <div class="border-l-2 border-organik pl-3">
                    <span class="text-[11px] font-mono text-ink-faint uppercase block">Terverifikasi</span>
                    <p class="text-lg font-mono font-bold text-organik mt-0.5">{{ number_format($verifiedInPeriod) }}</p>
                    <span class="text-[11px] text-ink-muted block">tuntas ditimbang</span>
                </div>
                <div class="border-l-2 border-anorganik pl-3">
                    <span class="text-[11px] font-mono text-ink-faint uppercase block">Volume Diterima</span>
                    <p class="text-lg font-mono font-bold text-ink mt-0.5">{{ number_format($weightInPeriod, 1) }} kg</p>
                    <span class="text-[11px] text-ink-muted block">berat bersih</span>
                </div>
                <div class="border-l-2 border-accent-poin pl-3">
                    <span class="text-[11px] font-mono text-ink-faint uppercase block">Poin Diterbitkan</span>
                    <p class="text-lg font-mono font-bold text-accent-poin mt-0.5">+{{ number_format($pointsIssuedInPeriod) }}</p>
                    <span class="text-[11px] text-ink-muted block">EcoPoints</span>
                </div>
            </div>
        </div>

        <!-- ===== Sirkulasi Poin & Status Setoran ===== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Sirkulasi Poin Nota -->
            <div class="border border-border bg-surface p-5">
                <div class="flex items-center justify-between border-b border-border pb-3 mb-4">
                    <span class="text-xs uppercase font-mono font-bold text-ink tracking-wider">Audit Sirkulasi Poin</span>
                    <span class="text-[11px] font-mono text-ink-faint">ALL TIME</span>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="receipt-row">
                        <span class="receipt-label">Total Poin Diterbitkan</span>
                        <span class="receipt-dots"></span>
                        <span class="receipt-value text-organik">+{{ number_format($totalPointsIssued) }}</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Total Poin Ditukarkan</span>
                        <span class="receipt-dots"></span>
                        <span class="receipt-value text-b3">-{{ number_format($totalPointsRedeemed) }}</span>
                    </div>
                    <div class="pt-3 border-t-2 border-dashed border-border flex items-baseline justify-between font-mono">
                        <span class="font-bold text-ink uppercase tracking-wider">Poin Beredar (Liabilitas)</span>
                        <span class="text-base font-bold text-accent-poin">{{ number_format($totalPointsIssued - $totalPointsRedeemed) }} PTS</span>
                    </div>
                </div>
            </div>

            <!-- Status Setoran Breakdown -->
            <div class="border border-border bg-surface p-5">
                <div class="flex items-center justify-between border-b border-border pb-3 mb-4">
                    <span class="text-xs uppercase font-mono font-bold text-ink tracking-wider">Status Antrean Transaksi</span>
                    <span class="text-[11px] font-mono text-ink-faint">{{ number_format($totalDeposits) }} Total</span>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="receipt-row">
                        <span class="receipt-label flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-accent-poin"></span> Pending / Menunggu
                        </span>
                        <span class="receipt-dots"></span>
                        <span class="receipt-value">{{ number_format($totalPendingDeposits) }}</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-organik"></span> Terverifikasi & Valid
                        </span>
                        <span class="receipt-dots"></span>
                        <span class="receipt-value text-organik">{{ number_format($totalVerifiedDeposits) }}</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-b3"></span> Ditolak Petugas
                        </span>
                        <span class="receipt-dots"></span>
                        <span class="receipt-value text-b3">{{ number_format(max(0, $totalDeposits - $totalVerifiedDeposits - $totalPendingDeposits)) }}</span>
                    </div>

                    @if($totalDeposits > 0)
                        <div class="pt-2">
                            <div class="w-full h-2 bg-bg border border-border flex overflow-hidden">
                                <div class="bg-organik h-full" style="width: {{ ($totalVerifiedDeposits / $totalDeposits) * 100 }}%"></div>
                                <div class="bg-accent-poin h-full" style="width: {{ ($totalPendingDeposits / $totalDeposits) * 100 }}%"></div>
                                <div class="bg-b3 h-full" style="width: {{ (max(0, $totalDeposits - $totalVerifiedDeposits - $totalPendingDeposits) / $totalDeposits) * 100 }}%"></div>
                            </div>
                            <div class="flex justify-between text-[11px] font-mono text-ink-faint mt-1">
                                <span>{{ round(($totalVerifiedDeposits / $totalDeposits) * 100) }}% valid</span>
                                <span>{{ round(($totalPendingDeposits / $totalDeposits) * 100) }}% pending</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ===== Tables Grid ===== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            <!-- Top Waste Types -->
            <div class="border border-border bg-surface overflow-hidden">
                <div class="p-4 border-b border-border flex items-center justify-between bg-bg/50">
                    <div>
                        <h3 class="text-xs uppercase font-mono font-bold text-ink">Volume per Jenis Sampah</h3>
                        <p class="text-[11px] text-ink-faint">Total akumulasi berat terverifikasi</p>
                    </div>
                </div>
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-border bg-bg/30 text-ink-muted uppercase font-mono text-[11px]">
                        <tr>
                            <th class="py-2.5 px-4">Jenis Sampah</th>
                            <th class="py-2.5 px-4 text-right">Setoran</th>
                            <th class="py-2.5 px-4 text-right">Berat (kg)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($depositsByWasteType as $item)
                            <tr class="hover:bg-bg/40 transition">
                                <td class="py-2.5 px-4 font-medium text-ink">{{ $item->wasteType?->name ?? 'N/A' }}</td>
                                <td class="py-2.5 px-4 text-right font-mono text-ink-muted">{{ number_format($item->total_deposits) }}</td>
                                <td class="py-2.5 px-4 text-right font-mono font-bold text-ink">{{ number_format($item->total_weight_kg, 1) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-center text-ink-faint font-mono text-xs">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Top Drop Points -->
            <div class="border border-border bg-surface overflow-hidden">
                <div class="p-4 border-b border-border flex items-center justify-between bg-bg/50">
                    <div>
                        <h3 class="text-xs uppercase font-mono font-bold text-ink">Drop Point Teraktif</h3>
                        <p class="text-[11px] text-ink-faint">Frekuensi penimbangan mitra</p>
                    </div>
                </div>
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-border bg-bg/30 text-ink-muted uppercase font-mono text-[11px]">
                        <tr>
                            <th class="py-2.5 px-4">Drop Point</th>
                            <th class="py-2.5 px-4 text-right">Setoran</th>
                            <th class="py-2.5 px-4 text-right">Berat (kg)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($depositsByDropPoint as $item)
                            <tr class="hover:bg-bg/40 transition">
                                <td class="py-2.5 px-4 font-medium text-ink">{{ $item->dropPoint?->name ?? 'N/A' }}</td>
                                <td class="py-2.5 px-4 text-right font-mono text-ink-muted">{{ number_format($item->total_deposits) }}</td>
                                <td class="py-2.5 px-4 text-right font-mono font-bold text-ink">{{ number_format($item->total_weight_kg, 1) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-center text-ink-faint font-mono text-xs">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Top Rewards -->
            <div class="border border-border bg-surface overflow-hidden">
                <div class="p-4 border-b border-border flex items-center justify-between bg-bg/50">
                    <div>
                        <h3 class="text-xs uppercase font-mono font-bold text-ink">Reward Terpopuler</h3>
                        <p class="text-[11px] text-ink-faint">Item paling sering ditukarkan</p>
                    </div>
                </div>
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-border bg-bg/30 text-ink-muted uppercase font-mono text-[11px]">
                        <tr>
                            <th class="py-2.5 px-4">Reward</th>
                            <th class="py-2.5 px-4 text-right">Tarif Poin</th>
                            <th class="py-2.5 px-4 text-right">Ditukar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($topRewards as $item)
                            <tr class="hover:bg-bg/40 transition">
                                <td class="py-2.5 px-4 font-medium text-ink">{{ $item->reward?->name ?? 'N/A' }}</td>
                                <td class="py-2.5 px-4 text-right font-mono text-accent-poin font-semibold">{{ number_format($item->reward?->point_cost ?? 0) }} pt</td>
                                <td class="py-2.5 px-4 text-right font-mono font-bold text-ink">{{ number_format($item->total_redeemed) }}x</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-center text-ink-faint font-mono text-xs">Belum ada penukaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Top Users by Points -->
            <div class="border border-border bg-surface overflow-hidden">
                <div class="p-4 border-b border-border flex items-center justify-between bg-bg/50">
                    <div>
                        <h3 class="text-xs uppercase font-mono font-bold text-ink">Leaderboard Nasabah</h3>
                        <p class="text-[11px] text-ink-faint">Saldo poin tertinggi saat ini</p>
                    </div>
                </div>
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-border bg-bg/30 text-ink-muted uppercase font-mono text-[11px]">
                        <tr>
                            <th class="py-2.5 px-4 w-10">#</th>
                            <th class="py-2.5 px-4">Nasabah</th>
                            <th class="py-2.5 px-4 text-right">Saldo Poin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($topUsersByPoints as $i => $user)
                            <tr class="hover:bg-bg/40 transition">
                                <td class="py-2.5 px-4 font-mono font-bold text-ink-faint">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-2.5 px-4">
                                    <div class="font-medium text-ink">{{ $user->name }}</div>
                                    <div class="text-[11px] font-mono text-ink-faint">{{ $user->email }}</div>
                                </td>
                                <td class="py-2.5 px-4 text-right font-mono font-bold text-accent-poin">{{ number_format($user->points_balance) }} pt</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-center text-ink-faint font-mono text-xs">Belum ada nasabah.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== Monthly Trend ===== -->
        @if($monthlyTrend->isNotEmpty())
            <div class="border border-border bg-surface overflow-hidden">
                <div class="p-4 border-b border-border flex items-center justify-between bg-bg/50">
                    <h3 class="text-xs uppercase font-mono font-bold text-ink">Tren Setoran Bulanan (6 Bulan Terakhir)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-border bg-bg/30 text-ink-muted uppercase font-mono text-[11px]">
                            <tr>
                                <th class="py-2.5 px-4">Bulan</th>
                                <th class="py-2.5 px-4 text-right">Total Setoran</th>
                                <th class="py-2.5 px-4 text-right">Berat Bersih (kg)</th>
                                <th class="py-2.5 px-4">Proporsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60">
                            @php $maxWeight = $monthlyTrend->max('total_weight_kg') ?: 1; @endphp
                            @foreach($monthlyTrend as $month)
                                <tr class="hover:bg-bg/40 transition">
                                    <td class="py-2.5 px-4 font-mono font-medium text-ink">
                                        {{ \Carbon\Carbon::create($month->year, $month->month)->translatedFormat('F Y') }}
                                    </td>
                                    <td class="py-2.5 px-4 text-right font-mono text-ink-muted">{{ number_format($month->total_deposits) }}</td>
                                    <td class="py-2.5 px-4 text-right font-mono font-bold text-ink">{{ number_format($month->total_weight_kg, 1) }}</td>
                                    <td class="py-2.5 px-4 w-1/3">
                                        <div class="w-full bg-bg border border-border h-2">
                                            <div class="bg-primary h-full" style="width: {{ ($month->total_weight_kg / $maxWeight) * 100 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
