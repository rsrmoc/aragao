<div x-data="etapasTabAditivos" x-on:clear-file-input="$refs.inputImagens.value = null, infoAditivo = null">
    <x-components.dashboard.navbar.navbar title="Aditivos da obra">
        @if (auth()->user()->type !== 'client')
            <button class="btn btn-sm btn-primary" x-on:click="$wire.modal = true">
                <span class="hidden sm:inline">Adicionar aditivo</span>
                <i class="fa-solid fa-plus sm:hidden"></i>
            </button>
        @endif
    </x-components.dashboard.navbar.navbar>

    <div>
        <table class="table table-xs table-zebra hidden sm:table">
            <thead>
                <tr class="active">
                    <th>Data</th>
                    <th>Titulo</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($aditivos as $aditivo)
                    <tr wire:loading.class="active" wire:target="excluirAditivo({{ $aditivo->id }})">
                        <td>{{ date_format(date_create($aditivo->created_at), 'd/m/Y H:i') }}</td>
                        <td>{{ $aditivo->titulo }}</td>
                        <td>{{ $aditivo->descricao }}</td>
                        <td>
                            <div wire:loading.remove wire:target="excluirAditivo({{ $aditivo->id }})">
                                <x-components.dashboard.dropdown.dropdown-table class="dropdown-top">
                                    <x-components.dashboard.dropdown.dropdown-item text="Informações" icon="fa-solid fa-circle-info"
                                        x-on:click="setInfoAditivo({{$aditivo}})" />

                                    @if (auth()->user()->type !== 'client')
                                        <x-components.dashboard.dropdown.dropdown-item text="Editar" icon="fa-solid fa-pen-to-square"
                                            x-on:click="setEditModal({{ $aditivo }}, () => $wire)" />

                                        <x-components.dashboard.dropdown.dropdown-item text="Excluir" icon="fa-solid fa-trash"
                                            x-on:click="excluirAditivo({{ $aditivo }}, () => $wire)" />
                                    @endif
                                </x-components.dashboard.dropdown.dropdown-table>
                            </div>

                            <div wire:loading wire:target="excluirAditivo({{ $aditivo->id }})">
                                <x-components.loading class="loading-sm" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="sm:hidden">
            @foreach ($aditivos as $aditivo)
                <div class="flex justify-between gap-3 p-3 border border-b-4 border-b-primary rounded-xl mb-2">
                    <div>
                        <div class="flex gap-2 mb-3">
                            <div wire:loading wire:target="excluirAditivo({{ $aditivo->id }})">
                                <x-components.loading class="loading-sm" />
                            </div>

                            <div class="w-full">
                                <strong class="text-lg">{{ $aditivo->titulo }}</strong>
                            </div>

                            <div class="w-full">
                                <strong class="text-lg">{{ $aditivo->descricao }}</strong>
                            </div>
                        </div>

                        <div class="flex mb-2">
                            <div class="text-sm">
                                <strong class="mr-1">Dt.:</strong>
                                <span>{{ date_format(date_create($aditivo->created_at), 'd/m/Y H:i') }}</span>
                            </div>

                            <div class="divider divider-horizontal"></div>

                            <div class="text-sm">
                                <strong class="mr-1">Resp.:</strong>
                                <span>{{ $aditivo->usuario?->name }}</span>
                            </div>
                        </div>

                        <div>
                            <div class="tooltip" data-tip="{{ $aditivo->descricao }}">
                                <span class="block w-72 text-ellipsis overflow-hidden whitespace-nowrap text-sm text-left">
                                    <strong>Descrição:</strong> {{ $aditivo->descricao }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-components.dashboard.dropdown.dropdown-table class="dropdown-top">
                            <x-components.dashboard.dropdown.dropdown-item text="Informações" icon="fa-solid fa-circle-info"
                                x-on:click="setInfoAditivo({{$aditivo}})" />

                            @if (auth()->user()->type !== 'client')
                                <x-components.dashboard.dropdown.dropdown-item text="Editar" icon="fa-solid fa-pen-to-square"
                                    x-on:click="setEditModal({{ $aditivo }}, () => $wire)" />

                                <x-components.dashboard.dropdown.dropdown-item text="Excluir" icon="fa-solid fa-trash"
                                    x-on:click="excluirAditivo({{ $aditivo }}, () => $wire)" />
                            @endif
                        </x-components.dashboard.dropdown.dropdown-table>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="py-6">
            {{ $aditivos->links() }}
        </div>

        @if (count($aditivos) == 0)
            <p class="text-center text-xs p-8 text-gray-600">Nenhum aditivo</p>
        @endif
    </div>

    <div class="modal" x-bind:class="{'modal-open': $wire.modal}">
        <form wire:submit.prevent="saveAditivo" class="modal-box">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg" x-text="`${$wire.editId ? 'Editar' : 'Novo'} aditivo`"></h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="closeModal(() => $wire)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            @if ($errors->get('inputsImages.*'))
                <label class="label">
                    <span class="label-text-alt text-red-600">Há erros de validação ao subir a(s) imagem(ns). Confira abaixo.</span>
                </label>
            @endif

            <div>
                <div class="flex gap-3 mb-2 flex-wrap sm:flex-nowrap">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Etapa <span class="text-red-500">*</span></span>
                        </label>

                        <select class="select select-sm select-bordered" required wire:model="inputs.id_etapa">
                            <option value="">SELECIONE</option>
                            @foreach ($etapas as $etapa)
                                <option value="{{ $etapa->id }}">#{{ $etapa->id }} - {{ $etapa->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <x-components.input type="date" label="Data do adivito" placeholder="Data do aditivo" required
                            class="input-sm" wire:model="inputs.dt_aditivo" name="inputs.dt_aditivo" />
                    </div>
                </div>

                <div class="form-control">
                    <x-components.input type="text" label="Titulo" placeholder="Titulo do Aditivo" required
                        class="input-sm" wire:model="inputs.titulo" name="inputs.titulo" />
                </div>

                <div class="mb-2">
                    <x-components.input type="textarea" label="Descrição" placeholder="Descrição" required
                        rows="5" class="textarea-sm" wire:model="inputs.descricao" name="inputs.descricao" />
                </div>

                <div class="flex gap-2 items-end">
                    <div wire:loading wire:target="inputsImages">
                        <x-components.loading class="loading-xs" />
                    </div>

                    <x-components.input type="file" label="Anexos (apenas imagens ou PDF)" placeholder="Anexos"
                        class="file-input-sm" accept=".jpg,.png,.webp,.jpeg,.pdf"
                        multiple wire:model="inputsImages" name="inputsImages" x-ref="inputImagens" />
                </div>

                <div>
                    @foreach ($errors->get('inputsImages.*') as $error_line)
                        @foreach ($error_line as $error)
                            <label class="label">
                                <span class="label-text-alt text-red-600">{{ $error }}</span>
                            </label>
                        @endforeach
                    @endforeach
                </div>

                <template x-if="infoAditivo">
                    <div class="mt-3">
                        <strong class="text-sm">Arquivos salvos anteriormente:</strong>
                    </div>
                </template>

                <div class="flex overflow-x-auto p-2 gap-3">
                    <template x-for="(imagem, i) in (infoAditivo?.imagens).filter(imagem => imagem.tipo != 'pdf')" :key="i">
                        <div class="w-28 h-28 relative">
                            <button type="button" class="btn btn-sm btn-ghost btn-circle absolute"
                                x-on:click="exclurImagem(imagem.id, () => $wire)">
                                <i class="fa-solid fa-trash text-red-600"></i>
                            </button>

                            <img x-bind:src="imagem.url" class="w-full h-full object-cover rounded-md cursor-pointer"
                                x-on:click="setModalImage(imagem.url)" />
                        </div>
                    </template>
                    <template x-for="(imagem, i) in (infoAditivo?.imagens).filter(imagem => imagem.tipo == 'pdf')" :key="i">
                        <div class="w-28 h-28 relative">
                            <button type="button" class="btn btn-sm btn-ghost btn-circle absolute"
                                x-on:click="exclurImagem(imagem.id, () => $wire)">
                                <i class="fa-solid fa-trash text-red-600"></i>
                            </button>

                            <img src="{{ asset('/images/pdf.webp') }}" class="w-full h-full object-cover rounded-md cursor-pointer"
                                x-on:click="abrirArquivo(imagem.url)" />
                        </div>
                    </template>
                </div>
            </div>

            <div class="modal-action">
                <button type="submit" class="btn btn-sm btn-primary" wire:loading.attr="disabled">
                    <div wire:loading wire:target="saveAditivo">
                        <x-components.loading class="loading-xs" />
                    </div>

                    <span>Salvar</span>
                </button>
            </div>
        </form>
    </div>

    <div class="modal" x-bind:class="{'modal-open': modalInfoAditivo}">
        <div class="modal-box">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">Informações do aditivo</h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="modalInfoAditivo = false">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div>
                <div class="mb-1">
                    <strong class="text-sm">Titulo:</strong>
                    <span class="text-sm" x-text="`#${infoAditivo?.titulo}`"></span>
                </div>

                <div class="mb-2">
                    <strong class="text-sm">Descrição:</strong>
                    <span class="text-sm" x-text="`#${infoAditivo?.descricao}`"></span>
                </div>

                <div class="mb-2">
                    <strong class="text-sm">Data de cadastro:</strong>
                    <span class="text-sm" x-text="$store.helpers.formatDate(infoAditivo?.created_at)"></span>
                </div>

                <strong class="text-sm">Imagens:</strong>
                <div class="flex overflow-x-auto p-2 gap-3">
                    <template x-for="(imagem, i) in (infoProjeto?.imagens).filter(imagem => imagem.tipo != 'pdf')" :key="i">
                        <div class="w-28 h-28 relative">
                            <img x-bind:src="imagem.url" class="w-full h-full object-cover rounded-md cursor-pointer"
                                x-on:click="setModalImage(imagem.url)" />
                        </div>
                    </template>
                    <template x-for="(imagem, i) in (infoProjeto?.imagens).filter(imagem => imagem.tipo == 'pdf')" :key="i">
                        <div class="w-28 h-28 relative">
                            <img src="{{ asset('/images/pdf.webp') }}" class="w-full h-full object-cover rounded-md cursor-pointer"
                                x-on:click="abrirArquivo(imagem.url)" />
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" x-bind:class="{'modal-open': modalImage}">
        <div class="modal-box p-0 relative max-w-4xl">
            <button type="button" class="btn btn-sm btn-ghost btn-circle text-white absolute top-3 right-3" x-on:click="modalImage = false">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <img x-bind:src="modalImageSrc" class="w-full" />
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/views/obras/etapas-tabs-aditivos.js');
@endpush
