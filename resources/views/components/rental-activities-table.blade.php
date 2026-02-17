<div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 overflow-hidden mb-6">
    <div class="p-6">
        <h3 class="font-extrabold text-lg text-slate-800 mb-5 flex items-center gap-2.5">
            <div class="bg-teal-50 p-2 rounded-xl">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            {{ __('Rental Activities') }}
        </h3>
        
        @if($myRentals->isEmpty() && $incomingRentals->isEmpty())
            <p class="text-slate-400 italic font-medium">{{ __('No rental history available yet.') }}</p>
        @else
            <div class="overflow-x-auto -mx-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider w-full">{{ __('Object') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider text-nowrap">{{ __('Dates') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider text-nowrap">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider text-nowrap">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        {{-- Uitgaande Huur (Mijn Huur) --}}
                        @foreach($myRentals as $rental)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium w-full">
                                    <a href="{{ route('market.show', $rental->advertisement) }}" class="text-emerald-600 hover:text-emerald-700 hover:underline transition-colors font-bold">
                                        {{ $rental->advertisement->title }}
                                    </a>
                                    <span class="block text-xs text-slate-400">{{ __('By me') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-teal-50 text-teal-600 border border-teal-200">{{ __('Renting') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($rental->return_photo_path)
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                {{ __('Returned') }}
                                            </span>
                                            <a href="{{ asset('storage/' . $rental->return_photo_path) }}" target="_blank" class="text-xs text-emerald-500 hover:text-emerald-600 hover:underline transition-colors font-semibold">
                                                {{ __('View Photo') }}
                                            </a>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('rentals.return', $rental) }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="flex items-center gap-2 justify-end">
                                                <input type="file" name="photo" required class="text-xs text-slate-500 bg-slate-50 border border-slate-100 rounded-full p-1.5 w-48 file:mr-2 file:py-0.5 file:px-2 file:rounded-full file:border-0 file:text-xs file:bg-emerald-50 file:text-emerald-600 file:font-bold">
                                                <button type="submit" class="text-emerald-600 hover:text-emerald-700 font-bold border border-emerald-200 px-3 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-100 transition-colors text-sm">
                                                    {{ __('Return') }}
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        {{-- Inkomende Huur (Mijn Verhuur) --}}
                        @foreach($incomingRentals as $rental)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium w-full">
                                    <a href="{{ route('market.show', $rental->advertisement) }}" class="text-emerald-600 hover:text-emerald-700 hover:underline transition-colors font-bold">
                                        {{ $rental->advertisement->title }}
                                    </a>
                                    <span class="block text-xs text-slate-400">{{ __('Incoming') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($rental->return_photo_path)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">
                                            {{ __('Returned') }}
                                        </span>
                                    @elseif(now()->startOfDay()->gt($rental->end_date))
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-50 text-red-500 border border-red-200">
                                            {{ __('Overdue') }}
                                        </span>
                                    @elseif(now()->startOfDay()->gte($rental->start_date))
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-teal-50 text-teal-600 border border-teal-200">
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-slate-50 text-slate-500 border border-slate-200">
                                            {{ __('Scheduled') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($rental->return_photo_path)
                                        <a href="{{ asset('storage/' . $rental->return_photo_path) }}" target="_blank" class="text-xs text-emerald-500 hover:text-emerald-600 hover:underline transition-colors font-semibold">
                                            {{ __('View Return') }}
                                        </a>
                                        @if($rental->wear_and_tear_cost > 0)
                                            <span class="block text-xs text-red-500 font-bold">+€{{ number_format($rental->wear_and_tear_cost, 2) }}</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 text-xs text-nowrap">
                                            @if(now()->startOfDay()->gt($rental->end_date))
                                                {{ __('Awaiting Return') }}
                                            @elseif(now()->startOfDay()->gte($rental->start_date))
                                                {{ __('Rented Out') }}
                                            @else
                                                {{ __('Upcoming') }}
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
