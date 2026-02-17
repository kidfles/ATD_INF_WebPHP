<x-app-layout>
    {{--
        Pagina: Mijn Aankopen
        Doel: Historisch overzicht van gekochte items.
        Bevat: Besteldetails, totale uitgaven en datum van aankoop.
    --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto space-y-6">

            <h2 class="text-2xl font-extrabold text-slate-800">{{ __('My Purchases') }}</h2>
            
            {{-- Summary Statistics --}}
            @if($orders->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-400">{{ __('Total Purchases') }}</p>
                            <p class="text-3xl font-extrabold text-slate-800">{{ $orders->count() }}</p>
                        </div>
                        <div class="bg-emerald-50 p-3 rounded-2xl">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-400">{{ __('Total Spent') }}</p>
                            <p class="text-3xl font-extrabold text-emerald-500">€{{ number_format($orders->sum('amount'), 2) }}</p>
                        </div>
                        <div class="bg-teal-50 p-3 rounded-2xl">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Filter and Sort Form --}}
            <form action="{{ route('dashboard.orders.index') }}" method="GET" class="mb-8 flex flex-wrap gap-4 items-center bg-white p-5 rounded-[2rem] shadow-soft border border-slate-100">
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
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                </select>

                <button type="submit" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-6 py-2 rounded-full font-bold text-sm shadow-lg hover:-translate-y-0.5 transition-all">
                    {{ __('Filter') }}
                </button>

                @if(request()->hasAny(['search', 'sort']))
                    <a href="{{ route('dashboard.orders.index') }}" class="text-sm text-slate-400 hover:text-emerald-500 font-medium transition-colors">
                        {{ __('Clear') }}
                    </a>
                @endif
            </form>

            {{-- Orders List --}}
            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 overflow-hidden">
                <div class="p-6">
                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <div class="bg-slate-50 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <p class="text-slate-400 font-medium">{{ __('No purchases found') }}</p>
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
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Price') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Qty') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Total') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-4 pr-4">
                                                @if($order->advertisement)
                                                    <a href="{{ route('market.show', $order->advertisement) }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">{{ $order->advertisement->title }}</a>
                                                @else
                                                    <span class="text-sm font-bold text-slate-400 italic">{{ __('Unavailable') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 pr-4 text-sm text-slate-500">{{ $order->seller->name ?? __('Unknown') }}</td>
                                            <td class="py-4 pr-4 text-sm text-slate-600 font-semibold">€{{ number_format($order->advertisement->price ?? $order->amount, 2) }}</td>
                                            <td class="py-4 pr-4 text-sm text-slate-500">1</td>
                                            <td class="py-4 pr-4 text-sm font-extrabold text-slate-800">€{{ number_format($order->amount, 2) }}</td>
                                            <td class="py-4 text-sm text-slate-400">{{ $order->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
