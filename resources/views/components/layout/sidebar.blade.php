<aside class="w-64 bg-white border-r min-h-screen">
    <div class="p-4 font-bold text-lg border-b">
        <a href="{{ route('dashboard.index') }}">MarketMashup</a>
    </div>
    
    <nav class="mt-4 space-y-1">
        <a href="{{ route('dashboard.index') }}" class="block p-3 hover:bg-gray-50 {{ request()->routeIs('dashboard.index') ? 'bg-gray-50 font-semibold' : '' }}">
            Overview
        </a>

        {{-- <a href="{{ route('favorites.index') }}" class="block p-3 hover:bg-gray-50 {{ request()->routeIs('favorites.*') ? 'bg-gray-50 font-semibold' : '' }}">
            Favorites
        </a> --}}

        {{-- Advertiser / Seller Links --}}
        {{-- Assuming all logged in users can create ads for now, or check role if implemented --}}
        <div class="px-3 pt-4 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Selling</div>
        <a href="{{ route('dashboard.advertisements.index') }}" class="block p-3 hover:bg-gray-50 {{ request()->routeIs('dashboard.advertisements.*') ? 'bg-gray-50 font-semibold' : '' }}">
            My Ads
        </a>
        
        {{-- 
        <a href="{{ route('rentals.calendar') }}" class="block p-3 hover:bg-gray-50">Rental Calendar</a>
        --}}

        {{-- Business Specific Links --}}
        {{-- 
        @if(auth()->user()->role === 'business_advertiser')
            <div class="px-3 pt-4 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">BUSINESS</div>
            <a href="{{ route('company.contract') }}" class="block p-3 hover:bg-gray-50">Download Contract</a>
            <a href="{{ route('company.design') }}" class="block p-3 hover:bg-gray-50">Customize Page</a>
        @endif 
        --}}
    </nav>

    <div class="p-4 border-t mt-auto">
         <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Log Out</button>
        </form>
    </div>
</aside>
