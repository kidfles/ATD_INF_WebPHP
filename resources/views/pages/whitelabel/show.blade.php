<x-whitelabel-layout :company="$company">
    
    @if($company->pageComponents->isEmpty())
        <div class="py-32 text-center">
            <h2 class="text-3xl font-bold text-gray-300">Page under construction</h2>
        </div>
    @endif

    @foreach($company->pageComponents as $component)
        
        {{-- HERO SECTION --}}
        @if($component->component_type === 'hero')
            <section id="hero-{{ $component->id }}" class="relative min-h-[70vh] flex items-center justify-center bg-gray-900 text-white overflow-hidden">
                {{-- Background Image & Overlay --}}
                <div class="absolute inset-0 z-0">
                    <div class="absolute inset-0 z-10 bg-gradient-to-r from-gray-900/90 via-gray-900/60 to-gray-900/30"></div>
                    <div class="absolute inset-0 z-10 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
                    
                    @if(isset($component->content['image']))
                        {{-- Parallax-like effect with object-cover --}}
                        <img src="{{ asset('storage/'.$component->content['image']) }}" 
                             class="w-full h-full object-cover opacity-60 transform scale-105"
                             style="mix-blend-mode: overlay;">
                    @else
                        {{-- Fallback gradient if no image --}}
                        <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900"></div>
                    @endif
                    
                    {{-- Brand Color Overlay --}}
                    <div class="absolute inset-0 z-20 mix-blend-color opacity-30 pointer-events-none"
                         style="background-color: {{ $company->brand_color ?? '#111827' }};"></div>
                </div>

                <div class="relative z-30 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20 pb-16">
                    <span class="inline-block py-1 px-3 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sm font-semibold tracking-wider uppercase mb-6 animate-fade-in-up">
                        {{ $company->user->name ?? 'Welcome' }}
                    </span>
                    <h2 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tighter mb-8 drop-shadow-xl text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-400">
                        {{ $component->content['title'] ?? 'Welcome' }}
                    </h2>
                    <p class="text-xl md:text-2xl text-gray-200 max-w-3xl mx-auto mb-12 font-medium leading-relaxed drop-shadow-md">
                        {{ $component->content['subtitle'] ?? '' }}
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="#products" class="px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl flex items-center gap-2"
                           style="background-color: {{ $company->brand_color ?? '#ffffff' }}; color: {{ $company->brand_color ? '#ffffff' : '#111827' }}">
                            View Products
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        </a>
                        <a href="#about" class="px-8 py-4 rounded-full font-bold text-lg bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white/20 transition-all duration-300">
                            About Us
                        </a>
                    </div>
                </div>
            </section>

        {{-- TEXT SECTION --}}
        @elseif($component->component_type === 'text')
            <section id="text-{{ $component->id }}" class="py-24 bg-white relative overflow-hidden">
                {{-- Decorative Elements --}}
                <div class="absolute top-0 left-0 w-64 h-64 bg-gray-50 rounded-full -translate-x-1/2 -translate-y-1/2 opacity-50"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-gray-50 rounded-full translate-x-1/3 translate-y-1/3 opacity-50"></div>

                <div class="max-w-4xl mx-auto px-6 relative z-10">
                    <div class="text-center mb-12">
                        <h3 class="text-3xl md:text-5xl font-black text-gray-900 mb-6 relative inline-block">
                            {{ $component->content['heading'] ?? 'About Us' }}
                            <svg class="absolute -bottom-2 w-full h-3 text-opacity-50" viewBox="0 0 100 10" preserveAspectRatio="none"
                                 style="color: {{ $company->brand_color ?? '#3b82f6' }}">
                                <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="3" fill="none" />
                            </svg>
                        </h3>
                    </div>
                    <div class="prose prose-lg prose-gray mx-auto text-gray-600 leading-loose text-justify md:text-center">
                        {!! nl2br(e($component->content['body'] ?? '')) !!}
                    </div>
                </div>
            </section>

        {{-- FEATURED PRODUCTS --}}
        @elseif($component->component_type === 'featured_ads')
            <section id="products" class="py-24 bg-gray-50 relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-20">
                        <h3 class="text-3xl md:text-5xl font-black text-gray-900 mb-4">Featured Products</h3>
                        <div class="h-1 w-20 mx-auto rounded-full mb-6" style="background-color: {{ $company->brand_color ?? '#3b82f6' }}"></div>
                        <p class="text-xl text-gray-500">Discover our professionally curated selection of high-quality items.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        @foreach($company->user->advertisements()->latest()->take(3)->get() as $ad)
                            <div class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-full transform hover:-translate-y-1">
                                <div class="relative h-72 w-full overflow-hidden bg-gray-100">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 z-10"></div>
                                    @if($ad->image_path)
                                        <img src="{{ asset('storage/' . $ad->image_path) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    
                                    <div class="absolute top-4 left-4 z-20">
                                        <span class="px-3 py-1 bg-white/90 backdrop-blur text-gray-900 text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm border border-gray-100">
                                            {{ $ad->type }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-8 flex-1 flex flex-col">
                                    <h4 class="font-bold text-2xl text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $ad->title }}</h4>
                                    <p class="text-gray-500 mb-6 line-clamp-2 leading-relaxed flex-1">{{ $ad->description }}</p>
                                    
                                    <div class="flex items-end justify-between mt-auto pt-6 border-t border-gray-50">
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Price</p>
                                            <span class="text-3xl font-black tracking-tight" style="color: {{ $company->brand_color ?? '#111827' }}">
                                                €{{ number_format($ad->price, 0, ',', '.') }}<span class="text-lg text-gray-400 font-medium">.00</span>
                                            </span>
                                        </div>
                                        <a href="{{ route('market.show', $ad) }}" 
                                           class="w-12 h-12 rounded-full flex items-center justify-center text-white shadow-lg transform group-hover:rotate-45 transition duration-300"
                                           style="background-color: {{ $company->brand_color ?? '#111827' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
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
