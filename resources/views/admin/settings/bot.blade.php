@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 dark:text-brand-200 leading-tight">Bot WhatsApp</h2>
        <a href="{{ route('admin.settings.evolution') }}" class="btn-pastel-secondary">Evolution API</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 text-sm text-emerald-600 dark:text-emerald-400">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4 text-sm text-red-600 dark:text-red-400">
            {{ session('error') }}
        </div>
        @endif

        {{-- Status do Bot --}}
        <div class="card-pastel">
            <h3 class="font-semibold text-brand-700 dark:text-brand-300 mb-4">Status do Bot</h3>
            <div class="flex items-center gap-3 mb-4">
                @if($company->bot_enabled)
                    <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">Ativo</span>
                @else
                    <span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span>
                    <span class="text-red-500 font-medium">Inativo</span>
                @endif
            </div>

            <h4 class="text-sm font-medium text-stone-600 dark:text-stone-400 mb-2">Estados da Conversa</h4>
            <p class="text-xs text-stone-400 dark:text-stone-500 mb-3">Fluxo de atendimento do bot no WhatsApp.</p>

            @php
                $states = [
                    'initial' => ['label' => 'Menu Inicial', 'color' => 'bg-stone-500', 'desc' => 'Aguardando opção do menu (1-9)'],
                    'choosing_service' => ['label' => 'Escolhendo Serviço', 'color' => 'bg-blue-500', 'desc' => 'Cliente seleciona o serviço'],
                    'choosing_professional' => ['label' => 'Escolhendo Profissional', 'color' => 'bg-blue-500', 'desc' => 'Cliente seleciona o profissional'],
                    'choosing_date' => ['label' => 'Escolhendo Data', 'color' => 'bg-blue-500', 'desc' => 'Cliente informa a data (DD/MM)'],
                    'choosing_time' => ['label' => 'Escolhendo Horário', 'color' => 'bg-blue-500', 'desc' => 'Cliente seleciona o horário disponível'],
                    'confirming' => ['label' => 'Confirmando', 'color' => 'bg-amber-500', 'desc' => 'Resumo do agendamento - confirmar ou cancelar'],
                ];
            @endphp

            <div class="space-y-2">
                @foreach($states as $key => $state)
                    <div class="flex items-center gap-3 p-3 bg-stone-50 dark:bg-stone-800 rounded-xl">
                        <span class="w-3 h-3 rounded-full {{ $state['color'] }} inline-block shrink-0"></span>
                        <div class="flex-1">
                            <p class="font-medium text-stone-800 dark:text-stone-200 text-sm">{{ $state['label'] }}</p>
                            <p class="text-xs text-stone-500 dark:text-stone-400">{{ $state['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Configurações --}}
        <div class="card-pastel">
            <h3 class="font-semibold text-brand-700 dark:text-brand-300 mb-4">Configurações do Bot</h3>
            <form method="POST" action="{{ route('admin.settings.bot.store') }}">
                @csrf
                <div class="space-y-6">

                    <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-stone-800 rounded-xl">
                        <div>
                            <p class="font-medium text-stone-700 dark:text-stone-300">Habilitar Bot</p>
                            <p class="text-sm text-stone-500 dark:text-stone-400">Ativa o atendimento automático via WhatsApp</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="bot_enabled" value="1" {{ $company->bot_enabled ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-stone-800 rounded-xl">
                        <div>
                            <p class="font-medium text-stone-700 dark:text-stone-300">Respeitar Horário Comercial</p>
                            <p class="text-sm text-stone-500 dark:text-stone-400">Fora do horário, responde apenas com a mensagem de "fora do horário"</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="bot_off_hours_enabled" value="1" {{ ($company->bot_off_hours_enabled ?? true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <div>
                        <label class="label">Mensagem de Boas-Vindas</label>
                        <p class="text-xs text-stone-500 dark:text-stone-400 mb-2">Cabeçalho exibido quando o cliente inicia a conversa.</p>
                        <textarea name="welcome_message" rows="3" class="input-pastel" placeholder="Olá! Bem-vindo(a)!&#10;&#10;Como posso te ajudar?">{{ old('welcome_message', $company->welcome_message) }}</textarea>
                        @error('welcome_message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Mensagem Fora do Horário</label>
                        <p class="text-xs text-stone-500 dark:text-stone-400 mb-2">Mensagem enviada quando o cliente envia msg fora do horário.</p>
                        <textarea name="off_hours_message" rows="4" class="input-pastel" placeholder="Olá! No momento estamos fora do horário.&#10;Deixe sua mensagem que retornamos!">{{ old('off_hours_message', $company->off_hours_message) }}</textarea>
                        @error('off_hours_message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-pastel-primary">Salvar Configurações</button>
                </div>
            </form>
        </div>

        {{-- Horários de Funcionamento --}}
        <div class="card-pastel">
            <h3 class="font-semibold text-brand-700 dark:text-brand-300 mb-4">Horários de Funcionamento</h3>
            <p class="text-sm text-stone-500 dark:text-stone-400 mb-4">Configure os horários que o bot vai respeitar.</p>

            <form method="POST" action="{{ route('admin.settings.bot.store') }}">
                @csrf
                <input type="hidden" name="save_hours" value="1">

                @php
                    $days = [1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta', 6 => 'Sábado', 0 => 'Domingo'];
                    $defaultStart = '08:00';
                    $defaultEnd = '00:00';
                @endphp

                <div class="space-y-2">
                    @foreach($days as $dayNum => $dayName)
                        @php
                            $wh = \App\Models\WorkingHour::where('day_of_week', $dayNum)->where('active', true)->first();
                            $startTime = $wh ? substr($wh->start_time, 0, 5) : '';
                            $endTime = $wh ? substr($wh->end_time, 0, 5) : '';
                            $isActive = $wh ? true : false;
                        @endphp
                        <div class="flex items-center gap-3 p-3 bg-stone-50 dark:bg-stone-800 rounded-xl">
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" name="hours[{{ $dayNum }}][active]" value="1" {{ $isActive ? 'checked' : '' }} class="sr-only peer" onchange="toggleDay({{ $dayNum }}, this.checked)">
                                <div class="w-9 h-5 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                            <span class="w-20 font-medium text-stone-700 dark:text-stone-300 text-sm shrink-0">{{ $dayName }}</span>
                            <div id="hours_{{ $dayNum }}" class="flex items-center gap-2 flex-1 {{ $isActive ? '' : 'opacity-40 pointer-events-none' }}">
                                <input type="time" name="hours[{{ $dayNum }}][start_time]" value="{{ $startTime ?: $defaultStart }}" class="input-pastel w-32 text-sm" {{ $isActive ? '' : 'disabled' }}>
                                <span class="text-stone-400 text-sm">até</span>
                                <input type="time" name="hours[{{ $dayNum }}][end_time]" value="{{ $endTime ?: $defaultEnd }}" class="input-pastel w-32 text-sm" {{ $isActive ? '' : 'disabled' }}>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit" class="btn-pastel-primary">Salvar Horários</button>
                </div>
            </form>
        </div>

        {{-- Menu do Bot --}}
        <div class="card-pastel">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-brand-700 dark:text-brand-300">Menu do Bot</h3>
                <a href="{{ route('admin.bot-menu.index') }}" class="btn-pastel-secondary text-sm">Gerenciar Itens</a>
            </div>
            <p class="text-sm text-stone-500 dark:text-stone-400 mb-4">Configure as opções que aparecem no menu de boas-vindas.</p>

            @php
                $menuItems = \App\Models\BotMenuItem::where('company_id', $company->id)->orderBy('sort_order')->get();
            @endphp

            @if($menuItems->isEmpty())
                <p class="text-stone-400 dark:text-stone-500 text-sm">Nenhum item configurado.</p>
            @else
                <div class="space-y-2">
                    @foreach($menuItems as $item)
                        <div class="flex items-center gap-3 p-3 bg-stone-50 dark:bg-stone-800 rounded-xl {{ !$item->is_active ? 'opacity-50' : '' }}">
                            <span class="w-7 h-7 rounded-full bg-brand-500 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                {{ $item->menu_number }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-stone-800 dark:text-stone-200 text-sm">{{ $item->label }}</p>
                                <p class="text-xs text-stone-500 dark:text-stone-400">{{ $item->getActionLabel() }}</p>
                            </div>
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $item->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-stone-200 text-stone-500 dark:bg-stone-700 dark:text-stone-400' }}">
                                {{ $item->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pré-visualização --}}
        <div class="card-pastel">
            <h3 class="font-semibold text-brand-700 dark:text-brand-300 mb-4">Pré-visualização</h3>
            <div class="bg-emerald-50 dark:bg-emerald-900/10 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white text-sm font-bold shrink-0">
                        B
                    </div>
                    <div class="bg-white dark:bg-stone-800 rounded-2xl rounded-tl-none p-4 max-w-sm shadow-sm">
                        <p class="text-sm text-stone-800 dark:text-stone-200">{!! nl2br(e($company->getDefaultWelcomeMessage())) !!}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleDay(day, checked) {
    var container = document.getElementById('hours_' + day);
    var inputs = container.querySelectorAll('input[type="time"]');
    inputs.forEach(function(input) {
        input.disabled = !checked;
    });
    container.classList.toggle('opacity-40', !checked);
    container.classList.toggle('pointer-events-none', !checked);
}
</script>
@endpush
