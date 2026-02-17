<div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl w-full md:w-64 flex-shrink-0 overflow-hidden">
    <div class="p-4 border-b border-white/5">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-violet-400 drop-shadow-[0_0_5px_rgba(139,92,246,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            {{ __('My ATD') }}
        </h2>
    </div>
    <nav class="flex flex-col p-2 space-y-0.5">
        
        <a href="{{ route('dashboard.index') }}" 
           class="{{ request()->routeIs('dashboard.index') ? 'bg-violet-500/20 text-violet-300 border-l-2 border-violet-400' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-r-lg transition-all">
            <svg class="{{ request()->routeIs('dashboard.index') ? 'text-violet-400' : 'text-gray-500 group-hover:text-gray-300' }} flex-shrink-0 mr-3 h-4 w-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            {{ __('Overview') }}
        </a>

        <a href="{{ route('dashboard.advertisements.index') }}" 
           class="{{ request()->routeIs('dashboard.advertisements.*') ? 'bg-violet-500/20 text-violet-300 border-l-2 border-violet-400' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-r-lg transition-all">
            <svg class="{{ request()->routeIs('dashboard.advertisements.*') ? 'text-violet-400' : 'text-gray-500 group-hover:text-gray-300' }} flex-shrink-0 mr-3 h-4 w-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            {{ __('My Advertisements') }}
        </a>

        <a href="{{ route('dashboard.bids.index') }}" 
           class="{{ request()->routeIs('dashboard.bids.*') ? 'bg-violet-500/20 text-violet-300 border-l-2 border-violet-400' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-r-lg transition-all">
            <svg class="{{ request()->routeIs('dashboard.bids.*') ? 'text-violet-400' : 'text-gray-500 group-hover:text-gray-300' }} flex-shrink-0 mr-3 h-4 w-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            {{ __('My Bids') }}
        </a>
        
        <a href="{{ route('dashboard.favorites.index') }}" 
           class="{{ request()->routeIs('dashboard.favorites.index') ? 'bg-violet-500/20 text-violet-300 border-l-2 border-violet-400' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-r-lg transition-all">
            <svg class="{{ request()->routeIs('dashboard.favorites.index') ? 'text-violet-400' : 'text-gray-500 group-hover:text-gray-300' }} flex-shrink-0 mr-3 h-4 w-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.486 4.486 0 000 6.364L12 20.364l7.682-7.682a4.486 4.486 0 000-6.364 4.486 4.486 0 00-6.364 0L12 7.636l-1.318-1.318a4.486 4.486 0 00-6.364 0z"></path></svg>
            {{ __('My Favorites') }}
        </a>

        <a href="{{ route('dashboard.rentals.index') }}" 
           class="{{ request()->routeIs('dashboard.rentals.*') ? 'bg-violet-500/20 text-violet-300 border-l-2 border-violet-400' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-r-lg transition-all">
             <svg class="{{ request()->routeIs('dashboard.rentals.*') ? 'text-violet-400' : 'text-gray-500 group-hover:text-gray-300' }} flex-shrink-0 mr-3 h-4 w-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            {{ __('My Rentals') }}
        </a>

        @if(in_array(Auth::user()->role, ['business_ad', 'private_ad']))
        <a href="{{ route('dashboard.agenda.index') }}" 
           class="{{ request()->routeIs('dashboard.agenda.*') ? 'bg-violet-500/20 text-violet-300 border-l-2 border-violet-400' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-r-lg transition-all">
            <svg class="{{ request()->routeIs('dashboard.agenda.*') ? 'text-violet-400' : 'text-gray-500 group-hover:text-gray-300' }} flex-shrink-0 mr-3 h-4 w-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            {{ __('Agenda') }}
        </a>
        @endif

        <a href="{{ route('dashboard.orders.index') }}" 
           class="{{ request()->routeIs('dashboard.orders.*') ? 'bg-violet-500/20 text-violet-300 border-l-2 border-violet-400' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-r-lg transition-all">
             <svg class="{{ request()->routeIs('dashboard.orders.*') ? 'text-violet-400' : 'text-gray-500 group-hover:text-gray-300' }} flex-shrink-0 mr-3 h-4 w-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            {{ __('My Purchases') }}
        </a>

        <div class="border-t border-white/5 my-2"></div>

        @if(Auth::user()->isBusinessAdvertiser())
            <a href="{{ route('dashboard.company.settings.edit') }}" 
               class="{{ request()->routeIs('dashboard.company.settings.*') ? 'bg-violet-500/20 text-violet-300 border-l-2 border-violet-400' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-r-lg transition-all">
                <svg class="{{ request()->routeIs('dashboard.company.settings.*') ? 'text-violet-400' : 'text-gray-500 group-hover:text-gray-300' }} flex-shrink-0 mr-3 h-4 w-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                {{ __('Company Settings') }}
            </a>
        @endif

        <a href="{{ route('profile.edit') }}" 
            class="{{ request()->routeIs('profile.edit') ? 'bg-violet-500/20 text-violet-300 border-l-2 border-violet-400' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-r-lg transition-all">
            <svg class="{{ request()->routeIs('profile.edit') ? 'text-violet-400' : 'text-gray-500 group-hover:text-gray-300' }} flex-shrink-0 mr-3 h-4 w-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            {{ __('Settings') }}
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left group flex items-center px-3 py-2.5 text-sm font-medium rounded-r-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 border-l-2 border-transparent transition-all">
                <svg class="text-red-500/70 group-hover:text-red-400 flex-shrink-0 mr-3 h-4 w-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                {{ __('Logout') }}
            </button>
        </form>

    </nav>
</div>
