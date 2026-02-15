<x-whitelabel-layout :company="$company">
    <div class="min-h-screen">
        {{-- Header / Hero --}}
        <header class="py-12 px-6 text-center">
            <h1 class="text-4xl font-bold mb-4">{{ $company->user->name }}</h1>
            <p class="text-xl opacity-90">Welcome to our page</p>
        </header>

        {{-- Content Area --}}
        <div class="max-w-6xl mx-auto px-6 py-8 bg-white text-gray-900 rounded-lg shadow-lg m-6">
            <h2 class="text-2xl font-bold mb-6">Our Offerings</h2>
            
            {{-- Here we could list the company's ads if we had the relation set up --}}
            <p>Welcome to the official page of {{ $company->user->name }}.</p>
            
            <div class="mt-8 text-center">
                <a href="{{ route('market.index') }}" class="text-blue-500 hover:underline">Back to Market</a>
            </div>
        </div>
    </div>
</x-whitelabel-layout>
