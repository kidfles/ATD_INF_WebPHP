<x-app-layout>
    {{--
        Pagina: Agenda
        Doel: Toont een overzicht van geplande afspraken of evenementen.
        Status: Work in progress (WIP).
    --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto">

            <h2 class="text-2xl font-extrabold text-slate-800 mb-6">{{ __('Agenda') }}</h2>

            {{-- Calendar Component --}}
            @if(Auth::user()->isAdvertiser())
            <x-agenda-calendar />
            @endif

            {{-- Rental List Component --}}
            <x-rental-activities-table :myRentals="$myRentals" :incomingRentals="$incomingRentals" />

        </div>
    </div>
</x-app-layout>
