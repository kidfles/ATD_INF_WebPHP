<x-app-layout>
    {{--
        Pagina: Mijn Huuritems
        Doel: Overzicht van items die de gebruiker momenteel huurt.
        Bevat: Huurperiode, status en retourformulier (indien actief).
    --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
                    <div class="p-6 md:p-8">
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
                            <div class="overflow-x-auto scrollbar-hide">
                                {{-- Removed min-w-full to prevent forced stretching, set text wrapping to normal --}}
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="border-b border-slate-100">
                                            <th class="py-3 pr-4 text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Object') }}</th>
                                            <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $view === 'rented' ? __('Owner') : __('Renter') }}</th>
                                            <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Pricing') }}</th>
                                            <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Dates') }}</th>
                                            <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Status') }}</th>
                                            <th class="pl-4 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    
                                    {{-- Loop over items and give each its own tbody for the accordion effect --}}
                                    @foreach($rentals as $rental)
                                        <tbody x-data="{ expanded: false }" class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-colors">
                                            <tr>
                                                <td class="py-4 pr-4 align-top text-sm font-medium w-[25%]">
                                                    @if($rental->advertisement)
                                                        <a href="{{ route('market.show', $rental->advertisement) }}" class="text-emerald-600 hover:text-emerald-700 hover:underline transition-colors font-bold block mb-1">
                                                            {{ $rental->advertisement->title }}
                                                        </a>
                                                        <span class="inline-block text-xs font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
                                                            {{ $view === 'rented' ? __('Outgoing') : __('Incoming') }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-400 italic">{{ __('Unavailable') }}</span>
                                                    @endif
                                                </td>
                                                
                                                <td class="px-4 py-4 align-top text-sm text-slate-500">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-6 h-6 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                                            {{ substr($view === 'rented' ? ($rental->advertisement->user->name ?? 'U') : ($rental->renter->name ?? 'U'), 0, 1) }}
                                                        </div>
                                                        <span class="font-medium whitespace-normal">
                                                            {{ $view === 'rented' ? ($rental->advertisement->user->name ?? __('Unknown')) : ($rental->renter->name ?? __('Unknown')) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                
                                                <td class="px-4 py-4 align-top text-sm">
                                                    <div class="flex flex-col items-start">
                                                        <button type="button" 
                                                                @click="expanded = !expanded" 
                                                                class="font-extrabold text-emerald-600 italic text-xs bg-emerald-50/50 px-2.5 py-1.5 rounded-lg border border-emerald-100 hover:bg-emerald-100 transition-all flex items-center gap-1.5 active:scale-95 w-fit">
                                                            €{{ number_format($rental->total_cost, 2) }}
                                                            <svg class="w-3 h-3 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                                        </button>
                                                        @if($rental->total_cost > $rental->total_price)
                                                            <span class="text-[10px] font-bold text-red-400/80 mt-1.5 ml-0.5">{{ __('Incl. fees') }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                
                                                <td class="px-4 py-4 align-top text-sm text-slate-500">
                                                    <div class="whitespace-nowrap">
                                                        <span class="font-medium text-slate-700">{{ $rental->start_date->format('M d') }}</span>
                                                        <span class="text-slate-400 mx-1">to</span>
                                                        <span class="font-medium text-slate-700">{{ $rental->end_date->format('M d') }}</span>
                                                    </div>
                                                </td>
                                                
                                                <td class="px-4 py-4 align-top text-sm">
                                                    @if($rental->status === 'returned')
                                                        <span class="px-2.5 py-1 inline-flex text-[11px] font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">{{ __('Returned') }}</span>
                                                    @elseif($rental->status === 'active')
                                                        <span class="px-2.5 py-1 inline-flex text-[11px] font-bold rounded-full bg-teal-50 text-teal-600 border border-teal-200">{{ __('Active') }}</span>
                                                    @elseif($rental->status === 'overdue')
                                                        <span class="px-2.5 py-1 inline-flex text-[11px] font-bold rounded-full bg-red-50 text-red-500 border border-red-200">{{ __('Overdue') }}</span>
                                                    @else
                                                        <span class="px-2.5 py-1 inline-flex text-[11px] font-bold rounded-full bg-slate-50 text-slate-500 border border-slate-200">{{ ucfirst(__($rental->status)) }}</span>
                                                    @endif
                                                </td>
                                                
                                                <td class="pl-4 py-4 align-top text-right text-sm font-medium">
                                                    @if($view === 'rented' && ($rental->status === 'active' || $rental->status === 'overdue') && $rental->advertisement)
                                                        <form action="{{ route('rentals.return', $rental) }}" method="POST" enctype="multipart/form-data" class="flex flex-col xl:flex-row items-end xl:items-center gap-2 justify-end">
                                                            @csrf
                                                            <input type="file" name="photo" class="hidden" id="return_photo_{{ $rental->id }}" required>
                                                            <label for="return_photo_{{ $rental->id }}" class="text-[10px] font-bold text-slate-400 cursor-pointer hover:text-emerald-500 transition-colors uppercase whitespace-nowrap">{{ __('Photo') }}</label>
                                                            <button type="submit" class="text-emerald-600 hover:text-emerald-700 font-bold border border-emerald-200 px-3 py-1 rounded-full bg-emerald-50 hover:bg-emerald-100 transition-colors text-xs whitespace-nowrap">{{ __('Return') }}</button>
                                                        </form>
                                                    @elseif($rental->return_photo_path)
                                                        <a href="{{ asset($rental->return_photo_path) }}" target="_blank" class="text-xs text-emerald-500 hover:text-emerald-600 hover:underline transition-colors font-semibold whitespace-nowrap">
                                                            {{ __('View Return') }}
                                                        </a>
                                                    @else
                                                        <span class="text-slate-200">—</span>
                                                    @endif
                                                </td>
                                            </tr>

                                            {{-- Expandable Cost Breakdown Row --}}
                                            <tr x-show="expanded" x-transition.opacity style="display: none;">
                                                <td colspan="6" class="pb-6 pt-2">
                                                    <div class="bg-white border border-slate-200 shadow-sm rounded-xl p-5 mx-4 lg:mx-0 lg:ml-auto max-w-sm relative overflow-hidden">
                                                        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-400"></div>
                                                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2 mb-3">{{ __('Cost Breakdown') }}</h4>
                                                        
                                                        <div class="space-y-2 text-sm">
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-slate-500">{{ __('Base Price') }}</span>
                                                                <span class="font-bold text-slate-800">€{{ number_format($rental->total_price, 2) }}</span>
                                                            </div>
                                                            
                                                            @if($rental->total_cost > $rental->total_price)
                                                                <div class="flex justify-between items-center">
                                                                    <span class="text-red-500 font-medium">{{ __('Penalty & Fees') }}</span>
                                                                    <span class="font-bold text-red-500">+€{{ number_format($rental->total_cost - $rental->total_price, 2) }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center">
                                                            <span class="font-extrabold text-slate-800">{{ __('Total Paid') }}</span>
                                                            <span class="font-extrabold text-lg text-emerald-600">€{{ number_format($rental->total_cost, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    @endforeach
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