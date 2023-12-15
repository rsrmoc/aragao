<div x-data="etapasObra">
    <div class="hidden sm:block">
        <x-components.dashboard.navbar.navbar title="#{{ $obra->id }} {{ $obra->nome }}">
            <span
                class="badge {{ App\Services\Helpers\StatusService::classStyleStatusObra($obra->status, 'badge') }} text-white font-bold mr-4">{{ $obra->status }}</span>
            <div class="radial-progress text-info" style="--value: {{ $porcGeral }}; --size: 2.5rem">
                <span class="text-xs">{{ $porcGeral }}%</span>
            </div>
        </x-components.dashboard.navbar.navbar>
    </div>

    <div class="sm:hidden mb-3">
        <h3 class="text-xl font-bold mb-3">#{{ $obra->id }} {{ $obra->nome }}</h3>

        <span
            class="badge {{ App\Services\Helpers\StatusService::classStyleStatusObra($obra->status, 'badge') }} text-white font-bold mr-4">{{ $obra->status }}</span>
        <div class="radial-progress text-info" style="--value: {{ $porcGeral }}; --size: 2.5rem">
            <span class="text-xs">{{ $porcGeral }}%</span>
        </div>
    </div>

    <div class="border rounded overflow-hidden mb-8" x-data="{ expanded: false }">
        <div class="flex justify-between p-3 text-sm text-zinc-500 cursor-pointer" x-on:click="expanded = ! expanded">
            <div>
                <i class="fa-solid fa-circle-info"></i>&ensp;
                <span>Sobre a obra</span>
            </div>

            <div>
                <i x-show="!expanded" class="fa-solid fa-caret-down"></i>
                <i x-show="expanded" class="fa-solid fa-caret-up"></i>
            </div>
        </div>

        <div class="p-3 bg-zinc-50" x-show="expanded" x-collapse>
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
                    <strong>Dt. de início:</strong>
                    <span>{{ date_format(date_create($obra->dt_inicio), 'd/m/Y') }}</span>
                </div>
    
                <div class="divider divider-horizontal"></div>
    
                <div class="text-xs">
                    <strong>Dt. de previsão:</strong>
                    <span>{{ date_format(date_create($obra->dt_previsao_termino), 'd/m/Y') }}</span>
                </div>
    
                <div class="divider divider-horizontal"></div>
    
                <div class="text-xs">
                    <strong>Dt. de término:</strong>
                    <span>{{ $obra->dt_termino ? date_format(date_create($obra->dt_termino), 'd/m/Y') : 'Não definido' }}</span>
                </div>
            </div>
    
            <div class="flex py-2">
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
    </div>

    <div class="tabs flex-nowrap mb-5 overflow-auto">
        <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 1 }" x-on:click="tab = 1">Etapas</button>
        <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 2 }" x-on:click="tab = 2">Evolução</button>
        <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 3 }"
            x-on:click="tab = 3">Relatórios</button>
        <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 4 }"
            x-on:click="tab = 4">Funcionários</button>
        @if (auth()->user()->type == 'admin' || auth()->user()->engineer_admin)
            <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 5 }"
                x-on:click="tab = 5">Profissionais</button>
            <button class="tab tab-lifted" x-bind:class="{ 'tab-active': tab == 6 }"
                x-on:click="tab = 6">Clientes</button>
        @endif
    </div>

    <div>
        <div x-show="tab == 1">
            <x-components.dashboard.navbar.navbar title="Etapas da obra">
                @if (auth()->user()->type !== 'client')
                    <button class="btn btn-sm btn-primary" x-on:click="$wire.modal = true">
                        <span class="hidden sm:inline">Adicionar etapa</span>
                        <i class="fa-solid fa-plus sm:hidden"></i>
                    </button>
                @endif
            </x-components.dashboard.navbar.navbar>

            <div>
                <table class="table table-xs table-zebra hidden sm:table">
                    <thead>
                        <tr class="active">
                            <th>Nome</th>
                            <th>Incidência etapa</th>
                            <th>Execução da etapa</th>
                            <th>Incidência executada</th>
                            <th>Valor da etapa</th>
                            <th>Valor gasto</th>
                            <th>Situação</th>
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
                                    <div class="radial-progress text-success"
                                    style="--value: {{ $item->porc_geral }}; --size: 2.6rem">
                                        <span class="text-xs">{{ $item->porc_geral }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="radial-progress @if ($item->porc_etapa > 99) text-success @else text-primary @endif"
                                        style="--value: {{ $item->porc_etapa }}; --size: 2.6rem">
                                        <span class="text-xs">{{ $item->porc_etapa }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="radial-progress text-primary"
                                        style="--value: {{ $item->insidencia_executada }}; --size: 2.6rem">
                                        <span class="text-xs">{{ $item->insidencia_executada }}%</span>
                                    </div>
                                </td>
                                <td>R$ {{ number_format($item->valor_etapa, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($item->valor_gasto, 2, ',', '.') }}</td>
                                <td>
                                    @if ($item->quitada)
                                        <span class="badge badge-sm badge-success font-bold text-white">Quitado</span>
                                    @else
                                        <span class="badge badge-sm badge-error font-bold text-white">Aberto</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->concluida)
                                        <span class="badge badge-sm badge-success font-bold text-white">Concluída</span>
                                    @else
                                        <span class="badge badge-sm badge-warning font-bold text-white">Andamento</span>
                                    @endif
                                </td>
                                @if (auth()->user()->type !== 'client')
                                    <td>
                                        <div wire:loading.remove wire:target="delEtapa({{ $item->id }})">
                                            <x-components.dashboard.dropdown.dropdown-table>
                                                <x-components.dashboard.dropdown.dropdown-item text="Editar"
                                                    icon="fa-solid fa-pen-to-square"
                                                    x-on:click="setEdit({{ $item }}, () => $wire)" />
                                                <x-components.dashboard.dropdown.dropdown-item text="Excluir"
                                                    icon="fa-solid fa-trash"
                                                    x-on:click="excluirEtapa({{ $item->id }}, () => $wire)" />
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

                    <tfoot>
                        <tr class="active">
                            <th>Total:</th>
                            <th>{{ $sumIncidencia }}%</th>
                            <th></th>
                            <th>{{ $porcGeral }}%</th>
                            <th>R$ {{ number_format($sumValorEtap, 2, ',', '.') }}</th>
                            <th>R$ {{ number_format($sumValorGasto, 2, ',', '.') }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

                <div class="sm:hidden">
                    @foreach ($etapas as $item)
                        <div class="flex justify-between gap-3 p-3 border border-b-4 border-b-primary rounded-xl mb-2">
                            <div class="w-full">
                                <div class="flex gap-2 items-center mb-3">
                                    <div wire:loading wire:target="delEtapa({{ $item->id }})">
                                        <x-components.loading class="loading-sm" />
                                    </div>

                                    <strong class="text-lg">{{ $item->nome }}</strong>
                                </div>

                                <div class="flex justify-between mb-3">
                                    <div class="flex flex-col items-center gap-2">
                                        <strong class="text-sm">Inc. etapa:</strong>
                                        <div class="radial-progress text-success"
                                            style="--value: {{ $item->porc_geral }}; --size: 2.6rem">
                                            <span class="text-xs">{{ $item->porc_geral }}%</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center gap-2">
                                        <strong class="text-sm">Exec. etapa:</strong>
                                        <div class="radial-progress @if ($item->porc_etapa > 99) text-success @else text-primary @endif"
                                            style="--value: {{ $item->porc_etapa }}; --size: 2.6rem">
                                            <span class="text-xs">{{ $item->porc_etapa }}%</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center gap-2">
                                        <strong class="text-sm">Inc. executada:</strong>
                                        <div class="radial-progress text-primary"
                                            style="--value: {{ $item->insidencia_executada }}; --size: 2.6rem">
                                            <span class="text-xs">{{ $item->insidencia_executada }}%</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex mb-2">
                                    <div class="text-xs">
                                        <strong>Val. etapa:</strong>
                                        <span>R$ {{ number_format($item->valor_etapa, 2, ',', '.') }}</span>
                                    </div>
                                    
                                    <div class="divider divider-horizontal mx-0"></div>
                                    
                                    <div class="text-xs">
                                        <strong>Val. gasto:</strong>
                                        <span>R$ {{ number_format($item->valor_gasto, 2, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <div>
                                        @if ($item->quitada)
                                            <span
                                                class="badge badge-sm badge-success font-bold text-white">Quitado</span>
                                        @else
                                            <span class="badge badge-sm badge-error font-bold text-white">Em
                                                aberto</span>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($item->concluida)
                                            <span
                                                class="badge badge-sm badge-success font-bold text-white">Concluída</span>
                                        @else
                                            <span class="badge badge-sm badge-warning font-bold text-white">Em
                                                andamento</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if (auth()->user()->type !== 'client')
                                <div>
                                    <x-components.dashboard.dropdown.dropdown-table>
                                        <x-components.dashboard.dropdown.dropdown-item text="Editar"
                                            icon="fa-solid fa-pen-to-square"
                                            x-on:click="setEdit({{ $item }}, () => $wire)" />
                                        <x-components.dashboard.dropdown.dropdown-item text="Excluir"
                                            icon="fa-solid fa-trash"
                                            x-on:click="excluirEtapa({{ $item->id }}, () => $wire)" />
                                    </x-components.dashboard.dropdown.dropdown-table>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <div class="mt-5 bg-zinc-50 p-3">
                        <h3 class="font-bold mb-2">Totais:</h3>

                        <div class="text-sm">
                            <strong>Inc. etapa:</strong>
                            <span>{{ $sumIncidencia }}%</span>
                        </div>

                        <div class="text-sm">
                            <strong>Inc. executada:</strong>
                            <span>{{ $porcGeral }}%</span>
                        </div>

                        <div class="text-sm">
                            <strong>Val. etapa:</strong>
                            <span>R$ {{ number_format($sumValorEtap, 2, ',', '.') }}</span>
                        </div>

                        <div class="text-sm">
                            <strong>Val. gasto:</strong>
                            <span>R$ {{ number_format($sumValorGasto, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                @if (count($etapas) == 0)
                    <div class="p-10">
                        <p class="text-gray-600 text-center text-xs">Nenhuma etapa criada</p>
                    </div>
                @endif
            </div>
        </div>

        <div x-show="tab == 2">
            <livewire:components.views.obras.etapas-tab-evolucoes obra="{{ $obra->id }}" />
        </div>

        <div x-show="tab == 3">
            <livewire:components.views.obras.etapas-tab-relatorios obra="{{ $obra->id }}" />
        </div>

        <div x-show="tab == 4">
            <livewire:components.views.obras.etapas-tab-funcionarios obra="{{ $obra->id }}" />
        </div>

        <div x-show="tab == 5">
            <livewire:components.views.obras.etapas-tab-usuarios type="engineer" obra="{{ $obra->id }}" />
        </div>

        <div x-show="tab == 6">
            <livewire:components.views.obras.etapas-tab-usuarios type="client" obra="{{ $obra->id }}" />
        </div>
    </div>

    @if (auth()->user()->type !== 'client')
        <div class="modal" x-bind:class="{ 'modal-open': $wire.modal }">
            <form wire:submit.prevent="salvarEtapa" class="modal-box max-w-3xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg">
                        <span x-text="`${ $wire.etapaIdEdit ? 'Editar' : 'Adicionar' } etapa`"></span>
                    </h3>

                    <button type="button" class="btn btn-sm btn-circle" x-on:click="closeModal(() => $wire)">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div>
                    <div class="flex gap-3 sm:gap-5 mb-5 flex-wrap sm:flex-nowrap">
                        <div class="w-full sm:w-6/12">
                            <x-components.input class="input-sm" label="Nome" placeholder="Nome" required
                                wire:model="inputsEtapa.nome" name="inputsEtapa.nome" />
                        </div>

                        <div class="w-5/12 sm:w-3/12">
                            <div class="flex items-end gap-2 w-max">
                                <x-components.input class="input-sm text-right w-full sm:w-20" label="Incidência" placeholder="Incidência" required
                                    wire:model="inputsEtapa.porc_geral" name="inputsEtapa.porc_geral"
                                    type="number" max="100" min="0" step="0.05" />
                                <span>%</span>
                            </div>
                        </div>

                        <div class="w-6/12 sm:w-3/12">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Execução da Etapa <span
                                            class="text-red-500">*</span></span>
                                </label>

                                <select class="select select-sm select-bordered w-24" required
                                    wire:model="inputsEtapa.porc_etapa">
                                    @for ($i = 0; $i <= 100; $i += 5)
                                        <option value="{{ $i }}">{{ $i }}%</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 sm:gap-5 mb-5 flex-col sm:flex-row">
                        <div>
                            <x-components.input type="date" class="input-sm" label="Data de inicio"
                                placeholder="Data de inicio" required wire:model="inputsEtapa.dt_inicio"
                                name="inputsEtapa.dt_inicio" />
                        </div>

                        <div>
                            <x-components.input type="date" class="input-sm" label="Data de previsão"
                                placeholder="Data de previsão" required wire:model="inputsEtapa.dt_previsao"
                                name="inputsEtapa.dt_previsao" />
                        </div>

                        <div>
                            <x-components.input type="date" class="input-sm" label="Data de termino"
                                placeholder="Data de termino" wire:model="inputsEtapa.dt_termino"
                                name="inputsEtapa.dt_termino" />
                        </div>

                        <div>
                            <x-components.input type="date" class="input-sm" label="Data de vencimento"
                                placeholder="Data de vencimento" required wire:model="inputsEtapa.dt_vencimento"
                                name="inputsEtapa.dt_vencimento" />
                        </div>
                    </div>

                    {{-- <div class="flex mb-5">
                        <div class="w-3/12">
                            <x-components.input class="input-sm" label="Valor da etapa" placeholder="Valor da etapa"
                                required wire:model="inputsEtapa.valor" name="inputsEtapa.valor"
                                x-mask:dynamic="$money($input, ',', '.', 2)" />
                        </div>
                    </div> --}}

                    <div class="flex gap-5 mb-5 items-center">
                        <div class="form-control inline-block">
                            <label class="label cursor-pointer">
                                <span class="label-text mr-3">Etapa concluída:</span>
                                <input type="checkbox" class="checkbox" wire:model="inputsEtapa.concluida" />
                            </label>
                        </div>

                        <div class="form-control inline-block">
                            <label class="label cursor-pointer">
                                <span class="label-text mr-3">Etapa quitada:</span>
                                <input type="checkbox" class="checkbox" wire:model="inputsEtapa.quitada" />
                            </label>
                        </div>
                    </div>

                    <x-components.input type="textarea" class="textarea-sm" label="Descrição completa da etapa"
                        placeholder="Descrição completa da etapa" wire:model="inputsEtapa.nome" rows="6"
                        wire:model="inputsEtapa.descricao_completa" name="inputsEtapa.descricao_completa" />
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
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            appearance: none;
        }
    </style>
@endpush

@push('scripts')
    @vite('resources/js/pages/dashboard/etapas-obras.js')
@endpush
