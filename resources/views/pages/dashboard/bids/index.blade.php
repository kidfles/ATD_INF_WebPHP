<x-app-layout>
    {{--
        Pagina: Mijn Biedingen
        Doel: Overzicht van alle biedingen die de gebruiker heeft geplaatst.
        Bevat: Advertentie details, bod status (hoogste/overboden) en bedrag.
    --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            <h2 class="text-2xl font-extrabold text-slate-800 mb-6">{{ __('Mijn Biedingen') }}</h2>

            {{-- Filter and Sort Form --}}
            <form action="{{ route('dashboard.bids.index') }}" method="GET" class="mb-8 flex flex-wrap gap-4 items-center bg-white p-5 rounded-[2rem] shadow-soft border border-slate-100">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="{{ __('Search by product name...') }}" 
                               class="w-full bg-slate-50 border-transparent rounded-xl text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all pl-10">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <select name="sort" class="bg-slate-50 border-transparent rounded-xl text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest first') }}</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('Oldest first') }}</option>
                    <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>{{ __('Bid: Low to High') }}</option>
                    <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>{{ __('Bid: High to Low') }}</option>
                </select>

                <button type="submit" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-6 py-2 rounded-full font-bold text-sm shadow-lg hover:-translate-y-0.5 transition-all">
                    {{ __('Filter') }}
                </button>

                @if(request()->hasAny(['search', 'sort']))
                    <a href="{{ route('dashboard.bids.index') }}" class="text-sm text-slate-400 hover:text-emerald-500 font-medium transition-colors">
                        {{ __('Clear') }}
                    </a>
                @endif
            </form>

            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100">
                <div class="p-6">
                    @if($bids->isEmpty())
                        <div class="text-center py-12">
                            <div class="bg-slate-50 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            </div>
                            <p class="text-slate-400 font-medium">{{ __('No bids found.') }}</p>
                            @if(!request()->hasAny(['search', 'sort']))
                                <a href="{{ route('market.index') }}" class="inline-flex items-center mt-4 px-5 py-2.5 bg-gradient-to-r from-emerald-400 to-teal-500 text-white rounded-full text-sm font-bold shadow-sm hover:shadow-emerald-500/30 transition-all">{{ __('Explore marketplace') }}</a>
                            @endif
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Product') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Seller') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Your Bid') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Highest Bid') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Status') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Date') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($bids as $bid)
                                        @php
                                            $highestBid = $bid->advertisement ? $bid->advertisement->bids->max('amount') : 0;
                                            $isHighest = $bid->amount >= $highestBid;
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-4 pr-4">
                                                @if($bid->advertisement)
                                                    <a href="{{ route('market.show', $bid->advertisement) }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">{{ $bid->advertisement->title }}</a>
                                                @else
                                                    <span class="text-sm font-bold text-slate-400 italic">{{ __('Unavailable') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 pr-4 text-sm text-slate-500">{{ $bid->advertisement->user->name ?? __('Unknown') }}</td>
                                            <td class="py-4 pr-4 text-sm font-extrabold text-slate-800">€{{ number_format($bid->amount, 2) }}</td>
                                            <td class="py-4 pr-4 text-sm font-bold text-slate-600">€{{ number_format($highestBid, 2) }}</td>
                                            <td class="py-4 pr-4">
                                                @if($isHighest)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">{{ __('Highest Bid') }}</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">{{ __('Outbid') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 pr-4 text-sm text-slate-400">{{ $bid->created_at->format('d M Y') }}</td>
                                            <td class="py-4">
                                                <form action="{{ route('bids.destroy', $bid) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this bid?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $bids->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
