<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mijn Favorieten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    @if($favorites->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen favorieten</h3>
                            <p class="mt-1 text-sm text-gray-500">Je hebt nog geen advertenties als favoriet gemarkeerd.</p>
                            <div class="mt-6">
                                <a href="{{ route('market.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Naar de Markt
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($favorites as $ad)
                                <div class="border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition bg-white flex flex-col h-full relative group">
                                    
                                    {{-- Image --}}
                                    <div class="relative h-48 bg-gray-100">
                                        @if($ad->image_path)
                                            <img src="{{ asset('storage/' . $ad->image_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        
                                        {{-- Remove Button (X) --}}
                                        <form action="{{ route('favorites.toggle', $ad) }}" method="POST" class="absolute top-2 right-2">
                                            @csrf
                                            <button type="submit" class="bg-white/90 backdrop-blur p-1.5 rounded-full text-red-500 hover:bg-red-50 transition shadow-sm" title="Verwijder uit favorieten">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>

                                        {{-- Badge --}}
                                        <div class="absolute top-2 left-2 px-2 py-1 bg-white/90 backdrop-blur rounded-full text-xs font-bold uppercase tracking-wider text-indigo-600 shadow-sm">
                                            {{ $ad->type }}
                                        </div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="p-4 flex flex-col flex-1">
                                        <h3 class="font-bold text-lg text-gray-900 truncate mb-1" title="{{ $ad->title }}">
                                            {{ $ad->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $ad->description }}</p>
                                        
                                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-100">
                                            <span class="font-black text-lg text-gray-900">€ {{ number_format($ad->price, 2) }}</span>
                                            <a href="{{ route('market.show', $ad) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                                                Bekijk &rarr;
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
