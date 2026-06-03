@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Financeiro</h2>
        <form method="GET" class="flex items-center gap-2">
            <select name="method" onchange="this.form.submit()" class="input-pastel text-sm">
                <option value="">Todas formas</option>
                <option value="dinheiro" @selected(request('method') === 'dinheiro')>Dinheiro</option>
                <option value="cartao" @selected(request('method') === 'cartao')>Cartão</option>
                <option value="pix" @selected(request('method') === 'pix')>PIX</option>
            </select>
            <select name="period" onchange="this.form.submit()" class="input-pastel text-sm">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hoje</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Esta Semana</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Este Mês</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Este Ano</option>
            </select>
        </form>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="card-pastel border-l-4 border-emerald-400">
                <div class="text-sm font-medium text-emerald-600">Receita do Período</div>
                <div class="mt-1 text-3xl font-semibold text-emerald-700">R$ {{ number_format($totalReceipts, 2, ',', '.') }}</div>
            </div>
            <div class="card-pastel border-l-4 border-red-400">
                <div class="text-sm font-medium text-red-600">Custo de Insumos</div>
                <div class="mt-1 text-3xl font-semibold text-red-700">R$ {{ number_format($productCost, 2, ',', '.') }}</div>
            </div>
            <div class="card-pastel border-l-4 border-blue-400">
                <div class="text-sm font-medium text-blue-600">Lucro Líquido</div>
                <div class="mt-1 text-3xl font-semibold text-blue-700">R$ {{ number_format($profit, 2, ',', '.') }}</div>
            </div>
            <div class="card-pastel border-l-4 border-amber-400">
                <div class="text-sm font-medium text-amber-600">A Receber (pendentes)</div>
                <div class="mt-1 text-3xl font-semibold text-amber-700">R$ {{ number_format($totalPending, 2, ',', '.') }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card-pastel">
                <h3 class="text-lg font-semibold text-brand-800 mb-4">Evolução Financeira</h3>
                <canvas id="financialChart" height="200"></canvas>
            </div>
            <div class="card-pastel">
                <h3 class="text-lg font-semibold text-brand-800 mb-4">Por Forma de Pagamento</h3>
                @if($byMethod->count() > 0)
                    <div class="space-y-3">
                        @php $methodLabels = ['dinheiro' => 'Dinheiro', 'cartao' => 'Cartão', 'pix' => 'PIX'] @endphp
                        @php $methodColors = ['dinheiro' => 'bg-emerald-400', 'cartao' => 'bg-blue-400', 'pix' => 'bg-purple-400'] @endphp
                        @php $totalAll = $byMethod->sum('total') @endphp
                        @foreach($byMethod as $m)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium">{{ $methodLabels[$m->method] ?? $m->method }}</span>
                                <span>R$ {{ number_format($m->total, 2, ',', '.') }} ({{ $m->count }} pagamentos)</span>
                            </div>
                            <div class="w-full bg-brand-100 rounded-full h-2.5">
                                @php $pct = $totalAll > 0 ? ($m->total / $totalAll * 100) : 0 @endphp
                                <div class="{{ $methodColors[$m->method] ?? 'bg-brand-400' }} h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-brand-400 text-center py-8">Nenhum pagamento no período.</p>
                @endif
            </div>
        </div>

        <div class="card-pastel p-0 overflow-hidden">
            <div class="p-4 border-b border-brand-100 bg-brand-50/30">
                <h3 class="font-semibold text-brand-700">Pagamentos Recebidos</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table-pastel">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Serviço</th>
                            <th>Valor</th>
                            <th>Forma</th>
                            <th>Registrado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $p)
                        <tr>
                            <td>{{ $p->paid_at->format('d/m/Y H:i') }}</td>
                            <td class="font-medium">{{ $p->appointment?->customer?->name ?? '—' }}</td>
                            <td>{{ $p->appointment?->service?->name ?? '—' }}</td>
                            <td class="font-semibold text-emerald-700">R$ {{ number_format($p->amount, 2, ',', '.') }}</td>
                            <td>
                                @php
                                    $methodLabels = ['dinheiro' => 'Dinheiro', 'cartao' => 'Cartão', 'pix' => 'PIX'];
                                    $methodColors = ['dinheiro' => 'bg-emerald-100 text-emerald-700', 'cartao' => 'bg-blue-100 text-blue-700', 'pix' => 'bg-purple-100 text-purple-700'];
                                @endphp
                                <span class="badge-pastel {{ $methodColors[$p->method] ?? 'bg-stone-100 text-stone-700' }}">
                                    {{ $methodLabels[$p->method] ?? $p->method }}
                                </span>
                            </td>
                            <td>{{ $p->registeredBy?->name ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-brand-400">Nenhum pagamento encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-brand-100">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('financialChart'), {
    type: 'bar',
    data: {
        labels: @json(array_column($chartData, 'label')),
        datasets: [{
            label: 'Receita (R$)',
            data: @json(array_column($chartData, 'value')),
            backgroundColor: '#22c55e',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: '#f0fdf4' } } }
    }
});
</script>
@endpush