<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto space-y-6">

            <h2 class="text-2xl font-bold text-white">{{ __('My Purchases') }}</h2>
            
            {{-- Summary Statistics --}}
            @if($orders->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">{{ __('Total Orders') }}</p>
                            <p class="text-2xl font-bold text-white">{{ $orders->count() }}</p>
                        </div>
                        <div class="p-3 bg-violet-500/10 rounded-xl">
                            <svg class="w-8 h-8 text-violet-400 drop-shadow-[0_0_5px_rgba(139,92,246,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                    </div>
                    <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">{{ __('Total Spent') }}</p>
                            <p class="text-2xl font-bold text-white">€ {{ number_format($orders->sum('amount'), 2) }}</p>
                        </div>
                        <div class="p-3 bg-emerald-500/10 rounded-xl">
                            <svg class="w-8 h-8 text-emerald-400 drop-shadow-[0_0_5px_rgba(16,185,129,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl">
                <div class="p-6">
                    @if($orders->isEmpty())
                        <div class="text-center py-16">
                            <div class="bg-white/5 border border-white/10 rounded-full p-4 w-20 h-20 mx-auto flex items-center justify-center mb-4">
                                <svg class="h-10 w-10 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <h3 class="mt-2 text-lg font-bold text-white">{{ __('No orders found') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('You haven\'t purchased anything yet.') }}</p>
                            <div class="mt-8">
                                <a href="{{ route('market.index') }}" class="inline-flex items-center px-6 py-3 bg-violet-500/20 text-violet-300 border border-violet-500/30 text-base font-medium rounded-xl hover:bg-violet-500/30 shadow-sm transition">
                                    {{ __('Browse Marketplace') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="space-y-5">
                            @foreach($orders as $order)
                                <div class="group flex flex-col md:flex-row gap-5 p-5 bg-white/5 border border-white/5 rounded-xl hover:border-violet-500/30 transition duration-200">
                                    {{-- Image --}}
                                    <div class="w-full md:w-32 h-32 flex-shrink-0 bg-white/5 rounded-lg overflow-hidden relative">
                                        @if($order->advertisement->image_path)
                                            <img class="w-full h-full object-cover group-hover:scale-105 transition duration-500" src="{{ asset('storage/' . $order->advertisement->image_path) }}" alt="{{ $order->advertisement->title }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-600">
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
                                                    <h3 class="text-lg font-bold text-white group-hover:text-violet-400 transition">{{ $order->advertisement->title }}</h3>
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        {{ __('Order ID') }}: <span class="font-mono text-gray-400">#{{ $order->id }}</span>
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xl font-bold text-white">€ {{ number_format($order->amount, 2) }}</p>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 mt-1">
                                                        {{ __('Paid') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <span class="block text-gray-500 text-xs uppercase font-bold">{{ __('Seller') }}</span>
                                                    <span class="font-medium text-gray-300">{{ $order->seller->name }}</span>
                                                </div>
                                                <div>
                                                    <span class="block text-gray-500 text-xs uppercase font-bold">{{ __('Order Date') }}</span>
                                                    <span class="font-medium text-gray-300 capitalize">{{ $order->created_at->translatedFormat('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-4 border-t border-white/5 flex justify-end gap-3">
                                            @if($order->advertisement)
                                                <a href="{{ route('market.show', $order->advertisement) }}" class="inline-flex items-center px-4 py-2 bg-violet-500/10 text-violet-300 border border-violet-500/20 rounded-xl font-semibold text-xs uppercase tracking-widest hover:bg-violet-500/20 transition">
                                                    {{ __('View Product') }}
                                                </a>
                                            @else
                                                <span class="inline-flex items-center px-4 py-2 bg-white/5 border border-white/5 rounded-xl font-semibold text-xs text-gray-500 uppercase tracking-widest cursor-not-allowed">
                                                    {{ __('Product Unavailable') }}
                                                </span>
                                            @endif
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
