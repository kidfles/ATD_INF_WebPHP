<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bedrijfspagina & Instellingen') }}
            </h2>
            <a href="{{ route('company.show', $company->custom_url_slug) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Bekijk Publieke Pagina
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- 1. GLOBAL FEEDBACK MESSAGES --}}
            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded mb-6 border border-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="bg-green-50 text-green-600 p-4 rounded mb-6 border border-green-200" 
                     x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    {{ session('status') }}
                </div>
            @endif
            

            {{-- 2. CONTRACT MANAGEMENT (COMPLETELY SEPARATE FROM MAIN FORM) --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-indigo-100">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Samenwerkingscontract</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Om toegang te krijgen tot de API en de officiële "Verified" status, hebben we een getekend contract nodig.
                    </p>
                </header>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    
                    {{-- Left: Download Section --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 h-full">
                        <h3 class="font-bold text-gray-800 mb-2">Stap 1: Downloaden</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Download het contract, print het uit en zet uw handtekening.
                        </p>
                        <a href="{{ route('dashboard.company.contract.download') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download PDF
                        </a>
                    </div>

                    {{-- Right: Upload & Status Section --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 h-full relative">
                        <h3 class="font-bold text-gray-800 mb-2">Stap 2: Uploaden</h3>
                        
                        {{-- Status Logic --}}
                        @if(auth()->user()->companyProfile->contract_status === 'approved')
                            <div class="flex items-center text-green-700 bg-green-100 p-3 rounded mb-4 border border-green-200">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <span class="font-bold block">Goedgekeurd!</span>
                                    <span class="text-xs">U heeft volledige toegang.</span>
                                </div>
                            </div>
                        @elseif(auth()->user()->companyProfile->contract_file_path)
                            <div class="flex items-center text-yellow-700 bg-yellow-100 p-3 rounded mb-4 border border-yellow-200">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <span class="font-bold block">In Behandeling</span>
                                    <span class="text-xs">We controleren uw document.</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mb-2">Nieuwe versie uploaden?</p>
                        @else
                            <p class="text-sm text-gray-500 mb-4">Upload hier de gescande versie van het getekende contract.</p>
                        @endif

                        {{-- Upload Form --}}
                        @if(auth()->user()->companyProfile->contract_status !== 'approved')
                            <form action="{{ route('dashboard.company.contract.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="file" name="contract_pdf" accept="application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                    <x-primary-button>
                                        {{ __('Upload') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            {{-- TEST BUTTON (DEVELOPMENT ONLY) --}}
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <p class="text-xs text-gray-400 mb-2 uppercase font-bold">Development Testing</p>
                                <form action="{{ route('dashboard.company.contract.approve_test') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-green-600 hover:text-green-800 underline font-bold">
                                        [TEST] Keur mijn contract direct goed
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3. API ACCESS --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-100">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">API Toegang</h2>
                    <p class="mt-1 text-sm text-gray-600">Gebruik deze gegevens om je advertenties in externe applicaties te laden.</p>
                </header>

                <div class="mt-6">
                    @if(auth()->user()->companyProfile->contract_status !== 'approved')
                        <div class="p-4 bg-red-50 text-red-700 rounded-md text-sm border border-red-200">
                            <strong>Toegang geblokkeerd:</strong> Uw contract moet eerst goedgekeurd zijn voordat u API-sleutels kunt genereren.
                        </div>
                    @else
                        @if (session('api_token'))
                            <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400">
                                <p class="text-sm font-bold text-yellow-800">Bewaar dit token goed! Je ziet het maar één keer:</p>
                                <code class="block mt-2 p-2 bg-white border rounded text-lg font-mono break-all">
                                    {{ session('api_token') }}
                                </code>
                            </div>
                        @endif

                        <form action="{{ route('dashboard.company.api_token') }}" method="POST">
                            @csrf
                            <p class="text-sm text-gray-500 mb-4">
                                Endpoint: <code class="bg-gray-100 px-2 py-1 rounded italic">{{ url('/api/my-ads') }}</code>
                            </p>
                            <x-primary-button>
                                {{ __('Nieuwe API Sleutel Genereren') }}
                            </x-primary-button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- 4. MAIN FORM: BRANDING & PAGE BUILDER --}}
            <form method="post" action="{{ route('dashboard.company.settings.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                {{-- CARD: BRANDING (Fixed Color Picker) --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Branding & URL</h2>
                        <p class="mt-1 text-sm text-gray-600">Pas hier je kleuren en webadres aan.</p>
                    </header>

                    <div class="mt-6 space-y-6 max-w-xl">
                        
                        {{-- Brand Color Input (Vanilla JS Sync) --}}
                        <div class="space-y-2">
                            <x-input-label for="brand_color" value="Huisstijl Kleur (Hex Code)" />
                            <div class="flex items-center gap-4 mt-1">
                                {{-- 1. Visual Picker (Updates Text Input) --}}
                                <input type="color" 
                                       id="color_picker_visual" 
                                       value="{{ old('brand_color', $company->brand_color ?? '#000000') }}"
                                       oninput="document.getElementById('brand_color_input').value = this.value"
                                       class="h-10 w-20 p-1 rounded border border-gray-300 cursor-pointer">
                                
                                {{-- 2. Text Input (Sends Data & Updates Picker) --}}
                                <x-text-input 
                                    id="brand_color_input"
                                    name="brand_color" 
                                    type="text" 
                                    class="block w-full uppercase" 
                                    :value="old('brand_color', $company->brand_color ?? '#000000')" 
                                    maxlength="7"
                                    oninput="document.getElementById('color_picker_visual').value = this.value" />
                            </div>
                        </div>

                        {{-- URL Slug --}}
                        <div class="space-y-2">
                            <x-input-label for="custom_url_slug" value="Publieke URL" />
                            <div class="flex items-center mt-1">
                                <span class="text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md px-3 py-2">
                                    {{ config('app.url') }}/company/
                                </span>
                                <x-text-input name="custom_url_slug" type="text" class="block w-full rounded-l-none" :value="old('custom_url_slug', $company->custom_url_slug)" />
                            </div>
                        </div>

                        {{-- KVK Number --}}
                        <div class="space-y-2">
                            <x-input-label for="kvk_number" value="KVK Nummer" />
                            <x-text-input name="kvk_number" type="text" class="block w-full mt-1" :value="old('kvk_number', $company->kvk_number)" />
                        </div>
                    </div>
                </div>

                {{-- CARD: PAGE BUILDER --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <header class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">Pagina Indeling</h2>
                            <p class="mt-1 text-sm text-gray-600">Beheer en sorteer de blokken op je bedrijfspagina.</p>
                        </div>
                    </header>

                    {{-- COMPONENTS LIST --}}
                    <div class="space-y-4 mb-6" id="components-list">
                        @forelse($company->pageComponents as $component)
                            <div class="component-card border rounded-lg p-4 bg-gray-50 relative group transition duration-300 ease-in-out">
                                
                                {{-- HIDDEN INPUT FOR SORT ORDER --}}
                                <input type="hidden" name="ordered_ids[]" value="{{ $component->id }}">
                                
                                <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                                    <div class="flex items-center gap-3">
                                        
                                        {{-- SORT BUTTONS --}}
                                        <div class="flex flex-col gap-1">
                                            <button type="button" onclick="window.moveUp(this)" class="p-1 hover:bg-gray-200 rounded text-gray-500 hover:text-gray-800 transition" title="Omhoog">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                            </button>
                                            <button type="button" onclick="window.moveDown(this)" class="p-1 hover:bg-gray-200 rounded text-gray-500 hover:text-gray-800 transition" title="Omlaag">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>
                                        </div>

                                        <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs uppercase font-bold tracking-wide">
                                            {{ $component->component_type }}
                                        </span>
                                    </div>
                                    
                                    <button type="button" onclick="if(confirm('Weet je zeker dat je dit blok wilt verwijderen?')) document.getElementById('delete-component-{{ $component->id }}').submit()" class="text-red-500 text-sm hover:underline font-medium">
                                        Verwijderen
                                    </button>
                                </div>

                                {{-- COMPONENT INPUTS --}}
                                <div class="space-y-3 pl-8">
                                    @if($component->component_type === 'hero')
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Titel</label>
                                            <input type="text" name="components[{{ $component->id }}][content][title]" value="{{ $component->content['title'] ?? '' }}" class="w-full border-gray-300 rounded text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Subtitel</label>
                                            <input type="text" name="components[{{ $component->id }}][content][subtitle]" value="{{ $component->content['subtitle'] ?? '' }}" class="w-full border-gray-300 rounded text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        
                                    @elseif($component->component_type === 'text')
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Koptekst</label>
                                            <input type="text" name="components[{{ $component->id }}][content][heading]" value="{{ $component->content['heading'] ?? '' }}" class="w-full border-gray-300 rounded text-sm font-bold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Inhoud</label>
                                            <textarea name="components[{{ $component->id }}][content][body]" rows="4" class="w-full border-gray-300 rounded text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $component->content['body'] ?? '' }}</textarea>
                                        </div>
                                        
                                    @elseif($component->component_type === 'featured_ads')
                                        <div class="bg-indigo-50 p-3 rounded border border-indigo-100">
                                            <p class="text-sm text-indigo-700 italic flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Dit blok toont automatisch je 3 nieuwste actieve advertenties.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Geen blokken</h3>
                                <p class="mt-1 text-sm text-gray-500">Begin met het toevoegen van content aan je pagina.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- STICKY SAVE BUTTON BAR --}}
                <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 flex justify-end items-center gap-4 z-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] sm:pl-64">
                    <span class="text-sm text-gray-500 hidden sm:inline italic">Vergeet niet op te slaan na het wijzigen van de volgorde of tekst.</span>
                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 text-lg px-8 py-3 shadow-md">
                        {{ __('Wijzigingen Opslaan') }}
                    </x-primary-button>
                </div>

            </form> {{-- END MAIN FORM --}}

            {{-- 4. ADD NEW COMPONENT FORM --}}
            <div class="p-4 sm:p-8 bg-gray-50 border border-gray-200 rounded-lg mt-8 mb-24">
                <h3 class="font-bold text-gray-700 mb-4">Nieuw Blok Toevoegen</h3>
                <form action="{{ route('dashboard.company.components.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                    @csrf
                    <div class="w-full max-w-xs">
                        <label class="block text-sm text-gray-600 mb-1 font-bold">Type Blok</label>
                        <select name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            <option value="text">Tekstblok (Over ons)</option>
                            <option value="hero">Hero Banner (Grote titel)</option>
                            <option value="featured_ads">Advertentie Grid</option>
                        </select>
                    </div>
                    <x-secondary-button type="submit" class="w-full sm:w-auto justify-center mb-[1px]">
                        + Toevoegen
                    </x-secondary-button>
                </form>
            </div>

        </div>
    </div>

    {{-- HIDDEN DELETE FORMS --}}
    @foreach($company->pageComponents as $component)
        <form id="delete-component-{{ $component->id }}" action="{{ route('dashboard.company.components.destroy', $component) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    {{-- JAVASCRIPT FOR REORDERING (Attached to Window) --}}
    <script>
        // Attach functions to window to ensure they are available globally
        window.moveUp = function(btn) {
            const card = btn.closest('.component-card');
            const prev = card.previousElementSibling;
            if (prev) {
                card.parentNode.insertBefore(card, prev);
            }
        };

        window.moveDown = function(btn) {
            const card = btn.closest('.component-card');
            const next = card.nextElementSibling;
            if (next) {
                card.parentNode.insertBefore(next, card);
            }
        };
    </script>

</x-app-layout>