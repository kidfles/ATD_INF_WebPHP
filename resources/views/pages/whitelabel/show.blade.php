<x-whitelabel-layout :company="$company">
    <x-slot name="title">{{ optional($company->user)->name ?? $company->name }}</x-slot>

    @if($company->pageComponents->isEmpty())
        <div class="py-20 text-center">
            <h1 class="text-4xl font-bold text-brand">Welcome to {{ optional($company->user)->name ?? $company->name }}</h1>
            <p class="mt-4 text-gray-600">This company hasn't configured their page yet.</p>
        </div>
    @endif

    @foreach($company->pageComponents as $component)
        
        {{-- HERO COMPONENT --}}
        @if($component->component_type === 'hero')
            <section class="bg-brand py-24 text-center" id="home">
                <div class="max-w-4xl mx-auto px-4">
                    <h1 class="text-5xl font-extrabold mb-6">
                        {{ $component->content['title'] ?? 'Welcome' }}
                    </h1>
                    <p class="text-xl opacity-90 mb-8">
                        {{ $component->content['subtitle'] ?? '' }}
                    </p>
                    @if(isset($component->content['image']))
                        <img src="{{ asset('storage/'.$component->content['image']) }}" class="mx-auto rounded-lg shadow-xl max-h-96 object-cover">
                    @endif
                </div>
            </section>

        {{-- TEXT / ABOUT COMPONENT --}}
        @elseif($component->component_type === 'text')
            <section class="py-16 bg-white">
                <div class="max-w-3xl mx-auto px-4 prose prose-lg">
                    <h2 class="text-brand text-3xl font-bold mb-4">{{ $component->content['heading'] ?? 'About Us' }}</h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($component->content['body'] ?? '')) !!}
                    </div>
                </div>
            </section>

        {{-- FEATURED ADS COMPONENT --}}
        @elseif($component->component_type === 'featured_ads')
            <section class="py-16 bg-gray-50" id="products">
                <div class="max-w-7xl mx-auto px-4">
                    <h2 class="text-3xl font-bold text-center mb-12">Featured Products</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {{-- 
                            Fetch the company's latest 3 ads dynamically.
                        --}}
                        @if($company->user)
                            @foreach($company->user->advertisements()->latest()->take(3)->get() as $ad)
                                <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                                    <div class="bg-gray-200 h-48 w-full overflow-hidden">
                                        @if($ad->image_path)
                                            <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-500">No Image</div>
                                        @endif
                                    </div>
                                    <div class="p-6">
                                        <h3 class="font-bold text-xl mb-2">{{ $ad->title }}</h3>
                                        <p class="text-brand font-bold text-lg">€{{ number_format($ad->price, 2) }}</p>
                                        <a href="{{ route('market.show', $ad) }}" class="mt-4 inline-block btn-brand text-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-gray-500 col-span-3">No products available.</p>
                        @endif
                    </div>
                </div>
            </section>

        @endif
    @endforeach

</x-whitelabel-layout>
