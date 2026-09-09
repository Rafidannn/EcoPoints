@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-ink text-xs font-mono font-bold text-ink focus:outline-none transition'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-mono text-ink-muted hover:text-ink hover:border-border focus:outline-none transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
