<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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
            </div>

            <!-- Smart Dashboard: Activity Overview -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- My Rentals (Outgoing & Incoming) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-bold text-lg mb-4">Rental Activity</h3>
                        
                        @if($myRentals->isEmpty() && $incomingRentals->isEmpty())
                            <p class="text-gray-500 italic">No rental history yet.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        {{-- Outgoing Rentals (I am renting) --}}
                                        @foreach($myRentals as $rental)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $rental->advertisement->title }}
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
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $rental->advertisement->title }}
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

                <!-- My Bids -->
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
