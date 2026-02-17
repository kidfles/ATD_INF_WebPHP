<x-app-layout>
    {{--
        Pagina: Mijn Biedingen
        Doel: Overzicht van alle biedingen die de gebruiker heeft geplaatst.
        Bevat: Advertentie details, bod status (hoogste/overboden) en bedrag.
    --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            <h2 class="text-2xl font-extrabold text-slate-800 mb-6">{{ __('Mijn Biedingen') }}</h2>

            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 overflow-hidden">
                <div class="p-6">
                    
                    @if($bids->isEmpty())
                        <div class="text-center py-12">
                            <div class="bg-slate-50 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <p class="text-slate-400 font-medium">{{ __('Je hebt nog geen biedingen geplaatst.') }}</p>
                            <a href="{{ route('market.index') }}" class="inline-flex items-center mt-4 px-5 py-2.5 bg-gradient-to-r from-emerald-400 to-teal-500 text-white rounded-full text-sm font-bold shadow-sm hover:shadow-emerald-500/30 transition-all">{{ __('Ontdek de marktplaats') }}</a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Advertentie') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Type') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Mijn Bod') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Hoogste Bod') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Status') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Datum') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($bids as $bid)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-4 pr-4">
                                                <a href="{{ route('market.show', $bid->advertisement) }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">{{ $bid->advertisement->title }}</a>
                                            </td>
                                            <td class="py-4 pr-4">
                                                <span class="px-2.5 py-1 rounded-full bg-slate-50 border border-slate-100 text-xs font-bold text-slate-500">{{ ucfirst($bid->advertisement->type) }}</span>
                                            </td>
                                            <td class="py-4 pr-4 text-sm font-extrabold text-slate-800">€{{ number_format($bid->amount, 2) }}</td>
                                            <td class="py-4 pr-4 text-sm text-slate-500">
                                                @php $highest = $bid->advertisement->bids->max('amount'); @endphp
                                                €{{ number_format($highest, 2) }}
                                            </td>
                                            <td class="py-4 pr-4">
                                                @if($bid->amount >= $highest)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">{{ __('Hoogste') }}</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-500 border border-red-200">{{ __('Overboden') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 text-sm text-slate-400">{{ $bid->created_at->diffForHumans() }}</td>
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
