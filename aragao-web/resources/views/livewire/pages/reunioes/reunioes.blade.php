<div x-data="pageReunioes">
    <x-components.dashboard.navbar.navbar title="Reuniões">
        <button class="btn btn-sm btn-primary" x-on:click="$wire.modal = true">Agendar reunião</button>
    </x-components.dashboard.navbar.navbar>

    <div>
        <table class="table table-sm">
            <thead>
                <tr class="active">
                    <th>#</th>
                    <th>Assunto</th>
                    <th>Data da reunião</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($reunioes as $item)    
                    <tr wire:loading.class="active" wire:target="excluirReuniao({{ $item->id }})">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->assunto }}</td>
                        <td>{{ date_format(date_create($item->dt_reuniao), 'd/m/Y \à\s H:i\h') }}</td>
                        <td>
                            <span class="badge {{ App\Services\Helpers\StatusService::classStyleStatusReuniao($item->situacao, 'badge') }} badge-sm font-bold text-white">{{ $item->situacao }}</span>
                        </td>
                        <td>
                            <div wire:loading.remove wire:target="excluirReuniao({{ $item->id }})">
                                <x-components.dashboard.dropdown.dropdown-table>
                                    @if ($item->id_usuario_solicitante === auth()->user()->id)
                                        @if ($item->situacao == 'cancelada' || $item->situacao == 'concluida')
                                            <x-components.dashboard.dropdown.dropdown-item text="Informações" icon="fa-solid fa-circle-info"
                                                x-on:click="setInfo({{ $item }})" />
                                        @else
                                            <x-components.dashboard.dropdown.dropdown-item text="Editar" icon="fa-solid fa-pen-to-square"
                                                x-on:click="setEdit({{ $item }})" />
                                        @endif
                                        <x-components.dashboard.dropdown.dropdown-item text="Excluir" icon="fa-solid fa-trash"
                                            x-on:click="excluir({{ $item }})" />
                                    @else
                                        <x-components.dashboard.dropdown.dropdown-item text="Informações" icon="fa-solid fa-circle-info"
                                            x-on:click="setInfo({{ $item }})" />
                                    @endif
                                </x-components.dashboard.dropdown.dropdown-table>
                            </div>

                            <div wire:loading wire:target="excluirReuniao({{ $item->id }})">
                                <x-components.loading class="loading-sm" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $reunioes->links() }}

        @if (count($reunioes) == 0)    
            <div class="p-8">
                <p class="text-xs text-center text-gray-600">Nenhuma reunião agendada</p>
            </div>
        @endif
    </div>

    <div class="modal" x-bind:class="{ 'modal-open': $wire.modal }">
        <form wire:submit.prevent="saveReuniao" class="modal-box">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">
                    <span x-text="`${ $wire.reuniaoIdEdit ? 'Editar' : 'Agendar' } reunião`"></span>
                </h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="closeModal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div>
                <div class="form-control mb-2">
                    <label class="label">
                        <span class="label-text">Obra <span class="text-red-600">*</span></span>
                    </label>
                    <select class="select select-bordered select-sm" required wire:model="inputs.id_obra"
                        x-bind:disabled="$wire.reuniaoIdEdit">
                        <option value="">SELECIONE</option>
                        @foreach ($obrasUsuario as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap gap-3 mb-2">
                    <div class="w-full">
                        <x-components.input label="Assunto" placeholder="Assunto da reunião" required
                            class="input-sm" wire:model="inputs.assunto" name="inputs.assunto" />
                    </div>

                    <div class="w-48">
                        <x-components.input type="datetime-local" class="input-sm" label="Data da reunião" required
                            wire:model="inputs.dt_reuniao" name="inputs.dt_reuniao" />
                    </div>
                </div>

                <div class="mb-2">
                    <x-components.input type="textarea" class="textarea-sm" label="Descrição" rows="4"
                        wire:model="inputs.descricao" name="inputs.descricao" />
                </div>

                <ul class="text-gray-500">
                    <li>
                        <span class="text-xs">
                            <strong>Gean Lima (Cliente)</strong> agendou essa reunião para 12/11/2022 às 12:30h.
                        </span>
                    </li>

                    <li>
                        <span class="text-xs">
                            <strong>Gean Lima (Engenheiro)</strong> confirmou presença.
                        </span>
                    </li>
                </ul>
            </div>

            <div class="modal-action">
                <template x-if="$wire.reuniaoIdEdit">
                    <div class="mr-auto">
                        <button type="button" class="btn btn-sm btn-error" x-on:click="reuniaoCancelada">
                            <div wire:loading wire:target="reuniaoSituacao('cancelada')">
                                <x-components.loading class="loading-sm" />
                            </div>
                            <i wire:loading.remove wire:target="reuniaoSituacao('cancelada')" class="fa-solid fa-ban"></i> 
                            <span>Cancelada</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-success" x-on:click="reuniaoConcluida">
                            <div wire:loading wire:target="reuniaoSituacao('concluida')">
                                <x-components.loading class="loading-sm" />
                            </div>
                            <i wire:loading.remove wire:target="reuniaoSituacao('concluida')" class="fa-solid fa-circle-check"></i> 
                            <span>Concluida</span>
                        </button>
                    </div>
                </template>

                <button type="submit" class="btn btn-sm btn-primary">
                    <div wire:loading wire:target="saveReuniao">
                        <x-components.loading class="loading-sm" />
                    </div>

                    <span x-text="`${ $wire.reuniaoIdEdit ? 'Salvar' : 'Agendar' }`"></span>
                </button>
            </div>
        </form>
    </div>

    <div class="modal" x-bind:class="{ 'modal-open': $wire.modalInfo }">
        <div class="modal-box">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">
                    <span x-text="`#${ infoReuniao?.id } - ${ infoReuniao?.assunto }`"></span>
                </h3>

                <div class="flex gap-3 items-center">
                    <span class="badge text-sm text-white font-bold"
                        x-bind:class="classStylesStatus[infoReuniao?.situacao]"
                        x-text="infoReuniao?.situacao"></span>

                    <button type="button" class="btn btn-sm btn-circle" x-on:click="$wire.modalInfo = false">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <div>
                <div class="mb-3">
                    <h4 class="font-bold mb-2">Descrição</h4>
                    <p class="text-sm" x-text="infoReuniao?.descricao"></p>
                </div>

                <div class="mb-3">
                    <h4 class="font-bold mb-1">Data da reunião</h4>
                    <span class="text-sm" x-text="formatDate(infoReuniao?.dt_reuniao)"></span>
                </div>

                <div>
                    <h4 class="font-bold mb-2">Histórico</h4>

                    <ul>
                        <template x-for="historico in infoReuniao?.historico">
                            <li>
                                <span class="text-xs" x-html="formatHistorico(historico)"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <template x-if="infoReuniao?.id_usuario_solicitante !== {{ auth()->user()->id }} && !['cancelada', 'concluida'].includes(infoReuniao?.situacao)">
                <div class="modal-action">
                    <button type="button" class="btn btn-sm btn-error" x-on:click="$wire.negarReuniao(infoReuniao?.id)"
                        wire:loading.attr="disabled">
                        <div wire:loading wire:target="negarReuniao">
                            <x-components.loading class="loading-sm" />
                        </div>
                        <span>Não disponivel</span>
                    </button>
                    <template x-if="infoReuniao?.situacao !== 'confirmada'">
                        <button type="button" class="btn btn-sm btn-success" x-on:click="$wire.confirmarReuniao(infoReuniao?.id)"
                            wire:loading.attr="disabled">
                            <div wire:loading wire:target="confirmarReuniao">
                                <x-components.loading class="loading-sm" />
                            </div>
                            <span>Confirmar presença</span>
                        </button>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>


@push('scripts')
    @vite('resources/js/pages/dashboard/reunioes.js')
@endpush