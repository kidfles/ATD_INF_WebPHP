<div class="bg-white shadow rounded-lg w-full md:w-64 flex-shrink-0">
    <div class="p-4 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Mijn ATD</h2>
    </div>
    <nav class="flex flex-col p-2 space-y-1">
        
        <a href="{{ route('dashboard.index') }}" 
           class="{{ request()->routeIs('dashboard.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
            <svg class="{{ request()->routeIs('dashboard.index') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Overzicht
        </a>

        <a href="{{ route('dashboard.advertisements.index') }}" 
           class="{{ request()->routeIs('dashboard.advertisements.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
            <svg class="{{ request()->routeIs('dashboard.advertisements.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Mijn Advertenties
        </a>

        <a href="{{ route('dashboard.bids.index') }}" 
           class="{{ request()->routeIs('dashboard.bids.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
            <svg class="{{ request()->routeIs('dashboard.bids.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            Mijn Biedingen
        </a>

        <a href="{{ route('dashboard.rentals.index') }}" 
           class="{{ request()->routeIs('dashboard.rentals.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
             <svg class="{{ request()->routeIs('dashboard.rentals.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Mijn Verhuur
        </a>

        <div class="border-t border-gray-100 my-2"></div>

        <a href="{{ route('profile.edit') }}" 
            class="{{ request()->routeIs('profile.edit') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
            <svg class="{{ request()->routeIs('profile.edit') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Instellingen
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left group flex items-center px-3 py-2 text-sm font-medium rounded-md text-red-600 hover:bg-red-50 hover:text-red-700">
                <svg class="text-red-400 group-hover:text-red-500 flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Uitloggen
            </button>
        </form>

    </nav>
</div>
