<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            <h2 class="text-2xl font-bold text-white mb-6">{{ __('Mijn Favorieten') }}</h2>

            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl">
                <div class="p-6 sm:p-8">
                    @if($favorites->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <h3 class="mt-3 text-sm font-medium text-gray-300">{{ __('Geen favorieten') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Je hebt nog geen advertenties als favoriet gemarkeerd.') }}</p>
                            <div class="mt-6">
                                <a href="{{ route('market.index') }}" class="inline-flex items-center px-5 py-2.5 bg-violet-500/20 text-violet-300 border border-violet-500/30 rounded-xl font-semibold text-sm hover:bg-violet-500/30 transition">
                                    {{ __('Naar de Markt') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($favorites as $ad)
                                <div class="bg-white/5 border border-white/5 rounded-xl overflow-hidden hover:border-violet-500/30 hover:-translate-y-0.5 transition-all duration-300 flex flex-col h-full relative group">
                                    
                                    {{-- Image --}}
                                    <div class="relative h-48 bg-white/5">
                                        @if($ad->image_path)
                                            <img src="{{ asset('storage/' . $ad->image_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-600">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        
                                        {{-- Remove Button --}}
                                        <form action="{{ route('favorites.toggle', $ad) }}" method="POST" class="absolute top-2 right-2">
                                            @csrf
                                            <button type="submit" class="bg-slate-900/80 backdrop-blur p-1.5 rounded-full text-red-400 hover:text-red-300 hover:bg-red-500/20 transition shadow-lg border border-white/10" title="{{ __('Verwijder uit favorieten') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>

                                        {{-- Badge --}}
                                        <div class="absolute top-2 left-2 px-2 py-1 bg-violet-500/20 backdrop-blur border border-violet-500/30 rounded-full text-xs font-bold uppercase tracking-wider text-violet-300 shadow-sm">
                                            {{ $ad->type }}
                                        </div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="p-4 flex flex-col flex-1">
                                        <h3 class="font-bold text-lg text-white truncate mb-1" title="{{ $ad->title }}">
                                            {{ $ad->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $ad->description }}</p>
                                        
                                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-white/5">
                                            <span class="font-black text-lg text-white">€ {{ number_format($ad->price, 2) }}</span>
                                            <a href="{{ route('market.show', $ad) }}" class="text-sm font-medium text-violet-400 hover:text-violet-300 transition-colors">
                                                {{ __('Bekijk') }} &rarr;
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $favorites->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
