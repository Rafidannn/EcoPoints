<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <div class="flex items-center gap-3">
                <h1 class="text-base font-semibold text-ink">Verifikasi Setoran #{{ str_pad($deposit->id, 5, '0', STR_PAD_LEFT) }}</h1>
                <span class="text-xs font-mono text-ink-faint">[{{ $deposit->created_at->format('d/m/Y H:i') }}]</span>
            </div>
            <a href="{{ route('petugas.deposits.index') }}" class="text-xs text-ink-muted hover:text-ink transition font-mono">
                &larr; Kembali ke Antrean
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 space-y-6" x-data="{
        actualWeight: '{{ old('actual_weight_kg', $deposit->weight_kg) }}',
        pointsPerKg: {{ $deposit->wasteType?->points_per_kg ?? 0 }},
        unitPrice: {{ $deposit->wasteType?->unit_price_per_kg ?? 0 }},
        get calculatedPoints() {
            if (!this.actualWeight || this.actualWeight <= 0) return 0;
            return Math.floor(parseFloat(this.actualWeight) * this.pointsPerKg);
        }
    }">
        @if ($errors->any())
            <div class="p-3 bg-surface border border-b3 text-b3 text-xs font-mono space-y-1">
                <div class="font-bold">[ERROR] Validasi gagal:</div>
                @foreach ($errors->all() as $error)
                    <div>- {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Summary & Metadata Nota -->
        <div class="border border-border bg-surface p-5 space-y-4">
            <div class="flex items-center justify-between border-b border-border pb-3">
                <div>
                    <span class="text-xs font-mono text-ink-faint uppercase">Nasabah</span>
                    <div class="text-sm font-semibold text-ink">{{ $deposit->user?->name }}</div>
                    <div class="text-xs font-mono text-ink-muted">{{ $deposit->user?->email }}</div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-mono text-ink-faint uppercase">Saldo Nasabah</span>
                    <div class="text-sm font-mono font-bold text-accent-poin">{{ number_format($deposit->user?->points_balance) }} pt</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <span class="text-ink-faint font-mono block">Kategori Sampah</span>
                    <span class="font-semibold text-ink">{{ $deposit->wasteType?->name }}</span>
                    <span class="text-ink-faint font-mono block text-[11px]">Tarif: {{ number_format($deposit->wasteType?->points_per_kg) }} pt/kg</span>
                </div>
                <div>
                    <span class="text-ink-faint font-mono block">Drop Point</span>
                    <span class="font-semibold text-ink">{{ $deposit->dropPoint?->name }}</span>
                </div>
                <div>
                    <span class="text-ink-faint font-mono block">Pengajuan Awal</span>
                    <span class="font-mono font-bold text-ink">{{ number_format($deposit->weight_kg, 2) }} kg</span>
                </div>
            </div>

            @if ($deposit->notes)
                <div class="p-3 bg-bg/50 border border-border/80 text-xs">
                    <span class="font-mono text-ink-faint text-[11px] block uppercase">Catatan Nasabah:</span>
                    <p class="text-ink italic mt-0.5">"{{ $deposit->notes }}"</p>
                </div>
            @endif

            @if ($deposit->photo)
                <div class="pt-2">
                    <span class="font-mono text-ink-faint text-[11px] block uppercase mb-1">Foto Bukti Fisik:</span>
                    <div class="border border-border max-w-xs bg-bg p-1 inline-block">
                        <img src="{{ asset('storage/' . $deposit->photo) }}" alt="Foto Setoran" class="max-h-56 object-cover">
                    </div>
                </div>
            @endif
        </div>

        @if ($deposit->status === 'pending')
            <!-- Scale Terminal / Timbangan Fisik -->
            <div class="border-2 border-ink bg-surface p-6 space-y-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-organik animate-pulse"></span>
                        <h2 class="text-sm font-mono font-bold uppercase text-ink">Scale Terminal // Penimbangan Fisik</h2>
                    </div>
                    <p class="text-xs text-ink-muted mt-1">Masukkan hasil penimbangan timbangan riil drop point untuk memproses poin.</p>
                </div>

                <!-- Digital Scale HUD -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-bg border border-border p-4 font-mono">
                    <div>
                        <span class="text-xs text-ink-faint block uppercase tracking-wider">Timbangan Riil</span>
                        <div class="text-3xl font-bold text-ink mt-1 flex items-baseline gap-1">
                            <span x-text="actualWeight || '0.00'"></span>
                            <span class="text-sm font-normal text-ink-muted">KG</span>
                        </div>
                    </div>
                    <div class="sm:text-right border-t sm:border-t-0 sm:border-l border-border pt-3 sm:pt-0 sm:pl-4">
                        <span class="text-xs text-ink-faint block uppercase tracking-wider">Kredit Poin Otomatis</span>
                        <div class="text-3xl font-bold text-accent-poin mt-1">
                            +<span x-text="calculatedPoints.toLocaleString('id-ID')"></span>
                            <span class="text-sm font-normal text-ink-muted">PTS</span>
                        </div>
                    </div>
                </div>

                <!-- Verify Form -->
                <form action="{{ route('petugas.deposits.verify', $deposit) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="actual_weight_kg" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                            Input Berat Aktual Timbangan (Kg) *
                        </label>
                        <input type="number" step="0.01" min="0.1" max="1000" name="actual_weight_kg" id="actual_weight_kg" x-model="actualWeight" required
                               class="w-full bg-bg border border-border text-ink font-mono font-bold text-lg p-2.5 focus:ring-1 focus:ring-ink focus:border-ink">
                    </div>

                    <div>
                        <label for="verify_notes" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                            Catatan Verifikasi Petugas (Opsional)
                        </label>
                        <input type="text" name="notes" id="verify_notes" placeholder="Kondisi bersih, sesuai jenis..."
                               class="w-full bg-bg border border-border text-ink text-xs p-2.5 focus:ring-1 focus:ring-ink focus:border-ink font-mono">
                    </div>

                    <div class="pt-2">
                        <button type="submit" onclick="return confirm('Konfirmasi berat dan kreditkan poin ke nasabah?')"
                                class="btn-primary w-full py-3 text-center text-xs font-mono uppercase tracking-wider">
                            [OK] Validasi Penimbangan & Terbitkan Poin
                        </button>
                    </div>
                </form>

                <!-- Rejection Accordion / Section -->
                <div class="pt-4 border-t border-border" x-data="{ showReject: false }">
                    <button type="button" @click="showReject = !showReject" class="text-xs font-mono text-b3 hover:underline">
                        <span x-show="!showReject">+ Tolak setoran ini (sampah tidak memenuhi syarat)</span>
                        <span x-show="showReject">- Tutup form penolakan</span>
                    </button>

                    <div x-show="showReject" x-cloak class="mt-3 p-4 bg-bg border border-b3/40 space-y-3">
                        <form action="{{ route('petugas.deposits.reject', $deposit) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label for="rejection_reason" class="block text-xs font-mono uppercase text-b3 font-semibold mb-1">
                                    Alasan Penolakan Wajib Diisi *
                                </label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" required placeholder="Jelaskan alasan penolakan (misal: tercampur B3, basah, dsb)..."
                                          class="w-full bg-surface border border-border text-ink text-xs p-2 font-mono focus:ring-1 focus:ring-b3 focus:border-b3">{{ old('rejection_reason') }}</textarea>
                            </div>

                            <button type="submit" onclick="return confirm('Tolak setoran ini secara permanen?')"
                                    class="bg-b3 text-bg text-xs font-mono font-semibold py-2 px-4 hover:opacity-90 transition">
                                Tolak Setoran
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Completed state detail -->
            <div class="border border-border bg-surface p-5">
                <div class="flex items-center justify-between font-mono text-xs">
                    <span class="text-ink-faint uppercase">Status Final</span>
                    @if ($deposit->status === 'verified')
                        <span class="text-organik font-bold">[TERVERIFIKASI]</span>
                    @else
                        <span class="text-b3 font-bold">[DITOLAK]</span>
                    @endif
                </div>

                @if ($deposit->status === 'verified')
                    <div class="mt-4 pt-4 border-t border-border/80 flex items-baseline justify-between font-mono">
                        <span class="text-xs text-ink-muted">Poin Dikreditkan:</span>
                        <span class="text-base font-bold text-accent-poin">+{{ number_format($deposit->points_earned) }} PTS</span>
                    </div>
                    @if($deposit->notes)
                        <p class="mt-2 text-xs font-mono text-ink-faint">Catatan: {{ $deposit->notes }}</p>
                    @endif
                @elseif ($deposit->status === 'rejected')
                    <div class="mt-3 p-3 bg-bg border border-b3/30 text-xs font-mono text-b3">
                        Alasan penolakan: {{ $deposit->notes }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
