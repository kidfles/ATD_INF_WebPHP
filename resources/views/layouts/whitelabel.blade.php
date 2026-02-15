<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $company->name ?? 'Company Page' }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* 1. Inject the color from the database */
            --brand-color: {{ $company->brand_color ?? '#3b82f6' }};
            
            /* 2. Calculate a darker shade for hover effects using CSS color-mix */
            --brand-dark: color-mix(in srgb, var(--brand-color), black 20%);
            
            /* 3. Determine readable text color (simplified) */
            --brand-text: #ffffff; 
        }

        /* Utility classes for the brand */
        .bg-brand { background-color: var(--brand-color); color: var(--brand-text); }
        .text-brand { color: var(--brand-color); }
        .border-brand { border-color: var(--brand-color); }
        
        .btn-brand {
            background-color: var(--brand-color);
            color: var(--brand-text);
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .btn-brand:hover {
            background-color: var(--brand-dark);
        }
        
        body {
            /* Reset body background to neutral, let sections handle color */
            background-color: #f9fafb; /* gray-50 */
            color: #111827; /* gray-900 */
        }
    </style>
</head>
<body class="font-sans antialiased">
    
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="font-bold text-2xl text-brand">
                 {{ optional($company->user)->name ?? $company->name }}
            </div>
            <nav class="space-x-4">
                <a href="#home" class="hover:text-brand transition">Home</a>
                <a href="#products" class="hover:text-brand transition">Products</a>
                <a href="{{ route('market.index') }}" class="text-gray-400 text-sm hover:text-gray-600 transition">Back to Market</a>
            </nav>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} {{ optional($company->user)->name ?? $company->name }}. Powered by MarketMashup.</p>
            <p class="text-gray-500 text-sm mt-2">KvK: {{ $company->kvk_number }}</p>
        </div>
    </footer>

</body>
</html>
