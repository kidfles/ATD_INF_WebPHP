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
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif

                    <span class="absolute top-4 left-4 z-10 bg-white/95 backdrop-blur px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider shadow-sm border"
                          style="color: {{ $brandColor }}; border-color: {{ $brandColor }};">
                        {{ $advertisement->type }}
                    </span>
                </div>

                <div class="flex flex-col justify-center">
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-2 leading-tight">{{ $advertisement->title }}</h1>
                    
                    <div class="flex items-center gap-2 mb-6 text-sm">
                        <span class="text-gray-500">Sold by</span>
                        <span class="font-bold text-gray-900 border-b-2 border-transparent transition"
                              onmouseover="this.style.borderColor='{{ $brandColor }}'"
                              onmouseout="this.style.borderColor='transparent'">
                            {{ $advertisement->user->name }}
                        </span>
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
                                </div>

                            @else
                                <button class="w-full text-white font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 text-xl flex items-center justify-center gap-3"
                                        style="background-color: {{ $brandColor }}">
                                    <span>Buy Now</span>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </button>
                            @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-market-layout>