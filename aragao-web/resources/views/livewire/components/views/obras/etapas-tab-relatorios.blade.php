<div x-data="etapasTabRelatorios">
    <x-components.dashboard.navbar.navbar title="Retatórios">
        <button class="btn btn-sm btn-primary" x-on:click="$wire.modal = true">
            <i class="fa-solid fa-plus sm:hidden"></i>
            <span class="hidden sm:inline">Gerar relatório</span>
        </button>
    </x-components.dashboard.navbar.navbar>

    <div>
        <table class="table table-xs table-zebra hidden sm:table">
            <thead>
                <tr class="active">
                    <th>#</th>
                    <th>Arquivo</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($relatorios as $relatorio)
                    <tr wire:key="{{ $relatorio->id }}" wire:loading.class="active" wire:target="excluirRelatorio({{ $relatorio->id }})">
                        <td>{{ $relatorio->id }}</td>
                        <td>
                            <i class="fa-solid {{ str_contains($relatorio->filename, '.pdf') ? 'fa-file-pdf text-red-700' : 'fa-file-csv text-green-700' }} text-lg"></i>
                            <span>Relário gerado em {{ date_format(date_create($relatorio->created_at), 'd/m/Y \à\s H:i') }}</span>
                        </td>
                        <td>
                            <div wire:loading.remove wire:target="excluirRelatorio({{ $relatorio->id }})">
                                <x-components.dashboard.dropdown.dropdown-table>
                                    <x-components.dashboard.dropdown.dropdown-item text="Visualizar" icon="fa-solid fa-eye" wire:click="renderFile({{ $relatorio->id }})" />
                                    <!-- <x-components.dashboard.dropdown.dropdown-item text="Download" icon="fa-solid fa-download" wire:click="downloadFile({{ $relatorio->id }})" /> -->
                                    <x-components.dashboard.dropdown.dropdown-item text="Excluir" icon="fa-solid fa-trash" x-on:click="excluir({{ $relatorio->id }}, () => $wire)" />
                                </x-components.dashboard.dropdown.dropdown-table>
                            </div>

                            <div wire:loading wire:target="excluirRelatorio({{ $relatorio->id }})">
                                <x-components.loading class="loading-sm" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="sm:hidden">
            @foreach ($relatorios as $relatorio)
                <div class="flex justify-between gap-3 py-3 border-b last:border-0">
                    <div class="w-full">
                        <div class="flex gap-2">
                            <div wire:loading wire:target="excluirRelatorio({{ $relatorio->id }})">
                                <x-components.loading class="loading-sm" />
                            </div>
    
                            <div>
                                <h3 class="font-bold text-base">
                                    <i class="fa-solid {{ str_contains($relatorio->filename, '.pdf') ? 'fa-file-pdf text-red-700' : 'fa-file-csv text-green-700' }} text-lg"></i>
                                    #{{ $relatorio->id }} -
                                    Relário gerado em {{ date_format(date_create($relatorio->created_at), 'd/m/Y \à\s H:i') }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div wire:loading.remove wire:target="excluirRelatorio({{ $relatorio->id }})">
                            <x-components.dashboard.dropdown.dropdown-table>
                                <x-components.dashboard.dropdown.dropdown-item text="Visualizar" icon="fa-solid fa-eye" wire:click="renderFile({{ $relatorio->id }})" />
                                <!-- <x-components.dashboard.dropdown.dropdown-item text="Download" icon="fa-solid fa-download" wire:click="downloadFile({{ $relatorio->id }})" /> -->
                                <x-components.dashboard.dropdown.dropdown-item text="Excluir" icon="fa-solid fa-trash" x-on:click="excluir({{ $relatorio->id }}, () => $wire)" />
                            </x-components.dashboard.dropdown.dropdown-table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="py-6">
            {{ $relatorios->links() }}
        </div>

        @if (count($relatorios) == 0)    
            <div class="p-8">
                <p class="text-xs text-gray-600 text-center">Nenhum relatório gerado</p>
            </div>
        @endif
    </div>

    <div class="modal" x-bind:class="{'modal-open': $wire.modal}">
        <div class="modal-box max-w-xs">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">Gerar relatório</h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="$wire.modal = false">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div>
                <label class="label">
                    <span class="label-text">Formato do relatório</span>
                </label>

                <div class="flex justify-around">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text mr-3">PDF</span>
                            <input type="radio" name="radio-formato" class="radio radio-primary" value="pdf" wire:model="formato" />
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text mr-3">CSV</span>
                            <input type="radio" name="radio-formato" class="radio radio-primary" value="csv" wire:model="formato" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="modal-action">
                <button wire:click="gerarRelatorio" class="btn btn-sm btn-primary"
                    wire:loading.attr="disabled" wire:target="gerarRelatorio">
                    <div wire:loading wire:target="gerarRelatorio">
                        <x-components.loading class="loading-sm" />
                    </div>
                    <span>Gerar</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/views/obras/etapas-tabs-relatorios.js')
@endpush
