<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen bg-gray-100">
            {{-- GLOBAL HEADER (Marktplaats Style) --}}
            <x-global-header />

            {{-- Main Content (No Sidebar) --}}
            <main class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    
                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mx-4 sm:mx-0">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-4 sm:mx-0">
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
