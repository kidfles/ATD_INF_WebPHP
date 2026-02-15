<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mijn Biedingen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($bids->isEmpty())
                        <p class="text-gray-500">Je hebt nog geen biedingen geplaatst.</p>
                        <a href="{{ route('market.index', ['type' => 'auction']) }}" class="text-blue-600 hover:underline mt-2 inline-block">Bekijk veilingen &rarr;</a>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Advertentie</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mijn Bod</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hoogste Bod</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actie</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bids as $bid)
                                        @php
                                            $highestBid = $bid->advertisement->bids->max('amount');
                                            $isHighest = $bid->amount >= $highestBid;
                                            $isWinning = $isHighest; // Simplified logic, in real app check if auction closed etc.
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($bid->advertisement->image_path)
                                                        <img class="h-10 w-10 rounded-full object-cover mr-3" src="{{ asset('storage/' . $bid->advertisement->image_path) }}" alt="">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-xs">IMG</div>
                                                    @endif
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('market.show', $bid->advertisement) }}" class="hover:underline">
                                                            {{ $bid->advertisement->title }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                                € {{ number_format($bid->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                € {{ number_format($highestBid, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($isHighest)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Hoogste Bieder
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Overboden
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $bid->created_at->format('d-m-Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-3">
                                                <a href="{{ route('market.show', $bid->advertisement) }}" class="text-indigo-600 hover:text-indigo-900">Bekijk</a>
                                                
                                                <form action="{{ route('bids.destroy', $bid) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je dit bod wilt intrekken?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Annuleren</button>
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
