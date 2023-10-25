<div x-data="tablesUsers">
    <x-components.dashboard.navbar.navbar title="{{ $this->title }}">
        <button class="btn btn-sm btn-primary" x-on:click="$wire.modalAdd = true">Adicionar</button>
    </x-components.dashboard.navbar.navbar>

    <p class="text-xs text-gray-600 mb-6">
        <i class="fa-solid fa-circle-info"></i>&ensp;{{ $descriptionPage }}
    </p>

    <table class="table table-sm table-zebra hidden sm:table w-full">
        <thead>
            <tr class="active">
                <th>#</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Celular</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
                <tr wire:loading.class="active" wire:target="delUser({{ $user->id }})">
                    <td>
                        <div wire:loading wire:target="delUser({{ $user->id }})">
                            <x-components.loading class="loading-xs" />
                        </div>
                        <span wire:loading.class="hidden"
                            wire:target="delUser({{ $user->id }})">{{ $user->id }}</span>
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone_number }}</td>
                    <td>
                        @if ($user->id !== auth()->user()->id)
                            <x-components.dashboard.dropdown.dropdown-table>
                                <x-components.dashboard.dropdown.dropdown-item icon="fa-solid fa-pen-to-square" text="Editar"
                                    x-on:click="setFormEdit({{ $user }}, () => $wire)" />

                                <x-components.dashboard.dropdown.dropdown-item icon="fa-solid fa-trash" text="Excluir"
                                    x-on:click="deleteUser({{ $user->id }}, '{{ substr(strtolower($this->title), 0, strlen($this->title) - 1) }}', '{{ $user->name }}', () => $wire)"
                                    wire:loading.attr="disabled" wire:target="delUser({{ $user->id }})" />
                            </x-components.dashboard.dropdown.dropdown-table>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="sm:hidden">
        @foreach ($users as $user)
            <div class="py-2 border-b last:border-0" wire:loading.class="bg-zinc-200" wire:target="delUser({{ $user->id }})">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div wire:loading wire:target="delUser({{ $user->id }})">
                            <x-components.loading class="loading-xs" />
                        </div>
                        <h3 class="font-bold text-lg">#{{ $user->id }} - {{ $user->name }}</h3>
                    </div>
    
                    <div>
                        @if ($user->id !== auth()->user()->id)
                            <x-components.dashboard.dropdown.dropdown-table>
                                <x-components.dashboard.dropdown.dropdown-item icon="fa-solid fa-pen-to-square" text="Editar"
                                    x-on:click="setFormEdit({{ $user }}, () => $wire)" />
    
                                <x-components.dashboard.dropdown.dropdown-item icon="fa-solid fa-trash" text="Excluir"
                                    x-on:click="deleteUser({{ $user->id }}, '{{ substr(strtolower($this->title), 0, strlen($this->title) - 1) }}', '{{ $user->name }}', () => $wire)"
                                    wire:loading.attr="disabled" wire:target="delUser({{ $user->id }})" />
                            </x-components.dashboard.dropdown.dropdown-table>
                        @endif
                    </div>
                </div>
    
                <div class="text-sm">
                    <div>
                        <strong>Email:</strong>
                        <span>{{ $user->email }}</span>
                    </div>
                    @if ($user->phone_number)
                        <div>
                            <strong>Telefone:</strong>
                            <span>{{ $user->phone_number }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{ $users?->links() }}

    @empty(count($users))
        <div class="p-5">
            <p class="text-sm text-center text-gray-600">{{ $this->title }} não cadastrados</p>
        </div>
    @endempty


    <div class="modal" x-bind:class="{ 'modal-open': $wire.modalAdd }">
        <form wire:submit.prevent="modalSubmit" class="modal-box">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">
                    <span x-text="$wire.userIdEdit ? 'Editar': 'Adicionar'"></span>
                    <span>{{ substr(strtolower($this->title), 0, strlen($this->title) - 1) }}</span>
                </h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="closeModal(() => $wire)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="mb-5">
                <div class="flex gap-5 mb-2">
                    <div class="w-1/2">
                        <x-components.input label="Nome do usuário" placeholder="Nome" class="input-sm" required
                            name="userName" wire:model="userName" />
                    </div>

                    <div class="w-1/2">
                        <x-components.input type="tel" label="Número" placeholder="Número" class="input-sm"
                            x-mask="(99) 99999-9999" name="userPhoneNumber" wire:model="userPhoneNumber" />
                    </div>
                </div>

                <div class="mb-5">
                    <x-components.input type="email" label="Email do usuário" placeholder="Email" class="input-sm"
                        required name="userEmail" wire:model="userEmail" />
                </div>

                <p class="text-xs text-gray-600 mb-6">
                    <i class="fa-solid fa-circle-info"></i>&ensp;Um email será enviado para a definição de senha
                </p>
            </div>

            <div class="modal-action">
                <button type="submit" class="btn btn-sm btn-primary">
                    <div wire:loading wire:target="modalSubmit">
                        <x-components.loading class="loading-xs" />
                    </div>
                    Salvar
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        @vite('resources/js/views/tables-users.js')
    @endpush
</div>

