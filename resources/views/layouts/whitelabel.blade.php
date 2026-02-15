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

    <header class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-40 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                {{-- Logo / Initial Icon --}}
                <div class="w-10 h-10 rounded-lg shadow-md flex items-center justify-center text-white font-bold text-xl transform hover:scale-105 transition duration-300"
                     style="background: linear-gradient(135deg, {{ $company->brand_color ?? '#3b82f6' }}, {{ md5($company->brand_color ?? '#3b82f6') }});">
                    {{ substr($company->user->name ?? 'C', 0, 1) }}
                </div>
                <h1 class="font-extrabold text-2xl text-gray-900 tracking-tight hover:text-gray-700 transition">
                    {{ $company->user->name ?? 'Company Name' }}
                </h1>
            </div>

            <nav class="hidden md:flex space-x-8">
                <a href="#home" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition uppercase tracking-wide">Home</a>
                <a href="#about" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition uppercase tracking-wide">About</a>
                <a href="#products" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition uppercase tracking-wide">Products</a>
            </nav>
            
            <button class="md:hidden text-gray-500 hover:text-gray-900 p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
    </header>

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
