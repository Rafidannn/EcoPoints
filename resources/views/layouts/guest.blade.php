<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EcoPoints') }}</title>

        <!-- Google Fonts: Public Sans + IBM Plex Mono -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Public+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-bg text-ink min-h-screen flex flex-col justify-center items-center py-10 px-4">
        <div class="mb-6 text-center">
            <a href="/" class="inline-flex items-center gap-2 font-mono font-bold text-ink">
                <span class="w-8 h-8 border-2 border-ink flex items-center justify-center bg-surface text-ink text-sm font-bold">
                    EP
                </span>
                <span class="text-xl tracking-tight font-bold">EcoPoints</span>
            </a>
            <p class="text-xs font-mono text-ink-faint mt-1">Platform Bank Sampah Digital & Daur Ulang</p>
        </div>

        <div class="w-full max-w-sm border border-border bg-surface p-6">
            {{ $slot }}
        </div>
    </body>
</html>
