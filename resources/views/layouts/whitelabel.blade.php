<!DOCTYPE html>
{{--
    Layout: Whitelabel Company Layout
    Doel: De basislayout voor specifieke bedrijfspagina's.
    Kenmerk: Minimalistisch, zodat de branding van het bedrijf (logo/kleuren) centraal staat.
--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $company->user->name ?? 'Company Page' }}</title>

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

    <footer class="bg-white border-t border-slate-100 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-700 font-bold">{{ optional($company->user)->name ?? 'Company Name' }}</p>
            <p class="text-slate-400 text-sm mt-2">KvK: {{ $company->kvk_number }} &bull; Powered by ATDWebshop</p>
        </div>
    </footer>

</body>
</html>
