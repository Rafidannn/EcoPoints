@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-bg border border-border text-ink text-xs font-mono p-2.5 rounded-none focus:border-ink focus:ring-1 focus:ring-ink transition-colors']) }}>
