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

        {{-- ═══════════════════════════════════════════════════════════════════
             FLOATING ISLAND NAVIGATION (Capsule Navbar)
             ═══════════════════════════════════════════════════════════════════ --}}
        <nav x-data="{ open: false, userMenu: false }" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-6xl">
            <div class="bg-slate-800/60 backdrop-blur-2xl border border-white/10 rounded-full shadow-2xl shadow-violet-500/5 px-6 py-3">
                <div class="flex items-center justify-between">

                    {{-- LEFT: Logo --}}
                    <a href="{{ route('market.index') }}" class="flex items-center gap-2 flex-shrink-0 group">
                        <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-cyan-400 rounded-lg flex items-center justify-center shadow-lg shadow-violet-500/30 group-hover:shadow-violet-500/50 transition-shadow">
                            <span class="text-white font-extrabold text-sm">A</span>
                        </div>
                        <span class="font-bold text-lg tracking-tight text-white hidden sm:block">ATD<span class="text-violet-400">Hub</span></span>
                    </a>

                    {{-- CENTER: Navigation Links (Desktop) --}}
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('market.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-full transition-all duration-200 {{ request()->routeIs('market.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            {{ __('Marketplace') }}
                        </a>
                        @auth
                        <a href="{{ route('dashboard.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-full transition-all duration-200 {{ request()->routeIs('dashboard.index') ? 'bg-violet-500/20 text-violet-300 border border-violet-500/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            {{ __('Dashboard Overview') }}
                        </a>
                        <a href="{{ route('dashboard.advertisements.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-full transition-all duration-200 {{ request()->routeIs('dashboard.advertisements.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            {{ __('My Ads') }}
                        </a>
                        <a href="{{ route('dashboard.rentals.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-full transition-all duration-200 {{ request()->routeIs('dashboard.rentals.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            {{ __('My Rentals') }}
                        </a>
                        <a href="{{ route('dashboard.orders.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-full transition-all duration-200 {{ request()->routeIs('dashboard.orders.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            {{ __('My Purchases') }}
                        </a>
                        @endauth
                    </div>

                    {{-- RIGHT: Lang Switch + User --}}
                    <div class="flex items-center gap-3">
                        {{-- Language Switcher --}}
                        <div class="flex items-center gap-1 bg-white/5 rounded-full px-1 py-0.5">
                            <a href="{{ route('lang.switch', 'nl') }}" 
                               class="px-2 py-0.5 text-xs rounded-full transition {{ app()->getLocale() == 'nl' ? 'bg-violet-500 text-white font-bold shadow-sm' : 'text-gray-400 hover:text-white' }}">
                               NL
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}" 
                               class="px-2 py-0.5 text-xs rounded-full transition {{ app()->getLocale() == 'en' ? 'bg-violet-500 text-white font-bold shadow-sm' : 'text-gray-400 hover:text-white' }}">
                               EN
                            </a>
                        </div>

                        @auth
                        {{-- User Dropdown --}}
                        <div class="relative">
                            <button @click="userMenu = !userMenu" @click.outside="userMenu = false"
                                    class="flex items-center gap-2 group focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-cyan-500 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-violet-500/20 group-hover:shadow-violet-500/40 transition-shadow">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <svg class="w-3 h-3 text-gray-400 transition-transform" :class="{ 'rotate-180': userMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="userMenu"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-4 w-64 bg-slate-800/90 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl py-2 z-50"
                                 style="display: none;">
                                
                                {{-- User Info --}}
                                <div class="px-4 py-3 border-b border-white/5">
                                    <p class="text-xs text-gray-500">{{ __('Logged in as') }}</p>
                                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                                </div>

                                <div class="py-1">
                                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 transition">
                                        <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        {{ __('Dashboard Overview') }}
                                    </a>
                                    <a href="{{ route('dashboard.bids.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 transition">
                                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                        {{ __('My Bids') }}
                                    </a>
                                    <a href="{{ route('dashboard.favorites.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 transition">
                                        <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.486 4.486 0 000 6.364L12 20.364l7.682-7.682a4.486 4.486 0 000-6.364 4.486 4.486 0 00-6.364 0L12 7.636l-1.318-1.318a4.486 4.486 0 00-6.364 0z"></path></svg>
                                        {{ __('My Favorites') }}
                                    </a>
                                    
                                    <div class="border-t border-white/5 my-1"></div>
                                    
                                    @if(Auth::user()->isBusinessAdvertiser())
                                    <a href="{{ route('dashboard.company.settings.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 transition">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        {{ __('Company Settings') }}
                                    </a>
                                    @endif
                                    
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ __('Profile Settings') }}
                                    </a>

                                    <div class="border-t border-white/5 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            {{ __('Logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Place Ad Button (Advertisers) --}}
                        @if(Auth::user()->role !== 'user')
                        <a href="{{ route('dashboard.advertisements.create') }}" class="hidden md:inline-flex items-center gap-1.5 px-4 py-1.5 bg-gradient-to-r from-violet-600 to-cyan-500 text-white text-sm font-semibold rounded-full shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 hover:scale-105 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            {{ __('Place advertisement') }}
                        </a>
                        @endif
                        @else
                        {{-- Guest Links --}}
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400 hover:text-white transition">{{ __('Log in') }}</a>
                        <a href="{{ route('register') }}" class="px-4 py-1.5 bg-violet-600 text-white text-sm font-semibold rounded-full hover:bg-violet-500 transition">{{ __('Register') }}</a>
                        @endauth

                        {{-- Mobile Hamburger --}}
                        <button @click="open = !open" class="md:hidden p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition">
                            <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-4"
                 class="md:hidden mt-2 bg-slate-800/90 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl p-4" style="display:none;">
                <div class="space-y-1">
                    <a href="{{ route('market.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">{{ __('Marketplace') }}</a>
                    @auth
                    <a href="{{ route('dashboard.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">{{ __('Dashboard Overview') }}</a>
                    <a href="{{ route('dashboard.advertisements.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">{{ __('My Ads') }}</a>
                    <a href="{{ route('dashboard.bids.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">{{ __('My Bids') }}</a>
                    <a href="{{ route('dashboard.rentals.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">{{ __('My Rentals') }}</a>
                    <a href="{{ route('dashboard.orders.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">{{ __('My Purchases') }}</a>
                    <a href="{{ route('dashboard.favorites.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">{{ __('My Favorites') }}</a>
                    <div class="border-t border-white/5 my-2"></div>
                    <div class="px-3 py-2">
                        <p class="text-xs text-gray-500">{{ Auth::user()->name }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">{{ __('Profile Settings') }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition">{{ __('Logout') }}</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">{{ __('Log in') }}</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">{{ __('Register') }}</a>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- ═══════════════════════════════════════════════════════════════════
             MAIN CONTENT AREA
             ═══════════════════════════════════════════════════════════════════ --}}
        <div class="relative z-10 min-h-screen pt-24">

            {{-- Page Header (Optional) --}}
            @isset($header)
                <header class="mb-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Main Content --}}
            <main class="pb-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    {{-- Flash Messages (Glass Style) --}}
                    @if(session('success'))
                        <div class="mb-6 bg-emerald-500/10 backdrop-blur-lg border border-emerald-500/20 text-emerald-300 px-5 py-3 rounded-xl flex items-center gap-3"
                             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 bg-red-500/10 backdrop-blur-lg border border-red-500/20 text-red-300 px-5 py-3 rounded-xl flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('status'))
                        <div class="mb-6 bg-cyan-500/10 backdrop-blur-lg border border-cyan-500/20 text-cyan-300 px-5 py-3 rounded-xl flex items-center gap-3"
                             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ __(session('status')) }}
                        </div>
                    @endif

                    {{-- Sidebar + Content Wrapper --}}
                    <div class="flex flex-col md:flex-row gap-6">
                        {{-- Sidebar (visible on sub-pages) --}}
                        @if(!request()->routeIs('dashboard.index'))
                        <div class="hidden md:block">
                            <x-dashboard-sidebar />
                        </div>
                        @endif

                        {{-- Main Slot --}}
                        <div class="flex-1 w-full">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
