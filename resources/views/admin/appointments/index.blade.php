@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-amber-800 leading-tight">Agenda</h2>
        <button onclick="document.getElementById('newAppointmentModal').classList.remove('hidden')" class="btn-pastel-primary">
            + Novo Agendamento
        </button>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <div id="calendar"></div>
        </div>
    </div>
</div>

@include('admin.appointments._modal')

@include('admin.appointments._detail_modal')
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    .fc-event { cursor: pointer; }
    .fc-event-title { font-weight: 500; }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/pt-br.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'timeGridDay',
        locale: 'pt-br',
        firstDay: 0,
        titleFormat: { year: 'numeric', month: 'long' },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia'
        },
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        slotMinTime: '07:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        editable: true,
        selectable: true,
        events: '/admin/appointments/calendar-data',
        select: function(info) {
            document.getElementById('start').value = info.startStr.slice(0, 16);
            document.getElementById('end').value = info.endStr.slice(0, 16);
            document.getElementById('newAppointmentModal').classList.remove('hidden');
        },
        eventClick: function(info) {
            currentEventId = info.event.id;
            const props = info.event.extendedProps;
            document.getElementById('detail-customer').textContent = props.customer;
            document.getElementById('detail-service').textContent = props.service;
            const statusMap = { scheduled: 'Agendado', confirmed: 'Confirmado', in_progress: 'Em Andamento', completed: 'Concluído', cancelled: 'Cancelado', no_show: 'Não Compareceu' };
            document.getElementById('detail-status').textContent = statusMap[props.status] || props.status;
            document.getElementById('detail-user').textContent = props.user;
            document.getElementById('detail-time').textContent = info.event.start.toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            document.getElementById('detail-price').textContent = 'R$ ' + parseFloat(props.price).toFixed(2).replace('.', ',');
            document.getElementById('detail-phone').textContent = props.phone;
            document.getElementById('detail-notes').textContent = props.notes || '—';

            document.getElementById('btnComplete').style.display = (props.status === 'completed' || props.status === 'cancelled' || props.status === 'no_show') ? 'none' : 'inline-block';
            document.getElementById('btnCancel').style.display = (props.status === 'completed' || props.status === 'cancelled' || props.status === 'no_show') ? 'none' : 'inline-block';

            document.getElementById('edit-customer').value = props.customer_id || '';
            document.getElementById('edit-user').value = props.user_id || '';
            document.getElementById('edit-service').value = props.service_id || '';
            document.getElementById('edit-start').value = info.event.start.toISOString().slice(0, 16);
            document.getElementById('edit-end').value = info.event.end.toISOString().slice(0, 16);
            document.getElementById('edit-notes').value = props.notes || '';

            document.getElementById('detailModal').classList.remove('hidden');
        },
        eventDrop: function(info) {
            fetch('/admin/appointments/' + info.event.id + '/reschedule', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    start: info.event.start.toISOString(),
                    end: info.event.end.toISOString()
                })
            }).catch(function() {
                info.revert();
            });
        }
    });
    calendar.render();
});
</script>
@endpush
