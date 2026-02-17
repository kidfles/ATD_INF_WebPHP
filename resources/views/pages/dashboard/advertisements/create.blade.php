<x-app-layout>
    {{--
        Pagina: Nieuwe Advertentie
        Doel: Formulier voor het aanmaken van een nieuwe advertentie.
        Functionaliteiten:
        - Keuze uit type (verkoop, verhuur, veiling).
        - Validatie en file upload voor afbeeldingen.
    --}}
    <div class="py-4">
        <div class="max-w-3xl mx-auto">

            <h2 class="text-2xl font-extrabold text-slate-800 mb-6">{{ __('Nieuwe Advertentie') }}</h2>

            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8">

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl mb-6">
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dashboard.advertisements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ type: '{{ old('type', 'sell') }}' }">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Titel') }}</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Beschrijving') }}</label>
                        <textarea name="description" rows="4" class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Prijs') }}</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Type') }}</label>
                        <select name="type" x-model="type" class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-3 text-sm text-slate-600 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50">
                            <option value="sell">{{ __('Verkoop') }}</option>
                            <option value="rent">{{ __('Verhuur') }}</option>
                            <option value="auction">{{ __('Veiling') }}</option>
                        </select>
                    </div>

                    <div x-show="type === 'auction'" style="display: none;">
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Einddatum (Verplicht voor Veiling)') }}</label>
                        <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-3 text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Gerelateerde Producten (Koppelverkoop)') }}</label>
                        <p class="text-xs text-slate-400 mb-2">{{ __('Selecteer producten die hierbij horen (houd Ctrl/Cmd ingedrukt om meerdere te selecteren)') }}</p>
                        <select name="related_ads[]" multiple class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-3 text-sm text-slate-600 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 h-32">
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
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Afbeelding') }}</label>
                        
                        <input type="file" name="image" class="block w-full text-sm text-slate-500
                          file:mr-4 file:py-2.5 file:px-5
                          file:rounded-full file:border-0
                          file:text-sm file:font-bold
                          file:bg-emerald-50 file:text-emerald-600
                          hover:file:bg-emerald-100 file:transition-all file:cursor-pointer"
                          @change="imageUrl = URL.createObjectURL($event.target.files[0])" 
                        />

                        <div x-show="imageUrl" class="mt-4">
                            <p class="text-xs text-slate-400 mb-1">{{ __('Voorbeeld:') }}</p>
                            <div class="h-40 w-40 rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
                                <img :src="imageUrl" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-8 py-3 rounded-full font-bold text-sm shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300">
                        {{ __('Opslaan') }}
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
