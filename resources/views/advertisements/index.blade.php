<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Advertenties') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Filter & Search Form --}}
                    <form action="{{ route('advertisements.index') }}" method="GET" class="mb-6 flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Zoek op titel..." class="border rounded px-4 py-2 w-full">
                        
                        <select name="type" class="border rounded px-4 py-2">
                            <option value="">Alle types</option>
                            <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>Verkoop</option>
                            <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>Verhuur</option>
                            <option value="auction" {{ request('type') == 'auction' ? 'selected' : '' }}>Veiling</option>
                        </select>

                        <select name="sort" class="border rounded px-4 py-2">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Nieuwste</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prijs (Laag-Hoog)</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prijs (Hoog-Laag)</option>
                        </select>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
                    </form>

                    <div class="mb-4">
                        <a href="{{ route('advertisements.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Nieuwe Advertentie</a>
                    </div>

                    {{-- Advertisements Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($advertisements as $ad)
                            <div class="border rounded p-4 shadow">
                                <h3 class="font-bold text-lg">{{ $ad->title }}</h3>
                                <p class="text-gray-600 truncate">{{ $ad->description }}</p>
                                <p class="font-bold mt-2">€ {{ number_format($ad->price, 2) }}</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($ad->type) }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $advertisements->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
