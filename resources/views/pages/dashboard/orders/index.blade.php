<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Purchases') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Summary Statistics --}}
            @if($orders->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Total Orders') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $orders->count() }}</p>
                        </div>
                        <div class="p-3 bg-indigo-50 rounded-full">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Total Spent') }}</p>
                            <p class="text-2xl font-bold text-gray-900">€ {{ number_format($orders->sum('amount'), 2) }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    @if($orders->isEmpty())
                        <div class="text-center py-16">
                            <div class="bg-gray-50 rounded-full p-4 w-20 h-20 mx-auto flex items-center justify-center mb-4">
                                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <h3 class="mt-2 text-lg font-bold text-gray-900">{{ __('No orders found') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('You haven\'t purchased anything yet.') }}</p>
                            <div class="mt-8">
                                <a href="{{ route('market.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition">
                                    {{ __('Browse Marketplace') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($orders as $order)
                                <div class="group flex flex-col md:flex-row gap-6 p-6 border rounded-xl hover:border-indigo-300 hover:shadow-md transition duration-200">
                                    {{-- Image --}}
                                    <div class="w-full md:w-32 h-32 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden relative">
                                        @if($order->advertisement->image_path)
                                            <img class="w-full h-full object-cover group-hover:scale-105 transition duration-500" src="{{ asset('storage/' . $order->advertisement->image_path) }}" alt="{{ $order->advertisement->title }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Order Details --}}
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $order->advertisement->title }}</h3>
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        {{ __('Order ID') }}: <span class="font-mono text-gray-700">#{{ $order->id }}</span>
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xl font-bold text-gray-900">€ {{ number_format($order->amount, 2) }}</p>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                        {{ __('Paid') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <span class="block text-gray-500 text-xs uppercase font-bold">{{ __('Seller') }}</span>
                                                    <span class="font-medium text-gray-900">{{ $order->seller->name }}</span>
                                                </div>
                                                <div>
                                                    <span class="block text-gray-500 text-xs uppercase font-bold">{{ __('Order Date') }}</span>
                                                    <span class="font-medium text-gray-900 capitalize">{{ $order->created_at->translatedFormat('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end gap-3">
                                            <a href="{{ route('market.show', $order->advertisement) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                                {{ __('View Product') }}
                                            </a>
                                            {{-- Future: Invoice Download --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
