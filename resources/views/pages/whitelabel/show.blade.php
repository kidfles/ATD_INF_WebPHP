<x-whitelabel-layout :company="$company">
    
    @if($company->pageComponents->isEmpty())
        <div class="py-32 text-center">
            <h2 class="text-3xl font-bold text-gray-300">Page under construction</h2>
        </div>
    @endif

    @foreach($company->pageComponents as $component)
        
        {{-- HERO SECTION --}}
        @if($component->component_type === 'hero')
            <section id="hero-{{ $component->id }}" class="relative py-32 md:py-48 bg-gray-900 text-white overflow-hidden">
                <div class="absolute inset-0 z-0" 
                     style="background-color: {{ $company->brand_color ?? '#111827' }}; opacity: 0.9;"></div>
                
                @if(isset($component->content['image']))
                    <img src="{{ asset('storage/'.$component->content['image']) }}" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-40 z-0">
                @endif

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 drop-shadow-lg leading-tight">
                        {{ $component->content['title'] ?? 'Welcome' }}
                    </h2>
                    <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto mb-12 font-light leading-relaxed drop-shadow-md">
                        {{ $component->content['subtitle'] ?? '' }}
                    </p>
                    <a href="#products" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-full text-gray-900 bg-white hover:bg-gray-100 transition duration-300 transform hover:scale-105 shadow-xl">
                        View Products
                    </a>
                </div>
            </section>

        {{-- TEXT SECTION --}}
        @elseif($component->component_type === 'text')
            <section id="text-{{ $component->id }}" class="py-24 bg-white">
                <div class="max-w-4xl mx-auto px-6 text-center">
                    <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-10 relative inline-block pb-4">
                        {{ $component->content['heading'] ?? 'About Us' }}
                        <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-24 h-1.5 rounded-full" 
                              style="background-color: {{ $company->brand_color ?? '#3b82f6' }}"></span>
                    </h3>
                    <div class="prose prose-lg prose-indigo mx-auto text-gray-600 leading-relaxed">
                        {!! nl2br(e($component->content['body'] ?? '')) !!}
                    </div>
                </div>
            </section>

        {{-- FEATURED PRODUCTS --}}
        @elseif($component->component_type === 'featured_ads')
            <section id="products" class="py-24 bg-gray-50 border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h3 class="text-3xl md:text-4xl font-bold text-gray-900">Featured Products</h3>
                        <p class="mt-4 text-xl text-gray-500">Check out our latest offerings</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        @foreach($company->user->advertisements()->latest()->take(3)->get() as $ad)
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden group flex flex-col h-full border border-gray-100">
                                <div class="relative h-64 w-full overflow-hidden bg-gray-200">
                                    @if($ad->image_path)
                                        <img src="{{ asset('storage/' . $ad->image_path) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                            <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide text-gray-800 shadow-sm">
                                        {{ $ad->type }}
                                    </div>
                                </div>
                                
                                <div class="p-8 flex-1 flex flex-col">
                                    <h4 class="font-bold text-xl text-gray-900 mb-3 line-clamp-1 group-hover:text-indigo-600 transition">{{ $ad->title }}</h4>
                                    <p class="text-gray-600 mb-6 line-clamp-2 text-sm flex-1">{{ $ad->description }}</p>
                                    
                                    <div class="flex items-center justify-between mt-auto pt-6 border-t border-gray-100">
                                        <span class="text-2xl font-bold" style="color: {{ $company->brand_color ?? '#3b82f6' }}">
                                            €{{ number_format($ad->price, 2) }}
                                        </span>
                                        <a href="{{ route('market.show', $ad) }}" class="inline-flex items-center text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition">
                                            View Details 
                                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

    @endforeach
</x-whitelabel-layout>
