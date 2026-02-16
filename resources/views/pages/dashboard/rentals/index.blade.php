<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mijn Huuritems') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(session('status'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($rentals->isEmpty())
                        <p class="text-gray-500">Je hebt nog geen items gehuurd.</p>
                        <a href="{{ route('market.index', ['type' => 'rent']) }}" class="text-blue-600 hover:underline mt-2 inline-block">Bekijk huur aanbod &rarr;</a>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($rentals as $rental)
                                <div class="border rounded-lg overflow-hidden {{ $rental->return_photo_path ? 'bg-gray-50' : 'bg-white' }} shadow-sm hover:shadow-md transition">
                                    <div class="w-full aspect-video">
                                        @if($rental->advertisement->image_path)
                                            <img src="{{ asset('storage/' . $rental->advertisement->image_path) }}" alt="{{ $rental->advertisement->title }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('images/placeholder.svg') }}" alt="Placeholder" class="w-full h-full object-cover opacity-50">
                                        @endif
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="font-bold text-lg mb-2">{{ $rental->advertisement->title }}</h3>
                                        <p class="text-gray-600 text-sm mb-1">
                                            <span class="font-semibold">Periode:</span> 
                                            {{ $rental->start_date->format('d-m-Y') }} t/m {{ $rental->end_date->format('d-m-Y') }}
                                        </p>
                                        <p class="text-gray-600 text-sm mb-4">
                                            <span class="font-semibold">Prijs per dag:</span> € {{ number_format($rental->advertisement->price, 2) }}
                                        </p>

                                        @if($rental->return_photo_path)
                                            <div class="bg-green-50 border border-green-200 rounded p-3">
                                                <p class="text-green-800 font-bold text-sm mb-1">Item is geretourneerd</p>
                                                <p class="text-green-700 text-sm">Totale kosten: € {{ number_format($rental->wear_and_tear_cost, 2) }}</p>
                                            </div>
                                        @else
                                            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                                                <h4 class="font-bold text-yellow-800 text-sm mb-2">Item Retourneren</h4>
                                                <form action="{{ route('rentals.return', $rental) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Upload foto van item staat</label>
                                                        <input type="file" name="photo" required class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                    </div>
                                                    <button type="submit" class="w-full bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">Retourneren</button>
                                                </form>
                                            </div>
                                        @endif
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
