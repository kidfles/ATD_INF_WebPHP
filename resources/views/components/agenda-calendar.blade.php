<div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden mb-6">
    <div class="p-6">
        <h3 class="font-bold text-lg text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-400 drop-shadow-[0_0_5px_rgba(245,158,11,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            {{ __('Agenda') }}
        </h3>
        <div id="calendar"></div>
    </div>
</div>

{{-- FullCalendar CDN --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

{{-- Dark Theme Override for FullCalendar --}}
<style>
    /* FullCalendar Deep Space Theme */
    .fc {
        --fc-border-color: rgba(255, 255, 255, 0.08);
        --fc-button-bg-color: rgba(139, 92, 246, 0.15);
        --fc-button-border-color: rgba(139, 92, 246, 0.3);
        --fc-button-text-color: #c4b5fd;
        --fc-button-hover-bg-color: rgba(139, 92, 246, 0.3);
        --fc-button-hover-border-color: rgba(139, 92, 246, 0.5);
        --fc-button-active-bg-color: rgba(139, 92, 246, 0.4);
        --fc-button-active-border-color: rgba(139, 92, 246, 0.6);
        --fc-page-bg-color: transparent;
        --fc-neutral-bg-color: rgba(255, 255, 255, 0.03);
        --fc-list-event-hover-bg-color: rgba(255, 255, 255, 0.05);
        --fc-today-bg-color: rgba(139, 92, 246, 0.08);
        --fc-event-bg-color: rgba(6, 182, 212, 0.2);
        --fc-event-border-color: rgba(6, 182, 212, 0.4);
        --fc-event-text-color: #67e8f9;
    }
    .fc .fc-col-header-cell-cushion,
    .fc .fc-daygrid-day-number,
    .fc .fc-list-day-text,
    .fc .fc-list-day-side-text {
        color: #d1d5db;
    }
    .fc .fc-toolbar-title {
        color: #f3f4f6;
        font-weight: 700;
    }
    .fc .fc-button {
        border-radius: 8px !important;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 12px;
    }
    .fc .fc-button-group > .fc-button {
        border-radius: 0 !important;
    }
    .fc .fc-button-group > .fc-button:first-child {
        border-radius: 8px 0 0 8px !important;
    }
    .fc .fc-button-group > .fc-button:last-child {
        border-radius: 0 8px 8px 0 !important;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: rgba(255, 255, 255, 0.05);
    }
    .fc .fc-daygrid-day.fc-day-today {
        background: rgba(139, 92, 246, 0.08);
    }
    .fc .fc-scrollgrid {
        border-radius: 12px;
        overflow: hidden;
        border-color: rgba(255, 255, 255, 0.05);
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
