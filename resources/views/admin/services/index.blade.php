@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-amber-800 leading-tight">Procedimentos</h2>
        <a href="{{ route('admin.services.create') }}" class="btn-pastel-primary">+ Novo Procedimento</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel p-0 overflow-hidden">
            <table class="table-pastel">
                <thead>
                    <tr>
                        <th>Procedimento</th>
                        <th>Duração</th>
                        <th>Valor</th>
                        <th>Ativo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $s)
                    <tr>
                        <td>
                            <span class="inline-block w-3 h-3 rounded-full mr-2 shadow-sm" style="background: {{ $s->color_hex }}"></span>
                            <span class="font-medium text-gray-800">{{ $s->name }}</span>
                        </td>
                        <td>{{ $s->duration_min }} min</td>
                        <td class="text-emerald-700 font-medium">R$ {{ number_format($s->price, 2, ',', '.') }}</td>
                        <td>{{ $s->active ? 'Sim' : 'Não' }}</td>
                        <td class="text-right space-x-2">
                            <a href="{{ route('admin.services.edit', $s) }}" class="text-amber-600 hover:text-amber-800 font-medium">Editar</a>
                            <form method="POST" action="{{ route('admin.services.destroy', $s) }}" class="inline" onsubmit="return confirm('Excluir procedimento? As agendas vinculadas também serão removidas.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-medium">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-amber-400">Nenhum procedimento cadastrado.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-amber-100">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
