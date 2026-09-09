<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary text-xs font-mono uppercase tracking-wider']) }}>
    {{ $slot }}
</button>
