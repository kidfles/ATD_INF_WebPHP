<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 text-gray-900">
        <h3 class="font-bold text-lg mb-4">{{ __('Rental Activities') }}</h3>
        
        @if($myRentals->isEmpty() && $incomingRentals->isEmpty())
            <p class="text-gray-500 italic">{{ __('No rental history available yet.') }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-full">{{ __('Object') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-nowrap">{{ __('Dates') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-nowrap">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider text-nowrap">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Uitgaande Huur (Mijn Huur) --}}
                        @foreach($myRentals as $rental)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-full">
                                    <a href="{{ route('market.show', $rental->advertisement) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                        {{ $rental->advertisement->title }}
                                    </a>
                                    <span class="block text-xs text-gray-500">{{ __('By me') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ __('Renting') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($rental->return_photo_path)
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ __('Returned') }}
                                            </span>
                                            <a href="{{ asset('storage/' . $rental->return_photo_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">
                                                {{ __('View Photo') }}
                                            </a>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('rentals.return', $rental) }}" enctype="multipart/form-data">
                                            @csrf
                                            {{-- Photo upload required for return --}}
                                            <div class="flex items-center gap-2 justify-end">
                                                <input type="file" name="photo" required class="text-xs border border-gray-300 rounded p-1 w-48">
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-bold border border-indigo-200 px-2 py-1 rounded bg-indigo-50 hover:bg-indigo-100">
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
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-full">
                                    <a href="{{ route('market.show', $rental->advertisement) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                        {{ $rental->advertisement->title }}
                                    </a>
                                    <span class="block text-xs text-gray-500">{{ __('Incoming') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($rental->return_photo_path)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ __('Returned') }}
                                        </span>
                                    @elseif(now()->startOfDay()->gt($rental->end_date))
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ __('Overdue') }}
                                        </span>
                                    @elseif(now()->startOfDay()->gte($rental->start_date))
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ __('Scheduled') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($rental->return_photo_path)
                                        <a href="{{ asset('storage/' . $rental->return_photo_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">
                                            {{ __('View Return') }}
                                        </a>
                                        @if($rental->wear_and_tear_cost > 0)
                                            <span class="block text-xs text-red-500 font-bold">+€{{ number_format($rental->wear_and_tear_cost, 2) }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-xs text-nowrap">
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
