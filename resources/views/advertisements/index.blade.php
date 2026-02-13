<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Advertisements') }}
            </h2>
            <a href="{{ route('advertisements.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                New Advertisement
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('advertisements.index') }}" class="flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search advertisements..." class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                    
                    <select name="sort" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Sort By</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Newest First</option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Oldest First</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">Filter</button>
                    @if(request()->hasAny(['search', 'sort']))
                        <a href="{{ route('advertisements.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Clear</a>
                    @endif
                </form>
            </div>

            <!-- List -->
            @if(session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($advertisements as $ad)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-full">
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded uppercase">{{ $ad->type }}</span>
                                <span class="text-gray-500 text-sm">{{ $ad->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $ad->title }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $ad->description }}</p>
                            <p class="text-xl font-bold text-gray-900">€{{ number_format($ad->price, 2, ',', '.') }}</p>
                            <p class="text-sm text-gray-500 mt-2">By: {{ $ad->user->name }}</p>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                            <a href="{{ route('advertisements.show', $ad) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View Details</a>
                            
                            @if(auth()->id() === $ad->user_id)
                                <div class="flex space-x-2">
                                    <a href="{{ route('advertisements.edit', $ad) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                    <form action="{{ route('advertisements.destroy', $ad) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        No advertisements found.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $advertisements->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
