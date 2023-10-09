<?php

namespace App\Livewire\Components\Views\MinhaConta;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AlterarSenha extends Component
{
    public $currentPassword;
    public $newPassword;
    public $newPassword_confirmation;

    public function alterarSenha() {
        $this->validate(
            rules: [
                'currentPassword' => 'required|string|min:8|current_password',
                'newPassword' => 'required|string|min:8|confirmed',
            ],
            attributes: [
                'currentPassword' => 'senha atual',
                'newPassword' => 'nova senha'
            ]
        );

        try {
            Auth::user()->update(['password' => Hash::make($this->newPassword)]);
            
            $this->reset();
            $this->dispatch('toast-event', 'Senha atualizada!', 'success');
        }
        catch(Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel atualizar sua senha. '.$e->getMessage(), 'error');
        }
    }

    public function render()
    {
        return view('livewire.components.views.minha-conta.alterar-senha');
    }
}
