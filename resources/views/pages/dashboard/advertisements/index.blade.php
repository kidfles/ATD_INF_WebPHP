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
                    <form action="{{ route('dashboard.advertisements.index') }}" method="GET" class="mb-6 flex gap-4">
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
                        <a href="{{ route('dashboard.advertisements.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Nieuwe Advertentie</a>
                    </div>

                    {{-- Advertisements Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($advertisements as $ad)
                            <div class="border rounded p-4 shadow hover:shadow-lg transition bg-white">
                                <a href="{{ route('dashboard.advertisements.show', $ad) }}">
                                    <h3 class="font-bold text-lg text-blue-600 truncate">{{ $ad->title }}</h3>
                                </a>
                                <p class="text-gray-600 truncate mt-2">{{ $ad->description }}</p>
                                <p class="font-bold mt-2">€ {{ number_format($ad->price, 2) }}</p>
                                <p class="text-sm text-gray-500 mb-4">{{ ucfirst($ad->type) }}</p>
                                
                                <div class="flex gap-2 pt-4 border-t">
                                    <a href="{{ route('dashboard.advertisements.edit', $ad) }}" class="flex-1 text-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded text-sm">Bewerken</a>
                                    
                                    <form action="{{ route('dashboard.advertisements.destroy', $ad) }}" method="POST" onsubmit="return confirm('Verwijderen?');" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">Verwijderen</button>
                                    </form>
                                </div>
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
