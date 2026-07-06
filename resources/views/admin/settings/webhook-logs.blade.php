@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-brand-800 dark:text-brand-200 leading-tight">Webhooks Recebidos ({{ number_format($total) }})</h2>
        <a href="{{ route('admin.settings.evolution') }}" class="btn-pastel-secondary">Evolution API</a>
    </div>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="card-pastel">
            <form method="GET" action="{{ route('admin.webhook-logs.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[150px]">
                    <label class="label">Evento</label>
                    <select name="event" class="input-pastel">
                        <option value="">Todos</option>
                        @foreach($events as $event)
                            <option value="{{ $event }}" {{ request('event') === $event ? 'selected' : '' }}>{{ $event }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="label">Telefone</label>
                    <input type="text" name="phone" value="{{ request('phone') }}" placeholder="Buscar por telefone..." class="input-pastel">
                </div>
                <button type="submit" class="btn-pastel-primary">Filtrar</button>
                @if(request('event') || request('phone'))
                    <a href="{{ route('admin.webhook-logs.index') }}" class="btn-pastel-secondary">Limpar</a>
                @endif
            </form>
        </div>

        <div class="card-pastel p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-pastel w-full">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Evento</th>
                            <th>Telefone</th>
                            <th>De</th>
                            <th>Mensagem</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($webhooks as $webhook)
                        <tr class="{{ $webhook->from_me ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                            <td class="text-xs text-stone-500 dark:text-stone-400 whitespace-nowrap">{{ $webhook->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <span class="px-2 py-0.5 text-xs rounded-full {{ str_contains($webhook->event ?? '', 'upsert') ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-stone-100 text-stone-600 dark:bg-stone-700 dark:text-stone-400' }}">
                                    {{ $webhook->event ?? '—' }}
                                </span>
                            </td>
                            <td class="text-sm text-stone-700 dark:text-stone-300">{{ $webhook->sender_phone ?? '—' }}</td>
                            <td class="text-sm">
                                @if($webhook->from_me)
                                    <span class="text-blue-600 dark:text-blue-400 font-medium">Bot</span>
                                @else
                                    <span class="text-stone-600 dark:text-stone-400">Cliente</span>
                                @endif
                            </td>
                            <td class="text-sm text-stone-700 dark:text-stone-300 max-w-xs truncate">{{ $webhook->message_content ?? '—' }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.webhook-logs.show', $webhook) }}" class="text-brand-600 hover:text-brand-800 dark:text-brand-400 dark:hover:text-brand-300 text-xs font-medium">Ver JSON</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center">
                                <svg class="w-10 h-10 mx-auto mb-2 text-brand-300 dark:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <p class="text-brand-400 dark:text-brand-500 text-sm">Nenhum webhook registrado.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-brand-100 dark:border-stone-700">
                {{ $webhooks->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
