@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Auditoria de Atividades</h2>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel p-0 overflow-hidden">
            <div class="p-4 border-b border-brand-100 bg-brand-50/30">
                <form method="GET" class="flex gap-2 flex-wrap items-end">
                    <select name="action" class="input-pastel w-auto">
                        <option value="">Todas as ações</option>
                        <option value="created" @selected(request('action') === 'created')>Criação</option>
                        <option value="updated" @selected(request('action') === 'updated')>Atualização</option>
                        <option value="deleted" @selected(request('action') === 'deleted')>Exclusão</option>
                    </select>
                    <select name="model" class="input-pastel w-auto">
                        <option value="">Todos os modelos</option>
                        <option value="Customer" @selected(request('model') === 'Customer')>Cliente</option>
                        <option value="Appointment" @selected(request('model') === 'Appointment')>Agendamento</option>
                        <option value="Service" @selected(request('model') === 'Service')>Procedimento</option>
                        <option value="Product" @selected(request('model') === 'Product')>Produto</option>
                        <option value="User" @selected(request('model') === 'User')>Usuário</option>
                        <option value="AnamnesisForm" @selected(request('model') === 'AnamnesisForm')>Anamnese</option>
                    </select>
                    <select name="user_id" class="input-pastel w-auto">
                        <option value="">Todos os usuários</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-pastel text-sm" title="Data início">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-pastel text-sm" title="Data fim">
                    <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}" class="input-pastel w-40">
                    <button type="submit" class="btn-pastel-secondary">Filtrar</button>
                    <a href="{{ route('admin.logs.index') }}" class="btn-pastel-secondary">Limpar</a>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="table-pastel">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Usuário</th>
                            <th>Ação</th>
                            <th>Modelo</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $log->user?->name ?? '—' }}</td>
                            <td>
                                <span class="badge-pastel
                                    @if($log->action === 'created') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                    @elseif($log->action === 'updated') bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400
                                    @elseif($log->action === 'deleted') bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400
                                    @endif">
                                    {{ $log->actionLabel() }}
                                </span>
                            </td>
                            <td>{{ $log->modelLabel() }}</td>
                            <td class="text-sm text-stone-600 max-w-md truncate" title="{{ $log->description }}">{{ $log->description }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center">
                            <svg class="w-10 h-10 mx-auto mb-2 text-brand-300 dark:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="text-brand-400 dark:text-brand-500 text-sm">Nenhum registro encontrado.</p>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-brand-100">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection