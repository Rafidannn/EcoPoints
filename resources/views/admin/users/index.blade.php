<x-app-layout>
    <x-slot name="header">
        <div class="flex items-baseline justify-between">
            <h1 class="text-base font-semibold text-ink">Manajemen Pengguna & Hak Akses</h1>
            <span class="text-xs font-mono text-ink-faint">Role & Otoritas</span>
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 space-y-4">
        @if(session('success'))
            <div class="p-3 bg-surface border border-organik/40 text-organik text-xs font-mono">
                [OK] {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-3 bg-surface border border-b3/40 text-b3 text-xs font-mono">
                [ERROR] {{ session('error') }}
            </div>
        @endif

        <!-- Filter bar -->
        <div class="border border-border bg-surface p-3">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                       class="text-xs bg-bg border border-border text-ink rounded-none py-1.5 px-3 flex-1 min-w-48 font-mono focus:ring-1 focus:ring-ink focus:border-ink">
                <select name="role" class="text-xs bg-bg border border-border text-ink rounded-none py-1.5 px-2 font-mono focus:ring-1 focus:ring-ink focus:border-ink">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->value }}" @selected(request('role') === $role->value)>{{ ucfirst($role->value) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Filter</button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-ink-muted hover:text-ink font-mono underline">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="border border-border bg-surface overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-border bg-bg/40 text-ink-muted uppercase font-mono text-[11px]">
                        <tr>
                            <th class="py-2.5 px-4">Pengguna</th>
                            <th class="py-2.5 px-4">Role</th>
                            <th class="py-2.5 px-4 text-right">Saldo Poin</th>
                            <th class="py-2.5 px-4 text-right">Total Setoran</th>
                            <th class="py-2.5 px-4">Terdaftar</th>
                            <th class="py-2.5 px-4 text-right">Ubah Otoritas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($users as $user)
                            <tr class="hover:bg-bg/40 transition">
                                <td class="py-3 px-4">
                                    <p class="font-semibold text-ink">{{ $user->name }}</p>
                                    <p class="text-[11px] font-mono text-ink-faint">{{ $user->email }}</p>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @php
                                        $roleValue = $user->role?->value ?? 'user';
                                        $dot = match($roleValue) {
                                            'admin' => 'bg-b3',
                                            'petugas' => 'bg-accent-poin',
                                            default => 'bg-organik',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 font-mono text-[11px] text-ink uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                                        {{ $roleValue }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-accent-poin whitespace-nowrap">
                                    {{ number_format($user->points_balance) }} pt
                                </td>
                                <td class="py-3 px-4 text-right font-mono text-ink whitespace-nowrap">
                                    {{ number_format($user->waste_deposits_count) }}
                                </td>
                                <td class="py-3 px-4 text-ink-muted text-[11px] font-mono whitespace-nowrap">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    @if($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="inline-flex items-center">
                                            @csrf @method('PATCH')
                                            <select name="role" onchange="this.form.submit()"
                                                    class="text-[11px] bg-bg border border-border text-ink rounded-none py-1 px-2 font-mono focus:ring-1 focus:ring-ink focus:border-ink">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->value }}" @selected($user->role?->value === $role->value)>
                                                        &rarr; {{ ucfirst($role->value) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    @else
                                        <span class="text-[11px] font-mono text-ink-faint italic">[Akun Anda]</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-ink-faint font-mono text-xs">
                                    -- Tidak ada pengguna ditemukan --
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-3 border-t border-border bg-bg/20">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
