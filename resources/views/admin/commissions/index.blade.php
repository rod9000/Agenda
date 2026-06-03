@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Comissões</h2>
        <form method="GET" class="flex items-center gap-2">
            <select name="user_id" onchange="this.form.submit()" class="input-pastel text-sm">
                <option value="">Todos os profissionais</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected($userId == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
            <select name="period" onchange="this.form.submit()" class="input-pastel text-sm">
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="card-pastel border-l-4 border-brand-400">
                <div class="text-sm font-medium text-brand-600">Total em Comissões</div>
                <div class="mt-1 text-3xl font-semibold text-brand-900">R$ {{ number_format($totalCommissions, 2, ',', '.') }}</div>
            </div>
            <div class="card-pastel border-l-4 border-emerald-400">
                <div class="text-sm font-medium text-emerald-600">Pago</div>
                <div class="mt-1 text-3xl font-semibold text-emerald-700">R$ {{ number_format($totalPaid, 2, ',', '.') }}</div>
            </div>
            <div class="card-pastel border-l-4 border-amber-400">
                <div class="text-sm font-medium text-amber-600">A Pagar</div>
                <div class="mt-1 text-3xl font-semibold text-amber-700">R$ {{ number_format($totalPending, 2, ',', '.') }}</div>
            </div>
        </div>

        @if($byUser->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($byUser as $b)
            <div class="card-pastel">
                <div class="font-semibold text-brand-700">{{ $b->user->name }}</div>
                <div class="mt-2 flex justify-between text-sm">
                    <span class="text-stone-500">Total:</span>
                    <span class="font-medium">R$ {{ number_format($b->total, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-stone-500">Pago:</span>
                    <span class="font-medium text-emerald-600">R$ {{ number_format($b->paid_total, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-stone-500">A pagar:</span>
                    <span class="font-medium text-amber-600">R$ {{ number_format($b->total - $b->paid_total, 2, ',', '.') }}</span>
                </div>
                <div class="mt-2 w-full bg-brand-100 rounded-full h-2">
                    @php $pct = $b->total > 0 ? ($b->paid_total / $b->total * 100) : 0 @endphp
                    <div class="bg-emerald-400 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="card-pastel p-0 overflow-hidden">
            <div class="p-4 border-b border-brand-100 bg-brand-50/30">
                <h3 class="font-semibold text-brand-700">Comissões Geradas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table-pastel">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Profissional</th>
                            <th>Cliente</th>
                            <th>Serviço</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $c)
                        <tr>
                            <td>{{ $c->created_at->format('d/m/Y') }}</td>
                            <td class="font-medium">{{ $c->user->name }}</td>
                            <td>{{ $c->appointment?->customer?->name ?? '—' }}</td>
                            <td>{{ $c->appointment?->service?->name ?? '—' }}</td>
                            <td class="font-semibold">R$ {{ number_format($c->value, 2, ',', '.') }}</td>
                            <td>
                                @if($c->paid)
                                    <span class="badge-pastel bg-emerald-100 text-emerald-700">Pago</span>
                                @else
                                    <span class="badge-pastel bg-amber-100 text-amber-700">Pendente</span>
                                @endif
                            </td>
                            <td>
                                @if(!$c->paid)
                                <form method="POST" action="{{ route('admin.commissions.mark-paid', $c) }}" class="inline" onsubmit="return confirm('Marcar comissão como paga?')">
                                    @csrf
                                    <button type="submit" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">Marcar Pago</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-brand-400">Nenhuma comissão encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-brand-100">
                {{ $commissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection