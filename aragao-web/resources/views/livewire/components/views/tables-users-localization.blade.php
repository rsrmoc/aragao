<div x-data="tablesUsersLocalization">
    <x-components.dashboard.navbar.navbar title="{{ $this->title }}">
    </x-components.dashboard.navbar.navbar>

    <p class="text-xs text-gray-600 mb-6">
        <i class="fa-solid fa-circle-info"></i>&ensp;{{ $descriptionPage }}
    </p>

    <table class="table table-xs table-zebra hidden sm:table w-full">
        <thead>
            <tr class="active">
                <th>#</th>
                <th>Data</th>
                <th>Endereço</th>
                <th>Localização</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($locais as $local)
                <tr wire:loading.class="active">
                    <td>{{ $local->id }}</td>
                    <td>{{ date_format(date_create($local->created_at), 'd/m/Y \à\s H:i') }}</td>
                    <td>{{ $local->endereco }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" x-on:click="showMap('{{ $local->latitude }}', '{{ $local->longitude }}', () => $wire)">
                            <i class="fa-solid fa-map"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="sm:hidden">
        @foreach ($locais as $local)
            <div class="py-2 border-b last:border-0" wire:loading.class="bg-zinc-200">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div wire:loading>
                            <x-components.loading class="loading-xs" />
                        </div>
                        <h3 class="font-bold text-lg">#{{ $local->id }}</h3>
                    </div>
                </div>

                <div class="text-sm">
                    <div>
                        <strong>Data:</strong>
                        <span>{{ date_format(date_create($local->created_at), 'd/m/Y \à\s H:i') }}</span>
                    </div>
                    <div>
                        <strong>Endereço:</strong>
                        <span>{{ $local->endereco }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="py-6">
        {{ $locais->links() }}
    </div>

    @empty(count($locais))
        <div class="p-5">
            <p class="text-sm text-center text-gray-600">{{ $this->title }} não cadastrados</p>
        </div>
    @endempty

    <div class="modal" x-bind:class="{ 'modal-open': $wire.modalAdd }">
        <form wire:submit.prevent="modalSubmit" class="modal-box" style="max-width: 50%;">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">
                    <span>Visualizar Localização</span>
                </h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="closeModal(() => $wire)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="mb-5">
                <iframe iframe scrolling="no" height="350" frameborder="0" id="map" 
                    marginheight="0" marginwidth="0" style="width: 100%;" src="">
                </iframe>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-sm btn-primary" x-on:click="closeModal(() => $wire)">
                    Fechar
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        @vite('resources/js/views/tables-users-localization.js')
    @endpush
    <script>
        var latitude = null;
        var longitude = null;
    </script>
</div>
