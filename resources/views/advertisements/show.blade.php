<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Advertisement Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Main Details -->
                        <div class="md:col-span-2">
                             <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $advertisement->title }}</h1>
                                    <div class="flex items-center space-x-2 text-gray-500 text-sm">
                                        <span>By {{ $advertisement->user->name }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $advertisement->created_at->format('d M Y') }}</span>
                                        <span>&bull;</span>
                                        <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2 py-0.5 rounded uppercase">{{ $advertisement->type }}</span>
                                    </div>
                                </div>
                                <p class="text-2xl font-bold text-indigo-600">€{{ number_format($advertisement->price, 2, ',', '.') }}</p>
                            </div>
                            
                            <hr class="my-4 border-gray-200">
                            
                            <div class="prose max-w-none text-gray-700">
                                {{ $advertisement->description }}
                            </div>
                            
                            <div class="mt-8">
                                <a href="{{ route('advertisements.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">&larr; Back to Listings</a>
                            </div>
                        </div>
                        
                        <!-- Sidebar: Upsells / Actions -->
                        <div class="md:col-span-1 space-y-6">
                            <!-- Actions -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                @if(auth()->id() === $advertisement->user_id)
                                    <a href="{{ route('advertisements.edit', $advertisement) }}" class="block w-full text-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded mb-2">
                                        Edit Advertisement
                                    </a>
                                @endif
                                
                                <button class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Contact Seller
                                </button>
                            </div>
                            
                            <!-- Upsells -->
                            @if($advertisement->upsells->count() > 0)
                                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                                    <h3 class="font-bold text-indigo-900 mb-3">Often Bought Together</h3>
                                    <ul class="space-y-3">
                                        @foreach($advertisement->upsells as $upsell)
                                            <li class="bg-white p-3 rounded shadow-sm flex justify-between items-center">
                                                <div>
                                                    <a href="{{ route('advertisements.show', $upsell) }}" class="font-medium text-gray-900 hover:underline">
                                                        {{ $upsell->title }}
                                                    </a>
                                                    <p class="text-sm text-gray-500">€{{ number_format($upsell->price, 2) }}</p>
                                                </div>
                                                <a href="{{ route('advertisements.show', $upsell) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                                                    View
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
