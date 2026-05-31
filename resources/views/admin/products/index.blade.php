@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Produtos</h2>
        <a href="{{ route('admin.products.create') }}" class="btn-pastel-primary">+ Novo Produto</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel p-0 overflow-hidden">
            <table class="table-pastel">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Marca</th>
                        <th>Validade</th>
                        <th>Valor Pago</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td class="font-medium text-stone-800">{{ $p->name }}</td>
                        <td>{{ $p->brand ?? '—' }}</td>
                        <td>{{ $p->expiry_date ? $p->expiry_date->format('d/m/Y') : '—' }}</td>
                        <td class="text-emerald-700 font-medium">R$ {{ number_format($p->purchase_price, 2, ',', '.') }}</td>
                        <td class="text-right space-x-2">
                            <a href="{{ route('admin.products.edit', $p) }}" class="text-brand-600 hover:text-brand-800 font-medium">Editar</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $p) }}" class="inline" onsubmit="return confirm('Excluir produto?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-brand-400">Nenhum produto cadastrado.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-brand-100">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
