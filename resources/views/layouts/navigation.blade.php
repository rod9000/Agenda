<nav x-data="{ open: false }" class="bg-gradient-to-r from-brand-100 via-yellow-50 to-brand-100 border-b border-brand-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('admin.appointments.index')" :active="request()->routeIs('admin.appointments.*')">
                        Agenda
                    </x-nav-link>
                    <div class="inline-flex items-center">
                        <x-dropdown align="center" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-1 px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-brand-600 hover:border-brand-300 focus:outline-none focus:text-brand-600 focus:border-brand-300 transition duration-150 ease-in-out @if(request()->routeIs('admin.customers.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.products.*')) border-brand-400 text-brand-900 @endif">
                                    <span>Cadastros</span>
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')">
                                    Clientes
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')">
                                    Procedimentos
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                                    Produtos
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="inline-flex items-center">
                        <x-dropdown align="center" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-1 px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-brand-600 hover:border-brand-300 focus:outline-none focus:text-brand-600 focus:border-brand-300 transition duration-150 ease-in-out @if(request()->routeIs('admin.financial.*') || request()->routeIs('admin.commissions.*')) border-brand-400 text-brand-900 @endif">
                                    <span>Financeiros</span>
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.financial.index')" :active="request()->routeIs('admin.financial.*')">
                                    Financeiro
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.commissions.index')" :active="request()->routeIs('admin.commissions.*')">
                                    Comissões
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="inline-flex items-center">
                        <x-dropdown align="center" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-1 px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-brand-600 hover:border-brand-300 focus:outline-none focus:text-brand-600 focus:border-brand-300 transition duration-150 ease-in-out @if(request()->routeIs('admin.anamnesis.*') || request()->routeIs('admin.settings.*')) border-brand-400 text-brand-900 @endif">
                                    <span>Fichas</span>
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.anamnesis.index')" :active="request()->routeIs('admin.anamnesis.*')">
                                    Anamnese
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.settings.working-hours')" :active="request()->routeIs('admin.settings.*')">
                                    Horários
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <button @click="dark = !dark" class="mr-3 p-2 rounded-lg text-stone-500 hover:text-brand-600 hover:bg-brand-100 dark:hover:bg-stone-700 transition-colors" title="Alternar tema">
                    <svg x-show="!dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-brand-600 hover:text-brand-800 hover:border-brand-300 focus:outline-none focus:text-brand-800 focus:border-brand-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                        <x-slot name="content">
                            @if(Auth::user()->isAdmin())
                            <x-dropdown-link :href="route('admin.users.index')">
                                Gerenciar Usuários
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.reports.index')">
                                Relatórios
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.backup.index')">
                                Backup
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.logs.index')">
                                Auditoria
                            </x-dropdown-link>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.appointments.index')" :active="request()->routeIs('admin.appointments.*')">
                Agenda
            </x-responsive-nav-link>
            <div class="pt-2 pb-1">
                <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Cadastros</div>
                <x-responsive-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')">
                    Clientes
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')">
                    Procedimentos
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                    Produtos
                </x-responsive-nav-link>
            </div>
            <div class="pt-2 pb-1">
                <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Financeiros</div>
                <x-responsive-nav-link :href="route('admin.financial.index')" :active="request()->routeIs('admin.financial.*')">
                    Financeiro
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.commissions.index')" :active="request()->routeIs('admin.commissions.*')">
                    Comissões
                </x-responsive-nav-link>
            </div>
            <div class="pt-2 pb-1">
                <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Fichas</div>
                <x-responsive-nav-link :href="route('admin.anamnesis.index')" :active="request()->routeIs('admin.anamnesis.*')">
                    Anamnese
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.settings.working-hours')" :active="request()->routeIs('admin.settings.*')">
                    Horários
                </x-responsive-nav-link>
            </div>
        </div>

            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-stone-700">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-stone-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500 dark:text-stone-400">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link href="#" @click.prevent="dark = !dark">
                        Tema: <span x-text="dark ? 'Claro' : 'Escuro'"></span>
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
    </div>
</nav>
