<form wire:submit.prevent="saveChanges" class="max-w-xl border-b pb-5 mb-5">
    <h3 class="font-bold mb-3">Meus dados</h3>

    <div class="flex gap-5 mb-3 flex-wrap sm:flex-nowrap">
        <x-components.input class="input-sm" label="Nome" placeholder="Nome" required
            wire:model="name" name="name" />
    
        <x-components.input class="input-sm" label="Número" placeholder="Número"
            wire:model="phoneNumber" name="phoneNumber" x-mask="(99) 99999-9999" />
    </div>

    @if ($user->type == 'admin')
        <div class="mb-5">
            <x-components.input class="input-sm" label="Email" placeholder="Email" required
                wire:model="email" name="email" />
        </div>
    @else    
        <x-components.input class="input-sm" label="Email" placeholder="Email" readonly disabled
            value="{{ $email }}" />
        <div class="mb-5 p-3">
            <p class="text-xs text-gray-600">
                <i class="fa-solid fa-circle-info"></i>&ensp;Solicite a um administrador a troca de email
            </p>
        </div>
    @endif

    <button type="submit" class="btn btn-xs btn-primary">Salvar alterações</button>
</form>