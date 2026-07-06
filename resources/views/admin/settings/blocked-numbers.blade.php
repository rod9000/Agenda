@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.settings.evolution') }}" class="text-stone-500 hover:text-stone-700 dark:text-stone-400 dark:hover:text-stone-200">&larr; Voltar</a>
            <h2 class="font-semibold text-xl text-brand-800 dark:text-brand-200 leading-tight">Números Bloqueados</h2>
        </div>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 text-sm text-emerald-600 dark:text-emerald-400">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4 text-sm text-red-600 dark:text-red-400">
            {{ session('error') }}
        </div>
        @endif

        <div class="card-pastel">
            <h3 class="font-semibold text-brand-700 dark:text-brand-300 mb-4">Bloquear Número</h3>
            <p class="text-sm text-stone-500 dark:text-stone-400 mb-4">Números bloqueados não receberão nenhuma mensagem do bot.</p>
            <form method="POST" action="{{ route('admin.blocked-numbers.store') }}">
                @csrf
                <div class="flex gap-4 items-end flex-wrap">
                    <div class="flex-1 min-w-[150px]">
                        <label class="label">Telefone</label>
                        <input type="text" name="phone" placeholder="5544999999999" required class="input-pastel">
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label class="label">Nome (opcional)</label>
                        <input type="text" name="name" placeholder="Nome do contato" class="input-pastel">
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label class="label">Motivo (opcional)</label>
                        <input type="text" name="reason" placeholder="Ex: spam, reclamação" class="input-pastel">
                    </div>
                    <button type="submit" class="btn-pastel-primary whitespace-nowrap">Bloquear</button>
                </div>
            </form>
        </div>

        <div class="card-pastel p-0 overflow-hidden">
            <div class="p-4 border-b border-brand-100 dark:border-stone-700 bg-brand-50/30 dark:bg-stone-800">
                <h3 class="font-semibold text-brand-700 dark:text-brand-300">Números Bloqueados ({{ $blockedNumbers->total() }})</h3>
            </div>

            @if($blockedNumbers->isEmpty())
                <div class="p-8 text-center">
                    <svg class="w-10 h-10 mx-auto mb-2 text-brand-300 dark:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    <p class="text-brand-400 dark:text-brand-500 text-sm">Nenhum número bloqueado.</p>
                </div>
            @else
                <div class="divide-y divide-brand-100/50 dark:divide-stone-700">
                    @foreach($blockedNumbers as $number)
                        <div class="flex items-center justify-between p-4">
                            <div class="flex-1">
                                <p class="font-medium text-stone-800 dark:text-stone-200 text-sm">{{ $number->phone }}</p>
                                @if($number->name)
                                    <p class="text-xs text-stone-500 dark:text-stone-400">{{ $number->name }}</p>
                                @endif
                                @if($number->reason)
                                    <p class="text-xs text-stone-400 dark:text-stone-500">Motivo: {{ $number->reason }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-xs rounded-full">Bloqueado</span>
                                <form method="POST" action="{{ route('admin.blocked-numbers.destroy', $number) }}" onsubmit="return confirm('Desbloquear este número?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-stone-400 hover:text-emerald-500 dark:hover:text-emerald-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 border-t border-brand-100 dark:border-stone-700">
                    {{ $blockedNumbers->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
