@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Usuários</h2>
        <a href="{{ route('admin.users.create') }}" class="btn-pastel-primary">+ Novo Usuário</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-pastel w-full whitespace-nowrap">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Função</th>
                            <th>Ativo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                        <tr>
                            <td class="font-medium text-stone-800 dark:text-stone-200">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->phone ?? '—' }}</td>
                            <td>
                                <span class="badge-pastel {{ $u->role === 'admin' ? 'bg-brand-100 text-brand-700' : 'bg-stone-100 text-stone-600' }}">
                                    {{ $u->role === 'admin' ? 'Admin' : 'Atendente' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-pastel {{ $u->active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $u->active ? 'Sim' : 'Não' }}
                                </span>
                            </td>
                            <td class="text-right space-x-2">
                                <a href="{{ route('admin.users.edit', $u) }}" class="text-brand-600 hover:text-brand-800 font-medium">Editar</a>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline" onsubmit="return confirm('Excluir usuário?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Excluir</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center">
                            <svg class="w-10 h-10 mx-auto mb-2 text-brand-300 dark:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-brand-400 dark:text-brand-500 text-sm">Nenhum usuário cadastrado.</p>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-brand-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
