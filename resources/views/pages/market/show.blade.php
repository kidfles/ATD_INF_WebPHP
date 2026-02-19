<x-app-layout :hideSidebar="true">
    {{--
        Pagina: Advertentie Detail (Publiek)
        Doel: Volledige weergave van een advertentie voor potentiële kopers.
        Bevat: Foto's, beschrijving, prijs, verkoper info en actieknoppen (kopen/bieden/huren).
    --}}
    <style>
        :root {
            --seller-color: {{ $brandColor ?? '#059669' }};
        }
        .text-seller { color: var(--seller-color); }
        .bg-seller { background-color: var(--seller-color); }
        .bg-seller:hover { filter: brightness(90%); }
    </style>
    
    {{-- Flatpickr for Rental Calendar --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_green.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6 flex items-center justify-between">
                {{-- Terug naar Marktplaats link (met behoud van filters indien aanwezig) --}}
                <a href="{{ session()->has('ad_filters') ? route('market.index', session('ad_filters')) : route('market.index') }}" 
                   class="text-slate-400 hover:text-slate-600 flex items-center transition font-bold">
                    &larr; {{ __('Back to market') }}
                </a>
                
                {{-- Knop naar de whitelabel bedrijfspagina (indien zakelijke adverteerder) --}}
                @if($advertisement->user->companyProfile && $advertisement->user->companyProfile->custom_url_slug)
                    <a href="{{ route('company.show', $advertisement->user->companyProfile->custom_url_slug) }}" 
                       class="text-sm font-bold px-4 py-2 rounded-full bg-white border border-slate-200 hover:text-white transition shadow-sm flex items-center gap-2 group/store"
                       style="border-color: {{ $brandColor }}; color: {{ $brandColor }};">
                        <span class="group-hover/store:text-white transition-colors duration-200">{{ __('Visit Store') }}</span>
                        <span class="group-hover/store:text-white transition-colors duration-200">&rarr;</span>
                        <style>
                            .group\/store:hover {
                                background-color: {{ $brandColor }} !important;
                                color: white !important;
                            }
                        </style>
                    </a>
                @endif
            </div>

            {{-- Hoofdsectie van de Advertentie --}}
            <div class="bg-white overflow-hidden shadow-soft rounded-[2rem] border-2 grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8"
                 style="border-color: {{ $brandColor ?? '#e2e8f0' }};">
                
                {{-- Afbeelding Sectie --}}
                <div class="relative bg-slate-50 rounded-2xl overflow-hidden h-96 border-2 shadow-inner group"
                     style="border-color: {{ $brandColor ?? '#e2e8f0' }};">
                    
                    @if($advertisement->image_path)
                        <img src="{{ asset('storage/' . $advertisement->image_path) }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                    @else
                        <img src="{{ asset('images/placeholder.svg') }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-500 bg-slate-50">
                    @endif

                    {{-- Type Label (Huur, Verkoop, Veiling) --}}
                    <span class="absolute top-4 left-4 z-10 bg-white/95 backdrop-blur px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider shadow-sm border"
                          style="color: {{ $brandColor }}; border-color: {{ $brandColor }};">
                        {{ __(ucfirst($advertisement->type)) }}
                    </span>
                </div>

                {{-- Informatie Sectie --}}
                <div class="flex flex-col justify-center">
                    <div class="flex items-center justify-between mb-2">
                        <h1 class="text-4xl font-extrabold text-slate-800 leading-tight">{{ $advertisement->title }}</h1>
                        {{-- Favorieten Knop --}}
                        @auth
                            <form action="{{ route('favorites.toggle', $advertisement) }}" method="POST">
                                @csrf
                                <button type="submit" class="group p-2 rounded-full bg-slate-50 hover:bg-slate-100 transition">
                                    @if(auth()->user()->favorites()->whereKey($advertisement->id)->exists())
                                        {{-- Gevulde Ster (Favoriet) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @else
                                        {{-- Lege Ster (Geen Favoriet) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300 group-hover:text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        @endauth
                    </div>
                    
                    {{-- Verkopersinformatie --}}
                    <div class="flex items-center gap-2 mb-6 text-sm">
                        <span class="text-slate-400">{{ __('Sold by') }}</span>
                        @if($advertisement->user->isBusinessAdvertiser() && $advertisement->user->companyProfile && $advertisement->user->companyProfile->custom_url_slug)
                            <a href="{{ route('company.show', $advertisement->user->companyProfile->custom_url_slug) }}" 
                               class="font-bold border-b-2 border-transparent transition hover:border-current"
                               style="color: {{ $brandColor }}">
                                {{ $advertisement->user->companyProfile->company_name ?? $advertisement->user->name }}
                            </a>
                        @else
                            <a href="{{ route('seller.show', $advertisement->user) }}" 
                               class="font-bold border-b-2 border-transparent transition hover:border-current"
                               style="color: {{ $brandColor }}">
                                {{ $advertisement->user->name }}
                            </a>
                        @endif
                    </div>

                    <p class="text-slate-500 mb-8 leading-relaxed text-lg">
                        {{ $advertisement->description }}
                    </p>

                    <div class="border-t border-slate-100 pt-8 mt-auto">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-slate-400 font-bold uppercase tracking-wide text-sm">{{ __('Current Price') }}</span>
                            <span class="text-4xl font-black" style="color: {{ $brandColor }}">
                                €{{ number_format($advertisement->price, 2) }}
                            </span>
                        </div>

                        {{-- Actie Sectie (Bieden, Huren of Kopen) --}}
                        @if(auth()->id() === $advertisement->user_id)
                            <div class="bg-slate-50 p-4 rounded-2xl text-center border border-slate-200">
                                <span class="text-slate-500">{{ __('This is your own advertisement.') }}</span>
                                <a href="{{ route('dashboard.advertisements.edit', $advertisement) }}" class="ml-2 font-bold hover:underline" style="color: {{ $brandColor }}">{{ __('Edit Advertisement') }}</a>
                            </div>
                        
                        @else
                            
                            {{-- VEILING LOGICA --}}
                            @if($advertisement->type === \App\Enums\AdvertisementType::Auction)
                                <div class="bg-slate-50 p-5 rounded-2xl border-l-4 shadow-sm" style="border-color: {{ $brandColor }}">
                                    <h3 class="font-extrabold text-lg mb-2 text-slate-800">{{ __('Place a bid') }}</h3>
                                    
                                    {{-- Auction End Date --}}
                                    @if($advertisement->expires_at)
                                        <p class="text-sm text-slate-500 mb-4">
                                            @if($advertisement->expires_at->isPast())
                                                {{ __('This auction has ended.') }}
                                            @else
                                                @php $daysLeft = now()->startOfDay()->diffInDays($advertisement->expires_at->startOfDay()); @endphp
                                                {{ __('Bidding ends') }}: {{ $advertisement->expires_at->translatedFormat('d M Y') }}
                                                ({{ $daysLeft }} {{ trans_choice('day|days', $daysLeft) }})
                                            @endif
                                        </p>
                                    @endif

                                    <p class="text-sm text-slate-500 mb-4">
                                        {{ __('Highest bid') }}: <span class="font-bold text-slate-800">€ {{ number_format($advertisement->bids->max('amount') ?? $advertisement->price, 2) }}</span>
                                    </p>
                                    
                                    <form action="{{ route('bids.store', $advertisement) }}" method="POST" class="flex gap-3">
                                        @csrf
                                        <div class="relative flex-grow">
                                            <span class="absolute left-3 top-3 text-slate-400">€</span>
                                            <input type="number" name="amount" step="0.01" required 
                                                   class="pl-7 rounded-xl border-slate-200 w-full bg-white focus:ring-2 focus:border-emerald-400 focus:ring-emerald-100/50"
                                                   placeholder="{{ __('Amount') }}">
                                        </div>
                                        <button type="submit" class="text-white font-bold py-3 px-6 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-0.5"
                                                style="background-color: {{ $brandColor }}">
                                            {{ __('Bid') }}
                                        </button>
                                    </form>
                                    @error('amount') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror

                                    @if($advertisement->bids->count() > 0)
                                        <div class="mt-4 pt-4 border-t border-slate-200">
                                            <span class="text-xs font-bold text-slate-400 uppercase">{{ __('Recent Bids') }}</span>
                                            <div class="mt-2 space-y-1">
                                                @foreach($advertisement->bids->sortByDesc('amount')->take(3) as $bid)
                                                    <div class="flex justify-between text-sm">
                                                        <span class="text-slate-500">{{ $bid->user->name }}</span>
                                                        <span class="font-mono font-bold text-slate-800">€{{ number_format($bid->amount, 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            {{-- HUUR LOGICA --}}
                            @elseif($advertisement->type === \App\Enums\AdvertisementType::Rent)
                                <div class="bg-slate-50 p-5 rounded-2xl border-l-4 shadow-sm" style="border-color: {{ $brandColor }}">
                                    <h3 class="font-extrabold text-lg mb-4 text-slate-800">{{ __('Rent this item') }}</h3>
                                    <form action="{{ route('rentals.store', $advertisement) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">{{ __('Start Date') }}</label>
                                                <input type="text" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="w-full border-slate-200 rounded-xl text-sm bg-white focus:ring-2 focus:border-emerald-400 focus:ring-emerald-100/50" placeholder="{{ __('Select start date') }}">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">{{ __('End Date') }}</label>
                                                <input type="text" id="end_date" name="end_date" value="{{ old('end_date') }}" required class="w-full border-slate-200 rounded-xl text-sm bg-white focus:ring-2 focus:border-emerald-400 focus:ring-emerald-100/50" placeholder="{{ __('Select end date') }}">
                                                @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="bg-white/50 rounded-xl p-4 border border-slate-200/50 mb-4 hidden" id="price-estimate">
                                            <div class="flex justify-between items-center text-sm font-bold text-slate-500 mb-1">
                                                <span>{{ __('Estimated Total') }}</span>
                                                <span class="text-lg text-slate-800" id="estimated-total">€0.00</span>
                                            </div>
                                            <p class="text-[10px] text-slate-400 italic">{{ __('Final cost may include wear & tear or late fees.') }}</p>
                                        </div>
                                         @error('start_date') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                        <button type="submit" class="w-full text-white font-bold py-3 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-0.5"
                                                style="background-color: {{ $brandColor }}">
                                            {{ __('Place Reservation') }}
                                        </button>
                                    </form>
                                </div>

                            {{-- VERKOOP LOGICA --}}
                            @else
                                <div class="mt-6">
                                    @if($advertisement->is_sold)
                                        <div class="w-full bg-slate-200 text-slate-500 text-center py-3 rounded-full font-bold cursor-not-allowed">
                                            {{ __('Sold') }}
                                        </div>
                                    @else
                                        {{-- Direct Kopen (Order aanmaken) --}}
                                        <form action="{{ route('orders.store', $advertisement) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('{{ __('Are you sure you want to buy this for') }} €{{ $advertisement->price }}?')"
                                                    class="w-full text-white font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 text-xl flex items-center justify-center gap-3"
                                                    style="background-color: {{ $brandColor }}">
                                                <span>{{ __('Buy now for') }} €{{ number_format($advertisement->price, 2) }}</span>
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif

                        @endif
                    </div>

                    {{-- QR Code Section --}}
                    <div class="mt-8 pt-8 border-t border-slate-100 flex flex-col items-center">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">{{ __('Share this Ad') }}</h3>

                        <div class="bg-white p-2 rounded-xl shadow-sm border border-slate-100">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->color(5, 150, 105)->generate(route('market.show', $advertisement)) !!}
                        </div>

                        <p class="text-xs text-slate-400 mt-2">{{ __('Scan to open on mobile') }}</p>
                    </div>
                </div>
            </div>
            </div>

            {{-- Gerelateerde Producten (Cross-Sell / Upsell) --}}
            @if($advertisement->relatedAds->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-2xl font-extrabold text-slate-800 mb-6">{{ __('Related Products') }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($advertisement->relatedAds as $related)
                            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-soft-lg">
                                {{-- Afbeelding --}}
                                <div class="w-full overflow-hidden bg-slate-50 relative group aspect-video">
                                    @if($related->image_path)
                                        <img src="{{ asset('storage/' . $related->image_path) }}" alt="{{ $related->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <img src="{{ asset('images/placeholder.svg') }}" alt="{{ __('No image available') }}" class="w-full h-full object-cover text-slate-400">
                                    @endif
                                </div>
                                <div class="p-5">
                                    {{-- Titel --}}
                                    <a href="{{ route('market.show', $related) }}">
                                        <h3 class="font-extrabold text-lg text-emerald-600 hover:text-emerald-700 transition-colors">{{ $related->title }}</h3>
                                    </a>
                                    <p class="text-slate-500 truncate mt-1">{{ $related->description }}</p>
                                    {{-- Prijs en type label --}}
                                    <div class="flex justify-between items-center mt-4">
                                        <p class="font-extrabold text-lg text-slate-800">€ {{ number_format($related->price, 2) }}</p>
                                        <span class="bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-full text-xs font-bold text-slate-500">{{ __(ucfirst($related->type)) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-12 bg-white p-6 rounded-[2rem] shadow-soft border border-slate-100">
                <h2 class="text-2xl font-extrabold text-slate-800 mb-6">{{ __('Reviews') }}</h2>

                {{-- 1. List Existing Reviews --}}
                @forelse($advertisement->reviews as $review)
                    <div class="mb-6 border-b border-slate-100 pb-4 last:border-0">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-700">{{ $review->reviewer->name ?? 'Gebruiker' }}</span>
                            <div class="flex text-yellow-400">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < $review->rating) ★ @else ☆ @endif
                                @endfor
                            </div>
                        </div>
                        <p class="text-slate-500">{{ $review->comment }}</p>
                        <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-slate-400 italic">{{ __('No reviews yet. Be the first!') }}</p>
                @endforelse

                {{-- 2. Review Form (Conditional: Only for Renters) --}}
                @auth
                    @php
                        $hasRented = auth()->user()->rentals()->where('advertisement_id', $advertisement->id)->exists();
                    @endphp

                    @if($hasRented)
                        <div class="mt-8 pt-8 border-t border-slate-100">
                            <h3 class="font-extrabold text-lg text-slate-800 mb-4">{{ __('Write a review') }}</h3>
                            
                            <form action="{{ route('reviews.store', $advertisement) }}" method="POST">
                                @csrf
                                
                                {{-- Rating Input --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-slate-600 mb-1">{{ __('Rating') }}</label>
                                    <select name="rating" class="bg-slate-50 border-transparent rounded-xl text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 block w-full sm:w-1/4">
                                        <option value="5">5 - {{ __('Excellent') }}</option>
                                        <option value="4">4 - {{ __('Good') }}</option>
                                        <option value="3">3 - {{ __('Average') }}</option>
                                        <option value="2">2 - {{ __('Fair') }}</option>
                                        <option value="1">1 - {{ __('Poor') }}</option>
                                    </select>
                                </div>

                                {{-- Comment Input --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-slate-600 mb-1">{{ __('Your experience') }}</label>
                                    <textarea name="comment" rows="3" class="w-full bg-slate-50 border-transparent rounded-xl text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50" placeholder="{{ __('Tell us what you thought...') }}"></textarea>
                                </div>

                                <button type="submit" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-6 py-2.5 rounded-full font-bold text-sm shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300">
                                    {{ __('Post Review') }}
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
    </div>

    {{-- Flatpickr Initialization --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Haal geboekte datums op uit de backend
            const rentals = @json($rentals ?? []);
            
            // Format voor Flatpickr disable config
            const disabledDates = rentals.map(rental => {
                return {
                    from: rental.start_date,
                    to: rental.end_date
                };
            });

            const commonConfig = {
                dateFormat: "Y-m-d",
                minDate: "today",
                disable: disabledDates,
                locale: {
                    firstDayOfWeek: 1 // Maandag start
                }
            };

            const pricePerDay = {{ $advertisement->price }};
            const wtPolicy = '{{ $advertisement->user?->companyProfile?->wear_and_tear_policy ?? 'none' }}';
            const wtValue = {{ $advertisement->user?->companyProfile?->wear_and_tear_value ?? 0 }};

            function updateEstimatedPrice() {
                const startStr = document.getElementById('start_date').value;
                const endStr = document.getElementById('end_date').value;
                const estimateDiv = document.getElementById('price-estimate');
                const totalEl = document.getElementById('estimated-total');

                if (startStr && endStr) {
                    const start = new Date(startStr);
                    const end = new Date(endStr);

                    if (end >= start) {
                        const diffTime = Math.abs(end - start);
                        let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        if (diffDays === 0) diffDays = 1; 

                        let basePrice = diffDays * pricePerDay;
                        let wearAndTear = 0;

                        if (wtPolicy === 'fixed') {
                            wearAndTear = parseFloat(wtValue);
                        } else if (wtPolicy === 'percentage') {
                            wearAndTear = basePrice * (parseFloat(wtValue) / 100);
                        }

                        const total = basePrice + wearAndTear;
                        totalEl.innerText = '€' + total.toFixed(2);
                        estimateDiv.classList.remove('hidden');
                    } else {
                        estimateDiv.classList.add('hidden');
                    }
                } else {
                    estimateDiv.classList.add('hidden');
                }
            }

            // Init Start Date
            const fpStart = flatpickr("#start_date", {
                ...commonConfig,
                onChange: function(selectedDates, dateStr, instance) {
                    fpEnd.set('minDate', dateStr);
                    updateEstimatedPrice();
                }
            });

            // Init End Date
            const fpEnd = flatpickr("#end_date", {
                ...commonConfig,
                onChange: function(selectedDates, dateStr, instance) {
                    updateEstimatedPrice();
                }
            });
        });
    </script>
</x-app-layout>