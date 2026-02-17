<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Market</title>

    {{-- Nunito Font --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-700 flex flex-col min-h-screen">
    
    <x-global-header />

    <main class="flex-grow">
        {{ $slot }}
    </main>
    
    <footer class="bg-white border-t border-slate-100 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-slate-400 text-sm font-medium">
            &copy; {{ date('Y') }} ATD Webshop. All rights reserved.
        </div>
    </footer>
</body>
</html>
