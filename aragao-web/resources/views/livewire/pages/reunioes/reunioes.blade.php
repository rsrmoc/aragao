<div x-data="pageReunioes" x-on:reset-all="infoReuniao = null">
    <x-components.dashboard.navbar.navbar title="Reuniões">
        <button class="btn btn-sm btn-primary" x-on:click="$wire.modal = true">
            <span class="sm:hidden">
                <i class="fa-solid fa-plus"></i>
            </span>
            <span class="hidden sm:inline">Agendar reunião</span>
        </button>
    </x-components.dashboard.navbar.navbar>

    <div>
        <table class="table table-xs table-zebra hidden sm:table">
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
                            <span class="badge {{ App\Services\Helpers\StatusService::classStyleStatusReuniao($item->situacao, 'badge') }} badge-sm font-bold text-white">
                                {{ ucfirst(str_replace('_', ' ', $item->situacao)) }}
                            </span>
                        </td>
                        <td>
                            <div wire:loading.remove wire:target="excluirReuniao({{ $item->id }})">
                                <x-components.dashboard.dropdown.dropdown-table>
                                    @if ($item->id_usuario_solicitante === auth()->user()->id)
                                        @if (in_array($item->situacao, ['cancelada', 'concluida', 'conteudo_pendente']))
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

        <div class="sm:hidden">
            @foreach ($reunioes as $item)
                <div class="flex gap-2 py-3 justify-between border-b last:border-0">
                    <div class="w-full">
                        <div class="flex gap-2 items-center mb-2">
                            <div wire:loading wire:target="excluirReuniao({{ $item->id }})">
                                <x-components.loading class="loadin-sm" />
                            </div>
    
                            <div class="w-full flex justify-between items-center">
                                <h3 class="font-bold text-lg">#{{ $item->id }} {{ $item->assunto }}</h3>
    
                                <span class="badge {{ App\Services\Helpers\StatusService::classStyleStatusReuniao($item->situacao, 'badge') }} badge-sm font-bold text-white">
                                    {{ ucfirst(str_replace('_', ' ', $item->situacao)) }}
                                </span>
                            </div>
                        </div>

                        <div class="text-sm">
                            <strong>Data da reunião:</strong>
                            <span>{{ date_format(date_create($item->dt_reuniao), 'd/m/Y \à\s H:i\h') }}</span>
                        </div>
                    </div>

                    <div>
                        <x-components.dashboard.dropdown.dropdown-table>
                            @if ($item->id_usuario_solicitante === auth()->user()->id)
                                @if (in_array($item->situacao, ['cancelada', 'concluida', 'conteudo_pendente']))
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
                </div>
            @endforeach
        </div>

        <div class="py-6">
            {{ $reunioes->links() }}
        </div>

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
                    <select class="select select-bordered select-sm" required wire:model.live="inputs.id_obra"
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

                <h3 class="mb-2 text-sm font-bold">Quem vai participar da reunião?</h3>
                <table class="table table-xs table-zebra">
                    <thead>
                        <tr class="active">
                            <th></th>
                            <th>Usuário</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>

                    <tbody wire:loading.remove wire:target="inputs.id_obra">
                        @foreach ($usuariosPorObra as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" class="checkbox" wire:model="participantes" value="{{ $item->usuario->id }}" />
                                </td>
                                <td>{{ $item->usuario->name }}</td>
                                <td>{{ $item->usuario->type == 'engineer' ? 'Profissional': 'Cliente' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex justify-center">
                    <div class="p-5" wire:loading wire:target="inputs.id_obra">
                        <x-components.loading class="loading-xs" />
                    </div>
                </div>

                @if (count($usuariosPorObra) == 0)
                    <p wire:loading.remove wire:target="inputs.id_obra" class="text-xs text-center p-5">Nenhum usuário</p>
                @endif

                <div class="mt-5">
                    <ul>
                        <template x-for="historico in infoReuniao?.historico">
                            <li>
                                <span class="text-xs" x-html="formatHistorico(historico)"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <div class="modal-action">
                <template x-if="$wire.reuniaoIdEdit">
                    <div class="mr-auto flex items-center gap-3">
                        <button type="button" class="btn btn-xs btn-error" x-on:click="reuniaoCancelada">
                            <div wire:loading wire:target="reuniaoSituacao('cancelada')">
                                <x-components.loading class="loading-sm" />
                            </div>
                            <span>Cancerlar</span>
                        </button>
                        <template x-if="infoReuniao?.situacao === 'confirmada'">
                            <button type="button" class="btn btn-xs btn-success" x-on:click="$wire.modalConteudo = true">
                                <span>Concluida</span>
                            </button>
                        </template>
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
                        x-text="infoReuniao?.situacao?.replace('_', ' ')"></span>

                    <button type="button" class="btn btn-sm btn-circle" x-on:click="closeInfo">
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

                <template x-if="['concluida', 'conteudo_pendente'].includes(infoReuniao?.situacao)">
                    <div class="mb-3">
                        <h4 class="font-bold mb-1">Conteúdo da reunião</h4>
                        <span class="text-sm" x-text="infoReuniao?.conteudo"></span>
                    </div>
                </template>

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

            <template x-if="infoReuniao?.id_usuario_solicitante !== {{ auth()->user()->id }} && !['cancelada', 'concluida', 'conteudo_pendente'].includes(infoReuniao?.situacao)">
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

            <template x-if="infoReuniao?.id_usuario_solicitante !== {{ auth()->user()->id }} && infoReuniao?.situacao === 'conteudo_pendente'">
                <div class="modal-action">
                    <button class="btn btn-sm btn-success" x-on:click="reuniaoConcluida" wire:loading.attr="disabled" wire:target="confirmarConteudo">
                        <div wire:loading wire:target="confirmarConteudo">
                            <x-components.loading class="loading-sm" />
                        </div>
                        <span>Confirmar conteúdo</span>
                    </button>
                </div>
            </template>

            <template x-if="infoReuniao?.id_usuario_solicitante === {{ auth()->user()->id }} && ['conteudo_pendente'].includes(infoReuniao?.situacao)">
                <div class="modal-action">
                    <button class="btn btn-sm btn-primary" x-on:click="setEditConteudoReuniao">
                        <span>Editar conteudo</span>
                    </button>
                </div>
            </template>
        </div>
    </div>

    <div class="modal" x-bind:class="{ 'modal-open': $wire.modalConteudo }">
        <form wire:submit.prevent="salvarConteudoReuniao" class="modal-box">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">
                    <span>Conteúdo da reunião</span>
                </h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="closeConteudo">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div>
                <p class="text-xs mb-3">Informe o que foi discutido na reunião</p>

                <div>
                    <x-components.input type="textarea" placeholder="Conteúdo" label="Conteúdo" required rows="10"
                        wire:model="inputConteudoReuniao" name="inputConteudoReuniao" />
                </div>
            </div>

            <div class="modal-action">
                <button type="submit" class="btn btn-sm btn-primary">
                    <div wire:loading wire:target="salvarConteudoReuniao">
                        <x-components.loading class="loading-xs" />
                    </div>
                    <span>Salvar</span>
                </button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
    @vite('resources/js/pages/dashboard/reunioes.js')
@endpush