<nav x-data="{ open: false }" class="bg-white border-b border-slate-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard.index') }}" class="font-extrabold text-lg text-slate-800">
                        ATD<span class="text-emerald-500">Webshop</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ml-8 sm:flex">
                    {{-- Public Market Link (Always Visible) --}}
                    <x-nav-link :href="route('market.index')" :active="request()->routeIs('market.index')">
                        {{ __('Market') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('dashboard.index')" :active="request()->routeIs('dashboard.index')">
                            {{ __('Overview') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dashboard.advertisements.index')" :active="request()->routeIs('dashboard.advertisements.*')">
                            {{ __('My Ads') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dashboard.bids.index')" :active="request()->routeIs('dashboard.bids.*')">
                            {{ __('My Bids') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dashboard.rentals.index')" :active="request()->routeIs('dashboard.rentals.*')">
                            {{ __('My Rentals') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dashboard.orders.index')" :active="request()->routeIs('dashboard.orders.*')">
                            {{ __('My Purchases') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-full text-slate-600 bg-white hover:bg-slate-50 hover:text-emerald-600 focus:outline-none transition-all duration-200">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 text-white flex items-center justify-center font-bold text-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>{{ Auth::user()->name }}</div>
                                <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="font-bold text-sm text-slate-500 hover:text-emerald-600 transition-colors">{{ __('Log in') }}</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full font-bold text-sm text-white shadow-sm hover:shadow-emerald-500/30 transition-all duration-200">{{ __('Register') }}</a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 focus:outline-none focus:bg-emerald-50 focus:text-emerald-500 transition duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- Public Market Link --}}
            <x-responsive-nav-link :href="route('market.index')" :active="request()->routeIs('market.index')">
                {{ __('Market') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('dashboard.index')" :active="request()->routeIs('dashboard.index')">
                    {{ __('Overview') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.advertisements.index')" :active="request()->routeIs('dashboard.advertisements.*')">
                    {{ __('My Ads') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.bids.index')" :active="request()->routeIs('dashboard.bids.*')">
                    {{ __('My Bids') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.rentals.index')" :active="request()->routeIs('dashboard.rentals.*')">
                    {{ __('My Rentals') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.orders.index')" :active="request()->routeIs('dashboard.orders.*')">
                    {{ __('My Purchases') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-100">
            @auth
                <div class="px-4">
                    <div class="font-bold text-base text-slate-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
