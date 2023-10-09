<form wire:submit.prevent="alterarSenha" class="max-w-xl">
    <h3 class="font-bold mb-3">Alterar senha</h3>

    <div class="flex flex-wrap gap-3 max-w-xs mb-5">
        <x-components.input type="password" label="Senha atual" placeholder="Senha atual" class="input-sm" required
            wire:model="currentPassword" name="currentPassword" />

        <x-components.input type="password" label="Nova senha" placeholder="Nova senha" class="input-sm" required
            wire:model="newPassword" name="newPassword" />

        <x-components.input type="password" label="Confirme a nova senha" placeholder="Confirme a nova senha" class="input-sm" required
            wire:model="newPassword_confirmation" name="newPassword_confirmation" />
    </div>

    <button type="submit" class="btn btn-xs btn-primary">Salvar senha</button>
</form>