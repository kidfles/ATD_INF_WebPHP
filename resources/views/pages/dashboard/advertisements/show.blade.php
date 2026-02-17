<x-app-layout>
    {{--
        Pagina: Advertentie Detail (Dashboard)
        Doel: Gedetailleerde weergave van een eigen advertentie.
        Bevat: Afbeelding, prijs, beschrijving en beheeropties.
    --}}
    <div class="py-4">
        <div class="max-w-5xl mx-auto">

            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                    {{-- Image --}}
                    <div class="relative">
                        @if($advertisement->image_path)
                            <img src="{{ asset('storage/' . $advertisement->image_path) }}" alt="{{ $advertisement->title }}" class="w-full h-full object-cover min-h-[320px]">
                        @else
                            <div class="w-full h-full min-h-[320px] bg-slate-50 flex items-center justify-center">
                                <span class="text-slate-400 text-sm font-medium">{{ __('Geen afbeelding') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="p-8 flex flex-col justify-between">
                        <div>
                            <h1 class="text-3xl font-extrabold text-slate-800 mb-3">{{ $advertisement->title }}</h1>
                            <p class="text-2xl font-extrabold text-emerald-500 mb-4">€ {{ number_format($advertisement->price, 2) }}</p>
                            <p class="text-slate-500 mb-6 leading-relaxed">{{ $advertisement->description }}</p>
                            
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-full text-xs font-bold text-slate-500 uppercase">{{ ucfirst($advertisement->type) }}</span>
                                <span class="text-slate-400 text-sm">{{ __('Aangeboden door') }}: <span class="text-slate-700 font-bold">{{ $advertisement->user->name }}</span></span>
                            </div>
                        </div>

                        @if(auth()->id() === $advertisement->user_id)
                            <div class="mt-8 pt-6 border-t border-slate-100 flex gap-3">
                                <a href="{{ route('dashboard.advertisements.edit', $advertisement) }}" class="bg-amber-50 text-amber-600 border border-amber-200 font-bold px-5 py-2.5 rounded-full text-sm hover:bg-amber-100 transition-all">{{ __('Bewerken') }}</a>
                                
                                <form action="{{ route('dashboard.advertisements.destroy', $advertisement) }}" method="POST" onsubmit="return confirm('{{ __('Weet je zeker dat je deze advertentie wilt verwijderen?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 text-red-500 border border-red-200 font-bold px-5 py-2.5 rounded-full text-sm hover:bg-red-100 transition-all">{{ __('Verwijderen') }}</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
