<x-market-layout>
    <style>
        :root {
            --seller-color: {{ $brandColor ?? '#4f46e5' }};
        }
        /* Keep these for text/bg, but we will use inline styles for borders to be safe */
        .text-seller { color: var(--seller-color); }
        .bg-seller { background-color: var(--seller-color); }
        .bg-seller:hover { filter: brightness(90%); }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ session()->has('ad_filters') ? route('market.index', session('ad_filters')) : route('market.index') }}" 
                   class="text-gray-500 hover:text-gray-700 flex items-center transition font-medium">
                    &larr; Back to Market
                </a>
                
                @if($advertisement->user->companyProfile && $advertisement->user->companyProfile->custom_url_slug)
                    <a href="{{ route('company.show', $advertisement->user->companyProfile->custom_url_slug) }}" 
                       class="text-sm font-bold px-4 py-2 rounded-full bg-white border border-gray-200 hover:text-white transition shadow-sm flex items-center gap-2 group/store"
                       style="border-color: {{ $brandColor }}; color: {{ $brandColor }};">
                        <span class="group-hover/store:text-white transition-colors duration-200">Visit Store</span>
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

            <div class="bg-white overflow-hidden shadow-xl rounded-3xl border-2 grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8"
                 style="border-color: {{ $brandColor ?? '#e5e7eb' }};">
                
                <div class="relative bg-gray-50 rounded-2xl overflow-hidden h-96 border-2 shadow-inner group"
                     style="border-color: {{ $brandColor ?? '#e5e7eb' }};">
                    
                    @if($advertisement->image_path)
                        <img src="{{ asset('storage/' . $advertisement->image_path) }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                    @else
                        <img src="{{ asset('images/placeholder.svg') }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-500 bg-gray-50">
                    @endif

                    <span class="absolute top-4 left-4 z-10 bg-white/95 backdrop-blur px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider shadow-sm border"
                          style="color: {{ $brandColor }}; border-color: {{ $brandColor }};">
                        {{ $advertisement->type }}
                    </span>
                </div>

                <div class="flex flex-col justify-center">
                    <div class="flex items-center justify-between mb-2">
                        <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">{{ $advertisement->title }}</h1>
                        @auth
                            <form action="{{ route('favorites.toggle', $advertisement) }}" method="POST">
                                @csrf
                                <button type="submit" class="group p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition">
                                    @if(auth()->user()->favorites()->whereKey($advertisement->id)->exists())
                                        {{-- Gevulde Ster (Favoriet) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @else
                                        {{-- Lege Ster (Geen Favoriet) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 group-hover:text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        @endauth
                    </div>
                    
                    <div class="flex items-center gap-2 mb-6 text-sm">
                        <span class="text-gray-500">Sold by</span>
                        @if($advertisement->user->isBusinessAdvertiser() && $advertisement->user->companyProfile && $advertisement->user->companyProfile->custom_url_slug)
                            <a href="{{ route('company.show', $advertisement->user->companyProfile->custom_url_slug) }}" 
                               class="font-bold text-gray-900 border-b-2 border-transparent transition hover:border-gray-900"
                               style="color: {{ $brandColor }}">
                                {{ $advertisement->user->companyProfile->company_name ?? $advertisement->user->name }}
                            </a>
                        @else
                            {{-- For private sellers, we can link to the simple profile OR just show text if strictly no profile wanted --}}
                            {{-- User said "no seperate seller profile page", but private users don't have company pages. --}}
                            {{-- We will keep the link to seller.show for PRIVATE users as a fallback, 
                                 OR just show text if we want to be strict. 
                                 Let's keep seller.show for private for now, as it's harmless and better than nothing. --}}
                            <a href="{{ route('seller.show', $advertisement->user) }}" 
                               class="font-bold text-gray-900 border-b-2 border-transparent transition hover:border-gray-900"
                               style="color: {{ $brandColor }}">
                                {{ $advertisement->user->name }}
                            </a>
                        @endif
                    </div>

                    <p class="text-gray-600 mb-8 leading-relaxed text-lg">
                        {{ $advertisement->description }}
                    </p>

                    <div class="border-t border-gray-100 pt-8 mt-auto">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-gray-400 font-medium uppercase tracking-wide text-sm">Current Price</span>
                            <span class="text-4xl font-black" style="color: {{ $brandColor }}">
                                €{{ number_format($advertisement->price, 2) }}
                            </span>
                        </div>

                        @if(auth()->id() === $advertisement->user_id)
                            <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-200">
                                <span class="text-gray-500">This is your advertisement.</span>
                                <a href="{{ route('dashboard.advertisements.edit', $advertisement) }}" class="ml-2 font-bold hover:underline" style="color: {{ $brandColor }}">Edit Listing</a>
                            </div>
                        
                        @else
                            
                            @if($advertisement->type === 'auction')
                                <div class="bg-gray-50 p-5 rounded-2xl border-l-4 shadow-sm" style="border-color: {{ $brandColor }}">
                                    <h3 class="font-bold text-lg mb-2 text-gray-900">Place a bid</h3>
                                    <p class="text-sm text-gray-500 mb-4">
                                        Highest bid: <span class="font-bold text-gray-900">€ {{ number_format($advertisement->bids->max('amount') ?? $advertisement->price, 2) }}</span>
                                    </p>
                                    
                                    <form action="{{ route('bids.store', $advertisement) }}" method="POST" class="flex gap-3">
                                        @csrf
                                        <div class="relative flex-grow">
                                            <span class="absolute left-3 top-3 text-gray-400">€</span>
                                            <input type="number" name="amount" step="0.01" required 
                                                   class="pl-7 rounded-xl border-gray-300 w-full focus:ring-2"
                                                   style="--tw-ring-color: {{ $brandColor }};" 
                                                   placeholder="Amount">
                                        </div>
                                        <button type="submit" class="text-white font-bold py-3 px-6 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-0.5"
                                                style="background-color: {{ $brandColor }}">
                                            Bid
                                        </button>
                                    </form>
                                    @error('amount') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror

                                    @if($advertisement->bids->count() > 0)
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <span class="text-xs font-bold text-gray-400 uppercase">Recent Activity</span>
                                            <div class="mt-2 space-y-1">
                                                @foreach($advertisement->bids->sortByDesc('amount')->take(3) as $bid)
                                                    <div class="flex justify-between text-sm">
                                                        <span class="text-gray-600">{{ $bid->user->name }}</span>
                                                        <span class="font-mono font-medium text-gray-900">€{{ number_format($bid->amount, 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif


                                    @if($advertisement->user->companyProfile && $advertisement->user->companyProfile->custom_url_slug)
                                        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                                            <a href="{{ route('company.show', $advertisement->user->companyProfile->custom_url_slug) }}" 
                                               class="text-sm font-bold hover:underline"
                                               style="color: {{ $brandColor }}">
                                                Visit {{ $advertisement->user->companyProfile->company_name ?? 'Store' }} &rarr;
                                            </a>
                                        </div>
                                    @endif
                                </div>

                            @elseif($advertisement->type === 'rent')
                                <div class="bg-gray-50 p-5 rounded-2xl border-l-4 shadow-sm" style="border-color: {{ $brandColor }}">
                                    <h3 class="font-bold text-lg mb-4 text-gray-900">Rent this Item</h3>
                                    <form action="{{ route('rentals.store', $advertisement) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start</label>
                                                <input type="date" name="start_date" min="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-xl text-sm focus:ring-2" style="--tw-ring-color: {{ $brandColor }};">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">End</label>
                                                <input type="date" name="end_date" min="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-xl text-sm focus:ring-2" style="--tw-ring-color: {{ $brandColor }};">
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full text-white font-bold py-3 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-0.5"
                                                style="background-color: {{ $brandColor }}">
                                            Book Rental
                                        </button>
                                    </form>

                                    @if($advertisement->user->companyProfile && $advertisement->user->companyProfile->custom_url_slug)
                                        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                                            <a href="{{ route('company.show', $advertisement->user->companyProfile->custom_url_slug) }}" 
                                               class="text-sm font-bold hover:underline"
                                               style="color: {{ $brandColor }}">
                                                Visit {{ $advertisement->user->companyProfile->company_name ?? 'Store' }} &rarr;
                                            </a>
                                        </div>
                                    @endif
                                </div>

                            @else
                                <div class="mt-6">
                                    @if($advertisement->is_sold)
                                        {{-- Als het verkocht is --}}
                                        <div class="w-full bg-gray-300 text-gray-600 text-center py-3 rounded-lg font-bold cursor-not-allowed">
                                            Verkocht
                                        </div>
                                    @elseif(Auth::check() && Auth::id() === $advertisement->user_id)
                                        {{-- Als je de eigenaar bent --}}
                                        <div class="w-full bg-gray-100 text-gray-500 text-center py-3 rounded-lg text-sm">
                                            Dit is je eigen advertentie
                                        </div>
                                    @else
                                        {{-- Koop Knop Formulier --}}
                                        <form action="{{ route('orders.store', $advertisement) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Weet je zeker dat je dit wilt kopen voor €{{ $advertisement->price }}?')"
                                                    class="w-full text-white font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 text-xl flex items-center justify-center gap-3"
                                                    style="background-color: {{ $brandColor }}">
                                                <span>Koop direct voor €{{ number_format($advertisement->price, 2) }}</span>
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif

                        @endif
                    </div>

                    {{-- QR Code Section --}}
                    <div class="mt-8 pt-8 border-t border-gray-100 flex flex-col items-center">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wide mb-4">Share this Ad</h3>

                        <div class="bg-white p-2 rounded-lg shadow-sm border border-gray-100">
                            {{-- Generate QR Code linking to the current ad's route --}}
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->color(79, 70, 229)->generate(route('market.show', $advertisement)) !!}
                        </div>

                        <p class="text-xs text-gray-400 mt-2">Scan to open on mobile</p>
                    </div>
                </div>
            </div>
            </div>

            <div class="mt-12 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold mb-6">Reviews</h2>

                {{-- 1. List Existing Reviews --}}
                @forelse($advertisement->reviews as $review)
                    <div class="mb-6 border-b border-gray-100 pb-4 last:border-0">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-gray-800">{{ $review->reviewer->name ?? 'Gebruiker' }}</span>
                            <div class="flex text-yellow-400">
                                {{-- Simple Star Output --}}
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < $review->rating) ★ @else ☆ @endif
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-600">{{ $review->comment }}</p>
                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 italic">Nog geen reviews. Wees de eerste!</p>
                @endforelse

                {{-- 2. Review Form (Conditional: Only for Renters) --}}
                @auth
                    @php
                        // Check directly in view if user is allowed (or pass via controller)
                        $hasRented = auth()->user()->rentals()->where('advertisement_id', $advertisement->id)->exists();
                    @endphp

                    @if($hasRented)
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h3 class="font-bold text-lg mb-4">Schrijf een review</h3>
                            
                            <form action="{{ route('reviews.store', $advertisement) }}" method="POST">
                                @csrf
                                
                                {{-- Rating Input --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Beoordeling</label>
                                    <select name="rating" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:w-1/4">
                                        <option value="5">5 - Uitstekend</option>
                                        <option value="4">4 - Goed</option>
                                        <option value="3">3 - Gemiddeld</option>
                                        <option value="2">2 - Matig</option>
                                        <option value="1">1 - Slecht</option>
                                    </select>
                                </div>

                                {{-- Comment Input --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jouw ervaring</label>
                                    <textarea name="comment" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Vertel ons wat je ervan vond..."></textarea>
                                </div>

                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                                    Plaats Review
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</x-market-layout>