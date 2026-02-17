<x-app-layout>
    {{--
        Pagina: Advertenties Overzicht
        Doel: Beheerpagina voor de eigen advertenties van de gebruiker.
        Bevat: Filteropties, lijstweergave en actieknoppen (bewerken/verwijderen).
    --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            {{-- Page Header --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">{{ __('Advertenties') }}</h2>
                @if(Auth::user()->role !== 'user')
                <a href="{{ route('dashboard.advertisements.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-400 to-teal-500 text-white rounded-full font-bold text-sm shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300 px-5 py-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    {{ __('Nieuwe Advertentie') }}
                </a>
                @endif
            </div>

            {{-- Filter & Search --}}
            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-5 mb-6">
                <form action="{{ route('dashboard.advertisements.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Zoek op titel...') }}" 
                           class="flex-1 bg-slate-50 border-transparent rounded-2xl px-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all">
                    
                    <select name="type" class="bg-slate-50 border-transparent rounded-2xl px-4 py-2.5 text-sm text-slate-600 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50">
                        <option value="">{{ __('Alle types') }}</option>
                        <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>{{ __('Verkoop') }}</option>
                        <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>{{ __('Verhuur') }}</option>
                        <option value="auction" {{ request('type') == 'auction' ? 'selected' : '' }}>{{ __('Veiling') }}</option>
                    </select>

                    <select name="sort" class="bg-slate-50 border-transparent rounded-2xl px-4 py-2.5 text-sm text-slate-600 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Nieuwste') }}</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('Prijs (Laag-Hoog)') }}</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('Prijs (Hoog-Laag)') }}</option>
                    </select>

                    <button type="submit" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-sm hover:shadow-emerald-500/30 transition-all">
                        {{ __('Filter') }}
                    </button>
                </form>
            </div>

            {{-- Advertisements Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($advertisements as $ad)
                    <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-5 hover:shadow-soft-lg hover:-translate-y-0.5 transition-all duration-300 group">
                        <a href="{{ route('dashboard.advertisements.show', $ad) }}">
                            <h3 class="font-extrabold text-lg text-emerald-600 group-hover:text-emerald-700 truncate transition-colors">{{ $ad->title }}</h3>
                        </a>
                        <p class="text-slate-400 truncate mt-2 text-sm">{{ $ad->description }}</p>
                        <p class="font-extrabold mt-2 text-slate-800 text-lg">€ {{ number_format($ad->price, 2) }}</p>
                        <p class="text-xs text-slate-400 mb-4">
                            <span class="px-2.5 py-1 rounded-full bg-slate-50 border border-slate-100 text-slate-500 font-semibold">{{ ucfirst($ad->type) }}</span>
                        </p>
                        
                        <div class="flex gap-2 pt-4 border-t border-slate-100">
                            <a href="{{ route('dashboard.advertisements.edit', $ad) }}" class="flex-1 text-center bg-amber-50 text-amber-600 border border-amber-200 font-bold py-2 px-4 rounded-full text-sm hover:bg-amber-100 transition-all">{{ __('Bewerken') }}</a>
                            
                            <form action="{{ route('dashboard.advertisements.destroy', $ad) }}" method="POST" onsubmit="return confirm('{{ __('Verwijderen?') }}');" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-50 text-red-500 border border-red-200 font-bold py-2 px-4 rounded-full text-sm hover:bg-red-100 transition-all">{{ __('Verwijderen') }}</button>
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
</x-app-layout>
