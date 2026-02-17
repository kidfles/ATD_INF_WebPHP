<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            <h2 class="text-2xl font-bold text-white mb-6">{{ __('Mijn Biedingen') }}</h2>

            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-6">
                    
                    @if($bids->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <p class="text-gray-500 mt-3">{{ __('Je hebt nog geen biedingen geplaatst.') }}</p>
                            <a href="{{ route('market.index', ['type' => 'auction']) }}" class="text-violet-400 hover:text-violet-300 mt-2 inline-block text-sm font-semibold transition-colors">{{ __('Bekijk veilingen') }} &rarr;</a>
                        </div>
                    @else
                        <div class="overflow-x-auto -mx-6">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-white/5">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Advertentie') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Mijn Bod') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Hoogste Bod') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Datum') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Actie') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($bids as $bid)
                                        @php
                                            $highestBid = $bid->advertisement->bids->max('amount');
                                            $isHighest = $bid->amount >= $highestBid;
                                        @endphp
                                        <tr class="hover:bg-white/5 transition">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($bid->advertisement->image_path)
                                                        <img class="h-10 w-10 rounded-lg object-cover mr-3 border border-white/10" src="{{ asset('storage/' . $bid->advertisement->image_path) }}" alt="">
                                                    @else
                                                        <div class="h-10 w-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center mr-3 text-xs text-gray-600">IMG</div>
                                                    @endif
                                                    <div class="text-sm font-medium text-white">
                                                        <a href="{{ route('market.show', $bid->advertisement) }}" class="hover:text-violet-400 transition-colors">
                                                            {{ $bid->advertisement->title }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-cyan-400 font-bold">
                                                € {{ number_format($bid->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                                € {{ number_format($highestBid, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($isHighest)
                                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">
                                                        {{ __('Hoogste Bieder') }}
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500/10 text-red-300 border border-red-500/20">
                                                        {{ __('Overboden') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $bid->created_at->format('d-m-Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">
                                                <a href="{{ route('market.show', $bid->advertisement) }}" class="bg-violet-500/10 text-violet-300 border border-violet-500/20 font-bold px-3 py-1.5 rounded-lg text-xs hover:bg-violet-500/20 transition">{{ __('Bekijk') }}</a>
                                                
                                                <form action="{{ route('bids.destroy', $bid) }}" method="POST" onsubmit="return confirm('{{ __('Weet je zeker dat je dit bod wilt intrekken?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500/10 text-red-300 border border-red-500/20 font-bold px-3 py-1.5 rounded-lg text-xs hover:bg-red-500/20 transition">{{ __('Annuleren') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
