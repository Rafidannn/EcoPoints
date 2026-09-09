@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-2 border-ink text-start text-xs font-mono font-bold text-ink bg-bg focus:outline-none transition'
            : 'block w-full ps-3 pe-4 py-2 border-l-2 border-transparent text-start text-xs font-mono text-ink-muted hover:text-ink hover:bg-bg focus:outline-none transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
