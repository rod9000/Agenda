@extends('layouts.app')

@section('header')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Movimentações de Estoque</h2>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-brand-600 hover:text-brand-800">&larr; Voltar para Produtos</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <form method="GET" class="flex flex-wrap items-end gap-3 pb-4 border-b border-brand-100">
            <div>
                <label class="text-xs font-medium text-brand-600">Tipo</label>
                <select name="type" onchange="this.form.submit()" class="input-pastel text-sm">
                    <option value="">Todos</option>
                    <option value="in" @selected(request('type') === 'in')>Entrada</option>
                    <option value="out" @selected(request('type') === 'out')>Saída</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-medium text-brand-600">Produto</label>
                <select name="product_id" onchange="this.form.submit()" class="input-pastel text-sm">
                    <option value="">Todos</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" @selected(request('product_id') == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-medium text-brand-600">De</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-pastel text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-brand-600">Até</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-pastel text-sm">
            </div>
            <div>
                <a href="{{ route('admin.products.movements') }}" class="text-xs text-brand-500 hover:text-brand-700">Limpar filtros</a>
            </div>
        </form>

        <div class="card-pastel p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-brand-50/50 dark:bg-stone-700">
                            <th class="px-2 py-2 text-left font-semibold text-brand-700 dark:text-brand-300">Data</th>
                            <th class="px-2 py-2 text-left font-semibold text-brand-700 dark:text-brand-300">Produto</th>
                            <th class="px-2 py-2 text-left font-semibold text-brand-700 dark:text-brand-300">Tipo</th>
                            <th class="px-2 py-2 text-left font-semibold text-brand-700 dark:text-brand-300">Qtd</th>
                            <th class="px-2 py-2 text-left font-semibold text-brand-700 dark:text-brand-300">Usuário</th>
                            <th class="px-2 py-2 text-left font-semibold text-brand-700 dark:text-brand-300">Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $m)
                        <tr class="border-t border-brand-100/50 hover:bg-brand-50/50 dark:border-stone-700 dark:hover:bg-stone-700/50">
                            <td class="px-2 py-2 text-stone-600 dark:text-stone-400">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-2 py-2">
                                <a href="{{ route('admin.products.show', $m->product) }}" class="font-medium text-brand-600 hover:text-brand-800">{{ $m->product->name }}</a>
                            </td>
                            <td class="px-2 py-2">
                                @if($m->type === 'in')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Entrada</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">Saída</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 font-semibold text-gray-800 dark:text-stone-200">{{ $m->quantity }}</td>
                            <td class="px-2 py-2 text-stone-600 dark:text-stone-400">{{ $m->user?->name ?? '—' }}</td>
                            <td class="px-2 py-2 text-stone-500 dark:text-stone-400 max-w-xs truncate">{{ $m->notes ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-brand-400">Nenhuma movimentação encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-brand-100 dark:border-stone-700">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
