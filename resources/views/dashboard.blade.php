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
                        <a href="{{ route('advertisements.index') }}" class="text-blue-600 font-semibold hover:text-blue-800">Manage Ads &rarr;</a>
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
                        <a href="{{ route('advertisements.create') }}" class="text-green-600 font-semibold hover:text-green-800">Create Now &rarr;</a>
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
                        <a href="{{ route('advertisements.index') }}" class="text-purple-600 font-semibold hover:text-purple-800">Start Browsing &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Your Role Status</h3>
                    <p>You are currently logged in as: <span class="font-bold uppercase text-indigo-600">{{ Auth::user()->role }}</span></p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
