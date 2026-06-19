@props(['type' => 'success', 'message' => null])

@if(session('success') || session('error') || $message)
<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    x-init="setTimeout(() => show = false, 4000)"
    class="fixed top-4 right-4 z-50 max-w-sm w-full"
>
    <div class="rounded-xl shadow-lg border p-4 flex items-start gap-3
        @if(session('error') || $type === 'error')
            bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800
        @else
            bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800
        @endif"
    >
        @if(session('error') || $type === 'error')
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        @else
            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        @endif
        <div class="flex-1">
            <p class="text-sm font-medium
                @if(session('error') || $type === 'error')
                    text-red-800 dark:text-red-200
                @else
                    text-emerald-800 dark:text-emerald-200
                @endif"
            >{{ $message ?? session('success') ?? session('error') }}</p>
        </div>
        <button @click="show = false" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>
@endif
