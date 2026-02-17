<nav class="bg-white border-b border-slate-100 shadow-sm">
{{--
    Component: Publieke Navigatie
    Doel: Navigatiemenu voor publieke pagina's.
--}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold text-slate-800 hover:text-emerald-600 transition-colors">
                        ATD<span class="text-emerald-500">Webshop</span>
                    </a>
                </div>

                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex">
                    <a href="{{ route('market.index') }}" class="inline-flex items-center px-4 py-2 my-auto rounded-full text-sm font-bold text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 transition-all duration-200">
                        {{ __('Marketplace') }}
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 transition-all duration-200">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">{{ __('Log in') }}</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full text-sm font-bold text-white shadow-sm hover:shadow-emerald-500/30 transition-all duration-200">{{ __('Register') }}</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
