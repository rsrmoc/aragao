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
            </tr>
        </thead>

        <tbody>
            @foreach ($locais as $local)
                <tr wire:loading.class="active">
                    <td>{{ $local->id }}</td>
                    <td>{{ date_format(date_create($local->created_at), 'd/m/Y \à\s H:i') }}</td>
                    <td>{{ $local->endereco }}</td>
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

    @push('scripts')
        @vite('resources/js/views/tables-users-localization.js')
    @endpush
</div>
