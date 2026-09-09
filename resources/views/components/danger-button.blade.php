<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-b3 border border-b3 text-bg font-mono font-bold text-xs uppercase tracking-wider hover:opacity-90 transition-opacity']) }}>
    {{ $slot }}
</button>
