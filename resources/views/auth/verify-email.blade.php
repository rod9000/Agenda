<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-brand-500 dark:text-brand-400" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-stone-600 dark:text-stone-400">
            Antes de começar, verifique seu e-mail clicando no link que enviamos.
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-emerald-600 dark:text-emerald-400">
                Um novo link de verificação foi enviado para seu e-mail.
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="underline text-sm text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-200">
                    Reenviar e-mail de verificação
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-200">
                    Sair
                </button>
            </form>
        </div>
    </x-auth-card>
</x-guest-layout>
