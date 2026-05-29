<div id="newAppointmentModal" class="hidden fixed inset-0 bg-amber-900/30 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-lg shadow-xl rounded-2xl bg-white/95">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-amber-800">Novo Agendamento</h3>
            <button onclick="document.getElementById('newAppointmentModal').classList.add('hidden')" class="text-amber-400 hover:text-amber-600 text-2xl leading-none">&times;</button>
        </div>

        <form id="newAppointmentForm">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-amber-700">Cliente</label>
                <select name="customer_id" required class="input-pastel">
                    <option value="">Selecione...</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->phone }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-amber-700">Profissional</label>
                <select name="user_id" required class="input-pastel">
                    <option value="">Selecione...</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-amber-700">Procedimento</label>
                <select name="service_id" required class="input-pastel">
                    <option value="">Selecione...</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->duration_min }}min - R$ {{ number_format($s->price, 2, ',', '.') }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-amber-700">Início</label>
                    <input type="datetime-local" name="start" id="start" required class="input-pastel">
                </div>
                <div>
                    <label class="block text-sm font-medium text-amber-700">Fim</label>
                    <input type="datetime-local" name="end" id="end" required class="input-pastel">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-amber-700">Observações</label>
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
document.getElementById('newAppointmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = new FormData(this);

    fetch('{{ route("admin.appointments.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: data
    }).then(r => r.json()).then(function(resp) {
        if (resp.success) location.reload();
    });
});
</script>
