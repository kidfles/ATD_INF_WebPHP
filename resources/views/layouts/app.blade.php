<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Nunito Font --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800&display=swap" rel="stylesheet" />

        {{-- Scripts --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-700">
        <div class="min-h-screen flex flex-col">

            {{-- ═══════════════════════════════════════════════════════════
                 FLOATING PILL NAVBAR (Desktop) / STICKY TOP BAR (Mobile)
                 ═══════════════════════════════════════════════════════════ --}}
            <nav x-data="{ mobileOpen: false }" class="sticky top-0 z-50 md:relative md:top-auto">
                {{-- Mobile: Sticky full-width bar --}}
                <div class="md:hidden bg-white/90 backdrop-blur-lg border-b border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between px-4 h-14">
                        <a href="{{ route('dashboard.index') }}" class="font-extrabold text-lg text-slate-800">
                            ATD<span class="text-emerald-500">Webshop</span>
                        </a>
                        <button @click="mobileOpen = !mobileOpen" class="text-slate-400 hover:bg-slate-100 hover:text-emerald-500 rounded-full p-2 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path :class="{'hidden': mobileOpen, 'inline-flex': !mobileOpen}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                <path :class="{'hidden': !mobileOpen, 'inline-flex': mobileOpen}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    {{-- Mobile Menu Dropdown --}}
                    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2" class="bg-white border-t border-slate-100 shadow-lg" style="display: none;">
                        <div class="p-4 space-y-1">
                            <a href="{{ route('market.index') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">{{ __('Market') }}</a>
                            <a href="{{ route('dashboard.index') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('dashboard.index') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-600' }} transition-all">{{ __('Overview') }}</a>
                            <a href="{{ route('dashboard.advertisements.index') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('dashboard.advertisements.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-600' }} transition-all">{{ __('My Ads') }}</a>
                            <a href="{{ route('dashboard.bids.index') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('dashboard.bids.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-600' }} transition-all">{{ __('My Bids') }}</a>
                            <a href="{{ route('dashboard.rentals.index') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('dashboard.rentals.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-600' }} transition-all">{{ __('My Rentals') }}</a>
                            <a href="{{ route('dashboard.orders.index') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('dashboard.orders.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-600' }} transition-all">{{ __('My Purchases') }}</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('profile.edit') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-600' }} transition-all">{{ __('Profile') }}</a>
                            <div class="border-t border-slate-100 my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold text-red-500 hover:bg-red-50 transition-all">{{ __('Logout') }}</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Desktop: Floating Pill --}}
                <div class="hidden md:block py-4">
                    <div class="max-w-5xl mx-auto">
                        <div class="bg-white rounded-full shadow-soft-lg px-6 py-3 flex items-center justify-between">
                            <a href="{{ route('dashboard.index') }}" class="font-extrabold text-lg text-slate-800">
                                ATD<span class="text-emerald-500">Webshop</span>
                            </a>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('market.index') }}" class="px-4 py-2 rounded-full text-sm font-bold {{ request()->routeIs('market.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }} transition-all">{{ __('Market') }}</a>
                                <a href="{{ route('dashboard.index') }}" class="px-4 py-2 rounded-full text-sm font-bold {{ request()->routeIs('dashboard.index') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }} transition-all">{{ __('Overview') }}</a>
                                <a href="{{ route('dashboard.advertisements.index') }}" class="px-4 py-2 rounded-full text-sm font-bold {{ request()->routeIs('dashboard.advertisements.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }} transition-all">{{ __('My Ads') }}</a>
                            </div>
                            <div class="flex items-center gap-3">
                                {{-- User Dropdown --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <span class="hidden lg:block">{{ Auth::user()->name }}</span>
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-300" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-soft-lg border border-slate-100 py-2 z-50" style="display: none;">
                                        <div class="px-4 py-3 border-b border-slate-100">
                                            <p class="text-xs text-slate-400">{{ __('Logged in as') }}</p>
                                            <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                        </div>
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">{{ __('Profile Settings') }}</a>
                                        @if(Auth::user()->isBusinessAdvertiser())
                                            <a href="{{ route('dashboard.company.settings.edit') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">{{ __('Company Settings') }}</a>
                                        @endif
                                        <div class="border-t border-slate-100 my-1"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-all">{{ __('Logout') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- ═══════════════════════════════════════════════════════════
                 MAIN CONTENT AREA (Sidebar + Page)
                 ═══════════════════════════════════════════════════════════ --}}
            <div class="flex-1">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex flex-col md:flex-row gap-6">

                        {{-- Sidebar (Desktop only) --}}
                        <aside class="hidden md:block flex-shrink-0">
                            <x-dashboard-sidebar />
                        </aside>

                        {{-- Page Content --}}
                        <main class="flex-1 min-w-0">
                            {{-- Page Header --}}
                            @isset($header)
                                <div class="mb-6">
                                    {{ $header }}
                                </div>
                            @endisset

                            {{-- Flash Messages --}}
                            @if (session('success'))
                                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-medium" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl text-sm font-medium">
                                    {{ session('error') }}
                                </div>
                            @endif

                            {{ $slot }}
                        </main>

                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
