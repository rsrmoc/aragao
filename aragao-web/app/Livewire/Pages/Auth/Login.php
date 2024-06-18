<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Login extends Component
{
    #[Rule('required|string|email|exists:users,email')]
    public $email;
    #[Rule('required|string|min:8')]
    public $password;

    public function login() {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], true)) {
            if (Auth::user()->engineer_admin) {
                return redirect()->route('dashboard.home', ['userId' => Auth::id()]);
            }
            return redirect()->route('dashboard.home');
        }

        $this->addError('password', 'Senha inválida!');
    }

    public function render()
    {
        return view('livewire.pages.auth.login');
    }
}
