<!DOCTYPE html>
{{--
    Layout: Guest Layout
    Doel: Minimalistische layout voor niet-ingelogde gebruikers (Login, Registratie).
    Stijl: Gecentreerde kaart op een rustige achtergrond, gericht op conversie/toegang.
--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Nunito Font --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-700 min-h-screen">

        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            {{-- Logo --}}
            <div class="animate-pop-in">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:shadow-emerald-500/40 group-hover:-translate-y-0.5 transition-all duration-300">
                        <span class="text-white font-extrabold text-xl">A</span>
                    </div>
                    <span class="font-extrabold text-2xl tracking-tight text-slate-800">ATD<span class="text-emerald-500">Hub</span></span>
                </a>
            </div>

            {{-- Auth Card --}}
            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-white rounded-[2rem] shadow-soft border border-slate-100 animate-pop-in" style="animation-delay: 100ms;">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
