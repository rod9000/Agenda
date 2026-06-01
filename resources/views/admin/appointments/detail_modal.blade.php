<div id="detailModal" class="hidden fixed inset-0 bg-brand-900/30 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-md shadow-xl rounded-2xl bg-white/95">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-brand-800">Detalhes do Agendamento</h3>
            <button onclick="closeDetailModal()" class="text-brand-400 hover:text-brand-600 text-2xl leading-none">&times;</button>
        </div>

        <div id="detailView">
            <div class="space-y-3 bg-brand-50/50 rounded-xl p-4">
                <div class="flex justify-between"><span class="font-medium text-brand-600">Cliente:</span> <span id="detail-customer" class="text-stone-800"></span></div>
                <div class="flex justify-between"><span class="font-medium text-brand-600">Profissional:</span> <span id="detail-user" class="text-stone-800"></span></div>
                <div class="flex justify-between"><span class="font-medium text-brand-600">Serviço:</span> <span id="detail-service" class="text-stone-800"></span></div>
                <div class="flex justify-between"><span class="font-medium text-brand-600">Status:</span> <span id="detail-status" class="font-semibold"></span></div>
                <div class="flex justify-between"><span class="font-medium text-brand-600">Data/Hora:</span> <span id="detail-time" class="text-stone-800"></span></div>
                <div class="flex justify-between"><span class="font-medium text-brand-600">Valor:</span> <span id="detail-price" class="text-emerald-700 font-semibold"></span></div>
                <div class="flex justify-between"><span class="font-medium text-brand-600">Telefone:</span> <span id="detail-phone" class="text-stone-800"></span></div>
                <div class="flex justify-between"><span class="font-medium text-brand-600">Obs:</span> <span id="detail-notes" class="text-stone-800"></span></div>
            </div>

            <div class="flex justify-between mt-6">
                <div class="space-x-2">
                    <button id="btnComplete" onclick="completeAppointment()" class="btn-pastel-success text-sm px-3 py-2">Concluir</button>
                    <button id="btnCancel" onclick="cancelAppointment()" class="btn-pastel-danger text-sm px-3 py-2">Cancelar</button>
                </div>
                <div class="space-x-2">
                    <button onclick="showEditForm()" class="btn-pastel-primary text-sm px-3 py-2">Editar</button>
                    <button onclick="closeDetailModal()" class="btn-pastel-secondary text-sm px-3 py-2">Fechar</button>
                </div>
            </div>
        </div>

        <div id="detailEdit" class="hidden">
            <form id="editAppointmentForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="appointment_id" id="edit-id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-brand-700 mb-1">Cliente</label>
                    <div class="sel-wrap">
                        <div class="sel-trigger" data-target="edit-customer">
                            <span class="placeholder-text">Selecione um cliente...</span>
                            <span class="arrow">&#9660;</span>
                        </div>
                        <div class="sel-dropdown">
                            <input type="text" class="sel-search" placeholder="Buscar cliente..." autocomplete="off">
                            <div class="sel-options">
                                @foreach($customers as $c)
                                    <div class="sel-option" data-value="{{ $c->id }}">{{ $c->name }}</div>
                                @endforeach
                            </div>
                        </div>
                        <select name="customer_id" id="edit-customer" required class="hidden">
                            <option value="">Selecione...</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-brand-700 mb-1">Profissional</label>
                    <div class="sel-wrap">
                        <div class="sel-trigger" data-target="edit-user">
                            <span class="placeholder-text">Selecione um profissional...</span>
                            <span class="arrow">&#9660;</span>
                        </div>
                        <div class="sel-dropdown">
                            <input type="text" class="sel-search" placeholder="Buscar profissional..." autocomplete="off">
                            <div class="sel-options">
                                @foreach($users as $u)
                                    <div class="sel-option" data-value="{{ $u->id }}">{{ $u->name }}</div>
                                @endforeach
                            </div>
                        </div>
                        <select name="user_id" id="edit-user" required class="hidden">
                            <option value="">Selecione...</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-brand-700 mb-1">Procedimento</label>
                    <div class="sel-wrap">
                        <div class="sel-trigger" data-target="edit-service">
                            <span class="placeholder-text">Selecione um procedimento...</span>
                            <span class="arrow">&#9660;</span>
                        </div>
                        <div class="sel-dropdown">
                            <input type="text" class="sel-search" placeholder="Buscar procedimento..." autocomplete="off">
                            <div class="sel-options">
                                @foreach($services as $s)
                                    <div class="sel-option" data-value="{{ $s->id }}">{{ $s->name }}</div>
                                @endforeach
                            </div>
                        </div>
                        <select name="service_id" id="edit-service" required class="hidden">
                            <option value="">Selecione...</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-700">Início</label>
                        <input type="datetime-local" name="start" id="edit-start" required class="input-pastel">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-700">Fim</label>
                        <input type="datetime-local" name="end" id="edit-end" required class="input-pastel">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-brand-700">Observações</label>
                    <textarea name="notes" id="edit-notes" rows="2" class="input-pastel"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="showDetailView()" class="btn-pastel-secondary">Cancelar</button>
                    <button type="submit" class="btn-pastel-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentEventId = null;

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#detailEdit .sel-wrap').forEach(initSearchableSelect);

    const serviceDurations = {
        @foreach($services as $s)
            {{ $s->id }}: {{ $s->duration_min }},
        @endforeach
    };

    function calcEditEnd(startVal, serviceId) {
        if (!startVal || !serviceId) return;
        const duration = serviceDurations[serviceId];
        if (!duration) return;
        const start = new Date(startVal);
        start.setMinutes(start.getMinutes() + duration);
        document.getElementById('edit-end').value = start.toISOString().slice(0, 16);
    }

    function getDetailSelWrapForTarget(target) {
        const trigger = document.querySelector('#detailEdit [data-target="' + target + '"]');
        return trigger ? trigger.closest('.sel-wrap') : null;
    }

    const editServiceWrap = getDetailSelWrapForTarget('edit-service');
    if (editServiceWrap) {
        editServiceWrap.querySelectorAll('.sel-option').forEach(function(opt) {
            opt.addEventListener('click', function() {
                const sid = this.dataset.value;
                const startVal = document.getElementById('edit-start').value;
                calcEditEnd(startVal, sid);
            });
        });
    }

    const editStartEl = document.getElementById('edit-start');
    if (editStartEl) {
        editStartEl.addEventListener('change', function() {
            const sel = editServiceWrap ? editServiceWrap.querySelector('select') : null;
            calcEditEnd(this.value, sel ? sel.value : null);
        });
    }

    const editForm = document.getElementById('editAppointmentForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const data = {
                customer_id: document.getElementById('edit-customer').value,
                user_id: document.getElementById('edit-user').value,
                service_id: document.getElementById('edit-service').value,
                start: document.getElementById('edit-start').value,
                end: document.getElementById('edit-end').value,
                notes: document.getElementById('edit-notes').value,
            };

            fetch('/admin/appointments/' + currentEventId, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            }).then(r => r.json()).then(function(resp) {
                if (resp.success) {
                    closeDetailModal();
                    calendar.refetchEvents();
                }
            });
        });
    }
});

window.closeDetailModal = function() {
    document.getElementById('detailModal').classList.add('hidden');
    showDetailView();
};

window.showDetailView = function() {
    document.getElementById('detailView').classList.remove('hidden');
    document.getElementById('detailEdit').classList.add('hidden');
};

window.showEditForm = function() {
    document.getElementById('detailView').classList.add('hidden');
    document.getElementById('detailEdit').classList.remove('hidden');
};

window.completeAppointment = function() {
    if (!confirm('Marcar este atendimento como concluído?')) return;
    updateAppointmentStatus('completed');
};

window.cancelAppointment = function() {
    if (!confirm('Cancelar este atendimento?')) return;
    updateAppointmentStatus('cancelled');
};

window.updateAppointmentStatus = function(status) {
    console.log('updateAppointmentStatus called with:', { status, currentEventId });
    if (!window.currentEventId) {
        alert('Erro: ID do agendamento não definido');
        return;
    }
    const url = '/admin/appointments/' + window.currentEventId;
    console.log('Fetching URL:', url);
    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    }).then(function(r) {
        console.log('Response status:', r.status);
        if (!r.ok) throw new Error('Erro ao atualizar: ' + r.status);
        return r.json();
    }).then(function(resp) {
        console.log('Response body:', resp);
        if (resp.success) {
            window.closeDetailModal();
            if (window.calendar) {
                window.calendar.refetchEvents();
            }
        }
    }).catch(function(err) {
        console.error('Fetch error:', err);
        alert(err.message);
    });
};
</script>
@endpush
