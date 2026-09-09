<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Tambah Jenis Sampah</h1>
            <a href="{{ route('admin.waste-types.index') }}" class="text-xs text-ink-muted hover:text-ink font-mono">
                &larr; Master Jenis Sampah
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto px-4 sm:px-6">
        <div class="border border-border bg-surface p-5 space-y-4">
            <div class="border-b border-border pb-3">
                <span class="text-xs font-mono uppercase text-ink font-bold">Form Input Klasifikasi Baru</span>
                <p class="text-xs text-ink-muted mt-0.5">Tentukan nama, nilai harga pasar, dan poin insentif per kilogram.</p>
            </div>

            <form method="POST" action="{{ route('admin.waste-types.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                        Nama Jenis Sampah *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Botol Plastik PET, Kardus Bekas..."
                           class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink @error('name') border-b3 @enderror">
                    @error('name')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="unit_price_per_kg" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                            Harga Pasar / Kg (Rp) *
                        </label>
                        <input type="number" id="unit_price_per_kg" name="unit_price_per_kg" value="{{ old('unit_price_per_kg') }}" required min="0" step="100"
                               placeholder="2500"
                               class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink @error('unit_price_per_kg') border-b3 @enderror">
                        @error('unit_price_per_kg')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="points_per_kg" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                            Poin Reward / Kg *
                        </label>
                        <input type="number" id="points_per_kg" name="points_per_kg" value="{{ old('points_per_kg') }}" required min="1"
                               placeholder="100"
                               class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink @error('points_per_kg') border-b3 @enderror">
                        @error('points_per_kg')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">Deskripsi / Petunjuk Pemilahan</label>
                    <textarea id="description" name="description" rows="3" placeholder="Contoh: Bersihkan dari sisa cairan/label..."
                              class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink @error('description') border-b3 @enderror">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked {{ old('is_active', true) ? 'checked' : '' }}
                           class="rounded-none border-border bg-bg text-ink focus:ring-0">
                    <label for="is_active" class="text-xs text-ink">Status aktif (dapat dipilih nasabah saat setor sampah)</label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-border">
                    <a href="{{ route('admin.waste-types.index') }}" class="text-xs font-mono text-ink-muted hover:text-ink">Batal</a>
                    <button type="submit" class="btn-primary text-xs">
                        [Simpan Jenis Sampah]
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
