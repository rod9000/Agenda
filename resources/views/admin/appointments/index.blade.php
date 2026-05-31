@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Agenda</h2>
        <button onclick="document.getElementById('newAppointmentModal').classList.remove('hidden'); document.querySelectorAll('#newAppointmentModal .sel-wrap').forEach(function(w) { w.querySelector('select').value = ''; var st = w.querySelector('.selected-text'); if (st) { st.remove(); } var pt = w.querySelector('.placeholder-text'); if (pt) pt.style.display = ''; });" class="btn-pastel-primary">
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
    .sel-wrap { position: relative; }
    .sel-trigger {
        display: flex; align-items: center; justify-content: space-between;
        width: 100%; padding: 10px 14px; border: 1px solid #BAC893;
        border-radius: 10px; background: #F4F7EE; cursor: pointer;
        font-size: 14px; color: #78716c; text-align: left; gap: 8px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .sel-trigger:hover { border-color: #7B8564; }
    .sel-trigger.open { border-color: #7B8564; box-shadow: 0 0 0 3px #E8EDDB; }
    .sel-trigger .arrow { font-size: 12px; color: #BAC893; transition: transform 0.2s; }
    .sel-trigger.open .arrow { transform: rotate(180deg); }
    .sel-trigger .selected-text { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #444; }
    .sel-trigger .placeholder-text { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #a8a29e; }
    .sel-dropdown {
        display: none; position: absolute; z-index: 200; top: calc(100% + 4px);
        left: 0; right: 0; background: #fff; border: 1px solid #BAC893;
        border-radius: 10px; box-shadow: 0 8px 24px rgba(120,80,40,0.12);
        overflow: hidden;
    }
    .sel-dropdown.open { display: block; }
    .sel-search {
        width: 100%; padding: 10px 12px; border: none; border-bottom: 1px solid #f0e6d3;
        font-size: 14px; outline: none; box-sizing: border-box; background: #fff;
    }
    .sel-search:focus { background: #fffbeb; }
    .sel-options { max-height: 220px; overflow-y: auto; }
    .sel-option {
        padding: 10px 14px; cursor: pointer; font-size: 14px; color: #444;
        transition: background 0.15s;
    }
    .sel-option:hover { background: #E8EDDB; }
    .sel-option.selected { background: #E8EDDB; color: #616C4B; font-weight: 600; }
    .sel-no-results { padding: 14px; text-align: center; color: #a8a29e; font-size: 14px; }
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

            setSearchableValue(document.querySelector('#edit-customer').closest('.sel-wrap'), props.customer_id || '');
            setSearchableValue(document.querySelector('#edit-user').closest('.sel-wrap'), props.user_id || '');
            setSearchableValue(document.querySelector('#edit-service').closest('.sel-wrap'), props.service_id || '');
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
