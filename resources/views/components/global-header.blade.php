<nav class="bg-gray-100 border-b border-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
        
        {{-- LEFT: LOGO --}}
        <div class="flex-shrink-0 flex items-center">
            <a href="{{ route('market.index') }}" class="font-bold text-2xl tracking-tight text-gray-800 hover:text-gray-600">
                ATD<span class="text-indigo-600">Webshop</span>
            </a>
        </div>

        {{-- CENTER: SEARCH BAR --}}
        <div class="flex-1 max-w-2xl mx-4 mr-8">
            <form action="{{ route('market.index') }}" method="GET" class="relative">
                <input type="text" name="search" placeholder="Wat zoek je?" 
                       class="w-full bg-white text-gray-900 border border-gray-300 rounded-lg py-2 pl-4 pr-4 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-500"
                       value="{{ request('search') }}">
            </form>
        </div>

        {{-- RIGHT: MENU --}}
        <div class="flex items-center gap-4 flex-shrink-0">
            
            <a href="{{ route('market.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 hidden md:block">
                Help en info
            </a>

            @auth
                {{-- AUTHENTICATED: MIJN ATD DROPDOWN --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            @click.outside="open = false" 
                            class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="hidden md:block">Mijn ATD</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5"
                         style="display: none;">
                        
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm">Ingelogd als</p>
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        </div>

                        <a href="{{ route('dashboard.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Dashboard Overzicht
                        </a>
                        <a href="{{ route('dashboard.advertisements.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Mijn Advertenties
                        </a>
                        <a href="{{ route('dashboard.bids.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Mijn Biedingen
                        </a>
                        <a href="{{ route('dashboard.rentals.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Mijn Verhuur
                        </a>
                        <a href="{{ route('dashboard.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Mijn Aankopen
                        </a>
                        
                        <div class="border-t border-gray-100 my-1"></div>

                        @if(Auth::user()->isBusinessAdvertiser())
                            <a href="{{ route('dashboard.company.settings.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Bedrijfsinstellingen
                            </a>
                        @endif
                        
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profielinstellingen
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                Uitloggen
                            </button>
                        </form>
                    </div>
                </div>

                {{-- PLACE AD BUTTON --}}
                <a href="{{ route('dashboard.advertisements.create') }}" class="ml-4 hidden md:inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                    Plaats advertentie
                </a>

            @else
                {{-- GUEST --}}
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Inloggen</a>
                <a href="{{ route('register') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Registreren</a>
            @endauth

        </div>
    </div>
</nav>
