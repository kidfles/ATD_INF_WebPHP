<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            <h2 class="text-2xl font-bold text-white mb-6">{{ __('Mijn Huuritems') }}</h2>

            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl">
                <div class="p-6">
                    
                    @if(session('status'))
                        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 px-4 py-3 rounded-xl mb-4 text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($rentals->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-gray-500 mt-3">{{ __('Je hebt nog geen items gehuurd.') }}</p>
                            <a href="{{ route('market.index', ['type' => 'rent']) }}" class="text-violet-400 hover:text-violet-300 mt-2 inline-block text-sm font-semibold transition-colors">{{ __('Bekijk huur aanbod') }} &rarr;</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($rentals as $rental)
                                <div class="bg-white/5 border border-white/5 rounded-xl overflow-hidden {{ $rental->return_photo_path ? 'opacity-80' : '' }} hover:border-violet-500/30 transition-all duration-300">
                                    <div class="w-full aspect-video relative">
                                        {{-- Status Badge --}}
                                        <div class="absolute top-2 right-2 z-10">
                                            @if($rental->return_photo_path)
                                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">{{ __('Returned') }}</span>
                                            @elseif(now()->gt($rental->end_date))
                                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-red-500/10 text-red-300 border border-red-500/20">{{ __('Overdue') }}</span>
                                            @else
                                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-cyan-500/10 text-cyan-300 border border-cyan-500/20">{{ __('Active') }}</span>
                                            @endif
                                        </div>

                                        @if($rental->advertisement->image_path)
                                            <img src="{{ asset('storage/' . $rental->advertisement->image_path) }}" alt="{{ $rental->advertisement->title }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('images/placeholder.svg') }}" alt="Placeholder" class="w-full h-full object-cover opacity-30">
                                        @endif
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="font-bold text-lg text-white mb-2">{{ $rental->advertisement->title }}</h3>
                                        <p class="text-gray-400 text-sm mb-1">
                                            <span class="font-semibold text-gray-300">{{ __('Periode') }}:</span> 
                                            {{ $rental->start_date->format('d-m-Y') }} t/m {{ $rental->end_date->format('d-m-Y') }}
                                        </p>
                                        <p class="text-gray-400 text-sm mb-1">
                                            <span class="font-semibold text-gray-300">{{ __('Prijs per dag') }}:</span> € {{ number_format($rental->advertisement->price, 2) }}
                                        </p>
                                        <p class="text-white font-bold text-lg mb-4">
                                            {{ __('Totaal') }}: <span class="text-cyan-400">€ {{ number_format($rental->total_price, 2) }}</span>
                                            <span class="text-xs font-normal text-gray-500">({{ $rental->start_date->diffInDays($rental->end_date) + 1 }} {{ __('dagen') }})</span>
                                        </p>

                                        @if($rental->return_photo_path)
                                            <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-3">
                                                <p class="text-emerald-300 font-bold text-sm mb-1">{{ __('Item is geretourneerd') }}</p>
                                                <p class="text-emerald-400/70 text-sm">{{ __('Totale kosten') }}: € {{ number_format($rental->wear_and_tear_cost, 2) }}</p>
                                            </div>
                                        @else
                                            <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3">
                                                <h4 class="font-bold text-amber-300 text-sm mb-2">{{ __('Item Retourneren') }}</h4>
                                                <form action="{{ route('rentals.return', $rental) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="block text-xs font-medium text-gray-400 mb-1">{{ __('Upload foto van item staat') }}</label>
                                                        <input type="file" name="photo" required class="block w-full text-xs text-gray-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-violet-500/20 file:text-violet-300 hover:file:bg-violet-500/30">
                                                    </div>
                                                    <button type="submit" class="w-full bg-violet-500/20 text-violet-300 border border-violet-500/30 px-3 py-2 rounded-xl text-sm font-bold hover:bg-violet-500/30 transition">{{ __('Retourneren') }}</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
