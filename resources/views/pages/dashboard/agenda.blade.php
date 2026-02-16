<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agenda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Calendar Component --}}
            @if(in_array(Auth::user()->role, ['business_ad', 'private_ad']))
            <x-agenda-calendar />
            @endif

            {{-- Rental List Component --}}
            <x-rental-activities-table :myRentals="$myRentals" :incomingRentals="$incomingRentals" />

        </div>
    </div>
</x-app-layout>
