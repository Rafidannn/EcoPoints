<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Tambah Item Reward</h1>
            <a href="{{ route('admin.rewards.index') }}" class="text-xs text-ink-muted hover:text-ink font-mono">
                &larr; Master Katalog Reward
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto px-4 sm:px-6">
        <div class="border border-border bg-surface p-5 space-y-4">
            <div class="border-b border-border pb-3">
                <span class="text-xs font-mono uppercase text-ink font-bold">Form Registrasi Item Hadiah / Voucher</span>
                <p class="text-xs text-ink-muted mt-0.5">Tentukan biaya poin penukaran dan kuota stok yang tersedia.</p>
            </div>

            <form method="POST" action="{{ route('admin.rewards.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                        Nama Item Reward *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Voucher Belanja Rp 25.000, Paket Sembako..."
                           class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink @error('name') border-b3 @enderror">
                    @error('name')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                        Deskripsi & Syarat Klaim
                    </label>
                    <textarea id="description" name="description" rows="3" placeholder="Keterangan cara klaim, masa berlaku, atau lokasi pengambilan..."
                              class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink @error('description') border-b3 @enderror">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="point_cost" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                            Biaya Poin *
                        </label>
                        <input type="number" id="point_cost" name="point_cost" value="{{ old('point_cost') }}" required min="1"
                               placeholder="5000"
                               class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink @error('point_cost') border-b3 @enderror">
                        @error('point_cost')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="stock" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                            Stok Unit *
                        </label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', 10) }}" required min="0"
                               placeholder="20"
                               class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink @error('stock') border-b3 @enderror">
                        @error('stock')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">Upload Gambar Produk (Opsional)</label>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="w-full text-xs font-mono text-ink file:mr-3 file:py-1.5 file:px-3 file:border file:border-border file:bg-bg file:text-xs file:font-mono file:text-ink hover:file:bg-surface">
                    <p class="mt-1 text-[11px] font-mono text-ink-faint">JPG, PNG, WebP. Maks 2 MB.</p>
                    @error('image')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                           class="rounded-none border-border bg-bg text-ink focus:ring-0">
                    <label for="is_active" class="text-xs text-ink">Status aktif (tampil di katalog nasabah)</label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-border">
                    <a href="{{ route('admin.rewards.index') }}" class="text-xs font-mono text-ink-muted hover:text-ink">Batal</a>
                    <button type="submit" class="btn-primary text-xs">
                        [Simpan Item Reward]
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
