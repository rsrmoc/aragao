<div x-data="obrasPage">
    <x-components.dashboard.navbar.navbar title="Obras">
        @if (auth()->user()->type == 'admin' || auth()->user()->engineer_admin)
            <button class="btn btn-sm btn-primary" x-on:click="$wire.modal = true">
                <span class="sm:hidden">
                    <i class="fa-solid fa-plus"></i>
                </span>
                <span class="hidden sm:inline">Adicionar</span>
            </button>
        @endif
    </x-components.dashboard.navbar.navbar>

    <div>
        <table class="table table-xs table-zebra hidden sm:table">
            <thead>
                <tr class="active">
                    <th>#</th>
                    <th>Nome</th>
                    <th>Data de início</th>
                    <th>Previsão</th>
                    <th>Valor</th>
                    <th>Saldo</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($obras as $item)
                    <tr wire:loading.class="active" wire:target="delObra({{ $item->id }})">
                        <td>
                            <div wire:loading wire:target="delObra({{ $item->id }})">
                                <x-components.loading class="loading-xs" />
                            </div>
                            <span wire:loading.class="hidden"
                                wire:target="delObra({{ $item->id }})">{{ $item->id }}</span>
                        </td>
                        <td>{{ $item->nome }}</td>
                        <td>{{ date_format(date_create($item->dt_inicio), 'd/m/Y') }}</td>
                        <td>{{ date_format(date_create($item->dt_previsao_termino), 'd/m/Y') }}</td>
                        <td>{{ App\Services\Helpers\MoneyService::formatToUICurrency($item->valor_quitado) }}</td>
                        <td>{{ App\Services\Helpers\MoneyService::formatToUICurrency($item->valor_aberto) }}</td>
                        <td>
                            <span class="badge {{ App\Services\Helpers\StatusService::classStyleStatusObra($item->status, 'badge') }} badge-sm text-white font-bold">{{ $item->status }}</span>
                        </td>
                        <td>
                            <x-components.dashboard.dropdown.dropdown-table>
                                <x-components.dashboard.dropdown.dropdown-item icon="fa-solid fa-stairs" text="Etapas"
                                    href="{{ route('dashboard.etapas-obra', ['obra' => $item->id]) }}" />
                                @if (auth()->user()->type == 'admin' || auth()->user()->engineer_admin)
                                    <x-components.dashboard.dropdown.dropdown-item icon="fa-solid fa-pen-to-square"
                                        text="Editar" x-on:click="setEditModal({{ $item }}, () => $wire)" />
                                    <x-components.dashboard.dropdown.dropdown-item icon="fa-solid fa-trash" text="Excluir"
                                        x-on:click="deleteObra({{ $item }}, () => $wire)" />
                                @endif
                            </x-components.dashboard.dropdown.dropdown-table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (auth()->user()->type === 'client')
            <div class="sm:hidden">
                @foreach ($obras as $item)
                    <a href="{{ route('dashboard.etapas-obra', ['obra' => $item->id]) }}"
                        class="flex justify-between p-4 border border-b-4 border-b-primary rounded-xl mb-2
                            shadow">
                        <div>
                            <div class="flex gap-2 items-center mb-2">
                                <div wire:loading wire:target="delObra({{ $item->id }})">
                                    <x-components.loading class="loading-xs" />
                                </div>
                                <div class="w-full">
                                    <h3 class="font-bold text-2xl">{{ $item->nome }}</h3>
                                    <div>
                                        <span class="badge {{ App\Services\Helpers\StatusService::classStyleStatusObra($item->status, 'badge') }} badge-sm text-white font-bold">{{ $item->status }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex mb-2">
                                <div class="text-sm">
                                    <strong>Inicio:</strong>
                                    <span>{{ date_format(date_create($item->dt_inicio), 'd/m/Y') }}</span>
                                </div>
                                <div class="divider divider-horizontal h-4"></div>
                                <div class="text-sm">
                                    <strong>Previsão:</strong>
                                    <span>{{ date_format(date_create($item->dt_previsao_termino), 'd/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="flex mb-2">
                                <div class="text-sm">
                                    <strong>Valor:</strong>
                                    <span>{{ App\Services\Helpers\MoneyService::formatToUICurrency($item->valor_quitado) }}</span>
                                </div>
                                <div class="divider divider-horizontal h-4"></div>
                                <div class="text-sm">
                                    <strong>Saldo:</strong>
                                    <span>{{ App\Services\Helpers\MoneyService::formatToUICurrency($item->valor_aberto) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="sm:hidden">
                @foreach ($obras as $item)
                    <div class="flex justify-between py-3 border-b last:border-0">
                        <div>
                            <div class="flex gap-2 items-center mb-2">
                                <div wire:loading wire:target="delObra({{ $item->id }})">
                                    <x-components.loading class="loading-xs" />
                                </div>
                                <div class="w-full">
                                    <h3 class="font-bold text-lg">#{{ $item->id }} {{ $item->nome }}</h3>
                                    <div>
                                        <span class="badge {{ App\Services\Helpers\StatusService::classStyleStatusObra($item->status, 'badge') }} badge-sm text-white font-bold">{{ $item->status }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex mb-2">
                                <div class="text-sm">
                                    <strong>Inicio:</strong>
                                    <span>{{ date_format(date_create($item->dt_inicio), 'd/m/Y') }}</span>
                                </div>
                                <div class="divider divider-horizontal h-4"></div>
                                <div class="text-sm">
                                    <strong>Previsão:</strong>
                                    <span>{{ date_format(date_create($item->dt_previsao_termino), 'd/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="flex mb-2">
                                <div class="text-sm">
                                    <strong>Valor:</strong>
                                    <span>{{ App\Services\Helpers\MoneyService::formatToUICurrency($item->valor_quitado) }}</span>
                                </div>
                                <div class="divider divider-horizontal h-4"></div>
                                <div class="text-sm">
                                    <strong>Saldo:</strong>
                                    <span>{{ App\Services\Helpers\MoneyService::formatToUICurrency($item->valor_aberto) }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <x-components.dashboard.dropdown.dropdown-table>
                                <x-components.dashboard.dropdown.dropdown-item icon="fa-solid fa-stairs" text="Etapas"
                                    href="{{ route('dashboard.etapas-obra', ['obra' => $item->id]) }}" />
                                @if (auth()->user()->type == 'admin' || auth()->user()->engineer_admin)
                                    <x-components.dashboard.dropdown.dropdown-item icon="fa-solid fa-pen-to-square"
                                        text="Editar" x-on:click="setEditModal({{ $item }}, () => $wire)" />
                                    <x-components.dashboard.dropdown.dropdown-item icon="fa-solid fa-trash" text="Excluir"
                                        x-on:click="deleteObra({{ $item }}, () => $wire)" />
                                @endif
                            </x-components.dashboard.dropdown.dropdown-table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{ $obras?->links() }}

        @empty(count($obras))
            <div class="p-5">
                <p class="text-sm text-center text-gray-600">Obras não cadastradas</p>
            </div>
        @endempty
    </div>

    @if (auth()->user()->type == 'admin' || auth()->user()->engineer_admin)
        <div class="modal" x-bind:class="{ 'modal-open': $wire.modal }">
            <form wire:submit.prevent="salvarObra" class="modal-box max-w-3xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg">
                        <span x-text="`${$wire.obraIdEdit ? 'Editar': 'Adicionar'} obra`"></span>
                    </h3>

                    <button type="button" class="btn btn-sm btn-circle" x-on:click="closeModal(() => $wire)">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="mb-8">
                    <div>
                        <div class="flex gap-4 mb-3 flex-col sm:flex-row">
                            <div class="w-full sm:w-2/4">
                                <x-components.input label="Nome" placeholder="Nome" class="input-sm" required
                                    wire:model="inputsAdd.nome" name="inputsAdd.nome" />
                            </div>

                            <div>
                                <label class="label">
                                    <span class="label-text">Tipo de recurso</span>
                                </label>
    
                                <select class="select select-sm select-bordered" wire:model="inputsAdd.tipo_recurso">
                                    <option value="">NENHUM</option>
                                    <option value="proprio">Proprio</option>
                                    <option value="financiamento_caixa">Financiamento caixa</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-4 mb-3 flex-col sm:flex-row">
                            <x-components.input type="date" label="Data de início" placeholder="Data de início"
                                class="input-sm" required wire:model="inputsAdd.dt_inicio" name="inputsAdd.dt_inicio" />

                            <x-components.input type="date" label="Data de previsão" placeholder="Data de previsão"
                                class="input-sm" required wire:model="inputsAdd.dt_previsao_termino"
                                name="inputsAdd.dt_previsao_termino" />

                            <x-components.input type="date" label="Data de término" placeholder="Data de término"
                                class="input-sm" wire:model="inputsAdd.dt_termino" name="inputsAdd.dt_termino" />
                        </div>

                        <div class="form-control mb-5">
                            <label class="label">
                                <span class="label-text">Descrição Completa da Obra</span>
                            </label>

                            <textarea class="textarea textarea-sm textarea-bordered" rows="6"
                                wire:model="inputsAdd.descricao_completa"></textarea>
                        </div>

                        <h4 class="font-bold border-b pb-1 mb-3">Endereço</h4>

                        <div class="flex gap-4 mb-3 flex-wrap sm:flex-nowrap">
                            <div class="w-full sm:w-5/12">
                                <x-components.input label="Rua" placeholder="Rua" class="input-sm" required
                                    wire:model="inputsAdd.endereco_rua" name="inputsAdd.endereco_rua" />
                            </div>

                            <div class="w-3/12 sm:w-2/12">
                                <x-components.input type="number" label="Número" placeholder="Número" class="input-sm"
                                    required wire:model="inputsAdd.endereco_numero" name="inputsAdd.endereco_numero" />
                            </div>

                            <div class="w-8/12 sm:w-5/12">
                                <x-components.input label="Bairro" placeholder="Bairro" class="input-sm" required
                                    wire:model="inputsAdd.endereco_bairro" name="inputsAdd.endereco_bairro" />
                            </div>
                        </div>

                        <div class="flex gap-4 flex-wrap sm:flex-nowrap">
                            <div class="w-full sm:w-5/12">
                                <x-components.input label="Cidade" placeholder="Cidade" class="input-sm" required
                                    wire:model="inputsAdd.endereco_cidade" name="inputsAdd.endereco_cidade" />
                            </div>

                            <div class="form-group w-6/12 sm:w-4/12">
                                <label class="label">
                                    <span class="label-text">Estado <span class="text-red-600">*</span></span>
                                </label>
                                <select class="select select-sm select-bordered w-full"
                                    wire:model="inputsAdd.endereco_uf" required>
                                    <option value="">SELECIONE</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state['acronym'] }}">{{ $state['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('inputsAdd.endereco_uf')
                                    <label class="label">
                                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <div class="w-5/12 sm:w-3/12">
                                <x-components.input label="CEP" placeholder="CEP" class="input-sm" required
                                    x-mask="99999-999" wire:model="inputsAdd.endereco_cep"
                                    name="inputsAdd.endereco_cep" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <div wire:loading wire:target="salvarObra">
                            <x-components.loading class="loading-sm" />
                        </div>
                        <span>Salvar</span>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

@push('scripts')
    @vite('resources/js/pages/dashboard/obras.js')
@endpush
