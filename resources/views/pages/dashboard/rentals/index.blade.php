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

            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100">
                <div class="p-6">
                    
                    @if(session('status'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl mb-4 text-sm font-medium">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if($rentals->isEmpty())
                        <div class="text-center py-12">
                            <div class="bg-slate-50 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-slate-400 font-medium">{{ __('No rentals found.') }}</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Item') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ $view === 'rented' ? __('Owner') : __('Renter') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Start') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('End') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4">{{ __('Status') }}</th>
                                        <th class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider pb-4 text-right">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($rentals as $rental)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-4 pr-4">
                                                @if($rental->advertisement)
                                                    <a href="{{ route('market.show', $rental->advertisement) }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">{{ $rental->advertisement->title }}</a>
                                                @else
                                                    <span class="text-sm font-bold text-slate-400 italic">{{ __('Unavailable') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 pr-4 text-sm text-slate-500">
                                                {{ $view === 'rented' ? ($rental->advertisement->user->name ?? __('Unknown')) : ($rental->renter->name ?? __('Unknown')) }}
                                            </td>
                                            <td class="py-4 pr-4 text-sm text-slate-600 font-semibold">{{ $rental->start_date->format('d M Y') }}</td>
                                            <td class="py-4 pr-4 text-sm text-slate-600 font-semibold">{{ $rental->end_date->format('d M Y') }}</td>
                                            <td class="py-4 pr-4">
                                                @if($rental->status === 'active')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">{{ __('Active') }}</span>
                                                @elseif($rental->status === 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">{{ __('Pending') }}</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-500 border border-red-200">{{ ucfirst(__($rental->status)) }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 text-sm text-right">
                                                @if($view === 'rented' && ($rental->status === 'active' || $rental->status === 'overdue') && $rental->advertisement)
                                                    <form action="{{ route('rentals.return', $rental) }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-end gap-2">
                                                        @csrf
                                                        <input type="file" name="photo" class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" required>
                                                        <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full text-xs font-bold hover:bg-emerald-100 transition-all w-fit">{{ __('Return') }}</button>
                                                    </form>
                                                @elseif($view === 'rented_out' && $rental->return_photo_path)
                                                    <a href="{{ asset('storage/' . $rental->return_photo_path) }}" target="_blank" class="px-3 py-1.5 bg-slate-50 text-slate-600 border border-slate-200 rounded-full text-xs font-bold hover:bg-slate-100 transition-all inline-block">
                                                        {{ __('Check return photo') }}
                                                    </a>
                                                @else
                                                    <span class="text-slate-300">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $rentals->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
