<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nieuwe Advertentie') }}
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

                    <form action="{{ route('dashboard.advertisements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block font-bold">Titel</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="border rounded w-full px-4 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold">Beschrijving</label>
                            <textarea name="description" class="border rounded w-full px-4 py-2">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold">Prijs</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="border rounded w-full px-4 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold">Type</label>
                            <select name="type" class="border rounded w-full px-4 py-2">
                                <option value="sell">Verkoop</option>
                                <option value="rent">Verhuur</option>
                                <option value="auction">Veiling</option>
                            </select>
                        </div>

                        <div class="mb-4" x-data="{ imageUrl: null }">
                            <label class="block font-bold mb-2">Afbeelding</label>
                            
                            <input type="file" name="image" class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100"
                              @change="imageUrl = URL.createObjectURL($event.target.files[0])" 
                            />

                            <div x-show="imageUrl" class="mt-4">
                                <p class="text-xs text-gray-500 mb-1">Voorbeeld:</p>
                                <div class="h-40 w-40 rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                                    <img :src="imageUrl" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Opslaan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
