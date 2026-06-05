@extends('layouts.app')

@section('header')
    @if (session('success'))
        <div class="bg-emerald-100 border border-emerald-300 text-emerald-700 px-4 py-3 rounded-xl relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Dashboard</h2>
        <form method="GET" class="flex items-center gap-2">
            <select name="period" onchange="this.form.submit()" class="input-pastel text-sm">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hoje</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Esta Semana</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Este Mês</option>
            </select>
        </form>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Métricas principais --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <div class="card-pastel border-l-4 border-brand-400">
                <div class="text-xs font-medium text-brand-600 uppercase tracking-wider">Concluídos</div>
                <div class="mt-1 text-2xl font-semibold text-brand-900">{{ $completedCount }}</div>
            </div>
            <div class="card-pastel border-l-4 border-orange-400">
                <div class="text-xs font-medium text-orange-600 uppercase tracking-wider">Pendentes</div>
                <div class="mt-1 text-2xl font-semibold text-orange-700">{{ $pendingCount }}</div>
            </div>
            <div class="card-pastel border-l-4 border-red-400">
                <div class="text-xs font-medium text-red-600 uppercase tracking-wider">Cancelados</div>
                <div class="mt-1 text-2xl font-semibold text-red-700">{{ $cancelledCount }}</div>
            </div>
            <div class="card-pastel border-l-4 border-emerald-400">
                <div class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Faturamento</div>
                <div class="mt-1 text-2xl font-semibold text-emerald-700">R$ {{ number_format($revenue, 2, ',', '.') }}</div>
            </div>
            <div class="card-pastel border-l-4 border-violet-400">
                <div class="text-xs font-medium text-violet-600 uppercase tracking-wider">Ticket Médio</div>
                <div class="mt-1 text-2xl font-semibold text-violet-700">R$ {{ number_format($avgTicket, 2, ',', '.') }}</div>
            </div>
            <div class="card-pastel border-l-4 border-sky-400">
                <div class="text-xs font-medium text-sky-600 uppercase tracking-wider">Clientes Atend.</div>
                <div class="mt-1 text-2xl font-semibold text-sky-700">{{ $uniqueCustomers }}</div>
            </div>
        </div>

        {{-- Receita Dia / Semana / Mês --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
            <div class="card-pastel text-center">
                <div class="text-xs font-semibold uppercase tracking-wider text-brand-500">Receita Hoje</div>
                <div class="mt-2 text-2xl font-bold text-brand-800">R$ {{ number_format($revenueDay, 2, ',', '.') }}</div>
                <div class="mt-1 text-sm text-brand-500">{{ $countDay }} concluído(s)</div>
            </div>
            <div class="card-pastel text-center">
                <div class="text-xs font-semibold uppercase tracking-wider text-brand-500">Receita Semana</div>
                <div class="mt-2 text-2xl font-bold text-brand-800">R$ {{ number_format($revenueWeek, 2, ',', '.') }}</div>
                <div class="mt-1 text-sm text-brand-500">{{ $countWeek }} concluído(s)</div>
            </div>
            <div class="card-pastel text-center">
                <div class="text-xs font-semibold uppercase tracking-wider text-brand-500">Receita Mês</div>
                <div class="mt-2 text-2xl font-bold text-brand-800">R$ {{ number_format($revenueMonth, 2, ',', '.') }}</div>
                <div class="mt-1 text-sm text-brand-500">{{ $countMonth }} concluído(s)</div>
            </div>
        </div>

        {{-- Gráficos lado a lado --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="card-pastel">
                <h3 class="text-lg font-semibold text-brand-800 mb-4">Faturamento - {{ ucfirst($period) }}</h3>
                <canvas id="revenueChart" height="200"></canvas>
            </div>
            <div class="card-pastel">
                <h3 class="text-lg font-semibold text-brand-800 mb-4">Serviços Mais Realizados</h3>
                @if($topServices->count() > 0)
                    <canvas id="servicesChart" height="200"></canvas>
                @else
                    <p class="text-brand-400 text-center py-8">Nenhum serviço realizado no período.</p>
                @endif
            </div>
        </div>

        {{-- Atendimentos Hoje + Próximos --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="card-pastel">
                <h3 class="text-lg font-semibold text-brand-800 mb-4">Atendimentos de Hoje</h3>
                @if($todayAppointments->count() > 0)
                    <ul class="divide-y divide-brand-100">
                        @foreach($todayAppointments as $app)
                        <li class="py-3 flex justify-between items-center">
                            <div class="min-w-0 flex-1">
                                <span class="font-semibold text-brand-700">{{ $app->start->format('H:i') }}</span>
                                <span class="ml-2 text-stone-700">{{ $app->customer->name }}</span>
                                <span class="text-sm text-brand-500 ml-2">
                                    {{ $app->services->pluck('name')->implode(', ') ?: $app->service->name }}
                                </span>
                            </div>
                            <span class="badge-pastel shrink-0 ml-2
                                @switch($app->status)
                                    @case('scheduled') bg-blue-100 text-blue-700 @break
                                    @case('confirmed') bg-emerald-100 text-emerald-700 @break
                                    @case('in_progress') bg-amber-100 text-amber-700 @break
                                    @case('completed') bg-stone-100 text-stone-700 @break
                                    @case('cancelled') bg-red-100 text-red-700 @break
                                    @case('no_show') bg-gray-200 text-gray-600 @break
                                    @default bg-stone-100 text-stone-600
                                @endswitch
                            ">
                                @switch($app->status)
                                    @case('scheduled') Agendado @break
                                    @case('confirmed') Confirmado @break
                                    @case('in_progress') Em Andamento @break
                                    @case('completed') Concluído @break
                                    @case('cancelled') Cancelado @break
                                    @case('no_show') Não Compareceu @break
                                    @default {{ $app->status }}
                                @endswitch
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-brand-400 text-center py-8">Nenhum atendimento hoje.</p>
                @endif
            </div>

            <div class="card-pastel">
                <h3 class="text-lg font-semibold text-brand-800 mb-4">Próximos Agendamentos</h3>
                @if($upcomingAppointments->count() > 0)
                    <ul class="divide-y divide-brand-100">
                        @foreach($upcomingAppointments as $app)
                        <li class="py-3 flex justify-between items-center">
                            <div class="min-w-0 flex-1">
                                <span class="font-semibold text-brand-700">{{ $app->start->format('d/m H:i') }}</span>
                                <span class="ml-2 text-stone-700">{{ $app->customer->name }}</span>
                                <span class="text-sm text-brand-500 ml-2">
                                    {{ $app->services->pluck('name')->implode(', ') ?: $app->service->name }}
                                </span>
                                <div class="text-xs text-brand-400 mt-0.5 ml-1">{{ $app->user->name }}</div>
                            </div>
                            <span class="badge-pastel shrink-0 ml-2
                                @switch($app->status)
                                    @case('scheduled') bg-blue-100 text-blue-700 @break
                                    @case('confirmed') bg-emerald-100 text-emerald-700 @break
                                    @case('in_progress') bg-amber-100 text-amber-700 @break
                                    @default bg-stone-100 text-stone-600
                                @endswitch
                            ">
                                @switch($app->status)
                                    @case('scheduled') Agendado @break
                                    @case('confirmed') Confirmado @break
                                    @case('in_progress') Em Andamento @break
                                    @default {{ $app->status }}
                                @endswitch
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-brand-400 text-center py-8">Nenhum agendamento futuro.</p>
                @endif
            </div>
        </div>

        {{-- Comissões Pendentes + Aniversariantes (admin) --}}
        @if($isAdmin)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="card-pastel">
                <h3 class="text-lg font-semibold text-brand-800 mb-4">Comissões Pendentes</h3>
                <div class="text-3xl font-bold text-amber-600 mb-2">
                    R$ {{ number_format($pendingCommissions, 2, ',', '.') }}
                </div>
                <a href="{{ route('admin.commissions.index') }}" class="text-sm text-brand-500 hover:text-brand-700 underline">
                    Ver comissões →
                </a>
            </div>

            <div class="card-pastel">
                <h3 class="text-lg font-semibold text-brand-800 mb-4">
                    Aniversariantes do Mês
                    <span class="text-sm font-normal text-brand-400">({{ $monthBirthdays->count() }})</span>
                </h3>
                @if($monthBirthdays->count() > 0)
                    <ul class="divide-y divide-brand-100">
                        @foreach($monthBirthdays as $cust)
                        <li class="py-2 flex justify-between items-center">
                            <span class="text-stone-700">{{ $cust->name }}</span>
                            <span class="text-sm text-brand-500">
                                {{ $cust->birth_date->format('d/m') }}
                                @if($cust->birth_date->isToday())
                                    <span class="badge-pastel bg-rose-100 text-rose-700 ml-1">Hoje!</span>
                                @endif
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-brand-400 text-center py-4">Nenhum aniversariante este mês.</p>
                @endif
            </div>
        </div>
        @endif

        {{-- Performance por Profissional (admin) --}}
        @if($isAdmin && $profPerformance->count() > 0)
        <div class="card-pastel mb-6">
            <h3 class="text-lg font-semibold text-brand-800 mb-4">Atendimentos por Profissional</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <canvas id="profChart" height="180"></canvas>
                </div>
                <div class="space-y-3">
                    @foreach($profPerformance as $prof)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-stone-700">{{ $prof->name }}</span>
                            <span class="font-semibold text-brand-700">{{ $prof->appointments_count }} atend.</span>
                        </div>
                        <div class="w-full bg-brand-100 rounded-full h-2.5">
                            @php $pct = $profPerformance->max('appointments_count') > 0 ? ($prof->appointments_count / $profPerformance->max('appointments_count')) * 100 : 0; @endphp
                            <div class="bg-gradient-to-r from-brand-400 to-brand-600 h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: @json(array_column($chartData, 'label')),
        datasets: [{
            label: 'Faturamento (R$)',
            data: @json(array_column($chartData, 'value')),
            backgroundColor: ['#fbbf24', '#fcd34d', '#fde68a', '#f59e0b', '#fbbf24', '#fcd34d', '#fde68a'],
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: '#fef3c7' } } }
    }
});

@if($topServices->count() > 0)
new Chart(document.getElementById('servicesChart'), {
    type: 'doughnut',
    data: {
        labels: @json($topServices->pluck('name')),
        datasets: [{
            data: @json($topServices->pluck('total')),
            backgroundColor: [
                '#7B8564', '#D4A373', '#A8B5A0', '#C9A96E', '#8EA1A0'
            ],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 12, usePointStyle: true, font: { size: 12 } }
            }
        }
    }
});
@endif

@if($isAdmin && $profPerformance->count() > 0)
new Chart(document.getElementById('profChart'), {
    type: 'bar',
    data: {
        labels: @json($profPerformance->pluck('name')),
        datasets: [{
            label: 'Atendimentos',
            data: @json($profPerformance->pluck('appointments_count')),
            backgroundColor: ['#7B8564', '#D4A373', '#A8B5A0', '#C9A96E', '#8EA1A0', '#B8A9C4'],
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, grid: { color: '#f5f0e8' }, ticks: { stepSize: 1 } },
            y: { grid: { display: false } }
        }
    }
});
@endif
</script>
@endpush
