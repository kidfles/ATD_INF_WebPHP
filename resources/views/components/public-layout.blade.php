<!DOCTYPE html>
{{--
    Layout: Publieke Layout
    Doel: Wrapper voor openbare pagina's (niet-dashboard).
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
    <body class="font-sans antialiased bg-slate-50 text-slate-700">
        <div class="min-h-screen bg-slate-50">
            {{-- GLOBAL HEADER --}}
            <x-global-header />

            {{-- Main Content (No Sidebar) --}}
            <main class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    
                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl mb-4 mx-4 sm:mx-0 text-sm font-medium" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl mb-4 mx-4 sm:mx-0 text-sm font-medium">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="w-full">
                        {{ $slot }}
                    </div>

                </div>
            </main>
        </div>
    </body>
</html>
