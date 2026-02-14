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
                        @else
                            {{-- Rental Form --}}
                            @if($advertisement->type === 'rent')
                                <div class="bg-gray-50 p-4 rounded border">
                                    <h3 class="font-bold text-lg mb-2">Huur dit item</h3>
                                    <form action="{{ route('rentals.store', $advertisement) }}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Startdatum</label>
                                                <input type="date" name="start_date" min="{{ date('Y-m-d') }}" required class="w-full border rounded px-3 py-2">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Einddatum</label>
                                                <input type="date" name="end_date" min="{{ date('Y-m-d') }}" required class="w-full border rounded px-3 py-2">
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700">Reserveer Nu</button>
                                    </form>
                                </div>
                            
                            {{-- Auction Form --}}
                            @elseif($advertisement->type === 'auction')
                                <div class="bg-gray-50 p-4 rounded border mb-6">
                                    <h3 class="font-bold text-lg mb-2">Plaats een bod</h3>
                                    <p class="text-sm text-gray-500 mb-4">Huidig hoogste bod: € {{ number_format($advertisement->bids->max('amount') ?? $advertisement->price, 2) }}</p>
                                    
                                    <form action="{{ route('bids.store', $advertisement) }}" method="POST" class="flex flex-col gap-2">
                                        @csrf
                                        <div class="flex gap-2">
                                            <input type="number" name="amount" step="0.01" min="{{ ($advertisement->bids->max('amount') ?? $advertisement->price) + 1 }}" required class="flex-1 border rounded px-3 py-2" placeholder="Uw bod">
                                            <button type="submit" class="bg-indigo-600 text-white font-bold px-4 py-2 rounded hover:bg-indigo-700">Bieden</button>
                                        </div>
                                        @error('amount')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </form>
                                </div>

                                {{-- Bids List --}}
                                @if($advertisement->bids->count() > 0)
                                    <div class="mt-4">
                                        <h4 class="font-bold mb-2">Recente biedingen</h4>
                                        <ul class="space-y-2">
                                            @foreach($advertisement->bids->sortByDesc('amount')->take(5) as $bid)
                                                <li class="flex justify-between text-sm border-b pb-1">
                                                    <span class="text-gray-600">{{ $bid->user->name }}</span>
                                                    <span class="font-bold">€ {{ number_format($bid->amount, 2) }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                            {{-- Standard Sale --}}
                            @else
                                <button class="bg-green-600 text-white px-6 py-3 rounded font-bold w-full hover:bg-green-700">
                                    Neem contact op met verkoper
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-market-layout>
