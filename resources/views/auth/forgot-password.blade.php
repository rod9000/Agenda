<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-brand-500 dark:text-brand-400" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-stone-600 dark:text-stone-400">
            Informe seu e-mail para receber o link de redefinição de senha.
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <x-label for="email" value="E-mail" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    Enviar Link
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
