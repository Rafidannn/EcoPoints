<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-secondary text-xs font-mono uppercase tracking-wider']) }}>
    {{ $slot }}
</button>
