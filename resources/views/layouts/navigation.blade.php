@php
    $role = Auth::user()?->role?->value ?? 'user';
    $dashboardRoute = match($role) {
        'admin'   => 'admin.dashboard',
        'petugas' => 'petugas.dashboard',
        default   => 'dashboard',
    };
@endphp

<header x-data="{ open: false }" class="bg-paper border-b border-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-12">

            {{-- Logo --}}
            <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-2 text-ink font-semibold text-sm">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-primary">
                    <path d="M10 2C5.58 2 2 5.58 2 10s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm.75 11.5h-1.5v-5.5h1.5v5.5zm0-7h-1.5V5h1.5v1.5z" fill="currentColor"/>
                    <path d="M10 2a8 8 0 100 16A8 8 0 0010 2zm0 1.5a6.5 6.5 0 110 13 6.5 6.5 0 010-13z" fill="currentColor" opacity=".3"/>
                    <path d="M7 10.5l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                EcoPoints
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden sm:flex items-center gap-0.5">
                @auth
                    @if($role === 'user')
                        <a href="{{ route('dashboard') }}"
                           class="{{ request()->routeIs('dashboard') ? 'nav-link-active' : 'nav-link' }}">Dashboard</a>
                        <a href="{{ route('deposits.create') }}"
                           class="{{ request()->routeIs('deposits.create') ? 'nav-link-active' : 'nav-link' }} ml-2">Setor Sampah</a>
                        <a href="{{ route('deposits.index') }}"
                           class="{{ request()->routeIs('deposits.index') ? 'nav-link-active' : 'nav-link' }}">Setoran</a>
                        <a href="{{ route('points.index') }}"
                           class="{{ request()->routeIs('points.*') ? 'nav-link-active' : 'nav-link' }}">Poin</a>
                        <a href="{{ route('rewards.index') }}"
                           class="{{ request()->routeIs('rewards.*') ? 'nav-link-active' : 'nav-link' }}">Reward</a>
                    @endif

                    @if($role === 'petugas')
                        <a href="{{ route('petugas.dashboard') }}"
                           class="{{ request()->routeIs('petugas.dashboard') ? 'nav-link-active' : 'nav-link' }}">Dashboard</a>
                        <a href="{{ route('petugas.deposits.index') }}"
                           class="{{ request()->routeIs('petugas.deposits.*') ? 'nav-link-active' : 'nav-link' }}">Verifikasi Setoran</a>
                    @endif

                    @if($role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="{{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : 'nav-link' }}">Dashboard</a>
                        <a href="{{ route('admin.waste-types.index') }}"
                           class="{{ request()->routeIs('admin.waste-types.*') ? 'nav-link-active' : 'nav-link' }}">Jenis Sampah</a>
                        <a href="{{ route('admin.drop-points.index') }}"
                           class="{{ request()->routeIs('admin.drop-points.*') ? 'nav-link-active' : 'nav-link' }}">Drop Point</a>
                        <a href="{{ route('admin.rewards.index') }}"
                           class="{{ request()->routeIs('admin.rewards.*') ? 'nav-link-active' : 'nav-link' }}">Reward</a>
                        <a href="{{ route('admin.users.index') }}"
                           class="{{ request()->routeIs('admin.users.*') ? 'nav-link-active' : 'nav-link' }}">Pengguna</a>
                        <a href="{{ route('admin.reports.index') }}"
                           class="{{ request()->routeIs('admin.reports.*') ? 'nav-link-active' : 'nav-link' }}">Laporan</a>
                    @endif
                @endauth
            </nav>

            {{-- Right side --}}
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    @if($role === 'user')
                        <span class="pts-display text-sm font-semibold">{{ number_format(Auth::user()->points_balance) }}<span class="text-ink-faint text-xs ml-1 font-sans font-normal">pts</span></span>
                    @endif

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="flex items-center gap-2 text-sm text-ink hover:text-ink-muted transition-colors py-1">
                            <span>{{ Auth::user()->name }}</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded border border-border text-ink-faint uppercase tracking-wide font-medium">{{ $role }}</span>
                            <svg class="w-3.5 h-3.5 text-ink-faint" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-transition
                             class="absolute right-0 top-full mt-1 w-44 bg-paper border border-border rounded shadow-lg z-50 py-1">
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 text-sm text-ink hover:bg-surface transition-colors">Profil</a>
                            <div class="border-t border-border my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-b3 hover:bg-surface transition-colors">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <button @click="open = !open" class="sm:hidden p-1.5 text-ink-muted hover:text-ink transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{'hidden': open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'hidden': !open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" class="sm:hidden border-t border-border bg-paper py-2">
        @auth
            @if($role === 'user')
                <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Dashboard</a>
                <a href="{{ route('deposits.create') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Setor Sampah</a>
                <a href="{{ route('deposits.index') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Riwayat Setoran</a>
                <a href="{{ route('points.index') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Riwayat Poin</a>
                <a href="{{ route('rewards.index') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Reward</a>
            @endif
            @if($role === 'petugas')
                <a href="{{ route('petugas.dashboard') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Dashboard</a>
                <a href="{{ route('petugas.deposits.index') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Verifikasi Setoran</a>
            @endif
            @if($role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Dashboard</a>
                <a href="{{ route('admin.waste-types.index') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Jenis Sampah</a>
                <a href="{{ route('admin.drop-points.index') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Drop Point</a>
                <a href="{{ route('admin.rewards.index') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Reward</a>
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Pengguna</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2.5 text-sm text-ink hover:bg-surface">Laporan</a>
            @endif
            <div class="border-t border-border mt-2 pt-2 px-4">
                <p class="text-xs text-ink-faint mb-1">{{ Auth::user()->name }}</p>
                @if($role === 'user')
                    <p class="pts-display text-sm mb-2">{{ number_format(Auth::user()->points_balance) }}<span class="text-ink-faint text-xs ml-1 font-sans font-normal">pts</span></p>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-b3">Keluar</button>
                </form>
            </div>
        @endauth
    </div>
</header>
