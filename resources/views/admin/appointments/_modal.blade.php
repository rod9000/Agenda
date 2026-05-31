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

            <div class="mb-4">
                <label class="block text-sm font-medium text-brand-700 mb-1">Procedimento</label>
                <div class="sel-wrap">
                    <div class="sel-trigger" data-target="service_id">
                        <span class="placeholder-text">Selecione um procedimento...</span>
                        <span class="arrow">&#9660;</span>
                    </div>
                    <div class="sel-dropdown">
                        <input type="text" class="sel-search" placeholder="Buscar procedimento..." autocomplete="off">
                        <div class="sel-options">
                            @foreach($services as $s)
                                <div class="sel-option" data-value="{{ $s->id }}">{{ $s->name }} ({{ $s->duration_min }}min - R$ {{ number_format($s->price, 2, ',', '.') }})</div>
                            @endforeach
                        </div>
                    </div>
                    <select name="service_id" required class="hidden">
                        <option value="">Selecione...</option>
                        @foreach($services as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->duration_min }}min - R$ {{ number_format($s->price, 2, ',', '.') }})</option>
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

<script>
function setSearchableValue(wrap, value) {
    const select = wrap.querySelector('select');
    const trigger = wrap.querySelector('.sel-trigger');
    const options = wrap.querySelectorAll('.sel-option');
    const placeholderText = trigger.querySelector('.placeholder-text');
    select.value = value;
    let found = false;
    options.forEach(function(opt) {
        if (opt.dataset.value === value) {
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
}

function initSearchableSelect(wrap) {
    const trigger = wrap.querySelector('.sel-trigger');
    const dropdown = wrap.querySelector('.sel-dropdown');
    const search = wrap.querySelector('.sel-search');
    const options = wrap.querySelectorAll('.sel-option');
    const select = wrap.querySelector('select');
    const placeholderText = trigger.querySelector('.placeholder-text');
    const selectedText = trigger.querySelector('.selected-text');

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
}

document.querySelectorAll('.sel-wrap').forEach(initSearchableSelect);

document.getElementById('start').addEventListener('change', function() {
    if (this.value) {
        const start = new Date(this.value);
        start.setHours(start.getHours() + 1);
        document.getElementById('end').value = start.toISOString().slice(0, 16);
    }
});

document.getElementById('newAppointmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = new FormData(this);

    fetch('{{ route("admin.appointments.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: data
    }).then(function(r) { return r.json(); }).then(function(resp) {
        if (resp.success) location.reload();
    });
});
</script>
