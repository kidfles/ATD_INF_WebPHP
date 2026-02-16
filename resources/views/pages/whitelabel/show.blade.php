<x-whitelabel-layout :company="$company">
    
    {{-- Page under construction message removed --}}

    @php
        $adComponents = $company->pageComponents->where('component_type', 'featured_ads');
        $textComponents = $company->pageComponents->where('component_type', 'text');
    @endphp

    {{-- MANDATORY HERO SECTION --}}
    <section class="bg-white pt-24 pb-20 border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight">
                {{ $company->company_name ?? 'Welcome' }}
            </h1>
            
            <p class="text-xl text-gray-500 mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ $company->slogan ?? 'Welkom op onze bedrijfspagina. Bekijk ons aanbod hieronder.' }}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                <a href="#products" 
                   class="px-8 py-3 rounded-full font-bold text-white shadow-lg transform transition hover:-translate-y-1"
                   style="background-color: {{ $company->brand_color ?? '#1f2937' }}">
                    View Collection
                </a>
                <a href="#reviews" class="px-8 py-3 rounded-full font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Read Reviews
                </a>
            </div>

            @if($company->hero_image)
                <div class="relative rounded-2xl overflow-hidden shadow-2xl mt-12">
                    <img src="{{ asset($company->hero_image) }}" 
                         class="w-full object-cover max-h-[500px] bg-gray-100">
                </div>
            @endif
        </div>
    </section>

    @foreach($adComponents as $component)
        <section id="products" class="py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-6">
                
                <div class="flex justify-between items-end mb-12 px-2">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Featured Products</h2>
                    </div>
                    <a href="{{ route('market.index', ['seller' => $company->user_id]) }}" class="text-sm font-bold text-gray-900 hover:text-gray-600 border-b-2 border-transparent hover:border-gray-900 transition">
                        View All &rarr;
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                    @if($company->user)
                        {{-- Advertisements are eager loaded in CompanyController --}}
                        @foreach($company->user->advertisements as $ad)
                            <a href="{{ route('market.show', $ad) }}" class="group block">
                                
                                <div class="relative h-64 bg-gray-200 rounded-2xl overflow-hidden mb-5">
                                    @if($ad->image_path)
                                        <img src="{{ asset('storage/' . $ad->image_path) }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    
                                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-2 py-1 rounded text-xs font-bold uppercase tracking-wider text-gray-900">
                                        {{ $ad->type }}
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-gray-600 transition line-clamp-1">
                                            {{ $ad->title }}
                                        </h3>
                                    </div>
                                    <p class="text-xl font-bold mt-1" style="color: {{ $company->brand_color ?? '#1f2937' }}">
                                        €{{ number_format($ad->price, 2) }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="col-span-3 py-12 text-center text-gray-400 bg-white rounded-xl border border-dashed border-gray-300">
                            No products found.
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endforeach

    @foreach($textComponents as $component)
        <section id="about" class="py-24 bg-white">
            <div class="max-w-3xl mx-auto px-6">
                <div class="text-center mb-10">
                    <span class="text-xs font-bold tracking-widest uppercase text-gray-400">About Us</span>
                    <h2 class="text-3xl font-bold text-gray-900 mt-2">{{ $component->content['heading'] ?? 'Who We Are' }}</h2>
                    <div class="w-12 h-1 mx-auto mt-6 rounded-full" style="background-color: {{ $company->brand_color ?? '#1f2937' }}"></div>
                </div>

                <div class="prose prose-lg mx-auto text-gray-600 leading-8 text-center">
                    {!! nl2br(e($component->content['body'] ?? '')) !!}
                </div>
            </div>
        </section>
    @endforeach

    {{-- Reviews Section --}}
    <section id="reviews" class="py-24 bg-gray-50 border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-xs font-bold tracking-widest uppercase text-gray-400">Testimonials</span>
                <h2 class="text-3xl font-bold text-gray-900 mt-2">What Customers Say</h2>
                
                <div class="flex items-center justify-center mt-4 text-yellow-500 gap-1">
                    <span class="text-2xl font-bold text-gray-900 mr-2">{{ number_format($averageRating, 1) }}</span>
                    @for($i=1; $i<=5; $i++)
                         @if($i <= round($averageRating)) 
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                         @else 
                            <svg class="w-6 h-6 text-gray-300 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                         @endif
                    @endfor
                    <span class="text-gray-400 text-sm ml-2">({{ $reviewCount }} reviews)</span>
                </div>
            </div>

            {{-- Review Form --}}
            @auth
                @if($user->hasSoldTo(auth()->user()) && auth()->id() !== $user->id)
                    <div class="mb-12 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-lg mb-4">Schrijf een review</h3>
                         <form action="{{ route('reviews.storeSeller', $user) }}" method="POST">
                            @csrf
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
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jouw ervaring</label>
                                <textarea name="comment" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Vertel ons wat je ervan vond..."></textarea>
                            </div>
                            <button type="submit" class="text-white px-6 py-2 rounded-lg font-bold shadow transition hover:opacity-90"
                                    style="background-color: {{ $company->brand_color ?? '#1f2937' }}">
                                Plaats Review
                            </button>
                         </form>
                    </div>
                @endif
            @endauth

            {{-- Reviews Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($user->reviewsReceived as $review)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500">
                                    {{ substr($review->reviewer->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-gray-900">{{ $review->reviewer->name }}</span>
                            </div>
                            <div class="flex text-yellow-400 text-sm">
                                @for($i=0; $i<$review->rating; $i++) 
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> 
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-600 italic leading-relaxed">"{{ $review->comment }}"</p>
                        <span class="block mt-4 text-xs text-gray-400 font-medium">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12">
                        <p class="text-gray-400 italic">No reviews yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

</x-whitelabel-layout>