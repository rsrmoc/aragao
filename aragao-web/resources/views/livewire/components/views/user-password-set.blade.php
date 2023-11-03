<div>
    <h3 class="font-bold mb-3">Altere sua senha</h3>
    <form wire:submit.prevent="passwordSet" class="max-w-xs">
        <div class="mb-2">
            <x-components.input type="password" placeholder="Senha atual" label="Senha atual" required class="input-sm"
                name="password" wire:model="password" />
        </div>

        <div class="mb-2">
            <x-components.input type="password" placeholder="Nova senha" label="Nova senha" required class="input-sm"
                name="new_password" wire:model="new_password" />
        </div>

        <div class="mb-5">
            <x-components.input type="password" placeholder="Confirme a nova senha" label="Confirme a nova senha"
                required class="input-sm" name="new_password_confirmation" wire:model="new_password_confirmation" />
        </div>

        <button type="submit" class="btn btn-sm btn-primary">
            <div wire:loading wire:target="passwordSet">
                <x-components.loading class="loading-sm" />
            </div>
            <span>Salvar senha</span>
        </button>
    </form>
</div>
