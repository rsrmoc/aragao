<div x-data="etapasTabUsuarios">
    <x-components.dashboard.navbar.navbar title="{{ $type == 'engineer' ? 'Profissionais' : 'Clientes' }}">
        <button class="btn btn-sm btn-primary" x-on:click="$wire.modal = true">
            <i class="fa-solid fa-plus sm:hidden"></i>
            <span class="hidden sm:inline">Adicionar</span>
        </button>
    </x-components.dashboard.navbar.navbar>

    <div>
        <table class="table table-xs table-zebra hidden sm:table">
            <thead>
                <tr class="active">
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Número</th>
                    @if ($type == 'engineer')
                        <th>Função</th>
                    @endif
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($usuariosAtribuidos as $item)
                    <tr wire:loading.class="active" wire:target="delUser({{ $item->id }})">
                        <td>{{ $item->usuario->name }}</td>
                        <td>{{ $item->usuario->email }}</td>
                        <td>{{ $item->usuario->phone_number }}</td>
                        @if ($type == 'engineer')
                            <td>{{ $funcoes[$item->tipo] ?? null }}</td>
                        @endif
                        <td>
                            <div wire:loading.remove wire:target="delUser({{ $item->id }})">
                                <x-components.dashboard.dropdown.dropdown-table>
                                    <x-components.dashboard.dropdown.dropdown-item text="Excluir" icon="fa-solid fa-trash" x-on:click="delUser({{ $item }}, () => $wire)" />
                                </x-components.dashboard.dropdown.dropdown-table>
                            </div>

                            <div wire:loading wire:target="delUser({{ $item->id }})">
                                <x-components.loading class="loading-sm" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="sm:hidden">
            @foreach ($usuariosAtribuidos as $item)
                <div class="flex justify-between gap-3 py-3 border-b last:border-0">
                    <div class="w-full">
                        <div class="flex gap-2 mb-2">
                            <div wire:loading wire:target="delUser({{ $item->id }})">
                                <x-components.loading class="loading-sm" />
                            </div>

                            <div>
                                <h3 class="font-bold text-lg">{{ $item->usuario->name }}</h3>
                                <span class="text-sm text-zinc-400">{{ $item->usuario->email }}</span>
                            </div>
                        </div>

                        <div class="text-sm">
                            <strong>Telefone:</strong>
                            <span>{{ $item->usuario->phone_number }}</span>
                        </div>

                        @if ($type == 'engineer')
                            <div class="text-sm mt-1">
                                <strong>Função:</strong>
                                <span>{{ $funcoes[$item->tipo] ?? null }}</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <x-components.dashboard.dropdown.dropdown-table>
                            <x-components.dashboard.dropdown.dropdown-item text="Excluir" icon="fa-solid fa-trash" x-on:click="delUser({{ $item }}, () => $wire)" />
                        </x-components.dashboard.dropdown.dropdown-table>
                    </div>
                </div>
            @endforeach
        </div>

        @empty(count($usuariosAtribuidos))    
            <div class="p-8">
                <p class="text-xs text-gray-600 text-center">Nenhum {{ $type == 'engineer' ? 'engenheiro' : 'cliente' }}
                    atribuído</p>
            </div>
        @endempty
    </div>

    <div class="modal" x-bind:class="{ 'modal-open': $wire.modal }">
        <form wire:submit.prevent="storeUser" class="modal-box">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">Adicionar</h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="$wire.modal = false">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="mb-10">
                <div class="form-control mb-2">
                    <label class="label">
                        <span class="label-text">{{ $type == 'engineer' ? 'Profissionais' : 'Clientes' }}:</span>
                    </label>
                    <select class="select select-bordered select-sm" required
                        wire:model="user">
                        <option value="">SELECIONE</option>
                        @foreach ($usuarios as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}, {{ $item->email }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($type == 'engineer')    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Função</span>
                        </label>

                        <select class="select select-sm select-bordered w-48" wire:model="funcao">
                            <option value="">Nenhum</option>

                            @foreach ($funcoes as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <div class="modal-action">
                <button class="btn btn-sm btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    @vite('resources/js/views/obras/etapas-tabs-usuarios.js')
@endpush