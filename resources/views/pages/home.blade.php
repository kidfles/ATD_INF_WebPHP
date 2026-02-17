<x-app-layout :hideSidebar="true">

{{--
    Pagina: Homepagina (Landing)
    Doel: De eerste indruk van de applicatie.
    Bevat:
    - Hero sectie met call-to-actions (Aanmelden/Marktplaats).
    - Uitgelichte advertenties (Featured Ads).
--}}

    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden rounded-[2rem] shadow-soft border border-slate-100 -mt-2">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                {{-- Decorative Gradient Blobs --}}
                <div class="absolute -top-20 -right-20 w-60 h-60 bg-gradient-to-br from-emerald-100 to-teal-50 rounded-full blur-3xl"></div>
                
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-slate-800 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">{{ __('Premium Marketplace for') }}</span>
                            <span class="block bg-gradient-to-r from-emerald-500 to-teal-500 bg-clip-text text-transparent xl:inline">{{ __('Professional Gear') }}</span>
                        </h1>
                        <p class="mt-3 text-base text-slate-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            {{ __('Buy, sell, rent, or auction high-quality equipment. Join our community of professionals today.') }}
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start gap-4">
                            <div>
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 text-base font-bold rounded-full text-white bg-gradient-to-r from-emerald-400 to-teal-500 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300 md:py-4 md:text-lg md:px-10">
                                    {{ __('Get Started') }}
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0">
                                <a href="{{ route('market.index') }}" class="w-full flex items-center justify-center px-8 py-3 text-base font-bold rounded-full text-emerald-600 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition-all duration-300 md:py-4 md:text-lg md:px-10">
                                    {{ __('Browse Ads') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-slate-50 flex items-center justify-center rounded-r-[2rem]">
             <div class="text-center text-slate-300">
                <svg class="h-64 w-64 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <p>Welcome Image</p>
             </div>
        </div>
    </div>

    <!-- Featured Ads Section -->
    <div class="py-12">
        <div class="text-center">
            <h2 class="text-base text-emerald-500 font-bold tracking-wide uppercase">{{ __('Marketplace') }}</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-slate-800 sm:text-4xl">{{ __('Latest Advertisements') }}</p>
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredAds as $ad)
                    <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-soft-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold uppercase rounded-full border border-emerald-200">
                                    {{ $ad->type }}
                                </span>
                                <span class="ml-auto text-sm text-slate-400">{{ $ad->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="mt-4 text-xl font-extrabold text-slate-800">
                                <a href="{{ route('market.show', $ad) }}" class="hover:text-emerald-600 transition-colors">{{ $ad->title }}</a>
                            </h3>
                            <p class="mt-2 text-base text-slate-500 line-clamp-3">
                                {{ $ad->description }}
                            </p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-extrabold text-slate-800">€{{ number_format($ad->price, 2) }}</span>
                                <a href="{{ route('market.show', $ad) }}" class="text-emerald-500 hover:text-emerald-600 font-bold text-sm transition-colors">View &rarr;</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-10 text-center">
                <a href="{{ route('market.index') }}" class="text-base font-bold text-emerald-500 hover:text-emerald-600 transition-colors">
                    {{ __('View all advertisements') }} <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>

</x-app-layout>
