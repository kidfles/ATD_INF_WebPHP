<x-app-layout>
    <div class="py-4">
        <div class="max-w-5xl mx-auto">

            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                    {{-- Image --}}
                    <div class="relative">
                        @if($advertisement->image_path)
                            <img src="{{ asset('storage/' . $advertisement->image_path) }}" alt="{{ $advertisement->title }}" class="w-full h-full object-cover min-h-[320px]">
                        @else
                            <div class="w-full h-full min-h-[320px] bg-white/5 flex items-center justify-center">
                                <span class="text-gray-600 text-sm">{{ __('Geen afbeelding') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="p-8 flex flex-col justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-3">{{ $advertisement->title }}</h1>
                            <p class="text-2xl font-bold text-cyan-400 mb-4">€ {{ number_format($advertisement->price, 2) }}</p>
                            <p class="text-gray-400 mb-6 leading-relaxed">{{ $advertisement->description }}</p>
                            
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-xs font-semibold text-gray-400 uppercase">{{ ucfirst($advertisement->type) }}</span>
                                <span class="text-gray-500 text-sm">{{ __('Aangeboden door') }}: <span class="text-gray-300">{{ $advertisement->user->name }}</span></span>
                            </div>
                        </div>

                        @if(auth()->id() === $advertisement->user_id)
                            <div class="mt-8 pt-6 border-t border-white/5 flex gap-3">
                                <a href="{{ route('dashboard.advertisements.edit', $advertisement) }}" class="bg-amber-500/10 text-amber-300 border border-amber-500/20 font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-amber-500/20 transition">{{ __('Bewerken') }}</a>
                                
                                <form action="{{ route('dashboard.advertisements.destroy', $advertisement) }}" method="POST" onsubmit="return confirm('{{ __('Weet je zeker dat je deze advertentie wilt verwijderen?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500/10 text-red-300 border border-red-500/20 font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-red-500/20 transition">{{ __('Verwijderen') }}</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
