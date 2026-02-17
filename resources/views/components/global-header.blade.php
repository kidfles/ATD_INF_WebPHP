<nav class="bg-white border-b border-slate-100 shadow-sm">
{{--
    Component: Globale Header
    Doel: De bovenste navigatiebalk zichtbaar op publieke pagina's.
    Bevat: Logo, zoekbalk en gebruikersmenu.
--}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
        
        {{-- LEFT: LOGO --}}
        <div class="flex-shrink-0 flex items-center">
            <a href="{{ route('market.index') }}" class="font-extrabold text-2xl tracking-tight text-slate-800 hover:text-emerald-600 transition-colors">
                ATD<span class="text-emerald-500">Webshop</span>
            </a>
        </div>

        {{-- CENTER: SEARCH BAR --}}
        <div class="flex-1 max-w-2xl mx-4 mr-8">
            <form action="{{ route('market.index') }}" method="GET" class="relative">
                <input type="text" name="search" placeholder="{{ __('Search marketplace...') }}" 
                       class="w-full bg-slate-50 text-slate-700 border border-transparent rounded-full py-2.5 pl-5 pr-12 shadow-sm focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 placeholder-slate-400 transition-all duration-200"
                       value="{{ request('search') }}">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        {{-- RIGHT: MENU --}}
        <div class="flex items-center gap-4 flex-shrink-0">

            <div class="flex items-center space-x-1">
                <a href="{{ route('lang.switch', 'nl') }}" 
                   class="px-2.5 py-1 text-xs font-bold rounded-full transition-all {{ app()->getLocale() == 'nl' ? 'bg-emerald-500 text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-emerald-50 hover:text-emerald-600' }}">
                   NL
                </a>
                <a href="{{ route('lang.switch', 'en') }}" 
                   class="px-2.5 py-1 text-xs font-bold rounded-full transition-all {{ app()->getLocale() == 'en' ? 'bg-emerald-500 text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-emerald-50 hover:text-emerald-600' }}">
                   EN
                </a>
            </div>
            
            <a href="{{ route('market.index') }}" class="text-sm font-bold text-slate-500 hover:text-emerald-600 hidden md:block transition-colors">
                {{ __('Help and info') }}
            </a>

            @auth
                {{-- AUTHENTICATED: MIJN ATD DROPDOWN --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            @click.outside="open = false" 
                            class="flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-emerald-600 focus:outline-none transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="hidden md:block">{{ __('My ATD') }}</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-300"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-soft-lg border border-slate-100 py-2 z-50"
                         style="display: none;">
                        
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-xs text-slate-400">{{ __('Logged in as') }}</p>
                            <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                        </div>

                        <a href="{{ route('dashboard.index') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                            {{ __('Dashboard Overview') }}
                        </a>
                        <a href="{{ route('dashboard.advertisements.index') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                            {{ __('My Advertisements') }}
                        </a>
                        <a href="{{ route('dashboard.bids.index') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                            {{ __('My Bids') }}
                        </a>
                        <a href="{{ route('dashboard.favorites.index') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                            {{ __('My Favorites') }}
                        </a>
                        <a href="{{ route('dashboard.rentals.index') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                            {{ __('My Rentals') }}
                        </a>
                        <a href="{{ route('dashboard.orders.index') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                            {{ __('My Purchases') }}
                        </a>
                        
                        <div class="border-t border-slate-100 my-1"></div>

                        @if(Auth::user()->isBusinessAdvertiser())
                            <a href="{{ route('dashboard.company.settings.edit') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                                {{ __('Company Settings') }}
                            </a>
                        @endif
                        
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                            {{ __('Profile Settings') }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-all">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- PLACE AD BUTTON --}}
                @if(Auth::user()->role !== 'user')
                    <a href="{{ route('dashboard.advertisements.create') }}" class="ml-2 hidden md:inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full text-sm font-bold text-white shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300">
                        {{ __('Place advertisement') }}
                    </a>
                @endif

            @else
                {{-- GUEST --}}
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">{{ __('Log in') }}</a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full text-sm font-bold text-white shadow-sm hover:shadow-emerald-500/30 transition-all duration-200">{{ __('Register') }}</a>
            @endauth

        </div>
    </div>
</nav>
