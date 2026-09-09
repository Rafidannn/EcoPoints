<section class="space-y-4">
    <header class="border-b border-border pb-3">
        <h2 class="text-xs font-mono uppercase font-bold text-b3">
            Hapus Akun Pengguna
        </h2>
        <p class="mt-0.5 text-xs text-ink-muted">
            Setelah akun dihapus, seluruh data historis transaksi dan saldo poin akan dihapus permanen.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Hapus Akun Ini</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-surface border border-border">
            @csrf
            @method('delete')

            <h2 class="text-sm font-bold font-mono text-ink">
                Konfirmasi Penghapusan Akun?
            </h2>

            <p class="mt-1 text-xs text-ink-muted">
                Tindakan ini permanen. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun.
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="Kata Sandi Akun" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full"
                    placeholder="Masukkan kata sandi..."
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-danger-button>
                    Hapus Akun Permanen
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
