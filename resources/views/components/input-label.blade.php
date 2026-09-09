@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-mono uppercase text-ink font-semibold mb-1']) }}>
    {{ $value ?? $slot }}
</label>
