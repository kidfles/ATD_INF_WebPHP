<x-app-layout :hideSidebar="true">

{{--
    Pagina: Homepagina (Landing)
    Doel: De eerste indruk van de applicatie.
    Bevat:
    - Hero sectie met call-to-actions (Aanmelden/Marktplaats).
    - Uitgelichte advertenties (Featured Ads).
--}}

    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden rounded-[2.5rem] shadow-soft-xl border border-slate-100 -mt-2">
        {{-- Background Effects --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-emerald-100/60 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-teal-50/60 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                {{-- Text Content --}}
                <div class="px-6 py-12 sm:py-16 md:py-20 lg:py-24 xl:py-28 lg:pl-12 xl:pl-16">
                    <div class="relative">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 mb-6 animate-pop-in">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-xs font-bold text-emerald-600 tracking-wide uppercase">{{ __('Now Live') }}</span>
                        </div>
                        
                        <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl mb-6 leading-tight">
                            <span class="block">{{ __('Premium Gear for') }}</span>
                            <span class="block bg-gradient-to-r from-emerald-500 to-teal-500 bg-clip-text text-transparent transform origin-left hover:scale-[1.02] transition-transform duration-500">{{ __('Creative Pros') }}</span>
                        </h1>
                        
                        <p class="mt-4 text-base text-slate-500 sm:text-lg md:text-xl max-w-lg mb-8 leading-relaxed">
                            {{ __('The ultimate marketplace to buy, sell, rent, or auction high-end equipment. Join a community that values quality as much as you do.') }}
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white transition-all duration-200 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full hover:from-emerald-600 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5">
                                {{ __('Get Started') }}
                                <svg class="ml-2 -mr-1 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                            <a href="{{ route('market.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-emerald-700 transition-all duration-200 bg-emerald-50 border border-emerald-200 rounded-full hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                {{ __('Browse Market') }}
                            </a>
                        </div>

                        {{-- Trust Indicators --}}
                        <div class="mt-10 flex items-center gap-6 text-sm text-slate-500 font-medium">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Verified Sellers</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <span>Secure Payments</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hero Image area --}}
                <div class="relative lg:h-full min-h-[400px] lg:min-h-[600px] w-full group overflow-hidden">
                   {{-- Gradient overlay for text readability if needed, but keeping it clean for light mode --}}
                   <div class="absolute inset-0 bg-gradient-to-t from-white/20 via-transparent to-transparent z-10 lg:bg-gradient-to-l"></div>
                   <img class="absolute inset-0 w-full h-full object-cover transform transition-transform duration-1000 group-hover:scale-105" src="{{ asset('images/welcomehero.jpg') }}" alt="Professional Camera Gear">
                   
                   {{-- Glass Card Overlay (Light) --}}
                   <div class="absolute bottom-8 left-8 right-8 z-20 hidden sm:block">
                       <div class="bg-white/80 backdrop-blur-md border border-white/50 p-4 rounded-2xl flex items-center justify-between shadow-soft-xl">
                           <div class="flex items-center gap-4">
                               <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-emerald-100 to-teal-50 flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                                   <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                               </div>
                               <div>
                                   <p class="text-slate-800 font-bold text-sm">Sony Alpha a7 IV</p>
                                   <p class="text-emerald-600 text-xs font-bold">Just Listed • €2,399</p>
                               </div>
                           </div>
                           <a href="{{ route('market.index') }}" class="h-10 w-10 rounded-full bg-slate-100 hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 flex items-center justify-center transition-all">
                               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                           </a>
                       </div>
                   </div>
                </div>
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
