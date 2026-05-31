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

        {{-- Cards principais --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="card-pastel border-l-4 border-brand-400">
                <div class="text-sm font-medium text-brand-600">Atend. Hoje</div>
                <div class="mt-1 text-3xl font-semibold text-brand-900">{{ $completedCount }}</div>
            </div>
            <div class="card-pastel border-l-4 border-orange-400">
                <div class="text-sm font-medium text-orange-600">Pendentes</div>
                <div class="mt-1 text-3xl font-semibold text-orange-700">{{ $pendingCount }}</div>
            </div>
            <div class="card-pastel border-l-4 border-emerald-400">
                <div class="text-sm font-medium text-emerald-600">Faturamento</div>
                <div class="mt-1 text-3xl font-semibold text-emerald-700">R$ {{ number_format($revenue, 2, ',', '.') }}</div>
            </div>
            <div class="card-pastel border-l-4 border-rose-400">
                <div class="text-sm font-medium text-rose-500">Aniversariantes</div>
                <div class="mt-1 text-3xl font-semibold text-rose-600">{{ $birthdayCount }}</div>
            </div>
        </div>

        {{-- Faturamento Dia / Semana / Mês --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
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

        {{-- Gráfico + Lista de Hoje --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="card-pastel">
                <h3 class="text-lg font-semibold text-brand-800 mb-4">Faturamento - {{ ucfirst($period) }}</h3>
                <canvas id="revenueChart" height="200"></canvas>
            </div>

            <div class="card-pastel">
                <h3 class="text-lg font-semibold text-brand-800 mb-4">Atendimentos de Hoje</h3>
                @if($todayAppointments->count() > 0)
                    <ul class="divide-y divide-brand-100">
                        @foreach($todayAppointments as $app)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <span class="font-semibold text-brand-700">{{ $app->start->format('H:i') }}</span>
                                <span class="ml-2 text-stone-700">{{ $app->customer->name }}</span>
                                <span class="text-sm text-brand-500 ml-2">{{ $app->service->name }}</span>
                            </div>
                            <span class="badge-pastel
                                @switch($app->status)
                                    @case('scheduled') bg-blue-100 text-blue-700 @break
                                    @case('confirmed') bg-emerald-100 text-emerald-700 @break
                                    @case('in_progress') bg-brand-100 text-brand-700 @break
                                    @case('completed') bg-stone-100 text-stone-700 @break
                                    @case('cancelled') bg-red-100 text-red-700 @break
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
        </div>
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
</script>
@endpush
