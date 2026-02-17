<div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 overflow-hidden mb-6">
{{--
    Component: Agenda Kalender
    Doel: Weergave van verhuur- en retourdata in een kalenderoverzicht.
--}}
    <div class="p-6">
        <h3 class="font-extrabold text-lg text-slate-800 mb-4 flex items-center gap-2.5">
            <div class="bg-amber-50 p-2 rounded-xl">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            {{ __('Agenda') }}
        </h3>
        <div id="calendar"></div>
    </div>
</div>

{{-- FullCalendar CDN --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

{{-- Light Theme Override for FullCalendar --}}
<style>
    /* FullCalendar Friendly Island Theme */
    .fc {
        --fc-border-color: #e2e8f0;
        --fc-button-bg-color: #ecfdf5;
        --fc-button-border-color: #a7f3d0;
        --fc-button-text-color: #059669;
        --fc-button-hover-bg-color: #d1fae5;
        --fc-button-hover-border-color: #6ee7b7;
        --fc-button-active-bg-color: #a7f3d0;
        --fc-button-active-border-color: #34d399;
        --fc-page-bg-color: transparent;
        --fc-neutral-bg-color: #f8fafc;
        --fc-list-event-hover-bg-color: #f1f5f9;
        --fc-today-bg-color: #ecfdf5;
        --fc-event-bg-color: #d1fae5;
        --fc-event-border-color: #6ee7b7;
        --fc-event-text-color: #065f46;
    }
    .fc .fc-col-header-cell-cushion,
    .fc .fc-daygrid-day-number,
    .fc .fc-list-day-text,
    .fc .fc-list-day-side-text {
        color: #475569;
        font-weight: 600;
    }
    .fc .fc-toolbar-title {
        color: #1e293b;
        font-weight: 800;
    }
    .fc .fc-button {
        border-radius: 9999px !important;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 6px 14px;
        box-shadow: none;
    }
    .fc .fc-button-group > .fc-button {
        border-radius: 0 !important;
    }
    .fc .fc-button-group > .fc-button:first-child {
        border-radius: 9999px 0 0 9999px !important;
    }
    .fc .fc-button-group > .fc-button:last-child {
        border-radius: 0 9999px 9999px 0 !important;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #f1f5f9;
    }
    .fc .fc-daygrid-day.fc-day-today {
        background: #ecfdf5;
    }
    .fc .fc-scrollgrid {
        border-radius: 16px;
        overflow: hidden;
        border-color: #e2e8f0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

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
