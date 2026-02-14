<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $advertisement->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                            
                            <div class="flex items-center justify-between">
                                <span class="bg-gray-200 px-3 py-1 rounded text-sm">{{ ucfirst($advertisement->type) }}</span>
                                <span class="text-gray-500 text-sm">Aangeboden door: {{ $advertisement->user->name }}</span>
                            </div>

                            @if(auth()->id() === $advertisement->user_id)
                                <div class="mt-8 flex gap-4">
                                    <a href="{{ route('advertisements.edit', $advertisement) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Bewerken</a>
                                    
                                    <form action="{{ route('advertisements.destroy', $advertisement) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze advertentie wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Verwijderen</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
