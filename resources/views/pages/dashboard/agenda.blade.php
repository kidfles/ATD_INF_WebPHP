<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            <h2 class="text-2xl font-bold text-white mb-6">{{ __('Agenda') }}</h2>

            {{-- Calendar Component --}}
            @if(in_array(Auth::user()->role, ['business_ad', 'private_ad']))
            <x-agenda-calendar />
            @endif

            {{-- Rental List Component --}}
            <x-rental-activities-table :myRentals="$myRentals" :incomingRentals="$incomingRentals" />

        </div>
    </div>
</x-app-layout>
