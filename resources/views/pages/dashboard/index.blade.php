<x-app-layout>
    {{-- No header slot — dashboard uses its own hero --}}

    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            {{-- ═══════════════════════════════════════════════════════════
                 HERO SECTION — Welcome + Key Stats (Animate FIRST) 
                 ═══════════════════════════════════════════════════════════ --}}
            <div class="mb-8 opacity-0 animate-fade-in-up">
                <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-8 relative overflow-hidden">
                    {{-- Decorative Gradient Glow --}}
                    <div class="absolute -top-20 -right-20 w-60 h-60 bg-gradient-to-br from-violet-500/20 to-cyan-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-16 -left-16 w-40 h-40 bg-gradient-to-tr from-cyan-500/10 to-violet-500/5 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10">
                        <h1 class="text-3xl font-bold text-white mb-1">
                            {{ __('Welcome') }}, <span class="bg-gradient-to-r from-violet-400 to-cyan-400 bg-clip-text text-transparent">{{ Auth::user()->name }}</span>
                        </h1>
                        <p class="text-gray-400 text-sm mb-6">{{ __('Dashboard Overview') }}</p>

                        {{-- Stats Row --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white/5 border border-white/5 rounded-xl p-4 text-center hover:border-violet-500/30 transition-colors">
                                <div class="text-2xl font-bold text-white">{{ $myAds->count() }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ __('My Advertisements') }}</div>
                            </div>
                            <div class="bg-white/5 border border-white/5 rounded-xl p-4 text-center hover:border-cyan-500/30 transition-colors">
                                <div class="text-2xl font-bold text-white">{{ $myRentals->count() }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ __('My Rentals') }}</div>
                            </div>
                            <div class="bg-white/5 border border-white/5 rounded-xl p-4 text-center hover:border-violet-500/30 transition-colors">
                                <div class="text-2xl font-bold text-white">{{ $myBids->count() }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ __('Active Bids') }}</div>
                            </div>
                            <div class="bg-white/5 border border-white/5 rounded-xl p-4 text-center hover:border-cyan-500/30 transition-colors">
                                <div class="text-2xl font-bold text-white">{{ $incomingRentals->count() }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ __('Rental Activities') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 QUICK ACTIONS — Bento Grid Row (Animate SECOND)
                 ═══════════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

                {{-- Manage Ads (Advertiser only) --}}
                @if(Auth::user()->role !== 'user')
                <div class="opacity-0 animate-fade-in-short" style="animation-delay: 100ms;">
                    <a href="{{ route('dashboard.advertisements.index') }}" 
                       class="group block h-full flex flex-col bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 hover:-translate-y-1 hover:shadow-cyan-500/20 hover:border-violet-500/30 transition-all duration-300">
                        <div class="w-12 h-12 bg-violet-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-violet-500/20 transition-colors">
                            <svg class="w-6 h-6 text-violet-400 drop-shadow-[0_0_5px_rgba(139,92,246,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">{{ __('My Advertisements') }}</h3>
                        <p class="text-sm text-gray-400 mb-3 flex-grow">{{ __('View and edit your current listings.') }}</p>
                        <span class="text-violet-400 text-sm font-semibold group-hover:text-violet-300 transition-colors mt-auto">{{ __('Manage') }} &rarr;</span>
                    </a>
                </div>
                @endif

                {{-- Place New Ad (Advertiser only) --}}
                @if(Auth::user()->role !== 'user')
                <div class="opacity-0 animate-fade-in-short" style="animation-delay: 200ms;">
                    <a href="{{ route('dashboard.advertisements.create') }}" 
                       class="group block h-full flex flex-col bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 hover:-translate-y-1 hover:shadow-cyan-500/20 hover:border-emerald-500/30 transition-all duration-300">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-500/20 transition-colors">
                            <svg class="w-6 h-6 text-emerald-400 drop-shadow-[0_0_5px_rgba(16,185,129,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">{{ __('Place Advertisement') }}</h3>
                        <p class="text-sm text-gray-400 mb-3 flex-grow">{{ __('Sell, rent or auction something new.') }}</p>
                        <span class="text-emerald-400 text-sm font-semibold group-hover:text-emerald-300 transition-colors mt-auto">{{ __('Start Now') }} &rarr;</span>
                    </a>
                </div>
                @endif

                {{-- Marketplace Explorer --}}
                <div class="opacity-0 animate-fade-in-short" style="animation-delay: 300ms;">
                    <a href="{{ route('market.index') }}" 
                       class="group block h-full flex flex-col bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 hover:-translate-y-1 hover:shadow-cyan-500/20 hover:border-cyan-500/30 transition-all duration-300">
                        <div class="w-12 h-12 bg-cyan-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-cyan-500/20 transition-colors">
                            <svg class="w-6 h-6 text-cyan-400 drop-shadow-[0_0_5px_rgba(6,182,212,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">{{ __('The Market') }}</h3>
                        <p class="text-sm text-gray-400 mb-3 flex-grow">{{ __('Browse what others are offering.') }}</p>
                        <span class="text-cyan-400 text-sm font-semibold group-hover:text-cyan-300 transition-colors mt-auto">{{ __('Explore') }} &rarr;</span>
                    </a>
                </div>

                {{-- Agenda (Advertiser only) --}}
                @if(in_array(Auth::user()->role, ['business_ad', 'private_ad']))
                <div class="opacity-0 animate-fade-in-short" style="animation-delay: 400ms;">
                    <a href="{{ route('dashboard.agenda.index') }}" 
                       class="group block h-full flex flex-col bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 hover:-translate-y-1 hover:shadow-cyan-500/20 hover:border-amber-500/30 transition-all duration-300">
                        <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-500/20 transition-colors">
                            <svg class="w-6 h-6 text-amber-400 drop-shadow-[0_0_5px_rgba(245,158,11,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">{{ __('Agenda') }}</h3>
                        <p class="text-sm text-gray-400 mb-3 flex-grow">{{ __('View your rental schedule and expiry dates.') }}</p>
                        <span class="text-amber-400 text-sm font-semibold group-hover:text-amber-300 transition-colors mt-auto">{{ __('Open Calendar') }} &rarr;</span>
                    </a>
                </div>
                @endif

                {{-- Company Settings (Business only) --}}
                @if(Auth::user()->role === 'business_ad')
                <div class="opacity-0 animate-fade-in-short" style="animation-delay: 500ms;">
                    <a href="{{ route('dashboard.company.settings.edit') }}" 
                       class="group block h-full flex flex-col bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 hover:-translate-y-1 hover:shadow-cyan-500/20 hover:border-violet-500/30 transition-all duration-300 relative">
                        <div class="w-12 h-12 bg-violet-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-violet-500/20 transition-colors">
                            <svg class="w-6 h-6 text-violet-400 drop-shadow-[0_0_5px_rgba(139,92,246,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">{{ __('Settings') }}</h3>
                        <p class="text-sm text-gray-400 mb-3 flex-grow">{{ __('Manage your company profile and branding.') }}</p>
                        
                        {{-- Contract Warning --}}
                        @if(Auth::user()->companyProfile && Auth::user()->companyProfile->contract_status !== 'approved')
                            <div class="bg-amber-500/10 border border-amber-500/20 rounded-lg p-2.5 mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <p class="text-xs text-amber-300"><strong>{{ __('Contract Required') }}:</strong> {{ __('Upload your signed contract for full access.') }}</p>
                                </div>
                            </div>
                        @endif

                        <span class="text-violet-400 text-sm font-semibold group-hover:text-violet-300 transition-colors mt-auto">{{ __('Edit') }} &rarr;</span>
                    </a>
                </div>
                @endif
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 CALENDAR + RENTAL TABLE (Animate THIRD)
                 ═══════════════════════════════════════════════════════════ --}}
            @if(in_array(Auth::user()->role, ['business_ad', 'private_ad']))
            <div class="opacity-0 animate-fade-in-short mb-6" style="animation-delay: 500ms;">
                <x-agenda-calendar />
            </div>
            @endif
            
            <div class="opacity-0 animate-fade-in-short mb-6" style="animation-delay: 600ms;">
                <x-rental-activities-table :myRentals="$myRentals" :incomingRentals="$incomingRentals" />
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 DATA PANELS — Ads List + Bids List (Animate LAST)
                 ═══════════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                {{-- My Advertisements --}}
                <div class="opacity-0 animate-fade-in-short" style="animation-delay: 700ms;">
                    <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-5">
                                <h3 class="font-bold text-lg text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-violet-400 drop-shadow-[0_0_5px_rgba(139,92,246,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    {{ __('My Advertisements') }}
                                </h3>
                                @if(Auth::user()->role !== 'user')
                                <a href="{{ route('dashboard.advertisements.create') }}" class="text-sm text-violet-400 hover:text-violet-300 font-medium transition-colors">{{ __('New +') }}</a>
                                @endif
                            </div>

                            @if($myAds->isEmpty())
                                <p class="text-gray-500 italic">{{ __('You haven\'t placed any advertisements yet.') }}</p>
                            @else
                                <ul class="divide-y divide-white/5 -mx-6">
                                    @foreach($myAds as $ad)
                                        <li class="px-6 py-3.5 flex justify-between items-center hover:bg-white/5 transition cursor-pointer group" onclick="window.location='{{ route('market.show', $ad) }}'">
                                            <div>
                                                <p class="text-sm font-medium text-violet-400 group-hover:text-violet-300 transition-colors">{{ $ad->title }}</p>
                                                <p class="text-xs text-gray-500">{{ ucfirst($ad->type) }} • €{{ number_format($ad->price, 2) }}</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('dashboard.advertisements.edit', $ad) }}" class="text-xs text-gray-500 hover:text-white transition-colors px-2 py-1 rounded-lg hover:bg-white/5" onclick="event.stopPropagation()">{{ __('Edit') }}</a>
                                                <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-4 pt-4 border-t border-white/5">
                                    <a href="{{ route('dashboard.advertisements.index') }}" class="text-sm font-medium text-violet-400 hover:text-violet-300 transition-colors">{{ __('All advertisements') }} &rarr;</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Active Bids --}}
                <div class="opacity-0 animate-fade-in-short" style="animation-delay: 800ms;">
                    <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="font-bold text-lg text-white mb-5 flex items-center gap-2">
                                <svg class="w-5 h-5 text-cyan-400 drop-shadow-[0_0_5px_rgba(6,182,212,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                {{ __('Active Bids') }}
                            </h3>

                            @if($myBids->isEmpty())
                                <p class="text-gray-500 italic">{{ __('No active bids at the moment.') }}</p>
                            @else
                                <ul class="divide-y divide-white/5 -mx-6">
                                    @foreach($myBids as $bid)
                                        <li class="px-6 py-3.5 flex justify-between items-center hover:bg-white/5 transition">
                                            <div>
                                                <p class="text-sm font-medium text-white">{{ $bid->advertisement->title }}</p>
                                                <p class="text-xs text-gray-500">{{ __('Your bid') }}: <span class="text-cyan-400 font-semibold">€{{ number_format($bid->amount, 2) }}</span></p>
                                            </div>
                                            <div class="text-xs text-gray-600">
                                                {{ $bid->created_at->diffForHumans() }}
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
