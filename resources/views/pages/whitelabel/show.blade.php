<x-whitelabel-layout :company="$company">
    
    @if($company->pageComponents->isEmpty())
        <div class="py-32 text-center bg-gray-50">
            <h1 class="text-2xl font-bold text-gray-400">Page under construction</h1>
        </div>
    @endif

    @php
        $heroComponents = $company->pageComponents->where('component_type', 'hero');
        $adComponents = $company->pageComponents->where('component_type', 'featured_ads');
        $textComponents = $company->pageComponents->where('component_type', 'text');
    @endphp

    @foreach($heroComponents as $component)
        <section class="bg-white pt-24 pb-20 border-b border-gray-100">
            <div class="max-w-5xl mx-auto px-6 text-center">
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight">
                    {{ $component->content['title'] ?? 'Welcome' }}
                </h1>
                
                <p class="text-xl text-gray-500 mb-10 max-w-2xl mx-auto leading-relaxed">
                    {{ $component->content['subtitle'] ?? '' }}
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                    <a href="#products" 
                       class="px-8 py-3 rounded-full font-bold text-white shadow-lg transform transition hover:-translate-y-1"
                       style="background-color: {{ $company->brand_color ?? '#1f2937' }}">
                        View Collection
                    </a>
                    <a href="#about" class="px-8 py-3 rounded-full font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        Our Story
                    </a>
                </div>

                @if(isset($component->content['image']))
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl mt-12">
                        <img src="{{ asset('storage/'.$component->content['image']) }}" 
                             class="w-full object-cover max-h-[500px] bg-gray-100">
                    </div>
                @endif
            </div>
        </section>
    @endforeach

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

</x-whitelabel-layout>