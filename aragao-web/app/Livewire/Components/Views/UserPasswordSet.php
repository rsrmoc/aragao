<?php

namespace App\Livewire\Components\Views;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserPasswordSet extends Component
{
    public $password = null;
    public $new_password = null;
    public $new_password_confirmation = null;

    public function passwordSet() {
        $this->validate(
            rules: [
                'password' => 'required|string|min:8|current_password',
                'new_password' => 'required|string|min:8|confirmed',
            ],
            attributes: [
                'password' => 'senha atual',
                'new_password' => 'nova senha',
            ]
        );

        try {
            User::find(Auth::user()->id)
                ->update([
                    'password' => Hash::make($this->new_password),
                    'password_user_set' => true
                ]);

            return redirect()->route('dashboard.home');
        }
        catch(Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel alterar a senha! '.$e->getMessage(), 'error');
        }
    }

    public function render()
    {
        return view('livewire.components.views.user-password-set');
    }
}
