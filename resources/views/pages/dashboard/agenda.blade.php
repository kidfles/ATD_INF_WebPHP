<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agenda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Calendar Container --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div id="calendar"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- FullCalendar CDN --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: '{{ app()->getLocale() }}',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: '{{ route("dashboard.agenda.events") }}',
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    if (props.type === 'rental') {
                        window.location.href = '{{ route("dashboard.rentals.index") }}';
                    } else if (props.type === 'expiry') {
                        window.location.href = '{{ route("dashboard.advertisements.index") }}';
                    }
                },
                height: 'auto',
                buttonText: {
                    today: '{{ __("Today") }}',
                    month: '{{ __("Month") }}',
                    week: '{{ __("Week") }}',
                    list: '{{ __("List") }}'
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>
