<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Inter Font --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-space-950 text-gray-100 min-h-screen">

        {{-- Background Mesh & Grid --}}
        <div class="fixed inset-0 bg-mesh-gradient pointer-events-none z-0"></div>
        <div class="fixed inset-0 bg-grid-pattern bg-grid pointer-events-none opacity-40 z-0"></div>

        <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            {{-- Logo --}}
            <div class="animate-fade-in-up">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-cyan-400 rounded-2xl flex items-center justify-center shadow-lg shadow-violet-500/30 group-hover:shadow-violet-500/50 transition-shadow">
                        <span class="text-white font-extrabold text-xl">A</span>
                    </div>
                    <span class="font-bold text-2xl tracking-tight text-white">ATD<span class="text-violet-400">Hub</span></span>
                </a>
            </div>

            {{-- Auth Card (Glass) --}}
            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-space-900/60 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl shadow-violet-500/5 animate-fade-in-up" style="animation-delay: 100ms;">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
