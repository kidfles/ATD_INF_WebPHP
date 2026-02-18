<x-app-layout>
    {{--
        Pagina: Mijn Huuritems
        Doel: Overzicht van items die de gebruiker momenteel huurt.
        Bevat: Huurperiode, status en retourformulier (indien actief).
    --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">{{ __('My Rental Activities') }}</h2>
                
                <div class="inline-flex p-1 bg-slate-100 rounded-2xl">
                    <a href="{{ route('dashboard.rentals.index', array_merge(request()->query(), ['view' => 'rented'])) }}" 
                       class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $view === 'rented' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        {{ __('My Bookings') }}
                    </a>
                    <a href="{{ route('dashboard.rentals.index', array_merge(request()->query(), ['view' => 'rented_out'])) }}" 
                       class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $view === 'rented_out' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        {{ __('My Rentals') }}
                    </a>
                </div>
            </div>

            {{-- Filter and Sort Form --}}
            <form action="{{ route('dashboard.rentals.index') }}" method="GET" class="mb-8 flex flex-wrap gap-4 items-center bg-white p-5 rounded-[2rem] shadow-soft border border-slate-100">
                <input type="hidden" name="view" value="{{ $view }}">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="{{ __('Search by product name...') }}" 
                               class="w-full bg-slate-50 border-transparent rounded-xl text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all pl-10">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <select name="status" class="bg-slate-50 border-transparent rounded-xl text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all" onchange="this.form.submit()">
                    <option value="">{{ __('All statuses') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>{{ __('Returned') }}</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>{{ __('Overdue') }}</option>
                </select>

                <select name="sort" class="bg-slate-50 border-transparent rounded-xl text-sm text-slate-700 focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50 transition-all" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest first') }}</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('Oldest first') }}</option>
                    <option value="start_asc" {{ request('sort') == 'start_asc' ? 'selected' : '' }}>{{ __('Start Date: Soonest') }}</option>
                    <option value="start_desc" {{ request('sort') == 'start_desc' ? 'selected' : '' }}>{{ __('Start Date: Furthest') }}</option>
                </select>

                <button type="submit" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-6 py-2 rounded-full font-bold text-sm shadow-lg hover:-translate-y-0.5 transition-all">
                    {{ __('Filter') }}
                </button>

                @if(request()->hasAny(['search', 'status', 'sort']))
                    <a href="{{ route('dashboard.rentals.index', ['view' => $view]) }}" class="text-sm text-slate-400 hover:text-emerald-500 font-medium transition-colors">
                        {{ __('Clear') }}
                    </a>
                @endif
            </form>

            <div class="opacity-0 animate-pop-in mb-6" style="animation-delay: 100ms;">
                <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 mb-6">
                    <div class="p-6">
                        <h3 class="font-extrabold text-lg text-slate-800 mb-5 flex items-center gap-2.5">
                            <div class="bg-teal-50 p-2 rounded-xl">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            {{ __('Rental Activities') }}
                        </h3>

                        @if(session('status'))
                            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-bold text-sm">{{ session('status') }}</span>
                            </div>
                        @endif
                    
                        @if($rentals->isEmpty())
                            <p class="text-slate-400 italic font-medium py-10 text-center">{{ __('No rental history available yet.') }}</p>
@else
                            <div class="overflow-x-auto -mx-6 px-6 scrollbar-hide">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-slate-100">
                                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Object') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider text-nowrap">{{ $view === 'rented' ? __('Owner') : __('Renter') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider text-nowrap">{{ __('Pricing') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider text-nowrap">{{ __('Dates') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider text-nowrap">{{ __('Status') }}</th>
                                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider text-nowrap">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($rentals as $rental)
                                            <tr class="hover:bg-slate-50 transition">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($rental->advertisement)
                                                        <a href="{{ route('market.show', $rental->advertisement) }}" class="text-emerald-600 hover:text-emerald-700 hover:underline transition-colors font-bold whitespace-normal">
                                                            {{ $rental->advertisement->title }}
                                                        </a>
                                                        <span class="block text-xs text-slate-400">
                                                            {{ $view === 'rented' ? __('Outgoing') : __('Incoming') }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-400 italic">{{ __('Unavailable') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[8px] font-bold text-slate-500">
                                                            {{ substr($view === 'rented' ? ($rental->advertisement->user->name ?? 'U') : ($rental->renter->name ?? 'U'), 0, 1) }}
                                                        </div>
                                                        <span class="font-medium">
                                                            {{ $view === 'rented' ? ($rental->advertisement->user->name ?? __('Unknown')) : ($rental->renter->name ?? __('Unknown')) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <div x-data="{ open: false }" class="relative flex flex-col items-start cursor-pointer" @click.away="open = false">
                                                        <button type="button" 
                                                                @click="open = !open" 
                                                                class="font-extrabold text-emerald-600 italic text-xs bg-emerald-50/50 px-2.5 py-1 rounded-lg border border-emerald-100 hover:bg-emerald-100 transition-all flex items-center gap-1.5 active:scale-95">
                                                            €{{ number_format($rental->total_cost, 2) }}
                                                            <svg class="w-2.5 h-2.5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                                        </button>
                                                        
                                                        {{-- Click Calculation Breakdown --}}
                                                        <div x-show="open" 
                                                             x-transition:enter="transition ease-out duration-200"
                                                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                             x-transition:leave="transition ease-in duration-100"
                                                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                                             class="absolute top-full left-0 mt-3 z-[100] min-w-[200px]" 
                                                             style="display: none;">
                                                            <div class="bg-white opacity-100 text-slate-900 py-3 px-4 rounded-2xl shadow-2xl border border-slate-100 ring-1 ring-slate-900/5">
                                                                <p class="text-slate-400 uppercase tracking-widest text-[9px] font-extrabold mb-2 border-b border-slate-50 pb-2">{{ __('Calculation') }}</p>
                                                                <div class="space-y-1.5 text-[11px]">
                                                                    <div class="flex justify-between items-center gap-4">
                                                                        <span class="text-slate-500">{{ __('Base Price') }}</span>
                                                                        <span class="font-extrabold text-slate-800">€{{ number_format($rental->total_price, 2) }}</span>
                                                                    </div>
                                                                    @if($rental->total_cost > $rental->total_price)
                                                                        <div class="flex justify-between items-center gap-4 pt-1.5 border-t border-slate-50">
                                                                            <span class="text-red-500 font-bold">{{ __('Penalty & Fees') }}</span>
                                                                            <span class="font-extrabold text-red-500">+€{{ number_format($rental->total_cost - $rental->total_price, 2) }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="mt-3 pt-2 border-t border-slate-100 flex justify-between items-center font-extrabold text-emerald-600 text-xs">
                                                                    <span>{{ __('Total') }}</span>
                                                                    <span>€{{ number_format($rental->total_cost, 2) }}</span>
                                                                </div>
                                                                
                                                                {{-- Tooltip Arrow --}}
                                                                <div class="absolute -top-1.5 left-5 w-3 h-3 bg-white rotate-45 border-l border-t border-slate-100"></div>
                                                            </div>
                                                        </div>

                                                        @if($rental->total_cost > $rental->total_price)
                                                            <span class="text-[9px] font-bold text-red-400/70 mt-1 ml-0.5">{{ __('Incl. fees') }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                                    {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($rental->status === 'returned')
                                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">{{ __('Returned') }}</span>
                                                    @elseif($rental->status === 'active')
                                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-teal-50 text-teal-600 border border-teal-200">{{ __('Active') }}</span>
                                                    @elseif($rental->status === 'overdue')
                                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-50 text-red-500 border border-red-200">{{ __('Overdue') }}</span>
                                                    @else
                                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-slate-50 text-slate-500 border border-slate-200">{{ ucfirst(__($rental->status)) }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    @if($view === 'rented' && ($rental->status === 'active' || $rental->status === 'overdue') && $rental->advertisement)
                                                        <form action="{{ route('rentals.return', $rental) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 justify-end">
                                                            @csrf
                                                            <input type="file" name="photo" class="hidden" id="return_photo_{{ $rental->id }}" required>
                                                            <label for="return_photo_{{ $rental->id }}" class="text-[10px] font-bold text-slate-400 cursor-pointer hover:text-emerald-500 transition-colors uppercase">{{ __('Photo') }}</label>
                                                            <button type="submit" class="text-emerald-600 hover:text-emerald-700 font-bold border border-emerald-200 px-3 py-1 rounded-full bg-emerald-50 hover:bg-emerald-100 transition-colors text-xs">{{ __('Return') }}</button>
                                                        </form>
                                                    @elseif($rental->return_photo_path)
                                                        <a href="{{ asset($rental->return_photo_path) }}" target="_blank" class="text-xs text-emerald-500 hover:text-emerald-600 hover:underline transition-colors font-semibold">
                                                            {{ __('View Return') }}
                                                        </a>
                                                    @else
                                                        <span class="text-slate-200">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-8 pt-6 border-t border-slate-50">
                                {{ $rentals->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
