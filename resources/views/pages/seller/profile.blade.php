<x-public-layout>
    {{-- PART 1: THE HERO SECTION --}}
    
    @if($isBusiness && $heroComponent)
        {{-- SCENARIO A: Business with Custom Hero (From Database) --}}
        <div class="relative bg-gray-900 h-64 flex items-center justify-center overflow-hidden">
            @if(isset($heroComponent->content['image']))
                <img src="{{ asset('storage/'.$heroComponent->content['image']) }}" class="absolute inset-0 w-full h-full object-cover opacity-50">
            @endif
            <div class="relative z-10 text-center text-white px-4">
                <h1 class="text-4xl font-bold">{{ $heroComponent->content['title'] ?? ($user->companyProfile->company_name ?? $user->name) }}</h1>
                <p class="mt-2 text-xl">{{ $heroComponent->content['subtitle'] ?? '' }}</p>
            </div>
        </div>

    @else
        {{-- SCENARIO B: Private Seller OR Business without setup (Default/Virtual Hero) --}}
        {{-- We generate this on the fly. No database record needed. --}}
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-48 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex items-center gap-6">
                
                {{-- Avatar --}}
                <div class="h-24 w-24 rounded-full bg-white p-1 shadow-lg flex-shrink-0">
                    <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                         class="h-full w-full rounded-full object-cover bg-gray-200">
                </div>

                {{-- User Info --}}
                <div>
                    <h1 class="text-3xl font-bold text-black">
                        {{ $isBusiness ? ($user->companyProfile->company_name ?? $user->name) : $user->name }}
                    </h1>
                    <div class="flex items-center gap-4 mt-2 text-indigo-100 text-sm">
                        <span>Lid sinds {{ $user->created_at->format('M Y') }}</span>
                        <span>•</span>
                        <span>{{ $user->advertisements->count() }} Advertenties</span>
                    </div>
                </div>

            </div>
        </div>
    @endif

    {{-- PART 2: MAIN CONTENT (Reviews & Ads) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- LEFT COLUMN: Reviews (Sticky) --}}
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6 sticky top-6 border border-gray-100">
                    <h3 class="text-lg font-bold mb-4">Reviews</h3>
                    
                    {{-- Average Score --}}
                    <div class="flex items-center mb-6">
                        <span class="text-3xl font-black text-gray-900 mr-2">
                            {{ number_format($averageRating, 1) }}
                        </span>
                        <div class="flex text-yellow-400 text-sm gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                @if($i <= round($averageRating)) 
                                   <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @else 
                                   <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-gray-500 ml-2 text-sm">({{ $reviewCount }})</span>
                    </div>

                    {{-- 'Write Review' Button Logic --}}
                    @auth
                        @if($user->hasSoldTo(auth()->user()) && auth()->id() !== $user->id)
                            <div class="mb-6 pt-6 border-t border-gray-100">
                                <button onclick="document.getElementById('seller-review-form').classList.toggle('hidden')" 
                                        class="w-full bg-indigo-600 text-white py-2 rounded-lg font-bold text-sm hover:bg-indigo-700 transition">
                                    Schrijf een Review
                                </button>
                            </div>
                            
                            {{-- Hidden Form --}}
                            <div id="seller-review-form" class="hidden mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <form action="{{ route('reviews.storeSeller', $user) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Score</label>
                                        <select name="rating" class="w-full text-sm border-gray-300 rounded-md">
                                            <option value="5">5 ★ - Uitstekend</option>
                                            <option value="4">4 ★ - Goed</option>
                                            <option value="3">3 ★ - Gemiddeld</option>
                                            <option value="2">2 ★ - Matig</option>
                                            <option value="1">1 ★ - Slecht</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="comment" rows="3" class="w-full text-sm border-gray-300 rounded-md" placeholder="Jouw ervaring..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-bold text-sm hover:bg-indigo-700 transition">Verstuur</button>
                                </form>
                            </div>
                        @elseif(auth()->id() === $user->id)
                            <p class="text-xs text-center text-gray-400 mb-6 italic">Dit is je eigen profiel</p>
                        @endif
                    @endauth

                    {{-- Review List --}}
                    <div class="space-y-6">
                        @forelse($user->reviewsReceived as $review)
                            <div class="border-b border-gray-100 pb-4 last:border-0">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-sm text-gray-900">{{ $review->reviewer?->name ?? 'Verwijderde gebruiker' }}</span>
                                    <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex text-yellow-400 text-xs mb-2">
                                    @for($i=0; $i<$review->rating; $i++) ★ @endfor
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">"{{ $review->comment }}"</p>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500 text-sm italic">Nog geen reviews.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

            {{-- RIGHT COLUMN: Advertisements --}}
            <div class="lg:col-span-2">
                <div class="flex justify-between items-end mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Actueel Aanbod</h3>
                    <span class="text-sm text-gray-500">{{ $user->advertisements->count() }} resultaten</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($user->advertisements as $ad)
                        <div class="border rounded p-4 shadow hover:shadow-lg transition bg-white">
                            <div class="w-full mb-4 overflow-hidden rounded bg-gray-100 relative group" style="height: 360px;">
                                @if($ad->image_path)
                                    <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <img src="{{ asset('images/placeholder.svg') }}" alt="{{ __('No image available') }}" class="w-full h-full object-cover text-gray-400">
                                @endif
                            </div>
                            <a href="{{ route('market.show', $ad) }}">
                                <h3 class="font-bold text-lg text-blue-600 hover:underline">{{ $ad->title }}</h3>
                            </a>
                            <p class="text-gray-600 truncate">{{ $ad->description }}</p>
                            <div class="flex justify-between items-center mt-4">
                                <p class="font-bold text-lg">€ {{ number_format($ad->price, 2) }}</p>
                                <span class="bg-gray-100 px-2 py-1 rounded text-sm text-gray-600">{{ __(ucfirst($ad->type)) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12 bg-white rounded border border-dashed border-gray-300">
                            <p class="text-gray-400">Deze verkoper heeft momenteel geen actieve advertenties.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-public-layout>
