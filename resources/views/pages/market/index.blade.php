<x-app-layout :hideSidebar="true">
    {{--
        Pagina: Marktplaats Overzicht
        Doel: De openbare catalogus van advertenties.
        Bevat: Zoekfilters, categorieën en rasterweergave van advertenties.
    --}}
    <div class="py-2">
        <h1 class="text-3xl font-extrabold text-slate-800 mb-6">{{ __('Marketplace') }}</h1>

        {{-- Zoek- en Filterformulier --}}
        <form action="{{ route('market.index') }}" method="GET" class="mb-8 flex flex-wrap gap-4 items-center bg-white p-5 rounded-2xl shadow-soft border border-slate-100">
            
            {{-- Onzichtbare input om zoekterm uit de globale header te behouden bij filteren --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
                <div class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full text-sm font-bold flex items-center gap-2">
                    {{ __('Search query') }}: "{{ request('search') }}"
                    <a href="{{ route('market.index', request()->except('search')) }}" class="text-emerald-400 hover:text-emerald-700 font-bold ml-1">&times;</a>
                </div>
            @endif

            {{-- Filter op Advertentie Type (Verkoop, Huur, Veiling) --}}
            <select name="type" class="bg-slate-50 border-transparent rounded-xl text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all" onchange="this.form.submit()">
                <option value="">{{ __('All types') }}</option>
                <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>{{ __('Sale') }}</option>
                <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>{{ __('Rental') }}</option>
                <option value="auction" {{ request('type') == 'auction' ? 'selected' : '' }}>{{ __('Auction') }}</option>
            </select>

            {{-- Sorteringsopties --}}
            <select name="sort" class="bg-slate-50 border-transparent rounded-xl text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all" onchange="this.form.submit()">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest first') }}</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('Oldest first') }}</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
            </select>

            {{-- Filterknop als fallback voor browsers zonder JavaScript --}}
            <noscript>
                <button type="submit" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:-translate-y-0.5 transition-all">{{ __('Filter') }}</button>
            </noscript>
            
            {{-- Knop om alle actieve filters in één keer te wissen --}}
            @if(request()->hasAny(['search', 'sort', 'type']))
                <a href="{{ route('market.index', ['clear' => 1]) }}" 
                   class="ml-auto text-sm text-slate-400 hover:text-emerald-500 font-medium transition-colors">
                   {{ __('Clear all filters') }}
                </a>
            @endif
        </form>

        {{-- Overzicht van Advertenties (Grid) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($advertisements as $ad)
                <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-soft-lg">
                    {{-- Afbeelding van de advertentie of een placeholder --}}
                    <div class="w-full overflow-hidden bg-slate-50 relative group" style="height: 360px;">
                        @if($ad->image_path)
                            <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <img src="{{ asset('images/placeholder.svg') }}" alt="{{ __('No image available') }}" class="w-full h-full object-cover text-slate-400">
                        @endif
                    </div>
                    <div class="p-5">
                        {{-- Titel en omschrijving --}}
                        <a href="{{ route('market.show', $ad) }}">
                            <h3 class="font-extrabold text-lg text-emerald-600 hover:text-emerald-700 transition-colors">{{ $ad->title }}</h3>
                        </a>
                        <p class="text-slate-500 truncate mt-1">{{ $ad->description }}</p>
                        {{-- Prijs en type label --}}
                        <div class="flex justify-between items-center mt-4">
                            <p class="font-extrabold text-lg text-slate-800">€ {{ number_format($ad->price, 2) }}</p>
                            <span class="bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-full text-xs font-bold text-slate-500">{{ __(ucfirst($ad->type)) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginering van de resultaten --}}
        <div class="mt-6">
            {{ $advertisements->links() }}
        </div>
    </div>
</x-app-layout>
