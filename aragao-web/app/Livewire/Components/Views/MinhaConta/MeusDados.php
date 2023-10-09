<?php

namespace App\Livewire\Components\Views\MinhaConta;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MeusDados extends Component
{
    public $user;

    public $name;
    public $phoneNumber;
    public $email;

    public function mount() {
        $this->user = Auth::user();

        $this->name = $this->user->name;
        $this->phoneNumber = $this->user->phone_number;
        $this->email = $this->user->email;
    }

    public function saveChanges() {
        $this->validate(
            rules: [
                'name' => 'required|string',
                'email' => [
                    'required', 'string', 'email',
                    Rule::unique('users', 'email')->ignore($this->user->id)->whereNull('deleted_at')
                ],
                'phoneNumber' => 'nullable|string|celular_com_ddd'
            ],
            attributes: [
                'name' => 'nome',
                'email' => 'email',
                'phoneNumber' => 'número'
            ]
        );

        try {
            DB::beginTransaction();

            $userTrashed = User::onlyTrashed()->where('email', $this->email)->first();

            if ($userTrashed) $userTrashed->forceDelete();

            if ($this->user->type == 'admin') {
                $this->user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone_number' => $this->phoneNumber
                ]);
            }
            else {
                $this->user->update([
                    'name' => $this->name,
                    'phone_number' => $this->phoneNumber
                ]);
            }

            DB::commit();

            $this->dispatch('toast-event', 'Alterações salvas!', 'success');
        }
        catch(Exception $e) {
            DB::rollBack();

            $this->dispatch(
                'toast-event',
                'Não foi possivel salvar as alterações. '.$e->getMessage(),
                'error'
            );
        }
    }

    public function render()
    {
        return view('livewire.components.views.minha-conta.meus-dados');
    }
}
