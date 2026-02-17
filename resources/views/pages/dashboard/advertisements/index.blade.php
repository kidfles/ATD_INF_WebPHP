<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            {{-- Page Header --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-white">{{ __('Advertenties') }}</h2>
                @if(Auth::user()->role !== 'user')
                <a href="{{ route('dashboard.advertisements.create') }}" class="inline-flex items-center gap-2 bg-violet-500/20 text-violet-300 border border-violet-500/30 rounded-xl font-semibold text-xs uppercase tracking-widest hover:bg-violet-500/30 transition px-4 py-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    {{ __('Nieuwe Advertentie') }}
                </a>
                @endif
            </div>

            {{-- Filter & Search --}}
            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-5 mb-6">
                <form action="{{ route('dashboard.advertisements.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Zoek op titel...') }}" 
                           class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:border-violet-500/50 focus:ring-violet-500/20 focus:outline-none transition">
                    
                    <select name="type" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-gray-300 focus:border-violet-500/50 focus:ring-violet-500/20 focus:outline-none">
                        <option value="">{{ __('Alle types') }}</option>
                        <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>{{ __('Verkoop') }}</option>
                        <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>{{ __('Verhuur') }}</option>
                        <option value="auction" {{ request('type') == 'auction' ? 'selected' : '' }}>{{ __('Veiling') }}</option>
                    </select>

                    <select name="sort" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-gray-300 focus:border-violet-500/50 focus:ring-violet-500/20 focus:outline-none">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Nieuwste') }}</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('Prijs (Laag-Hoog)') }}</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('Prijs (Hoog-Laag)') }}</option>
                    </select>

                    <button type="submit" class="bg-violet-500/20 text-violet-300 border border-violet-500/30 px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-violet-500/30 transition">
                        {{ __('Filter') }}
                    </button>
                </form>
            </div>

            {{-- Advertisements Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($advertisements as $ad)
                    <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-5 hover:border-violet-500/30 hover:-translate-y-0.5 transition-all duration-300 group">
                        <a href="{{ route('dashboard.advertisements.show', $ad) }}">
                            <h3 class="font-bold text-lg text-violet-400 group-hover:text-violet-300 truncate transition-colors">{{ $ad->title }}</h3>
                        </a>
                        <p class="text-gray-400 truncate mt-2 text-sm">{{ $ad->description }}</p>
                        <p class="font-bold mt-2 text-white text-lg">€ {{ number_format($ad->price, 2) }}</p>
                        <p class="text-xs text-gray-500 mb-4">
                            <span class="px-2 py-0.5 rounded-full bg-white/5 border border-white/5 text-gray-400">{{ ucfirst($ad->type) }}</span>
                        </p>
                        
                        <div class="flex gap-2 pt-4 border-t border-white/5">
                            <a href="{{ route('dashboard.advertisements.edit', $ad) }}" class="flex-1 text-center bg-amber-500/10 text-amber-300 border border-amber-500/20 font-bold py-2 px-4 rounded-xl text-sm hover:bg-amber-500/20 transition">{{ __('Bewerken') }}</a>
                            
                            <form action="{{ route('dashboard.advertisements.destroy', $ad) }}" method="POST" onsubmit="return confirm('{{ __('Verwijderen?') }}');" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500/10 text-red-300 border border-red-500/20 font-bold py-2 px-4 rounded-xl text-sm hover:bg-red-500/20 transition">{{ __('Verwijderen') }}</button>
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
