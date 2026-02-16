<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bedrijfspagina & Instellingen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- ERROR FEEDBACK --}}
            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded mb-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SUCCESS FEEDBACK --}}
            @if (session('status'))
                <div class="bg-green-50 text-green-600 p-4 rounded mb-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                    {{ session('status') }}
                </div>
            @endif

            {{-- ONE MAIN FORM FOR EVERYTHING --}}
            <form method="post" action="{{ route('dashboard.company.settings.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                {{-- CARD 1: BRANDING & SETTINGS --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Branding & URL</h2>
                        <p class="mt-1 text-sm text-gray-600">Pas hier je kleuren en webadres aan.</p>
                    </header>

                    <div class="mt-6 space-y-6 max-w-xl">
                        
                        {{-- COLOR PICKER FIX --}}
                        <div class="space-y-2" x-data="{ color: @json($company->brand_color ?? '#000000') }">
                            <x-input-label for="brand_color" value="Huisstijl Kleur (Hex Code)" />
                            <div class="flex items-center gap-4 mt-1">
                                {{-- REMOVED name="brand_color" from the color picker so it doesn't conflict --}}
                                <input type="color" x-model="color" class="h-10 w-20 p-1 rounded border border-gray-300 cursor-pointer">
                                
                                {{-- This Text Input is now the ONLY one sending the value --}}
                                <x-text-input name="brand_color" type="text" class="block w-full uppercase" x-model="color" maxlength="7" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="custom_url_slug" value="Publieke URL" />
                            <div class="flex items-center mt-1">
                                <span class="text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md px-3 py-2">
                                    {{ config('app.url') }}/company/
                                </span>
                                <x-text-input name="custom_url_slug" type="text" class="block w-full rounded-l-none" :value="old('custom_url_slug', $company->custom_url_slug)" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="kvk_number" value="KVK Nummer" />
                            <x-text-input name="kvk_number" type="text" class="block w-full mt-1" :value="old('kvk_number', $company->kvk_number)" />
                        </div>
                    </div>
                </div>

                {{-- CARD 2: PAGE BUILDER --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <header class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">Pagina Indeling</h2>
                            <p class="mt-1 text-sm text-gray-600">Beheer de blokken op je bedrijfspagina.</p>
                        </div>
                    </header>

                    {{-- EXISTING COMPONENTS LIST --}}
                    <div class="space-y-4 mb-6">
                        @forelse($company->pageComponents as $component)
                            <div class="border rounded-lg p-4 bg-gray-50 relative group">
                                <input type="hidden" name="ordered_ids[]" value="{{ $component->id }}">
                                
                                <div class="flex justify-between items-center mb-4">
                                    <span class="badge bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs uppercase font-bold">
                                        {{ $component->component_type }}
                                    </span>
                                    
                                    {{-- Delete Button (Needs its own small form or JS) --}}
                                    <button type="button" onclick="if(confirm('Weet je zeker dat je dit blok wilt verwijderen?')) document.getElementById('delete-component-{{ $component->id }}').submit()" class="text-red-500 text-sm hover:underline">
                                        Verwijderen
                                    </button>
                                </div>

                                {{-- COMPONENT INPUTS --}}
                                <div class="space-y-3">
                                    @if($component->component_type === 'hero')
                                        <label class="text-xs font-bold text-gray-500 uppercase">Titel</label>
                                        <input type="text" name="components[{{ $component->id }}][content][title]" value="{{ $component->content['title'] ?? '' }}" class="w-full border-gray-300 rounded text-sm">
                                        
                                        <label class="text-xs font-bold text-gray-500 uppercase">Subtitel</label>
                                        <input type="text" name="components[{{ $component->id }}][content][subtitle]" value="{{ $component->content['subtitle'] ?? '' }}" class="w-full border-gray-300 rounded text-sm">
                                        
                                    @elseif($component->component_type === 'text')
                                        <label class="text-xs font-bold text-gray-500 uppercase">Koptekst</label>
                                        <input type="text" name="components[{{ $component->id }}][content][heading]" value="{{ $component->content['heading'] ?? '' }}" class="w-full border-gray-300 rounded text-sm font-bold">
                                        
                                        <label class="text-xs font-bold text-gray-500 uppercase">Inhoud</label>
                                        <textarea name="components[{{ $component->id }}][content][body]" rows="4" class="w-full border-gray-300 rounded text-sm">{{ $component->content['body'] ?? '' }}</textarea>
                                        
                                    @elseif($component->component_type === 'featured_ads')
                                        <p class="text-sm text-gray-500 italic">Dit blok toont automatisch je 3 nieuwste advertenties.</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 py-4 border border-dashed rounded">Je hebt nog geen blokken toegevoegd.</p>
                        @endforelse
                    </div>
                </div>

                {{-- STICKY SAVE BUTTON BAR --}}
                <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 flex justify-end items-center gap-4 z-50 shadow-lg sm:pl-64">
                    <span class="text-sm text-gray-500 hidden sm:inline">Vergeet niet op te slaan na het wijzigen van blokken.</span>
                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 text-lg px-8 py-3">
                        {{ __('Wijzigingen Opslaan') }}
                    </x-primary-button>
                </div>

            </form> {{-- END MAIN FORM --}}

            {{-- SEPARATE SECTION: ADD NEW COMPONENT --}}
            <div class="p-4 sm:p-8 bg-gray-50 border border-gray-200 rounded-lg mt-8 mb-24">
                <h3 class="font-bold text-gray-700 mb-4">Nieuw Blok Toevoegen</h3>
                <form action="{{ route('dashboard.company.components.store') }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div class="w-full max-w-xs">
                        <label class="block text-sm text-gray-600 mb-1">Type Blok</label>
                        <select name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            <option value="text">Tekstblok (Over ons)</option>
                            <option value="hero">Hero Banner (Grote titel)</option>
                            <option value="featured_ads">Advertentie Grid</option>
                        </select>
                    </div>
                    <x-secondary-button type="submit" class="mb-[2px]">
                        + Toevoegen
                    </x-secondary-button>
                </form>
            </div>

        </div>
    </div>

    {{-- HIDDEN DELETE FORMS (Must be outside the main form) --}}
    @foreach($company->pageComponents as $component)
        <form id="delete-component-{{ $component->id }}" action="{{ route('dashboard.company.components.destroy', $component) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

</x-app-layout>