<div x-data="etapasTabFuncionarios">
    <x-components.dashboard.navbar.navbar title="Funcionários">
        @if (auth()->user()->type !== 'client')
            <button class="btn btn-sm btn-primary" x-on:click="$wire.modal = true">Adicionar</button>
        @endif
    </x-components.dashboard.navbar.navbar>

    <div>
        <table class="table table-sm">
            <thead>
                <tr class="active">
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Número</th>
                    <th>Função</th>
                    <th>Conselho</th>
                    @if (auth()->user()->type !== 'client')
                        <th>Ações</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach ($obrasFuncionarios as $item)
                    <tr wire:loading.class="active" wire:target="delFuncionario({{ $item->id }})">
                        <td>{{ $item->funcionario->nome }}</td>
                        <td>{{ $item->funcionario->cpf }}</td>
                        <td>{{ $item->funcionario->telefone }}</td>
                        <td>{{ $item->funcao }}</td>
                        <td>{{ $item->conselho }}</td>
                        @if (auth()->user()->type !== 'client')
                            <td>
                                <div wire:loading.remove wire:target="delFuncionario({{ $item->id }})">
                                    <x-components.dashboard.dropdown.dropdown-table>
                                        <x-components.dashboard.dropdown.dropdown-item text="Excluir" icon="fa-solid fa-trash"
                                            x-on:click="delFuncionario({{ $item }}, () => $wire)" />
                                    </x-components.dashboard.dropdown.dropdown-table>
                                </div>

                                <div wire:loading wire:target="delFuncionario({{ $item->id }})">
                                    <x-components.loading class="loading-sm" />
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (count($obrasFuncionarios) == 0)
            <p class="text-center text-xs text-gray-600 p-8">Nenhum funcionário</p>
        @endif
    </div>

    <div class="modal" x-bind:class="{ 'modal-open': $wire.modal }"> 
        <form wire:submit.prevent="saveFuncionario" class="modal-box">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">Novo funcionário</h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="closeModal(() => $wire)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div>
                <div class="flex w-full md:w-6/12 gap-2 mb-3">
                    <x-components.input class="input-sm" label="CPF" placeholder="CPF" required
                        x-mask="999.999.999-99" wire:model="inputsFuncionario.cpf" name="inputsFuncionario.cpf" />

                    <button type="button" class="btn btn-sm mt-9" wire:click="pesquisaFuncionarioInputs"
                        wire:loading.attr="disabled" wire:target="pesquisaFuncionarioInputs">
                        <div wire:loading wire:target="pesquisaFuncionarioInputs">
                            <x-components.loading class="loading-xs" />
                        </div>
                        <i wire:loading.remove wire:target="pesquisaFuncionarioInputs" class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

                <div class="mb-3">
                    <x-components.input class="input-sm" label="Nome" placeholder="Nome" required x-bind:disabled="$wire.inputsDisabled"
                        wire:model="inputsFuncionario.nome" name="inputsFuncionario.nome"  />
                </div>

                <div class="flex gap-3 mb-3">
                    <div>
                        <x-components.input class="input-sm" label="Número" placeholder="Número" required x-bind:disabled="$wire.inputsDisabled"
                            x-mask="(99) 99999-9999" wire:model="inputsFuncionario.telefone" name="inputsFuncionario.telefone"  />
                    </div>

                    <div>
                        <x-components.input type="number" class="input-sm" label="RG" placeholder="RG" required x-bind:disabled="$wire.inputsDisabled"
                            wire:model="inputsFuncionario.rg" name="inputsFuncionario.rg"  />
                    </div>
                </div>

                <div class="flex gap-3">
                    <div>
                        <x-components.input class="input-sm" label="Função" placeholder="Função" required
                            wire:model="inputsFuncionarioObra.funcao" name="inputsFuncionarioObra.funcao"  />
                    </div>

                    <div>
                        <x-components.input class="input-sm" label="Conselho" placeholder="Conselho" required
                            wire:model="inputsFuncionarioObra.conselho" name="inputsFuncionarioObra.conselho"  />
                    </div>
                </div>
            </div>

            <div class="modal-action mt-10">
                <button class="btn btn-sm btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    @vite('resources/js/views/obras/etapas-tabs-funcionarios.js')
@endpush
