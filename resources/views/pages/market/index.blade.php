<x-market-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Marketplace</h1>

        <form action="{{ route('market.index') }}" method="GET" class="mb-8 flex flex-wrap gap-4 items-center bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            
            {{-- Hidden Input to Preserve Search Term from Global Header --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
                <div class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium flex items-center gap-2">
                    Zoekopdracht: "{{ request('search') }}"
                    <a href="{{ route('market.index', request()->except('search')) }}" class="text-indigo-500 hover:text-indigo-900 font-bold ml-1">&times;</a>
                </div>
            @endif

            <select name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onchange="this.form.submit()">
                <option value="">Alle types</option>
                <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>Verkoop</option>
                <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>Verhuur</option>
                <option value="auction" {{ request('type') == 'auction' ? 'selected' : '' }}>Veiling</option>
            </select>

            <select name="sort" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onchange="this.form.submit()">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Nieuwste eerst</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prijs: Laag naar Hoog</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prijs: Hoog naar Laag</option>
            </select>

            {{-- "Filter" button is less necessary with auto-submit, but good for accessibility/clarity if js fails --}}
            <noscript>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">Filter</button>
            </noscript>
            
            @if(request()->hasAny(['search', 'sort', 'type']))
                <a href="{{ route('market.index') }}" 
                   class="ml-auto text-sm text-gray-500 hover:text-gray-900 underline">
                   Wis alle filters
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
