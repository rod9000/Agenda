@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">Migração do Banco</h2>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="card-pastel text-center">
            <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-stone-800 dark:text-stone-200 mb-2">Executar Migrações?</h3>
            <p class="text-sm text-stone-500 dark:text-stone-400 mb-6">
                Isso atualizará a estrutura do banco de dados. Recomendado apenas após upload de novas migrations.
            </p>
            <form method="POST" action="{{ route('admin.migrate') }}">
                @csrf
                <button type="submit" class="btn-pastel-primary" onclick="return confirm('Tem certeza? Esta ação pode travar se houver muitas tabelas.')">
                    Executar Migrate
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn-pastel-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
