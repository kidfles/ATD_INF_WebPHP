<x-market-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <a href="{{ session()->has('ad_filters') ? route('market.index', session('ad_filters')) : route('market.index') }}" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Terug naar overzicht</a>

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
                            <div class="flex items-center space-x-2 text-gray-500 text-sm">
                                <span>Aangeboden door:</span>
                                @if($advertisement->user->companyProfile)
                                    <a href="{{ route('company.show', $advertisement->user->companyProfile->custom_url_slug) }}" 
                                       class="font-bold text-indigo-600 hover:underline flex items-center gap-1">
                                        {{ $advertisement->user->name }}
                                    </a>
                                @else
                                    <span class="font-semibold">{{ $advertisement->user->name }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Smart Action Buttons --}}
                        @if(auth()->id() !== $advertisement->user_id)
                            
                            {{-- Auction: Place Bid --}}
                            @if($advertisement->type === 'auction')
                                <div class="bg-gray-50 p-4 rounded border mb-6">
                                    <h3 class="font-bold text-lg mb-2">Plaats een bod</h3>
                                    <p class="text-sm text-gray-500 mb-4">Huidig hoogste bod: <span class="font-bold text-indigo-600">€ {{ number_format($advertisement->bids->max('amount') ?? $advertisement->price, 2) }}</span></p>
                                    
                                    <form action="{{ route('bids.store', $advertisement) }}" method="POST" class="mt-4">
                                        @csrf
                                        <label class="block font-medium text-sm text-gray-700">Jouw Bod</label>
                                        <div class="flex gap-2 mt-1">
                                            <input type="number" name="amount" step="0.01" min="{{ ($advertisement->bids->max('amount') ?? $advertisement->price) + 0.01 }}" required class="border-gray-300 rounded-md shadow-sm w-full" placeholder="€ 0.00">
                                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                                Plaats Bod
                                            </button>
                                        </div>
                                        @error('amount')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </form>

                                    {{-- Recent Bids List --}}
                                    @if($advertisement->bids->count() > 0)
                                        <div class="mt-6 border-t pt-4">
                                            <h4 class="font-bold mb-2 text-sm text-gray-700">Recente biedingen</h4>
                                            <ul class="space-y-2">
                                                @foreach($advertisement->bids->sortByDesc('amount')->take(5) as $bid)
                                                    <li class="flex justify-between text-sm">
                                                        <span class="text-gray-600">{{ $bid->user->name }}</span>
                                                        <span class="font-medium">€ {{ number_format($bid->amount, 2) }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                            {{-- Rent: Reservation Form --}}
                            @elseif($advertisement->type === 'rent')
                                <div class="bg-gray-50 p-4 rounded border">
                                    <h3 class="font-bold text-lg mb-2">Huur dit item</h3>
                                    <form action="{{ route('rentals.store', $advertisement) }}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Startdatum</label>
                                                <input type="date" name="start_date" min="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Einddatum</label>
                                                <input type="date" name="end_date" min="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded hover:bg-green-700">
                                            Reserveer Nu
                                        </button>
                                    </form>
                                </div>

                            {{-- Sell: Contact --}}
                            @else
                                <button class="bg-green-600 text-white px-6 py-3 rounded font-bold w-full hover:bg-green-700">
                                    Neem contact op met verkoper
                                </button>
                            @endif

                        @else
                            {{-- Owner View --}}
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                                <p class="text-sm text-yellow-700">
                                    Jij bent de eigenaar van deze advertentie.
                                    <a href="{{ route('dashboard.advertisements.edit', $advertisement) }}" class="font-bold underline ml-1">Bewerk hem hier.</a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-market-layout>
