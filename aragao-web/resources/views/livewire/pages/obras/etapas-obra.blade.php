<div x-data="etapasObra">
    <x-components.dashboard.navbar.navbar title="#{{ $obra->id }} {{ $obra->nome }}">
        <span class="badge {{ App\Services\Helpers\StatusService::classStyleStatusObra($obra->status, 'badge') }} text-white font-bold mr-4">{{ $obra->status }}</span>
        <div class="radial-progress text-info" style="--value: {{ $porcGeral }}; --size: 2.5rem">
            <span class="text-xs">{{ $porcGeral }}%</span>
        </div>
    </x-components.dashboard.navbar.navbar>

    <div class="mb-8">
        <div class="flex py-2 border-b">
            <div class="text-xs">
                <strong>Código:</strong>
                <span>#{{ $obra->id }}</span>
            </div>

            <div class="divider divider-horizontal"></div>

            <div class="text-xs">
                <strong>Nome:</strong>
                <span>{{ $obra->nome }}</span>
            </div>
        </div>

        <div class="flex py-2 border-b">
            <div class="text-xs">
                <strong>Data de início:</strong>
                <span>{{ date_format(date_create($obra->dt_inicio), 'd/m/Y') }}</span>
            </div>

            <div class="divider divider-horizontal"></div>
    
            <div class="text-xs">
                <strong>Data de previsão:</strong>
                <span>{{ date_format(date_create($obra->dt_previsao_termino), 'd/m/Y') }}</span>
            </div>

            <div class="divider divider-horizontal"></div>
    
            <div class="text-xs">
                <strong>Data de término:</strong>
                <span>{{ $obra->dt_termino ? date_format(date_create($obra->dt_termino), 'd/m/Y') : 'Não definido' }}</span>
            </div>
        </div>

        <div class="flex py-2 border-b">
            <div class="text-xs">
                <strong>Valor:</strong>
                <span>{{ App\Services\Helpers\MoneyService::formatToUICurrency($obra->valor) }}</span>
            </div>

            <div class="divider divider-horizontal"></div>
    
            <div class="text-xs">
                <strong>Saldo:</strong>
                <span>{{ App\Services\Helpers\MoneyService::formatToUICurrency($obra->valor_saldo) }}</span>
            </div>
        </div>
    </div>

    <div class="tabs mb-5">
        <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 1 }" x-on:click="tab = 1">Etapas</button>
        <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 2 }" x-on:click="tab = 2">Relatórios</button>
        <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 3 }"
            x-on:click="tab = 3">Funcionários</button>
        @if (auth()->user()->type == 'admin')
            <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 4 }"
                x-on:click="tab = 4">Engenheiros</button>
            <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 5 }"
                x-on:click="tab = 5">Clientes</button>
        @endif
    </div>

    <div>
        <div x-show="tab == 1">
            <x-components.dashboard.navbar.navbar title="Etapas da obra">
                @if (auth()->user()->type !== 'client')
                    <button class="btn btn-sm btn-primary" x-on:click="$wire.modal = true">Adicionar etapa</button>
                @endif
            </x-components.dashboard.navbar.navbar>

            <div>
                <table class="table table-sm">
                    <thead>
                        <tr class="active">
                            <th>Nome</th>
                            <th>Progresso</th>
                            <th>Geral</th>
                            <th>Status</th>
                            @if (auth()->user()->type !== 'client')
                                <th>Ações</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($etapas as $item)    
                            <tr wire:loading.class="active" wire:target="delEtapa({{ $item->id }})">
                                <td>
                                    <strong>{{ $item->nome }}</strong>
                                </td>
                                <td>
                                    <div class="radial-progress @if ($item->porc_etapa > 99) text-success @else text-primary @endif" style="--value: {{ $item->porc_etapa }}; --size: 2.6rem">
                                        <span class="text-xs">{{ $item->porc_etapa }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="radial-progress text-primary" style="--value: {{ $item->porc_geral }}; --size: 2.6rem">
                                        <span class="text-xs">{{ $item->porc_geral }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->concluida)
                                        <span class="badge badge-sm badge-success font-bold text-white">Concluída</span>
                                    @else
                                        <span class="badge badge-sm badge-warning font-bold text-white">Em andamento</span>
                                    @endif
                                </td>
                                @if (auth()->user()->type !== 'client')
                                    <td>
                                        <div wire:loading.remove wire:target="delEtapa({{ $item->id }})">
                                            <x-components.dashboard.dropdown.dropdown-table>
                                                <x-components.dashboard.dropdown.dropdown-item text="Editar"
                                                    icon="fa-solid fa-pen-to-square" x-on:click="setEdit({{ $item }}, () => $wire)" />
                                                <x-components.dashboard.dropdown.dropdown-item text="Excluir"
                                                    icon="fa-solid fa-trash" x-on:click="excluirEtapa({{ $item->id }}, () => $wire)" />
                                            </x-components.dashboard.dropdown.dropdown-table>
                                        </div>

                                        <div wire:loading wire:target="delEtapa({{ $item->id }})">
                                            <x-components.loading class="loading-sm" />
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if (count($etapas) == 0)
                    <div class="p-10">
                        <p class="text-gray-600 text-center text-xs">Nenhuma etapa criada</p>
                    </div>
                @endif
            </div>
        </div>

        <div x-show="tab == 2">
            <livewire:components.views.obras.etapas-tab-relatorios obra="{{ $obra->id }}" />
        </div>

        <div x-show="tab == 3">
            <livewire:components.views.obras.etapas-tab-funcionarios obra="{{ $obra->id }}" />
        </div>

        <div x-show="tab == 4">
            <livewire:components.views.obras.etapas-tab-usuarios type="engineer" obra="{{ $obra->id }}" />
        </div>

        <div x-show="tab == 5">
            <livewire:components.views.obras.etapas-tab-usuarios type="client" obra="{{ $obra->id }}" />
        </div>
    </div>

    @if (auth()->user()->type !== 'client')
        <div class="modal" x-bind:class="{ 'modal-open': $wire.modal }">
            <form wire:submit.prevent="salvarEtapa" class="modal-box">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg">Adicionar etapa</h3>

                    <button type="button" class="btn btn-sm btn-circle" x-on:click="closeModal(() => $wire)">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div>
                    <div class="mb-3">
                        <x-components.input class="input-sm" label="Nome" placeholder="Nome" required
                            wire:model="inputsEtapa.nome" name="inputsEtapa.nome" />
                    </div>

                    <div class="flex gap-5 mb-5">
                        <div class="w-1/2">
                            <x-components.input type="number" class="input-sm text-end" label="Progresso" placeholder="Progresso" required
                                min="0" max="100" icon-end="fa-solid fa-percent" wire:model="inputsEtapa.porc_etapa" name="inputsEtapa.porc_etapa" />
                        </div>
        
                        <div class="w-1/2">
                            <x-components.input type="number" class="input-sm text-end" label="Porcentagem geral" placeholder="Geral" required
                                min="0" max="100" icon-end="fa-solid fa-percent" wire:model="inputsEtapa.porc_geral" name="inputsEtapa.porc_geral" />
                        </div>
                    </div>

                    <div>
                        <div class="form-control inline-block">
                            <label class="label cursor-pointer">
                                <span class="label-text mr-3">Etapa concluída:</span>
                                <input type="checkbox" checked="checked" class="checkbox" wire:model="inputsEtapa.concluida" />
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <div wire:loading wire:target="salvarEtapa">
                            <x-components.loading class="loading-sm" />
                        </div>
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

@push('styles')
    <style>
        input[type=number]::-webkit-outer-spin-button, input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            appearance: none;
        }
    </style>
@endpush

@push('scripts')
    @vite('resources/js/pages/dashboard/etapas-obras.js')
@endpush
