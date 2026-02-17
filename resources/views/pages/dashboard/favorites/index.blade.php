<x-app-layout>
    {{--
        Pagina: Mijn Favorieten
        Doel: Lijst van advertenties die de gebruiker als favoriet heeft gemarkeerd.
        Bevat: Afbeeldingen, prijzen en optie om te verwijderen.
    --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            <h2 class="text-2xl font-extrabold text-slate-800 mb-6">{{ __('Mijn Favorieten') }}</h2>

            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100">
                <div class="p-6 sm:p-8">
                    @if($favorites->isEmpty())
                        <div class="text-center py-12">
                            <div class="bg-red-50 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.486 4.486 0 000 6.364L12 20.364l7.682-7.682a4.486 4.486 0 000-6.364 4.486 4.486 0 00-6.364 0L12 7.636l-1.318-1.318a4.486 4.486 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <p class="text-slate-400 font-medium">{{ __('Je hebt nog geen favorieten.') }}</p>
                            <a href="{{ route('market.index') }}" class="inline-flex items-center mt-4 px-5 py-2.5 bg-gradient-to-r from-emerald-400 to-teal-500 text-white rounded-full text-sm font-bold shadow-sm hover:shadow-emerald-500/30 transition-all">{{ __('Ontdek de marktplaats') }}</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($favorites as $ad)
                                <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden hover:shadow-soft hover:-translate-y-0.5 transition-all duration-300 group">
                                    @if($ad->image_path)
                                        <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" class="w-full h-40 object-cover">
                                    @else
                                        <div class="w-full h-40 bg-slate-100 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <a href="{{ route('market.show', $ad) }}" class="font-bold text-slate-800 group-hover:text-emerald-600 transition-colors block truncate">{{ $ad->title }}</a>
                                        <p class="text-emerald-500 font-extrabold mt-1">€{{ number_format($ad->price, 2) }}</p>
                                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                                            <span class="text-xs text-slate-400 font-semibold">{{ ucfirst($ad->type) }}</span>
                                            <form action="{{ route('favorites.toggle', $ad) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="{{ __('Remove from favorites') }}">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
