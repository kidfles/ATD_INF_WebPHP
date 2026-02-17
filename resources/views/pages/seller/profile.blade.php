<x-public-layout>
    {{-- PART 1: THE HERO SECTION --}}
    
    @if($isBusiness && $heroComponent)
        {{-- SCENARIO A: Business with Custom Hero (From Database) --}}
        <div class="relative bg-slate-800 h-64 flex items-center justify-center overflow-hidden">
            @if(isset($heroComponent->content['image']))
                <img src="{{ asset('storage/'.$heroComponent->content['image']) }}" class="absolute inset-0 w-full h-full object-cover opacity-50">
            @endif
            <div class="relative z-10 text-center text-white px-4">
                <h1 class="text-4xl font-extrabold">{{ $heroComponent->content['title'] ?? ($user->companyProfile->company_name ?? $user->name) }}</h1>
                <p class="mt-2 text-xl">{{ $heroComponent->content['subtitle'] ?? '' }}</p>
            </div>
        </div>

    @else
        {{-- SCENARIO B: Private Seller OR Business without setup (Default/Virtual Hero) --}}
        <div class="bg-gradient-to-r from-emerald-400 to-teal-500 h-48 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex items-center gap-6">
                
                {{-- Avatar --}}
                <div class="h-24 w-24 rounded-full bg-white p-1 shadow-lg flex-shrink-0">
                    <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=ecfdf5&color=059669' }}" 
                         class="h-full w-full rounded-full object-cover bg-emerald-50">
                </div>

                {{-- User Info --}}
                <div>
                    <h1 class="text-3xl font-extrabold text-white">
                        {{ $isBusiness ? ($user->companyProfile->company_name ?? $user->name) : $user->name }}
                    </h1>
                    <div class="flex items-center gap-4 mt-2 text-white/80 text-sm font-medium">
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- LEFT COLUMN: Reviews (Sticky) --}}
            <div class="lg:col-span-1">
                <div class="bg-white shadow-soft rounded-[2rem] p-6 sticky top-6 border border-slate-100">
                    <h3 class="text-lg font-extrabold text-slate-800 mb-4">Reviews</h3>
                    
                    {{-- Average Score --}}
                    <div class="flex items-center mb-6">
                        <span class="text-3xl font-black text-slate-800 mr-2">
                            {{ number_format($averageRating, 1) }}
                        </span>
                        <div class="flex text-yellow-400 text-sm gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                @if($i <= round($averageRating)) 
                                   <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @else 
                                   <svg class="w-4 h-4 text-slate-200 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-slate-400 ml-2 text-sm">({{ $reviewCount }})</span>
                    </div>

                    {{-- 'Write Review' Button Logic --}}
                    @auth
                        @if($user->hasSoldTo(auth()->user()) && auth()->id() !== $user->id)
                            <div class="mb-6 pt-6 border-t border-slate-100">
                                <button onclick="document.getElementById('seller-review-form').classList.toggle('hidden')" 
                                        class="w-full bg-gradient-to-r from-emerald-400 to-teal-500 text-white py-2.5 rounded-full font-bold text-sm shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300">
                                    Schrijf een Review
                                </button>
                            </div>
                            
                            {{-- Hidden Form --}}
                            <div id="seller-review-form" class="hidden mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <form action="{{ route('reviews.storeSeller', $user) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-xs font-bold text-slate-600 mb-1">Score</label>
                                        <select name="rating" class="w-full text-sm bg-white border-slate-200 rounded-xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50">
                                            <option value="5">5 ★ - Uitstekend</option>
                                            <option value="4">4 ★ - Goed</option>
                                            <option value="3">3 ★ - Gemiddeld</option>
                                            <option value="2">2 ★ - Matig</option>
                                            <option value="1">1 ★ - Slecht</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="comment" rows="3" class="w-full text-sm bg-white border-slate-200 rounded-xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50" placeholder="Jouw ervaring..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-400 to-teal-500 text-white py-2.5 rounded-full font-bold text-sm shadow-lg hover:-translate-y-0.5 transition-all">Verstuur</button>
                                </form>
                            </div>
                        @elseif(auth()->id() === $user->id)
                            <p class="text-xs text-center text-slate-400 mb-6 italic">Dit is je eigen profiel</p>
                        @endif
                    @endauth

                    {{-- Review List --}}
                    <div class="space-y-6">
                        @forelse($user->reviewsReceived as $review)
                            <div class="border-b border-slate-100 pb-4 last:border-0">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-sm text-slate-700">{{ $review->reviewer?->name ?? 'Verwijderde gebruiker' }}</span>
                                    <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex text-yellow-400 text-xs mb-2">
                                    @for($i=0; $i<$review->rating; $i++) ★ @endfor
                                </div>
                                <p class="text-slate-500 text-sm leading-relaxed">"{{ $review->comment }}"</p>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-slate-400 text-sm italic">Nog geen reviews.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

            {{-- RIGHT COLUMN: Advertisements --}}
            <div class="lg:col-span-2">
                <div class="flex justify-between items-end mb-6">
                    <h3 class="text-xl font-extrabold text-slate-800">Actueel Aanbod</h3>
                    <span class="text-sm text-slate-400 font-medium">{{ $user->advertisements->count() }} resultaten</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($user->advertisements as $ad)
                        <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-soft-lg">
                            <div class="w-full overflow-hidden bg-slate-50 relative group" style="height: 280px;">
                                @if($ad->image_path)
                                    <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <img src="{{ asset('images/placeholder.svg') }}" alt="{{ __('No image available') }}" class="w-full h-full object-cover text-slate-400">
                                @endif
                            </div>
                            <div class="p-5">
                                <a href="{{ route('market.show', $ad) }}">
                                    <h3 class="font-extrabold text-lg text-emerald-600 hover:text-emerald-700 transition-colors">{{ $ad->title }}</h3>
                                </a>
                                <p class="text-slate-500 truncate mt-1">{{ $ad->description }}</p>
                                <div class="flex justify-between items-center mt-4">
                                    <p class="font-extrabold text-lg text-slate-800">€ {{ number_format($ad->price, 2) }}</p>
                                    <span class="bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-full text-xs font-bold text-slate-500">{{ __(ucfirst($ad->type)) }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12 bg-white rounded-[2rem] border border-dashed border-slate-200 shadow-soft">
                            <p class="text-slate-400">Deze verkoper heeft momenteel geen actieve advertenties.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-public-layout>
