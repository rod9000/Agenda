@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-amber-800 leading-tight">Clientes</h2>
        <a href="{{ route('admin.customers.create') }}" class="btn-pastel-primary">+ Novo Cliente</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel p-0 overflow-hidden">
            <div class="p-4 border-b border-amber-100 bg-amber-50/30">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" placeholder="Buscar por nome, CPF ou telefone..." value="{{ request('search') }}" class="input-pastel flex-1">
                    <button type="submit" class="btn-pastel-secondary">Buscar</button>
                </form>
            </div>

            <table class="table-pastel">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Nascimento</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr>
                        <td class="font-medium text-gray-800">{{ $c->name }}</td>
                        <td>{{ $c->cpf }}</td>
                        <td>{{ $c->phone }}</td>
                        <td>{{ $c->birth_date->format('d/m/Y') }}</td>
                        <td class="text-right space-x-2">
                            <a href="{{ route('admin.customers.edit', $c) }}" class="text-amber-600 hover:text-amber-800 font-medium">Editar</a>
                            <form method="POST" action="{{ route('admin.customers.destroy', $c) }}" class="inline" onsubmit="return confirm('Excluir cliente? Os agendamentos vinculados também serão removidos.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-medium">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-amber-400">Nenhum cliente cadastrado.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-amber-100">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
