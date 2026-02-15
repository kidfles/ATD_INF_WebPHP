<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $company->user->name ?? 'Company Page' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 flex flex-col min-h-screen">

    <x-global-header />



    <main class="flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-gray-100 border-t border-gray-200 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-600 font-medium">{{ $company->user->name }}</p>
            <p class="text-gray-400 text-sm mt-2">KvK: {{ $company->kvk_number }} &bull; Powered by ATDWebshop</p>
        </div>
    </footer>

</body>
</html>
