<x-market-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Marketplace</h1>

        <form action="{{ route('market.index') }}" method="GET" class="mb-8 flex gap-4">
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
            
            @if(request()->hasAny(['search', 'sort', 'type']))
                <a href="{{ route('market.index', ['clear' => 1]) }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition flex items-center">
                   Wis Filters
                </a>
            @endif
        </form>

        {{-- Advertisements Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($advertisements as $ad)
                <div class="border rounded p-4 shadow hover:shadow-lg transition bg-white">
                    @if($ad->image_path)
                        <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" class="w-full h-48 object-cover mb-4 rounded">
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded flex items-center justify-center text-gray-500 mb-4">
                            Geen afbeelding
                        </div>
                    @endif
                    <a href="{{ route('market.show', $ad) }}">
                        <h3 class="font-bold text-lg text-blue-600 hover:underline">{{ $ad->title }}</h3>
                    </a>
                    <p class="text-gray-600 truncate">{{ $ad->description }}</p>
                    <div class="flex justify-between items-center mt-4">
                        <p class="font-bold text-lg">€ {{ number_format($ad->price, 2) }}</p>
                        <span class="bg-gray-100 px-2 py-1 rounded text-sm text-gray-600">{{ ucfirst($ad->type) }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $advertisements->links() }}
        </div>
    </div>
</x-market-layout>
