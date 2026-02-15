<x-whitelabel-layout :company="$company">
    
    @if($company->pageComponents->isEmpty())
        <div class="py-32 text-center">
            <h2 class="text-3xl font-bold text-gray-300">Page under construction</h2>
        </div>
    @endif

    @foreach($company->pageComponents as $component)
        
        {{-- HERO SECTION --}}
        @if($component->component_type === 'hero')
            <section id="home" class="relative py-24 md:py-32 bg-gray-900 text-white overflow-hidden">
                <div class="absolute inset-0 opacity-90" 
                     style="background-color: {{ $company->brand_color ?? '#111827' }}"></div>
                
                @if(isset($component->content['image']))
                    <img src="{{ asset('storage/'.$component->content['image']) }}" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50">
                @endif

                <div class="relative max-w-7xl mx-auto px-4 text-center">
                    <h2 class="text-5xl md:text-6xl font-extrabold tracking-tight mb-6">
                        {{ $component->content['title'] ?? 'Welcome' }}
                    </h2>
                    <p class="text-xl md:text-2xl opacity-90 max-w-2xl mx-auto mb-10">
                        {{ $component->content['subtitle'] ?? '' }}
                    </p>
                    <a href="#products" class="inline-block bg-white text-gray-900 font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition transform hover:scale-105">
                        View Products
                    </a>
                </div>
            </section>

        {{-- TEXT SECTION --}}
        @elseif($component->component_type === 'text')
            <section id="about" class="py-20 bg-white">
                <div class="max-w-4xl mx-auto px-4 text-center">
                    <h3 class="text-3xl font-bold text-gray-900 mb-8 relative inline-block">
                        {{ $component->content['heading'] ?? 'About Us' }}
                        <span class="absolute bottom-0 left-0 w-full h-1 rounded" 
                              style="background-color: {{ $company->brand_color ?? '#3b82f6' }}"></span>
                    </h3>
                    <div class="prose prose-lg mx-auto text-gray-600">
                        {!! nl2br(e($component->content['body'] ?? '')) !!}
                    </div>
                </div>
            </section>

        {{-- FEATURED PRODUCTS --}}
        @elseif($component->component_type === 'featured_ads')
            <section id="products" class="py-20 bg-gray-50 border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4">
                    <h3 class="text-3xl font-bold text-gray-900 mb-12 text-center">Featured Products</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach($company->user->advertisements()->latest()->take(3)->get() as $ad)
                            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden group">
                                <div class="bg-gray-200 h-56 w-full overflow-hidden relative">
                                    @if($ad->image_path)
                                        <img src="{{ asset('storage/' . $ad->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">No Image</div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-lg text-gray-900 line-clamp-1">{{ $ad->title }}</h4>
                                        <span class="text-xs font-bold uppercase px-2 py-1 bg-gray-100 rounded text-gray-600">{{ $ad->type }}</span>
                                    </div>
                                    <p class="text-xl font-bold mb-4" style="color: {{ $company->brand_color ?? '#3b82f6' }}">
                                        €{{ number_format($ad->price, 2) }}
                                    </p>
                                    <a href="{{ route('market.show', $ad) }}" class="block w-full text-center bg-gray-900 text-white font-medium py-2 rounded hover:bg-gray-800 transition">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

    @endforeach
</x-whitelabel-layout>
