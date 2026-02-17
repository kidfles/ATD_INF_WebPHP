<x-app-layout>
    {{--
        Pagina: Bedrijfspagina Instellingen
        Doel: Beheer de whitelabel omgeving.
        Functionaliteiten:
        - Aanpassen styling (kleuren, logo).
        - Beheren van pagina-secties (hero, tekst, advertenties).
        - Uploaden van contracten voor goedkeuring.
    --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-extrabold text-slate-800">{{ __('Company Page & Settings') }}</h2>
                <a href="{{ route('company.show', $company->custom_url_slug) }}" target="_blank" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-400 to-teal-500 text-white rounded-full font-bold text-sm shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300 px-5 py-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    {{ __('View Public Page') }}
                </a>
            </div>
            
            {{-- GLOBAL FEEDBACK MESSAGES --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl">
                    <ul class="list-disc pl-5 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl font-medium" 
                     x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    {{ __(session('status')) }}
                </div>
            @endif
            

            {{-- CONTRACT MANAGEMENT --}}
            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8">
                <header>
                    <h2 class="text-lg font-extrabold text-slate-800">{{ __('Collaboration Contract') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ __('To access the API and get the official \'Verified\' status, you must sign our contract.') }}
                    </p>
                </header>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    
                    {{-- Left: Download Section --}}
                    <div class="bg-slate-50 border border-slate-100 p-5 rounded-2xl h-full">
                        <h3 class="font-bold text-slate-800 mb-2">{{ __('Step 1: Download') }}</h3>
                        <p class="text-sm text-slate-500 mb-4">
                            {{ __('Download the contract, print it and sign it.') }}
                        </p>
                        <a href="{{ route('dashboard.company.contract.download') }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full font-bold text-sm hover:bg-emerald-100 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            {{ __('Download PDF') }}
                        </a>
                    </div>

                    {{-- Right: Upload & Status Section --}}
                    <div class="bg-slate-50 border border-slate-100 p-5 rounded-2xl h-full relative">
                        <h3 class="font-bold text-slate-800 mb-2">{{ __('Step 2: Upload') }}</h3>
                        
                        {{-- Status Logic --}}
                        @if(auth()->user()->companyProfile->contract_status === 'approved')
                            <div class="flex items-center bg-emerald-50 border border-emerald-200 text-emerald-700 p-3 rounded-xl mb-4">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <span class="font-bold block">{{ __('Approved!') }}</span>
                                    <span class="text-xs text-emerald-600">{{ __('You have full access.') }}</span>
                                </div>
                            </div>
                        @elseif(auth()->user()->companyProfile->contract_file_path)
                            <div class="flex items-center bg-amber-50 border border-amber-200 text-amber-700 p-3 rounded-xl mb-4">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <span class="font-bold block">{{ __('In Review') }}</span>
                                    <span class="text-xs text-amber-600">{{ __('We are checking your document.') }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 mb-2">{{ __('Upload new version?') }}</p>
                        @else
                            <p class="text-sm text-slate-500 mb-4">{{ __('Upload the signed contract here.') }}</p>
                        @endif

                        {{-- Upload Form --}}
                        @if(auth()->user()->companyProfile->contract_status !== 'approved')
                            <form action="{{ route('dashboard.company.contract.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="file" name="contract_pdf" accept="application/pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-600 hover:file:bg-emerald-100" required>
                                    <button type="submit" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-5 py-2 rounded-full font-bold text-sm shadow-sm hover:shadow-emerald-500/30 transition-all whitespace-nowrap">
                                        {{ __('Upload') }}
                                    </button>
                                </div>
                            </form>

                            {{-- TEST BUTTON (DEVELOPMENT ONLY) --}}
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <p class="text-xs text-slate-300 mb-2 uppercase font-bold">Development Testing</p>
                                <form action="{{ route('dashboard.company.contract.approve_test') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-emerald-500 hover:text-emerald-600 underline font-bold transition">
                                        {{ __('[TEST] Approve my contract properly') }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- API ACCESS --}}
            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8">
                <header>
                    <h2 class="text-lg font-extrabold text-slate-800">{{ __('API Access') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('Use these credentials to push advertisements automatically from your own CRM.') }}</p>
                </header>

                <div class="mt-6">
                    @if(auth()->user()->companyProfile->contract_status !== 'approved')
                        <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl text-sm font-medium">
                            <strong>{{ __('Access blocked') }}:</strong> {{ __('Your contract must be approved first before you can use the API.') }}
                        </div>
                    @else
                        @if (session('api_token'))
                            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl">
                                <p class="text-sm font-bold text-amber-700">{{ __('Keep this token safe! It will not be shown again.') }}</p>
                                <code class="block mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl text-lg font-mono break-all text-emerald-600">
                                    {{ session('api_token') }}
                                </code>
                            </div>
                        @endif

                        <form action="{{ route('dashboard.company.api_token') }}" method="POST">
                            @csrf
                            <p class="text-sm text-slate-500 mb-4">
                                Endpoint: <code class="bg-slate-50 border border-slate-200 px-2 py-1 rounded-xl text-emerald-600 italic font-semibold">{{ url('/api/my-ads') }}</code>
                            </p>
                            <button type="submit" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-6 py-2.5 rounded-full font-bold text-sm shadow-sm hover:shadow-emerald-500/30 transition-all">
                                {{ __('Generate New API Key') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- CSV BULK IMPORT --}}
            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8">
                <header>
                    <h2 class="text-lg font-extrabold text-slate-800">{{ __('CSV Advertisement Import') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('Upload a CSV file to bulk import advertisements.') }}</p>
                </header>

                <div class="mt-6">
                    {{-- Upload Form --}}
                    <form action="{{ route('dashboard.company.import_csv') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="csv_file" class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('CSV File') }}</label>
                            <div class="mt-1 flex items-center gap-4">
                                <input type="file" name="csv_file" id="csv_file" accept=".csv" 
                                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-600 hover:file:bg-emerald-100" 
                                       required>
                                <button type="submit" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-5 py-2.5 rounded-full font-bold text-sm shadow-sm hover:shadow-emerald-500/30 transition-all whitespace-nowrap">
                                    {{ __('Import') }}
                                </button>
                            </div>
                            @error('csv_file')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </form>

                    {{-- Expected format --}}
                    <div class="mt-6 p-5 bg-slate-50 border border-slate-100 rounded-2xl">
                        <h3 class="text-sm font-bold text-slate-700 mb-2">{{ __('Expected CSV format') }}</h3>
                        <p class="text-xs text-slate-400 mb-3">{{ __('The first row must contain headers. Use a comma (,) as separator.') }}</p>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-xs border border-slate-100 rounded-xl overflow-hidden">
                                <thead>
                                    <tr class="border-b border-slate-100 bg-white">
                                        <th class="px-3 py-2 text-left font-bold text-slate-500">title</th>
                                        <th class="px-3 py-2 text-left font-bold text-slate-500">description</th>
                                        <th class="px-3 py-2 text-left font-bold text-slate-500">price</th>
                                        <th class="px-3 py-2 text-left font-bold text-slate-500">type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-slate-100">
                                        <td class="px-3 py-2 text-slate-600">Bureaulamp</td>
                                        <td class="px-3 py-2 text-slate-600">Stijlvolle lamp</td>
                                        <td class="px-3 py-2 text-slate-600">24.99</td>
                                        <td class="px-3 py-2"><span class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-2 py-0.5 rounded-full text-xs font-bold">sell</span></td>
                                    </tr>
                                    <tr class="border-t border-slate-100">
                                        <td class="px-3 py-2 text-slate-600">Boormachine</td>
                                        <td class="px-3 py-2 text-slate-600">Voor huur beschikbaar</td>
                                        <td class="px-3 py-2 text-slate-600">15.00</td>
                                        <td class="px-3 py-2"><span class="bg-teal-50 text-teal-600 border border-teal-200 px-2 py-0.5 rounded-full text-xs font-bold">rent</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-slate-400 mt-3">
                            {{ __('Allowed types') }}: <code class="bg-white border border-slate-200 px-1.5 rounded-lg text-slate-600 font-semibold">sell</code>, <code class="bg-white border border-slate-200 px-1.5 rounded-lg text-slate-600 font-semibold">rent</code>, <code class="bg-white border border-slate-200 px-1.5 rounded-lg text-slate-600 font-semibold">auction</code>.
                            {{ __('Max 4 ads per type.') }}
                        </p>
                        <a href="{{ asset('storage/example_ads.csv') }}" download class="inline-flex items-center mt-3 text-sm text-emerald-500 hover:text-emerald-600 font-bold transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            {{ __('Download Example CSV') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- MAIN FORM: BRANDING & PAGE BUILDER --}}
            <form method="post" action="{{ route('dashboard.company.settings.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                {{-- CARD: BRANDING --}}
                <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8">
                    <header>
                        <h2 class="text-lg font-extrabold text-slate-800">{{ __('Branding & URL') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Adjust your colors and web address here.') }}</p>
                    </header>

                    <div class="mt-6 space-y-6 max-w-xl">
                        
                        {{-- Brand Color Input --}}
                        <div class="space-y-2">
                            <label for="brand_color" class="block text-sm font-bold text-slate-700">{{ __('Brand Color (Hex Code)') }}</label>
                            <div class="flex items-center gap-4 mt-1">
                                <input type="color" 
                                       id="color_picker_visual" 
                                       value="{{ old('brand_color', $company->brand_color ?? '#000000') }}"
                                       oninput="document.getElementById('brand_color_input').value = this.value"
                                       class="h-10 w-20 p-1 rounded-xl border border-slate-200 cursor-pointer bg-white">
                                
                                <input id="brand_color_input"
                                    name="brand_color" 
                                    type="text" 
                                    class="block w-full bg-slate-50 border-transparent rounded-2xl px-4 py-2.5 text-sm text-slate-700 uppercase focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all" 
                                    value="{{ old('brand_color', $company->brand_color ?? '#000000') }}" 
                                    maxlength="7"
                                    oninput="document.getElementById('color_picker_visual').value = this.value">
                            </div>
                        </div>

                        {{-- URL Slug --}}
                        <div class="space-y-2">
                            <label for="custom_url_slug" class="block text-sm font-bold text-slate-700">{{ __('Public URL') }}</label>
                            <div class="flex items-center mt-1">
                                <span class="text-slate-400 bg-slate-50 border border-r-0 border-slate-200 rounded-l-2xl px-3 py-2.5 text-sm font-medium">
                                    {{ config('app.url') }}/company/
                                </span>
                                <input name="custom_url_slug" type="text" class="block w-full bg-slate-50 border-transparent rounded-r-2xl px-4 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all" value="{{ old('custom_url_slug', $company->custom_url_slug) }}">
                            </div>
                        </div>

                        {{-- KVK Number --}}
                        <div class="space-y-2">
                            <label for="kvk_number" class="block text-sm font-bold text-slate-700">{{ __('Chamber of Commerce Number') }}</label>
                            <input name="kvk_number" type="text" class="block w-full bg-slate-50 border-transparent rounded-2xl px-4 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all mt-1" value="{{ old('kvk_number', $company->kvk_number) }}">
                        </div>
                    </div>
                </div>

                {{-- CARD: RETURN POLICY --}}
                <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8">
                    <header>
                        <h2 class="text-lg font-extrabold text-slate-800">{{ __('Return Policy & Wear/Tear') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Define how costs are calculated when a customer returns a rented item.') }}</p>
                    </header>

                    <div class="mt-6 space-y-6 max-w-xl">
                        
                        @if($company->contract_status === 'approved')
                            <div x-data="{ policy: '{{ old('wear_and_tear_policy', $company->wear_and_tear_policy ?? 'none') }}' }">
                                {{-- Policy Type --}}
                                <div class="space-y-2">
                                    <label for="wear_and_tear_policy" class="block text-sm font-bold text-slate-700">{{ __('Wear & Tear Fee Policy') }}</label>
                                    <select id="wear_and_tear_policy" name="wear_and_tear_policy" x-model="policy" class="block w-full bg-slate-50 border-transparent rounded-2xl px-4 py-2.5 text-sm text-slate-600 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50">
                                        <option value="none">{{ __('None (No extra fee)') }}</option>
                                        <option value="fixed">{{ __('Fixed Fee (e.g. Cleaning Cost)') }}</option>
                                        <option value="percentage">{{ __('Percentage of Total Price') }}</option>
                                    </select>
                                </div>

                                {{-- Policy Value (Conditional) --}}
                                <div class="space-y-2 mt-4" x-show="policy !== 'none'" x-transition>
                                    <label for="wear_and_tear_value" class="block text-sm font-bold text-slate-700">{{ __('Fee Value') }}</label>
                                    <div class="relative rounded-2xl shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-slate-400 sm:text-sm font-bold" x-text="policy === 'fixed' ? '€' : '%'"></span>
                                        </div>
                                        <input type="number" step="0.01" name="wear_and_tear_value" id="wear_and_tear_value" 
                                            value="{{ old('wear_and_tear_value', $company->wear_and_tear_value) }}"
                                            class="block w-full bg-slate-50 border-transparent rounded-2xl pl-10 px-4 py-2.5 text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all" 
                                            placeholder="0.00">
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1" x-text="policy === 'fixed' ? '{{ __('Fixed amount added to the return cost.') }}' : '{{ __('Percentage calculated from the base rental price.') }}'"></p>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl flex items-start gap-3">
                                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <div>
                                    <strong class="block text-sm">{{ __('Functionality Locked') }}</strong>
                                    <span class="text-sm opacity-90">{{ __('You can only set a return policy once your contract has been approved.') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- CARD: PAGE BUILDER --}}
                <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8">
                    <header class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-800">{{ __('Page Layout') }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ __('Manage and sort blocks. Drag & Drop not supported, use arrows.') }}</p>
                        </div>
                    </header>

                    {{-- COMPONENTS LIST --}}
                    <div class="space-y-4 mb-6" id="components-list">
                        @forelse($company->pageComponents as $component)
                            <div class="component-card bg-slate-50 border border-slate-100 rounded-2xl p-4 relative group transition duration-300 ease-in-out hover:border-emerald-200 hover:shadow-sm">
                                
                                {{-- HIDDEN INPUT FOR SORT ORDER --}}
                                <input type="hidden" name="ordered_ids[]" value="{{ $component->id }}">
                                
                                <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                                    <div class="flex items-center gap-3">
                                        
                                        {{-- SORT BUTTONS --}}
                                        <div class="flex flex-col gap-1">
                                            <button type="button" onclick="window.moveUp(this)" class="p-1 hover:bg-emerald-50 rounded-lg text-slate-400 hover:text-emerald-600 transition" title="{{ __('Move Up') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                            </button>
                                            <button type="button" onclick="window.moveDown(this)" class="p-1 hover:bg-emerald-50 rounded-lg text-slate-400 hover:text-emerald-600 transition" title="{{ __('Move Down') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>
                                        </div>

                                        <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-2.5 py-1 rounded-full text-xs uppercase font-bold tracking-wide">
                                            {{ $component->component_type }}
                                        </span>
                                    </div>
                                    
                                    <button type="button" onclick="if(confirm('{{ __('Are you sure you want to remove this block?') }}')) document.getElementById('delete-component-{{ $component->id }}').submit()" class="text-red-400 text-sm hover:text-red-600 font-bold transition">
                                        {{ __('Remove') }}
                                    </button>
                                </div>

                                {{-- COMPONENT INPUTS --}}
                                <div class="space-y-3 pl-8">
                                    @if($component->component_type === 'hero')
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">{{ __('Title') }}</label>
                                            <input type="text" name="components[{{ $component->id }}][content][title]" value="{{ $component->content['title'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">{{ __('Subtitle') }}</label>
                                            <input type="text" name="components[{{ $component->id }}][content][subtitle]" value="{{ $component->content['subtitle'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all">
                                        </div>
                                        
                                    @elseif($component->component_type === 'text')
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">{{ __('Heading') }}</label>
                                            <input type="text" name="components[{{ $component->id }}][content][heading]" value="{{ $component->content['heading'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-sm text-slate-700 font-bold focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">{{ __('Content') }}</label>
                                            <textarea name="components[{{ $component->id }}][content][body]" rows="4" class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all">{{ $component->content['body'] ?? '' }}</textarea>
                                        </div>
                                        
                                    @elseif($component->component_type === 'featured_ads')
                                        <div class="bg-emerald-50 border border-emerald-200 p-3 rounded-xl">
                                            <p class="text-sm text-emerald-700 italic flex items-center font-medium">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ __('This block automatically shows your most recent advertisements.') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 border-2 border-dashed border-slate-200 rounded-2xl">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <h3 class="mt-2 text-sm font-bold text-slate-600">{{ __('No blocks added') }}</h3>
                                <p class="mt-1 text-sm text-slate-400">{{ __('Start adding content blocks below.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- STICKY SAVE BUTTON BAR --}}
                <div class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-xl border-t border-slate-100 p-4 flex justify-end items-center gap-4 z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] sm:pl-64">
                    <span class="text-sm text-slate-400 hidden sm:inline italic">{{ __('Don\'t forget to save your changes!') }}</span>
                    <button type="submit" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-8 py-3 rounded-full font-bold text-sm shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300">
                        {{ __('Save Changes') }}
                    </button>
                </div>

            </form> {{-- END MAIN FORM --}}

            {{-- ADD NEW COMPONENT FORM --}}
            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8 mt-8 mb-24">
                <h3 class="font-extrabold text-slate-800 mb-4">{{ __('Add New Block') }}</h3>
                <form action="{{ route('dashboard.company.components.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                    @csrf
                    <div class="w-full max-w-xs">
                        <label class="block text-sm text-slate-500 mb-1 font-bold">{{ __('Block Type') }}</label>
                        <select name="type" class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-2.5 text-sm text-slate-600 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50">
                            <option value="text">{{ __('Text Block (About Us)') }}</option>
                            <option value="hero">{{ __('Hero Banner (Large Title)') }}</option>
                            <option value="featured_ads">{{ __('Advertisement Grid') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-slate-50 text-slate-600 border border-slate-200 px-5 py-2.5 rounded-full font-bold text-sm hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-600 transition-all">
                        + {{ __('Add') }}
                    </button>
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

    {{-- JAVASCRIPT FOR REORDERING --}}
    <script>
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