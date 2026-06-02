@extends('layouts.app')

@section('header')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Fichas de Anamnese</h2>
        <a href="{{ route('admin.anamnesis.create') }}" class="btn-pastel-primary w-full sm:w-auto justify-center">+ Nova Ficha</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel p-0 overflow-hidden">
            <div class="p-4 hidpi:p-5 border-b border-brand-100 bg-brand-50/30">
                <form method="GET" class="flex flex-col sm:flex-row gap-2 hidpi:gap-3">
                    <input type="text" name="search" placeholder="Buscar por nome ou CPF do cliente..." value="{{ request('search') }}" class="input-pastel flex-1 hidpi:text-base hidpi:py-2.5">
                    <button type="submit" class="btn-pastel-secondary w-full sm:w-auto justify-center hidpi:text-base hidpi:py-2.5">Buscar</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="table-pastel w-full">
                    <thead>
                        <tr>
                            <th class="hidden sm:table-cell">Cliente</th>
                            <th class="hidden sm:table-cell">CPF</th>
                            <th>Data</th>
                            <th class="hidden md:table-cell">Preenchido por</th>
                            <th class="hidden sm:table-cell">Consentimento</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($forms as $f)
                        <tr>
                            <td class="font-medium text-gray-800 hidpi:text-base">
                                <span class="sm:hidden block text-xs hidpi:text-sm text-brand-400">Cliente</span>
                                {{ $f->customer?->name ?? '—' }}
                                <span class="sm:hidden block text-xs hidpi:text-sm text-stone-400">{{ $f->customer?->cpf ?? '' }}</span>
                            </td>
                            <td class="hidden sm:table-cell hidpi:text-base">{{ $f->customer?->cpf ?? '—' }}</td>
                            <td class="hidpi:text-base">
                                <span class="sm:hidden block text-xs hidpi:text-sm text-brand-400">Data</span>
                                {{ $f->answered_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="hidden md:table-cell hidpi:text-base">{{ $f->answeredBy?->name ?? '—' }}</td>
                            <td class="hidden sm:table-cell hidpi:text-base">
                                @if($f->consent)
                                    <span class="text-emerald-600 font-medium">Assinado</span>
                                @else
                                    <span class="text-amber-600">Pendente</span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1 sm:gap-2 hidpi:gap-3">
                                    <a href="{{ route('admin.anamnesis.show', $f) }}" class="text-brand-600 hover:text-brand-800 font-medium px-2 hidpi:px-3 py-1 hidpi:py-1.5">Ver</a>
                                    <a href="{{ route('admin.anamnesis.edit', $f) }}" class="text-brand-600 hover:text-brand-800 font-medium px-2 hidpi:px-3 py-1 hidpi:py-1.5">Editar</a>
                                    <form method="POST" action="{{ route('admin.anamnesis.destroy', $f) }}" class="inline" onsubmit="return confirm('Excluir esta ficha de anamnese?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 font-medium px-2 hidpi:px-3 py-1 hidpi:py-1.5">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 hidpi:px-6 py-8 text-center text-brand-400">Nenhuma ficha de anamnese encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-brand-100 overflow-x-auto">
                {{ $forms->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
