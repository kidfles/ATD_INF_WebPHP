<x-market-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">{{ __('Marketplace') }}</h1>

        {{-- Zoek- en Filterformulier --}}
        <form action="{{ route('market.index') }}" method="GET" class="mb-8 flex flex-wrap gap-4 items-center bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            
            {{-- Onzichtbare input om zoekterm uit de globale header te behouden bij filteren --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
                <div class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium flex items-center gap-2">
                    {{ __('Search query') }}: "{{ request('search') }}"
                    <a href="{{ route('market.index', request()->except('search')) }}" class="text-indigo-500 hover:text-indigo-900 font-bold ml-1">&times;</a>
                </div>
            @endif

            {{-- Filter op Advertentie Type (Verkoop, Huur, Veiling) --}}
            <select name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onchange="this.form.submit()">
                <option value="">{{ __('All types') }}</option>
                <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>{{ __('Sale') }}</option>
                <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>{{ __('Rental') }}</option>
                <option value="auction" {{ request('type') == 'auction' ? 'selected' : '' }}>{{ __('Auction') }}</option>
            </select>

            {{-- Sorteringsopties --}}
            <select name="sort" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onchange="this.form.submit()">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest first') }}</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
            </select>

            {{-- Filterknop als fallback voor browsers zonder JavaScript --}}
            <noscript>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">{{ __('Filter') }}</button>
            </noscript>
            
            {{-- Knop om alle actieve filters in één keer te wissen --}}
            @if(request()->hasAny(['search', 'sort', 'type']))
                <a href="{{ route('market.index') }}" 
                   class="ml-auto text-sm text-gray-500 hover:text-gray-900 underline">
                   {{ __('Clear all filters') }}
                </a>
            @endif
        </form>

        {{-- Overzicht van Advertenties (Grid) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($advertisements as $ad)
                <div class="border rounded p-4 shadow hover:shadow-lg transition bg-white">
                    {{-- Afbeelding van de advertentie of een placeholder --}}
                    <div class="w-full mb-4 overflow-hidden rounded bg-gray-100 relative group" style="height: 360px;">
                        @if($ad->image_path)
                            <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <img src="{{ asset('images/placeholder.svg') }}" alt="{{ __('No image available') }}" class="w-full h-full object-cover text-gray-400">
                        @endif
                    </div>
                    {{-- Titel en omschrijving --}}
                    <a href="{{ route('market.show', $ad) }}">
                        <h3 class="font-bold text-lg text-blue-600 hover:underline">{{ $ad->title }}</h3>
                    </a>
                    <p class="text-gray-600 truncate">{{ $ad->description }}</p>
                    {{-- Prijs en type label --}}
                    <div class="flex justify-between items-center mt-4">
                        <p class="font-bold text-lg">€ {{ number_format($ad->price, 2) }}</p>
                        <span class="bg-gray-100 px-2 py-1 rounded text-sm text-gray-600">{{ __(ucfirst($ad->type)) }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginering van de resultaten --}}
        <div class="mt-6">
            {{ $advertisements->links() }}
        </div>
    </div>
</x-market-layout>
