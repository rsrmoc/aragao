<x-layouts.app>
    <div class="drawer lg:drawer-open">
        <input type="checkbox" class="drawer-toggle" id="drawerCheck" />
        <div class="drawer-content overflow-y-auto h-screen">
            <div class="navbar shadow bg-white lg:hidden">
                <div class="navbar-start">
                    <label for="drawerCheck" class="btn btn-ghost">
                        <i class="fa-solid fa-bars"></i>
                    </label>
                </div>

                <div class="navbar-center">
                    <img src="{{ asset('/images/logo_square.webp') }}" class="w-10" />
                </div>
            </div>

            <div class="p-3">
                <div class="bg-white p-5 shadow-sm">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <div class="drawer-side">
            <label for="drawerCheck" class="drawer-overlay"></label>

            <ul class="menu p-4 w-72 min-h-full bg-zinc-900 text-base-100">
                <div class="mb-5">
                    <img src="{{ asset('/images/big_logo.webp') }}" alt="Aragão Construtora" class="w-28 mx-auto" />
                </div>

                <li>
                    <x-components.dashboard.menu.link route="dashboard.home" icon="fa-solid fa-house" text="Início" />
                </li>

                @if (auth()->user()->type == 'admin')
                    <li>
                        <x-components.dashboard.menu.link route="dashboard.usuarios" icon="fa-solid fa-users" text="Usuários" />
                    </li>

                    <li>
                        <x-components.dashboard.menu.link route="dashboard.engenheiros" icon="fa-solid fa-house-user" text="Engenheiros" />
                    </li>
                    
                    <li>
                        <x-components.dashboard.menu.link route="dashboard.clientes" icon="fa-solid fa-user-tie" text="Clientes" />
                    </li>
                @endif

                <li>
                    <x-components.dashboard.menu.link route="dashboard.obras" icon="fa-solid fa-person-digging" text="Obras" />
                </li>

                <li>
                    <x-components.dashboard.menu.link route="dashboard.reunioes" icon="fa-solid fa-video" text="Reuniões" />
                </li>

                <li>
                    <x-components.dashboard.menu.link route="login" icon="fa-solid fa-message" text="Chat" />
                </li>

                <div class="mt-auto">
                    <div class="flex items-center gap-3 p-3">
                        <div class="avatar placeholder">
                            <div class="w-8 bg-primary rounded-full">
                                <span>{{ auth()->user()->name_sigla }}</span>
                            </div>
                        </div>
                        <span>{{ auth()->user()->name }}</span>
                    </div>

                    <li>
                        <x-components.dashboard.menu.link route="dashboard.minha-conta" icon="fa-solid fa-circle-user" text="Minha conta" />
                    </li>

                    <li>
                        <x-components.dashboard.menu.link route="dashboard.logout" icon="fa-solid fa-arrow-right-from-bracket" text="Sair" />
                    </li>
                </div>
            </ul>
        </div>
    </div>

    @push('scripts')
        @vite('resources/sass/dashboard.scss')        
    @endpush
</x-layouts.app>
