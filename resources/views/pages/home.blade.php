<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ATD Webshop</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <!-- Navigation -->
    <x-global-header />

    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">{{ __('Premium Marketplace for') }}</span>
                            <span class="block text-indigo-600 xl:inline">{{ __('Professional Gear') }}</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            {{ __('Buy, sell, rent, or auction high-quality equipment. Join our community of professionals today.') }}
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    {{ __('Get Started') }}
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="{{ route('market.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
                                    {{ __('Browse Ads') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-gray-100 flex items-center justify-center">
             <!-- Placeholder for hero image -->
             <div class="text-center text-gray-400">
                <svg class="h-64 w-64 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <p>Welcome Image</p>
             </div>
        </div>
    </div>

    <!-- Featured Ads Section -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">{{ __('Marketplace') }}</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">{{ __('Latest Advertisements') }}</p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($featuredAds as $ad)
                        <div class="flow-root bg-white rounded-lg shadow-lg overflow-hidden transition transform hover:-translate-y-1 hover:shadow-xl">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-bold uppercase rounded-md">
                                        {{ $ad->type }}
                                    </span>
                                    <span class="ml-auto text-sm text-gray-500">{{ $ad->created_at->diffForHumans() }}</span>
                                </div>
                                <h3 class="mt-4 text-xl font-bold text-gray-900">
                                    <a href="{{ route('market.show', $ad) }}" class="hover:underline">{{ $ad->title }}</a>
                                </h3>
                                <p class="mt-2 text-base text-gray-500 line-clamp-3">
                                    {{ $ad->description }}
                                </p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-lg font-bold text-indigo-600">€{{ number_format($ad->price, 2) }}</span>
                                    <a href="{{ route('market.show', $ad) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View &rarr;</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-10 text-center">
                    <a href="{{ route('market.index') }}" class="text-base font-semibold text-indigo-600 hover:text-indigo-500">
                        {{ __('View all advertisements') }} <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-base text-gray-400">
                &copy; {{ date('Y') }} ATD Webshop. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>
