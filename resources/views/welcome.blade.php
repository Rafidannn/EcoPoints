<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoPoints — Bank Sampah Digital & Daur Ulang</title>

    <!-- Google Fonts: Public Sans + IBM Plex Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Public+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-bg text-ink selection:bg-accent-poin selection:text-ink">

    <!-- Top Navigation -->
    <header class="border-b border-border bg-bg/90 sticky top-0 z-30 backdrop-blur-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
            <a href="/" class="inline-flex items-center gap-2.5 font-mono font-bold text-ink">
                <span class="w-6 h-6 border-2 border-ink flex items-center justify-center bg-surface text-ink text-xs">
                    EP
                </span>
                <span class="tracking-tight text-sm font-bold">EcoPoints</span>
            </a>

            <nav class="flex items-center gap-3 text-xs font-mono">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary text-xs">
                        Buka Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 text-ink hover:underline">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary text-xs">
                            Daftar Nasabah
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-12 space-y-16">

        <!-- Hero: Scale & Mission -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center pt-4">
            <div class="lg:col-span-7 space-y-5">
                <div class="inline-flex items-center gap-2 border border-border bg-surface px-2.5 py-1 text-xs font-mono">
                    <span class="w-2 h-2 rounded-full bg-organik"></span>
                    <span class="text-ink font-semibold">Sistem Penimbangan Bank Sampah Terpadu</span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-ink leading-tight">
                    Setiap kilogram sampah terpilah kini bernilai poin nyata.
                </h1>

                <p class="text-sm text-ink-muted leading-relaxed max-w-lg">
                    EcoPoints mengubah kebiasaan memilah sampah rumah tangga menjadi saldo poin yang dapat ditukarkan dengan sembako, voucher belanja, dan pulsa di jaringan drop point terdekat.
                </p>

                <div class="pt-2 flex flex-wrap items-center gap-3">
                    @auth
                        <a href="{{ route('deposits.create') }}" class="btn-primary text-xs py-2.5 px-4 font-mono">
                            [+] Setor Sampah Sekarang &rarr;
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary text-xs py-2.5 px-4 font-mono">
                            Daftar Jadi Nasabah &rarr;
                        </a>
                        <a href="{{ route('login') }}" class="btn-secondary text-xs py-2.5 px-4 font-mono">
                            Masuk Akun
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Interactive Scale Simulator Preview -->
            <div class="lg:col-span-5" x-data="{
                weight: 4.5,
                cat: 'plastik',
                rates: { organik: 50, plastik: 300, kertas: 150, elektronik: 1000 },
                get pts() {
                    return Math.floor(this.weight * (this.rates[this.cat] || 100));
                }
            }">
                <div class="border-2 border-ink bg-surface p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-border pb-2.5 font-mono text-xs">
                        <div class="flex items-center gap-1.5 font-bold">
                            <span class="w-2 h-2 rounded-full bg-organik animate-pulse"></span>
                            <span>SCALE SIMULATOR // EPS-01</span>
                        </div>
                        <span class="text-ink-faint">LIVE CONVERT</span>
                    </div>

                    <!-- Digital Scale HUD -->
                    <div class="bg-bg border border-border p-4 font-mono grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-[10px] uppercase text-ink-faint tracking-wider block">Berat Terukur</span>
                            <div class="text-2xl font-bold text-ink mt-0.5">
                                <span x-text="weight"></span> <span class="text-xs font-normal text-ink-muted">KG</span>
                            </div>
                        </div>
                        <div class="text-right border-l border-border pl-3">
                            <span class="text-[10px] uppercase text-ink-faint tracking-wider block">Estimasi Nilai</span>
                            <div class="text-2xl font-bold text-accent-poin mt-0.5">
                                +<span x-text="pts.toLocaleString('id-ID')"></span> <span class="text-xs font-normal text-ink-muted">PTS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Interactive Slider & Category Selection -->
                    <div class="space-y-3 text-xs font-mono">
                        <div>
                            <div class="flex justify-between text-[11px] text-ink-muted mb-1">
                                <span>Simulasi Berat:</span>
                                <span class="font-bold text-ink" x-text="weight + ' kg'"></span>
                            </div>
                            <input type="range" min="0.5" max="25" step="0.5" x-model="weight"
                                   class="w-full accent-ink bg-border h-1.5 rounded-none cursor-pointer">
                        </div>

                        <div>
                            <span class="text-[11px] text-ink-muted block mb-1.5">Pilih Kategori Sampah:</span>
                            <div class="grid grid-cols-3 gap-1.5 text-center text-[11px]">
                                <button type="button" @click="cat = 'plastik'" :class="cat === 'plastik' ? 'bg-ink text-bg font-bold' : 'border border-border bg-bg text-ink'" class="py-1 px-2 transition">
                                    Plastik (300)
                                </button>
                                <button type="button" @click="cat = 'kertas'" :class="cat === 'kertas' ? 'bg-ink text-bg font-bold' : 'border border-border bg-bg text-ink'" class="py-1 px-2 transition">
                                    Kertas (150)
                                </button>
                                <button type="button" @click="cat = 'organik'" :class="cat === 'organik' ? 'bg-ink text-bg font-bold' : 'border border-border bg-bg text-ink'" class="py-1 px-2 transition">
                                    Organik (50)
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-border text-[11px] font-mono text-ink-faint text-center">
                        * Poin otomatis dikreditkan sesaat setelah timbangan divalidasi petugas.
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: 4-Step Ledger Workflow -->
        <div class="border-t border-border pt-12 space-y-6">
            <div class="flex items-baseline justify-between">
                <div>
                    <h2 class="text-xs uppercase font-mono font-bold tracking-wider text-ink">Alur Sirkulasi</h2>
                    <p class="text-lg font-bold text-ink mt-0.5">Empat Langkah Dari Sampah Menjadi Manfaat</p>
                </div>
                <span class="text-xs font-mono text-ink-faint">Standard SOP</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="border border-border bg-surface p-4 space-y-2">
                    <span class="text-xs font-mono text-ink-faint font-bold">[STEP 01]</span>
                    <h3 class="text-sm font-bold text-ink">Pilah di Sumber</h3>
                    <p class="text-xs text-ink-muted leading-relaxed">Pisahkan sampah organik basah, anorganik kering (plastik/kertas/kaleng), dan limbah B3 dari rumah.</p>
                </div>

                <div class="border border-border bg-surface p-4 space-y-2">
                    <span class="text-xs font-mono text-ink-faint font-bold">[STEP 02]</span>
                    <h3 class="text-sm font-bold text-ink">Bawa ke Drop Point</h3>
                    <p class="text-xs text-ink-muted leading-relaxed">Kunjungi pos penimbangan mitra EcoPoints terdekat dan serahkan sampah kepada petugas jaga.</p>
                </div>

                <div class="border border-border bg-surface p-4 space-y-2">
                    <span class="text-xs font-mono text-ink-faint font-bold">[STEP 03]</span>
                    <h3 class="text-sm font-bold text-ink">Penimbangan Riil</h3>
                    <p class="text-xs text-ink-muted leading-relaxed">Petugas menimbang dengan timbangan presisi. Poin langsung bertambah di akun Anda secara realtime.</p>
                </div>

                <div class="border border-border bg-surface p-4 space-y-2">
                    <span class="text-xs font-mono text-accent-poin font-bold">[STEP 04]</span>
                    <h3 class="text-sm font-bold text-ink">Tukar Reward</h3>
                    <p class="text-xs text-ink-muted leading-relaxed">Gunakan saldo poin untuk klaim voucher digital, sembako, minyak goreng, atau merchandise bank sampah.</p>
                </div>
            </div>
        </div>

        <!-- Section: Kategori Sampah Standar Indonesia -->
        <div class="border-t border-border pt-12 space-y-6">
            <div class="flex items-baseline justify-between">
                <div>
                    <h2 class="text-xs uppercase font-mono font-bold tracking-wider text-ink">Klasifikasi Penerimaan</h2>
                    <p class="text-lg font-bold text-ink mt-0.5">Panduan Pemilahan Sampah</p>
                </div>
                <span class="text-xs font-mono text-ink-faint">Standard SNI</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Organik -->
                <div class="border border-border bg-surface p-5 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-organik"></span>
                        <span class="text-xs font-mono uppercase font-bold text-ink">Sampah Organik</span>
                    </div>
                    <p class="text-xs text-ink-muted leading-relaxed">
                        Sisa sayuran, buah-buahan, daun kering, dan limbah dapur non-minyak. Diolah menjadi pupuk kompos dan pakan maggot.
                    </p>
                    <div class="text-[11px] font-mono text-organik font-semibold pt-1 border-t border-border">
                        Tarif: 50–100 poin / kg
                    </div>
                </div>

                <!-- Anorganik -->
                <div class="border border-border bg-surface p-5 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-anorganik"></span>
                        <span class="text-xs font-mono uppercase font-bold text-ink">Sampah Anorganik</span>
                    </div>
                    <p class="text-xs text-ink-muted leading-relaxed">
                        Botol plastik PET, gelas mineral, kardus, koran, kaleng aluminium, botol kaca bening. Dicuci dan dikeringkan sebelum setor.
                    </p>
                    <div class="text-[11px] font-mono text-anorganik font-semibold pt-1 border-t border-border">
                        Tarif: 150–500 poin / kg
                    </div>
                </div>

                <!-- B3 / Khusus -->
                <div class="border border-border bg-surface p-5 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-b3"></span>
                        <span class="text-xs font-mono uppercase font-bold text-ink">Limbah B3 & Elektronik</span>
                    </div>
                    <p class="text-xs text-ink-muted leading-relaxed">
                        Baterai bekas, aki, lampu neon, kabel, barang elektronik rusak (e-waste). Ditangani dengan prosedur keamanan lingkungan khusus.
                    </p>
                    <div class="text-[11px] font-mono text-b3 font-semibold pt-1 border-t border-border">
                        Tarif: 500–2.000 poin / unit
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-border bg-surface py-8 mt-16 text-xs text-ink-muted">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 font-mono">
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 border border-ink flex items-center justify-center bg-bg text-[9px] font-bold text-ink">EP</span>
                <span class="text-ink font-semibold">EcoPoints &copy; {{ date('Y') }}</span>
                <span class="text-ink-faint">| Platform Sirkular Ekonomi Mandiri</span>
            </div>
            <div class="text-[11px] text-ink-faint">
                Built with precision typography & digital scale mechanics.
            </div>
        </div>
    </footer>

</body>
</html>
