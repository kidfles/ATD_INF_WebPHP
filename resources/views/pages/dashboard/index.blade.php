<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Manage My Ads -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">My Advertisements</h3>
                        <p class="text-sm text-gray-500 mb-4">View and edit your current listings.</p>
                        <a href="{{ route('dashboard.advertisements.index') }}" class="text-blue-600 font-semibold hover:text-blue-800">Manage Ads &rarr;</a>
                    </div>
                </div>

                <!-- Create New Ad -->
                @if(Auth::user()->role !== 'user')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Create New Ad</h3>
                        <p class="text-sm text-gray-500 mb-4">Sell, rent, or auction a new item.</p>
                        <a href="{{ route('dashboard.advertisements.create') }}" class="text-green-600 font-semibold hover:text-green-800">Create Now &rarr;</a>
                    </div>
                </div>
                @endif

                <!-- Browse Market -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-purple-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Internal Market</h3>
                        <p class="text-sm text-gray-500 mb-4">Browse items from other colleagues.</p>
                        <a href="{{ route('market.index') }}" class="text-purple-600 font-semibold hover:text-purple-800">Start Browsing &rarr;</a>
                    </div>
                </div>

                <!-- Company Settings (Business Only) -->
                @if(Auth::user()->role === 'business_ad')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="bg-indigo-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Company Settings</h3>
                        <p class="text-sm text-gray-500 mb-4">Manage your business profile and branding.</p>
                        
                        @if(Auth::user()->companyProfile && Auth::user()->companyProfile->contract_status !== 'approved')
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-yellow-700">
                                            <strong>Contract vereist:</strong> Upload uw getekende contract om volledige toegang te krijgen.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <a href="{{ route('dashboard.company.settings.edit') }}" class="text-indigo-600 font-semibold hover:text-indigo-800">Manage Settings &rarr;</a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Smart Dashboard: Activity Overview -->
            <!-- Smart Dashboard: Activity Overview -->
            
            <!-- 1. Rental Activity (Full Width) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-4">Rental Activity</h3>
                    
                    @if($myRentals->isEmpty() && $incomingRentals->isEmpty())
                        <p class="text-gray-500 italic">No rental history yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-full">Item</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-nowrap">Dates</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-nowrap">Type</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider text-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    {{-- Outgoing Rentals (I am renting) --}}
                                    @foreach($myRentals as $rental)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-full">
                                                <a href="{{ route('market.show', $rental->advertisement) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                                    {{ $rental->advertisement->title }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Renting</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form method="POST" action="{{ route('rentals.return', $rental) }}">
                                                    @csrf
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900">Return</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- Incoming Rentals (Someone is renting from me) --}}
                                    @foreach($incomingRentals as $rental)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-full">
                                                <a href="{{ route('market.show', $rental->advertisement) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                                    {{ $rental->advertisement->title }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Incoming</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <span class="text-gray-400">View</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Grid for My Ads & Bids -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- My Advertisements -->
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-lg">My Advertisements</h3>
                            @if(Auth::user()->role !== 'user')
                            <a href="{{ route('dashboard.advertisements.create') }}" class="text-sm text-blue-600 hover:underline">New Ad +</a>
                            @endif
                        </div>
                        
                        @if($myAds->isEmpty())
                            <p class="text-gray-500 italic">You haven't posted any ads yet.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($myAds as $ad)
                                    <li class="py-4 flex justify-between items-center hover:bg-gray-50 transition cursor-pointer" onclick="window.location='{{ route('market.show', $ad) }}'">
                                        <div>
                                            <p class="text-sm font-medium text-indigo-600 hover:underline">{{ $ad->title }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($ad->type) }} • €{{ number_format($ad->price, 2) }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('dashboard.advertisements.edit', $ad) }}" class="text-sm text-gray-600 hover:text-gray-900" onclick="event.stopPropagation()">Edit</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-4 pt-4 border-t">
                                <a href="{{ route('dashboard.advertisements.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all ads &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- My Active Bids -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-bold text-lg mb-4">My Active Bids</h3>
                        
                        @if($myBids->isEmpty())
                            <p class="text-gray-500 italic">No active bids.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($myBids as $bid)
                                    <li class="py-4 flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $bid->advertisement->title }}</p>
                                            <p class="text-sm text-gray-500">Bid Amount: €{{ number_format($bid->amount, 2) }}</p>
                                        </div>
                                        <div class="text-sm text-gray-500">
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
</x-app-layout>
