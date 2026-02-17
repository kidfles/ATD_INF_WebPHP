<x-app-layout>
    <div class="py-4">
        <div class="max-w-3xl mx-auto">

            <h2 class="text-2xl font-bold text-white mb-6">{{ __('Nieuwe Advertentie') }}</h2>

            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 sm:p-8">

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500/20 text-red-300 px-4 py-3 rounded-xl mb-6">
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dashboard.advertisements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-300 mb-1.5">{{ __('Titel') }}</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:border-violet-500/50 focus:ring-violet-500/20 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-300 mb-1.5">{{ __('Beschrijving') }}</label>
                        <textarea name="description" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:border-violet-500/50 focus:ring-violet-500/20 focus:outline-none transition">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-300 mb-1.5">{{ __('Prijs') }}</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:border-violet-500/50 focus:ring-violet-500/20 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-300 mb-1.5">{{ __('Type') }}</label>
                        <select name="type" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-gray-300 focus:border-violet-500/50 focus:ring-violet-500/20 focus:outline-none">
                            <option value="sell">{{ __('Verkoop') }}</option>
                            <option value="rent">{{ __('Verhuur') }}</option>
                            <option value="auction">{{ __('Veiling') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-300 mb-1.5">{{ __('Gerelateerde Producten (Koppelverkoop)') }}</label>
                        <p class="text-xs text-gray-500 mb-2">{{ __('Selecteer producten die hierbij horen (houd Ctrl/Cmd ingedrukt om meerdere te selecteren)') }}</p>
                        <select name="related_ads[]" multiple class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-gray-300 focus:border-violet-500/50 focus:ring-violet-500/20 focus:outline-none h-32">
                            @foreach($myAdvertisements as $option)
                                <option value="{{ $option->id }}" 
                                    @if(is_array(old('related_ads')) && in_array($option->id, old('related_ads'))) selected @endif
                                >
                                    {{ ucfirst($option->type) }}: {{ $option->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div x-data="{ imageUrl: null }">
                        <label class="block text-sm font-bold text-gray-300 mb-1.5">{{ __('Afbeelding') }}</label>
                        
                        <input type="file" name="image" class="block w-full text-sm text-gray-400
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-xl file:border-0
                          file:text-sm file:font-semibold
                          file:bg-violet-500/20 file:text-violet-300
                          hover:file:bg-violet-500/30 file:transition"
                          @change="imageUrl = URL.createObjectURL($event.target.files[0])" 
                        />

                        <div x-show="imageUrl" class="mt-4">
                            <p class="text-xs text-gray-500 mb-1">{{ __('Voorbeeld:') }}</p>
                            <div class="h-40 w-40 rounded-xl border border-white/10 shadow-lg overflow-hidden">
                                <img :src="imageUrl" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full sm:w-auto bg-violet-500/20 text-violet-300 border border-violet-500/30 px-6 py-3 rounded-xl font-bold text-sm hover:bg-violet-500/30 transition">
                        {{ __('Opslaan') }}
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
