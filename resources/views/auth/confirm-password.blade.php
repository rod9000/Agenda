<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-brand-500 dark:text-brand-400" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-stone-600 dark:text-stone-400">
            Por favor, confirme sua senha antes de continuar.
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-label for="password" value="Senha" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    Confirmar
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
