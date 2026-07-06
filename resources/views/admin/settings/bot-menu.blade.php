@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.settings.bot') }}" class="text-stone-500 hover:text-stone-700 dark:text-stone-400 dark:hover:text-stone-200">&larr; Voltar</a>
            <h2 class="font-semibold text-xl text-brand-800 dark:text-brand-200 leading-tight">Menu do Bot</h2>
        </div>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 text-sm text-emerald-600 dark:text-emerald-400">
            {{ session('success') }}
        </div>
        @endif

        {{-- Adicionar item --}}
        <div class="card-pastel">
            <h3 class="font-semibold text-brand-700 dark:text-brand-300 mb-4">Adicionar Item ao Menu</h3>
            <form method="POST" action="{{ route('admin.bot-menu.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="label">Nome do Item</label>
                        <input type="text" name="label" required maxlength="100" placeholder="Ex: Agendar Horário" class="input-pastel">
                    </div>
                    <div>
                        <label class="label">Ação</label>
                        <select name="action" required class="input-pastel">
                            @foreach($actionTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Texto de Resposta (opcional)</label>
                        <textarea name="response_text" rows="3" class="input-pastel" placeholder="Texto customizado para esta opção..."></textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="btn-pastel-primary">Adicionar Item</button>
                </div>
            </form>
        </div>

        {{-- Lista de itens --}}
        <div class="card-pastel p-0 overflow-hidden">
            <div class="p-4 border-b border-brand-100 dark:border-stone-700 bg-brand-50/30 dark:bg-stone-800">
                <h3 class="font-semibold text-brand-700 dark:text-brand-300">Itens do Menu</h3>
            </div>

            @if($menuItems->isEmpty())
                <div class="p-8 text-center">
                    <svg class="w-10 h-10 mx-auto mb-2 text-brand-300 dark:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <p class="text-brand-400 dark:text-brand-500 text-sm">Nenhum item configurado.</p>
                </div>
            @else
                <div class="divide-y divide-brand-100/50 dark:divide-stone-700">
                    @foreach($menuItems as $item)
                        <div class="flex items-center gap-3 p-4 {{ !$item->is_active ? 'opacity-50' : '' }}">
                            <span class="w-8 h-8 rounded-full bg-brand-500 text-white flex items-center justify-center text-sm font-bold shrink-0">
                                {{ $item->menu_number }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-stone-800 dark:text-stone-200 text-sm">{{ $item->label }}</p>
                                <p class="text-xs text-stone-500 dark:text-stone-400">{{ $item->getActionLabel() }}</p>
                                @if($item->response_text)
                                    <p class="text-xs text-stone-400 dark:text-stone-500 mt-1 truncate">{{ Str::limit($item->response_text, 60) }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $item->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-stone-200 text-stone-500 dark:bg-stone-700 dark:text-stone-400' }}">
                                    {{ $item->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                                <form method="POST" action="{{ route('admin.bot-menu.destroy', $item) }}" class="inline" onsubmit="return confirm('Remover este item?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-stone-400 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
