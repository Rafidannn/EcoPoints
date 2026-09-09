<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Tambah Drop Point Baru</h1>
            <a href="{{ route('admin.drop-points.index') }}" class="text-xs text-ink-muted hover:text-ink font-mono">
                &larr; Master Drop Point
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto px-4 sm:px-6">
        <div class="border border-border bg-surface p-5 space-y-4">
            <div class="border-b border-border pb-3">
                <span class="text-xs font-mono uppercase text-ink font-bold">Form Input Drop Point / Pos Pengumpulan</span>
                <p class="text-xs text-ink-muted mt-0.5">Daftarkan lokasi penimbangan sampah fisik bagi nasabah.</p>
            </div>

            <form method="POST" action="{{ route('admin.drop-points.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                        Nama Pos / Drop Point *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Bank Sampah RW 04 Kelurahan Menteng"
                           class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink @error('name') border-b3 @enderror">
                    @error('name')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="address" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">
                        Alamat Lengkap *
                    </label>
                    <textarea id="address" name="address" rows="3" required placeholder="Jl. ..., Kelurahan, Kecamatan, Kota"
                              class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink @error('address') border-b3 @enderror">{{ old('address') }}</textarea>
                    @error('address')<p class="mt-1 text-xs text-b3 font-mono">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">Latitude</label>
                        <input type="number" id="latitude" name="latitude" value="{{ old('latitude') }}" step="0.00000001" placeholder="-6.2088"
                               class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink">
                    </div>
                    <div>
                        <label for="longitude" class="block text-xs font-mono uppercase text-ink font-semibold mb-1">Longitude</label>
                        <input type="number" id="longitude" name="longitude" value="{{ old('longitude') }}" step="0.00000001" placeholder="106.8456"
                               class="w-full bg-bg border border-border text-ink text-xs p-2.5 font-mono focus:ring-1 focus:ring-ink focus:border-ink">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                           class="rounded-none border-border bg-bg text-ink focus:ring-0">
                    <label for="is_active" class="text-xs text-ink">Status aktif (dapat dipilih nasabah saat setor sampah)</label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-border">
                    <a href="{{ route('admin.drop-points.index') }}" class="text-xs font-mono text-ink-muted hover:text-ink">Batal</a>
                    <button type="submit" class="btn-primary text-xs">
                        [Simpan Drop Point]
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
