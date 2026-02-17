<div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden mb-6">
    <div class="p-6">
        <h3 class="font-bold text-lg text-white mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 text-cyan-400 drop-shadow-[0_0_5px_rgba(6,182,212,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            {{ __('Rental Activities') }}
        </h3>
        
        @if($myRentals->isEmpty() && $incomingRentals->isEmpty())
            <p class="text-gray-500 italic">{{ __('No rental history available yet.') }}</p>
        @else
            <div class="overflow-x-auto -mx-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-full">{{ __('Object') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider text-nowrap">{{ __('Dates') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider text-nowrap">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider text-nowrap">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        {{-- Uitgaande Huur (Mijn Huur) --}}
                        @foreach($myRentals as $rental)
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium w-full">
                                    <a href="{{ route('market.show', $rental->advertisement) }}" class="text-violet-400 hover:text-violet-300 hover:underline transition-colors">
                                        {{ $rental->advertisement->title }}
                                    </a>
                                    <span class="block text-xs text-gray-500">{{ __('By me') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-500/10 text-cyan-300 border border-cyan-500/20">{{ __('Renting') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($rental->return_photo_path)
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">
                                                {{ __('Returned') }}
                                            </span>
                                            <a href="{{ asset('storage/' . $rental->return_photo_path) }}" target="_blank" class="text-xs text-violet-400 hover:text-violet-300 hover:underline transition-colors">
                                                {{ __('View Photo') }}
                                            </a>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('rentals.return', $rental) }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="flex items-center gap-2 justify-end">
                                                <input type="file" name="photo" required class="text-xs text-gray-400 bg-white/5 border border-white/10 rounded-lg p-1.5 w-48 file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-xs file:bg-violet-500/20 file:text-violet-300">
                                                <button type="submit" class="text-violet-400 hover:text-violet-300 font-bold border border-violet-500/20 px-3 py-1.5 rounded-lg bg-violet-500/10 hover:bg-violet-500/20 transition-colors">
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
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium w-full">
                                    <a href="{{ route('market.show', $rental->advertisement) }}" class="text-violet-400 hover:text-violet-300 hover:underline transition-colors">
                                        {{ $rental->advertisement->title }}
                                    </a>
                                    <span class="block text-xs text-gray-500">{{ __('Incoming') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($rental->return_photo_path)
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">
                                            {{ __('Returned') }}
                                        </span>
                                    @elseif(now()->startOfDay()->gt($rental->end_date))
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500/10 text-red-300 border border-red-500/20">
                                            {{ __('Overdue') }}
                                        </span>
                                    @elseif(now()->startOfDay()->gte($rental->start_date))
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-500/10 text-cyan-300 border border-cyan-500/20">
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-500/10 text-gray-400 border border-gray-500/20">
                                            {{ __('Scheduled') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($rental->return_photo_path)
                                        <a href="{{ asset('storage/' . $rental->return_photo_path) }}" target="_blank" class="text-xs text-violet-400 hover:text-violet-300 hover:underline transition-colors">
                                            {{ __('View Return') }}
                                        </a>
                                        @if($rental->wear_and_tear_cost > 0)
                                            <span class="block text-xs text-red-400 font-bold">+€{{ number_format($rental->wear_and_tear_cost, 2) }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500 text-xs text-nowrap">
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
