<x-market-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('market.index') }}" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Terug naar overzicht</a>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        @if($advertisement->image_path)
                            <img src="{{ asset('storage/' . $advertisement->image_path) }}" alt="{{ $advertisement->title }}" class="w-full rounded shadow">
                        @else
                            <div class="w-full h-64 bg-gray-200 rounded flex items-center justify-center text-gray-500">
                                Geen afbeelding
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-4">{{ $advertisement->title }}</h1>
                        <p class="text-2xl font-bold text-blue-600 mb-4">€ {{ number_format($advertisement->price, 2) }}</p>
                        <p class="text-gray-600 mb-6">{{ $advertisement->description }}</p>
                        
                        <div class="flex items-center justify-between mb-8">
                            <span class="bg-gray-200 px-3 py-1 rounded text-sm">{{ ucfirst($advertisement->type) }}</span>
                            <span class="text-gray-500 text-sm">Aangeboden door: {{ $advertisement->user->name }}</span>
                        </div>

                        {{-- Owner Controls --}}
                        @if(auth()->id() === $advertisement->user_id)
                            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-sm text-yellow-800 font-bold mb-2">Dit is jouw advertentie</p>
                                <a href="{{ route('dashboard.advertisements.show', $advertisement) }}" class="text-blue-600 hover:underline">Beheer deze advertentie in je dashboard &rarr;</a>
                            </div>
                        @endif

                        {{-- Action Buttons (e.g., Contact, Bid, Rent) would go here --}}
                        <button class="bg-blue-600 text-white px-6 py-3 rounded font-bold w-full hover:bg-blue-700">
                            Reageer op advertentie
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-market-layout>
