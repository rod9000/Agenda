<div id="newAppointmentModal" class="hidden fixed inset-0 bg-brand-900/30 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-lg shadow-xl rounded-2xl bg-white/95">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-brand-800">Novo Agendamento</h3>
            <button onclick="document.getElementById('newAppointmentModal').classList.add('hidden')" class="text-brand-400 hover:text-brand-600 text-2xl leading-none">&times;</button>
        </div>

        <form id="newAppointmentForm">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-brand-700 mb-1">Cliente</label>
                <div class="sel-wrap">
                    <div class="sel-trigger" data-target="customer_id">
                        <span class="placeholder-text">Selecione um cliente...</span>
                        <span class="arrow">&#9660;</span>
                    </div>
                    <div class="sel-dropdown">
                        <input type="text" class="sel-search" placeholder="Buscar cliente..." autocomplete="off">
                        <div class="sel-options">
                            @foreach($customers as $c)
                                <div class="sel-option" data-value="{{ $c->id }}">{{ $c->name }} - {{ $c->phone }}</div>
                            @endforeach
                        </div>
                    </div>
                    <select name="customer_id" required class="hidden">
                        <option value="">Selecione...</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->phone }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-brand-700 mb-1">Profissional</label>
                <div class="sel-wrap">
                    <div class="sel-trigger" data-target="user_id">
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
                    <select name="user_id" required class="hidden">
                        <option value="">Selecione...</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-brand-700 mb-1">Início</label>
                    <input type="datetime-local" name="start" id="start" required class="input-pastel">
                </div>
                <div>
                    <label class="block text-sm font-medium text-brand-700 mb-1">Fim</label>
                    <input type="datetime-local" name="end" id="end" required class="input-pastel">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-brand-700 mb-1">Procedimentos</label>
                <div id="serviceCheckboxes" class="space-y-2 max-h-48 overflow-y-auto p-3 bg-brand-50/50 rounded-xl border border-brand-100">
                    @foreach($services as $s)
                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/80 cursor-pointer transition-all">
                        <input type="checkbox" name="service_ids[]" value="{{ $s->id }}" data-duration="{{ $s->duration_min }}" data-price="{{ $s->price }}"
                               class="w-4 h-4 rounded border-brand-300 text-brand-600 focus:ring-brand-400 service-checkbox"
                               onchange="updateServiceSelection()">
                        <span class="text-sm text-stone-700">{{ $s->name }} <span class="text-xs text-stone-400">({{ $s->duration_min }}min - R$ {{ number_format($s->price, 2, ',', '.') }})</span></span>
                    </label>
                    @endforeach
                </div>
                <p id="serviceCount" class="text-xs text-stone-400 mt-1">Nenhum procedimento selecionado</p>
            </div>

            <div class="mb-4 p-4 bg-amber-50 rounded-xl border border-amber-200">
                <h4 class="font-semibold text-sm text-amber-800 mb-2">Agendamento Recorrente</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-amber-700">Repetir</label>
                        <select name="recurring_frequency" class="input-pastel text-sm">
                            <option value="">Não repetir</option>
                            <option value="daily">Diariamente</option>
                            <option value="weekly">Semanalmente</option>
                            <option value="biweekly">Quinzenalmente</option>
                            <option value="monthly">Mensalmente</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-amber-700">Até</label>
                        <input type="date" name="recurring_until" class="input-pastel text-sm">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-brand-700 mb-1">Observações</label>
                <textarea name="notes" rows="2" class="input-pastel"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('newAppointmentModal').classList.add('hidden')" class="btn-pastel-secondary">Cancelar</button>
                <button type="submit" class="btn-pastel-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
window.setSearchableValue = function(wrap, value) {
    const select = wrap.querySelector('select');
    const trigger = wrap.querySelector('.sel-trigger');
    const options = wrap.querySelectorAll('.sel-option');
    const placeholderText = trigger.querySelector('.placeholder-text');
    select.value = value;
    let found = false;
    const stringValue = value !== null && value !== undefined ? String(value) : '';
    options.forEach(function(opt) {
        if (opt.dataset.value === stringValue) {
            let st = trigger.querySelector('.selected-text');
            if (!st) {
                st = document.createElement('span');
                st.className = 'selected-text';
                trigger.insertBefore(st, placeholderText);
            }
            st.textContent = opt.textContent;
            placeholderText.style.display = 'none';
            opt.classList.add('selected');
            found = true;
        } else {
            opt.classList.remove('selected');
        }
    });
    if (!found) {
        const st = trigger.querySelector('.selected-text');
        if (st) st.remove();
        placeholderText.style.display = '';
    }
};

window.initSearchableSelect = function(wrap) {
    const trigger = wrap.querySelector('.sel-trigger');
    const dropdown = wrap.querySelector('.sel-dropdown');
    const search = wrap.querySelector('.sel-search');
    const options = wrap.querySelectorAll('.sel-option');
    const select = wrap.querySelector('select');
    const placeholderText = trigger.querySelector('.placeholder-text');

    function filterOptions(term) {
        const lower = term.toLowerCase();
        let visible = 0;
        options.forEach(function(opt) {
            const match = opt.textContent.toLowerCase().includes(lower);
            opt.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        const noResults = wrap.querySelector('.sel-no-results');
        if (visible === 0) {
            if (!noResults) {
                const el = document.createElement('div');
                el.className = 'sel-no-results';
                el.textContent = 'Nenhum resultado encontrado';
                wrap.querySelector('.sel-options').appendChild(el);
            }
        } else {
            if (noResults) noResults.remove();
        }
    }

    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = dropdown.classList.contains('open');
        document.querySelectorAll('.sel-dropdown.open').forEach(function(d) {
            if (d !== dropdown) d.classList.remove('open');
        });
        document.querySelectorAll('.sel-trigger.open').forEach(function(t) {
            if (t !== trigger) t.classList.remove('open');
        });
        if (isOpen) {
            dropdown.classList.remove('open');
            trigger.classList.remove('open');
        } else {
            dropdown.classList.add('open');
            trigger.classList.add('open');
            search.value = '';
            search.focus();
            filterOptions('');
        }
    });

    search.addEventListener('input', function() {
        filterOptions(this.value);
    });

    function getOrCreateSelectedText() {
        let el = trigger.querySelector('.selected-text');
        if (!el) {
            el = document.createElement('span');
            el.className = 'selected-text';
            trigger.insertBefore(el, placeholderText);
        }
        return el;
    }

    options.forEach(function(opt) {
        opt.addEventListener('click', function() {
            const value = this.dataset.value;
            const text = this.textContent;
            select.value = value;
            select.dispatchEvent(new Event('change'));
            const st = getOrCreateSelectedText();
            st.textContent = text;
            placeholderText.style.display = 'none';
            dropdown.classList.remove('open');
            trigger.classList.remove('open');
            trigger.dispatchEvent(new Event('change'));
        });
    });

    document.addEventListener('click', function() {
        dropdown.classList.remove('open');
        trigger.classList.remove('open');
    });

    dropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });
};

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#newAppointmentModal .sel-wrap').forEach(window.initSearchableSelect);

    window.updateServiceSelection = function() {
        const checkboxes = document.querySelectorAll('#serviceCheckboxes .service-checkbox:checked');
        const count = checkboxes.length;
        const countEl = document.getElementById('serviceCount');
        countEl.textContent = count === 0 ? 'Nenhum procedimento selecionado' : count + ' procedimento(s) selecionado(s)';

        let totalMinutes = 0;
        checkboxes.forEach(function(cb) {
            totalMinutes += parseInt(cb.dataset.duration) || 0;
        });

        const startVal = document.getElementById('start').value;
        if (startVal && totalMinutes > 0) {
            const start = new Date(startVal);
            start.setMinutes(start.getMinutes() + totalMinutes);
            document.getElementById('end').value = window.toLocalDatetimeLocal(start);
        } else if (startVal) {
            document.getElementById('end').value = startVal;
        }
    };

    window.toLocalDatetimeLocal = function(value) {
        const date = new Date(value);
        const pad = (n) => String(n).padStart(2, '0');
        const year = date.getFullYear();
        const month = pad(date.getMonth() + 1);
        const day = pad(date.getDate());
        const hours = pad(date.getHours());
        const minutes = pad(date.getMinutes());
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    };

    function getSelWrapForTarget(target) {
        const trigger = document.querySelector('#newAppointmentModal [data-target="' + target + '"]');
        return trigger ? trigger.closest('.sel-wrap') : null;
    }

    const startEl = document.getElementById('start');
    if (startEl) {
        startEl.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('#serviceCheckboxes .service-checkbox:checked');
            let totalMinutes = 0;
            checkboxes.forEach(function(cb) {
                totalMinutes += parseInt(cb.dataset.duration) || 0;
            });
            if (this.value && totalMinutes > 0) {
                const start = new Date(this.value);
                start.setMinutes(start.getMinutes() + totalMinutes);
                document.getElementById('end').value = window.toLocalDatetimeLocal(start);
            } else if (this.value) {
                document.getElementById('end').value = this.value;
            }
        });
    }

    const newAppointmentForm = document.getElementById('newAppointmentForm');
    if (newAppointmentForm) {
        newAppointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const data = new FormData(this);

            fetch('{{ route("admin.appointments.store") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: data
            }).then(function(r) { return r.json(); }).then(function(resp) {
                if (resp.success) {
                    document.getElementById('newAppointmentModal').classList.add('hidden');
                    newAppointmentForm.reset();
                    if (window.calendar) window.calendar.refetchEvents();
                }
            });
        });
    }
});
</script>
@endpush
