@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-3 bg-bg border border-organik/40 text-organik text-xs font-mono']) }}>
        [OK] {{ $status }}
    </div>
@endif
