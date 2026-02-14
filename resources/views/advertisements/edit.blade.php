<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Advertentie Bewerken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('advertisements.update', $advertisement) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="block font-bold">Titel</label>
                            <input type="text" name="title" value="{{ old('title', $advertisement->title) }}" class="border rounded w-full px-4 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold">Beschrijving</label>
                            <textarea name="description" class="border rounded w-full px-4 py-2">{{ old('description', $advertisement->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold">Prijs</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $advertisement->price) }}" class="border rounded w-full px-4 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold">Type</label>
                            <select name="type" class="border rounded w-full px-4 py-2">
                                <option value="sell" {{ old('type', $advertisement->type) == 'sell' ? 'selected' : '' }}>Verkoop</option>
                                <option value="rent" {{ old('type', $advertisement->type) == 'rent' ? 'selected' : '' }}>Verhuur</option>
                                <option value="auction" {{ old('type', $advertisement->type) == 'auction' ? 'selected' : '' }}>Veiling</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold">Afbeelding (Optioneel)</label>
                            @if($advertisement->image_path)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $advertisement->image_path) }}" alt="Huidige afbeelding" class="h-20 w-auto rounded">
                                </div>
                            @endif
                            <input type="file" name="image" class="border rounded w-full px-4 py-2">
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Bijwerken</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
