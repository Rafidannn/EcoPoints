<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Form Setor Sampah</h1>
            <a href="{{ route('deposits.index') }}" class="text-xs text-ink-muted hover:text-ink transition-colors">Riwayat setoran</a>
        </div>
    </x-slot>

    <div class="max-w-2xl" x-data="{
        selectedType: '',
        weight: '',
        typesData: {{ json_encode($wasteTypes->keyBy('id')) }},
        get currentType() {
            return this.typesData[this.selectedType] || null;
        },
        get estimatedPoints() {
            if (!this.currentType || !this.weight || this.weight <= 0) return null;
            return Math.floor(parseFloat(this.weight) * this.currentType.points_per_kg);
        },
        get estimatedValue() {
            if (!this.currentType || !this.weight || this.weight <= 0) return null;
            return Math.floor(parseFloat(this.weight) * this.currentType.unit_price_per_kg);
        }
    }">

        @if ($errors->any())
            <div class="alert-error mb-5">
                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <ul class="space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('deposits.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Drop Point --}}
            <div>
                <label for="drop_point_id" class="field-label">Lokasi drop point <span class="text-b3">*</span></label>
                <select name="drop_point_id" id="drop_point_id" required class="field-input">
                    <option value="">Pilih drop point / bank sampah</option>
                    @foreach ($dropPoints as $point)
                        <option value="{{ $point->id }}" {{ old('drop_point_id') == $point->id ? 'selected' : '' }}>
                            {{ $point->name }} — {{ $point->address }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Waste Type --}}
            <div>
                <label for="waste_type_id" class="field-label">Jenis sampah <span class="text-b3">*</span></label>
                <select name="waste_type_id" id="waste_type_id" x-model="selectedType" required class="field-input">
                    <option value="">Pilih kategori sampah</option>
                    @foreach ($wasteTypes as $type)
                        <option value="{{ $type->id }}" {{ old('waste_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }} · {{ number_format($type->points_per_kg) }} poin/kg · Rp {{ number_format($type->unit_price_per_kg) }}/kg
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Weight Input --}}
            <div>
                <label for="weight_kg" class="field-label">Perkiraan berat <span class="text-b3">*</span></label>
                <div class="flex items-center gap-2">
                    <input type="number" step="0.01" min="0.1" max="1000"
                           name="weight_kg" id="weight_kg"
                           x-model="weight"
                           value="{{ old('weight_kg') }}"
                           placeholder="0.00"
                           class="field-input font-mono text-lg w-36 tabular-nums">
                    <span class="text-sm text-ink-muted font-mono">kg</span>
                </div>
                <p class="text-xs text-ink-faint mt-1">Petugas akan menimbang ulang saat penyerahan.</p>
            </div>

            {{-- Scale Display: weight → points --}}
            <div x-show="estimatedPoints !== null" x-transition class="card border-border">
                <div class="p-5 border-b border-border">
                    <p class="text-xs text-ink-faint mb-3">Estimasi hasil setoran</p>

                    <div class="flex items-center gap-4">
                        {{-- Weight display --}}
                        <div class="flex-1">
                            <p class="text-[10px] text-ink-faint mb-1 font-mono uppercase tracking-wider">Berat</p>
                            <div class="font-mono text-4xl font-bold tabular-nums text-ink leading-none">
                                <span x-text="parseFloat(weight || 0).toFixed(2)"></span>
                                <span class="text-lg font-normal text-ink-muted">kg</span>
                            </div>
                        </div>

                        {{-- Arrow --}}
                        <div class="text-border">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </div>

                        {{-- Points display (the "scale readout") --}}
                        <div class="flex-1 text-right">
                            <p class="text-[10px] text-ink-faint mb-1 font-mono uppercase tracking-wider">EcoPoints</p>
                            <div class="font-mono text-4xl font-bold tabular-nums text-poin leading-none">
                                <span x-text="(estimatedPoints || 0).toLocaleString('id-ID')"></span>
                                <span class="text-lg font-normal text-poin/60">pts</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Receipt breakdown --}}
                <div class="px-5 py-4 text-xs font-mono text-ink-muted space-y-1">
                    <div class="receipt-row">
                        <span class="receipt-label" x-text="currentType?.name || ''"></span>
                        <span class="receipt-dots"></span>
                        <span class="receipt-value" x-text="`${weight || 0} kg`"></span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Nilai ekonomi</span>
                        <span class="receipt-dots"></span>
                        <span class="receipt-value" x-text="`Rp ${(estimatedValue || 0).toLocaleString('id-ID')}`"></span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Tarif poin</span>
                        <span class="receipt-dots"></span>
                        <span class="receipt-value" x-text="`${currentType?.points_per_kg || 0} pts/kg`"></span>
                    </div>
                </div>
            </div>

            {{-- Photo --}}
            <div>
                <label for="photo" class="field-label">Foto bukti <span class="text-ink-faint text-xs font-normal">(opsional)</span></label>
                <input type="file" name="photo" id="photo" accept="image/*"
                       class="w-full text-sm text-ink-muted file:mr-3 file:py-1.5 file:px-3 file:rounded file:border file:border-border file:text-xs file:font-medium file:bg-surface file:text-ink hover:file:bg-border transition-colors cursor-pointer">
                <p class="text-xs text-ink-faint mt-1">JPG, PNG, WEBP — maks. 3MB</p>
            </div>

            {{-- Notes --}}
            <div>
                <label for="notes" class="field-label">Catatan <span class="text-ink-faint text-xs font-normal">(opsional)</span></label>
                <textarea name="notes" id="notes" rows="2"
                          placeholder="Misal: sudah dipilah, tidak basah..."
                          class="field-input">{{ old('notes') }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-border">
                <a href="{{ route('deposits.index') }}" class="btn-ghost">Batal</a>
                <button type="submit" class="btn-primary">Kirim Setoran</button>
            </div>
        </form>
    </div>
</x-app-layout>
