@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 dark:text-brand-200 leading-tight">Detalhe do Webhook</h2>
        <a href="{{ route('admin.webhook-logs.index') }}" class="btn-pastel-secondary">Voltar</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        <div class="card-pastel">
            <div class="space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-stone-500 dark:text-stone-400">Data/Hora:</span>
                    <span class="font-medium text-stone-800 dark:text-stone-200">{{ $webhook->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-stone-500 dark:text-stone-400">Evento:</span>
                    <span class="font-medium text-stone-800 dark:text-stone-200">{{ $webhook->event ?? '—' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-stone-500 dark:text-stone-400">Instância:</span>
                    <span class="font-medium text-stone-800 dark:text-stone-200">{{ $webhook->instance ?? '—' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-stone-500 dark:text-stone-400">Telefone:</span>
                    <span class="font-medium text-stone-800 dark:text-stone-200">{{ $webhook->sender_phone ?? '—' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-stone-500 dark:text-stone-400">Remote JID:</span>
                    <span class="font-medium text-stone-800 dark:text-stone-200 font-mono text-xs">{{ $webhook->remote_jid ?? '—' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-stone-500 dark:text-stone-400">Direção:</span>
                    <span class="font-medium {{ $webhook->from_me ? 'text-blue-600 dark:text-blue-400' : 'text-stone-800 dark:text-stone-200' }}">
                        {{ $webhook->from_me ? 'Bot (enviada)' : 'Cliente (recebida)' }}
                    </span>
                </div>
                @if($webhook->message_content)
                <div class="pt-4 border-t border-brand-100 dark:border-stone-700">
                    <span class="text-sm text-stone-500 dark:text-stone-400 block mb-2">Mensagem:</span>
                    <div class="bg-stone-50 dark:bg-stone-800 rounded-xl p-4 text-sm text-stone-800 dark:text-stone-200">
                        {!! nl2br(e($webhook->message_content)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="card-pastel mt-6">
            <h3 class="font-semibold text-brand-700 dark:text-brand-300 mb-4">Payload JSON</h3>
            <pre class="bg-stone-50 dark:bg-stone-800 rounded-xl p-4 text-xs text-stone-700 dark:text-stone-300 overflow-x-auto max-h-96"><code>{{ json_encode($webhook->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
        </div>

    </div>
</div>
@endsection
