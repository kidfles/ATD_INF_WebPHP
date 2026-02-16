<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Dashboard Widgets --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Beheer Eigen Advertenties (Alleen voor adverteerders) -->
                @if(Auth::user()->role !== 'user')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('My Advertisements') }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ __('View and edit your current listings.') }}</p>
                        <a href="{{ route('dashboard.advertisements.index') }}" class="text-blue-600 font-semibold hover:text-blue-800">{{ __('Manage') }} &rarr;</a>
                    </div>
                </div>
                @endif

                <!-- Nieuwe Advertentie Aanmaken -->
                @if(Auth::user()->role !== 'user')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Place Advertisement') }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ __('Sell, rent or auction something new.') }}</p>
                        <a href="{{ route('dashboard.advertisements.create') }}" class="text-green-600 font-semibold hover:text-green-800">{{ __('Start Now') }} &rarr;</a>
                    </div>
                </div>
                @endif

                <!-- Marktplaats Verkenner -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-purple-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('The Market') }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ __('Browse what others are offering.') }}</p>
                        <a href="{{ route('market.index') }}" class="text-purple-600 font-semibold hover:text-purple-800">{{ __('Explore') }} &rarr;</a>
                    </div>
                </div>

                <!-- Agenda (Alleen voor adverteerders) -->
                @if(in_array(Auth::user()->role, ['business_ad', 'private_ad']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="bg-amber-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Agenda') }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ __('View your rental schedule and expiry dates.') }}</p>
                        <a href="{{ route('dashboard.agenda.index') }}" class="text-amber-600 font-semibold hover:text-amber-800">{{ __('Open Calendar') }} &rarr;</a>
                    </div>
                </div>
                @endif

                <!-- Bedrijfsinstellingen (Alleen voor Zakelijke Adverteerders) -->
                @if(Auth::user()->role === 'business_ad')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="bg-indigo-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Settings') }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ __('Manage your company profile and branding.') }}</p>
                        
                        {{-- Waarschuwing indien het contract nog niet geüpload/goedgekeurd is --}}
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
                                            <strong>{{ __('Contract Required') }}:</strong> {{ __('Upload your signed contract for full access.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <a href="{{ route('dashboard.company.settings.edit') }}" class="text-indigo-600 font-semibold hover:text-indigo-800">{{ __('Edit') }} &rarr;</a>
                    </div>
                </div>
                @endif
            </div>

            {{-- Agenda & Huur Activiteiten --}}
            @if(in_array(Auth::user()->role, ['business_ad', 'private_ad']))
            <x-agenda-calendar />
            @endif
            
            <x-rental-activities-table :myRentals="$myRentals" :incomingRentals="$incomingRentals" />
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Mijn Advertenties -->
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-lg">{{ __('My Advertisements') }}</h3>
                            @if(Auth::user()->role !== 'user')
                            <a href="{{ route('dashboard.advertisements.create') }}" class="text-sm text-blue-600 hover:underline">{{ __('New +') }}</a>
                            @endif
                        </div>
                        
                        @if($myAds->isEmpty())
                            <p class="text-gray-500 italic">{{ __('You haven\'t placed any advertisements yet.') }}</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($myAds as $ad)
                                    <li class="py-4 flex justify-between items-center hover:bg-gray-50 transition cursor-pointer" onclick="window.location='{{ route('market.show', $ad) }}'">
                                        <div>
                                            <p class="text-sm font-medium text-indigo-600 hover:underline">{{ $ad->title }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($ad->type) }} • €{{ number_format($ad->price, 2) }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('dashboard.advertisements.edit', $ad) }}" class="text-sm text-gray-600 hover:text-gray-900" onclick="event.stopPropagation()">{{ __('Edit') }}</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-4 pt-4 border-t">
                                <a href="{{ route('dashboard.advertisements.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">{{ __('All advertisements') }} &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Mijn Actieve Biedingen -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-bold text-lg mb-4">{{ __('Active Bids') }}</h3>
                        
                        @if($myBids->isEmpty())
                            <p class="text-gray-500 italic">{{ __('No active bids at the moment.') }}</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($myBids as $bid)
                                    <li class="py-4 flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $bid->advertisement->title }}</p>
                                            <p class="text-sm text-gray-500">{{ __('Your bid') }}: €{{ number_format($bid->amount, 2) }}</p>
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
