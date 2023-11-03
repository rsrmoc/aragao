<?php

namespace App\Livewire\Components\Views;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TablesUsers extends Component
{
    public $type;
    public $title;
    public $descriptionPage;

    public $modalAdd = false;

    public $userIdEdit = null;
    public $userName = null;
    public $userEmail = null;
    public $userPhoneNumber = null;
    public $userPassword = null;
    public $userEngineerAdmin = false;

    public function mount() {
        $this->title = match ($this->type) {
            'admin' => 'Usuários',
            'engineer' => 'Profissionais',
            'client' => 'Clientes'
        };

        $this->descriptionPage = match ($this->type) {
            'admin' => 'Aqui você cadastra outros administradores',
            'engineer' => 'Aqui você cadastra engnheiros que terão acesso ao app e as obras',
            'client' => 'Aqui você cadastra clientes que terão acesso ao app e as obras'
        };
    }

    public function storeUser() {
        $this->validate(
            rules: [
                'userName' => 'required|string',
                'userEmail' => ['required', 'string', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
                'userPassword' => 'required|string|min:8',
                'userPhoneNumber' => 'nullable|string|celular_com_ddd'
            ],
            attributes: [
                'userName' => 'nome',
                'userEmail' => 'email',
                'userPassword' => 'senha',
                'userPhoneNumber' => 'número'
            ]
        );

        try {
            DB::beginTransaction();

            $userTrashed = User::onlyTrashed()->where('email', $this->userEmail)->first();

            if ($userTrashed) {
                $userTrashed->update([
                    'name' => $this->userName,
                    'email' => $this->userEmail,
                    'phone_number' => $this->userPhoneNumber,
                    'password' => Hash::make($this->userPassword),
                    'type' => $this->type,
                    'engineer_admin' => $this->userEngineerAdmin
                ]);

                $userTrashed->restore();
            }
            else {
                User::create([
                    'name' => $this->userName,
                    'email' => $this->userEmail,
                    'phone_number' => $this->userPhoneNumber,
                    'password' => Hash::make($this->userPassword),
                    'type' => $this->type,
                    'engineer_admin' => $this->userEngineerAdmin
                ]);
            }

            DB::commit();

            $this->resetExcept('type', 'title', 'descriptionPage');
            $this->dispatch('toast-event', 'Criado com sucesso!', 'success');
        }
        catch(Exception $e) {
            DB::rollBack();

            $this->dispatch(
                'toast-event',
                'Não foi possivel criar o '.substr(strtolower($this->title), 0, strlen($this->title) - 1).'. '.$e->getMessage(),
                'error'
            );
        }
    }

    public function delUser(int $id) {
        try {
            User::find($id)->delete();
            $this->dispatch('toast-event', 'Excluído!', 'success');

        }
        catch (Exception $e) {
            $this->dispatch(
                'toast-event',
                'Não foi possivel excluir o '.substr(strtolower($this->title), 0, strlen($this->title) - 1).'. '.$e->getMessage(),
                'error'
            );
        }
    }

    public function updateUser() {
        $this->validate(
            rules: [
                'userName' => 'required|string',
                'userEmail' => [
                    'required', 'string', 'email',
                    Rule::unique('users', 'email')->ignore($this->userIdEdit)->whereNull('deleted_at')
                ],
                'userPassword' => 'nullable|string|min:8',
                'userPhoneNumber' => 'nullable|string|celular_com_ddd'
            ],
            attributes: [
                'userName' => 'nome',
                'userEmail' => 'email',
                'userPassword' => 'senha',
                'userPhoneNumber' => 'número'
            ]
        );

        try {
            DB::beginTransaction();

            $userTrashed = User::onlyTrashed()->where('email', $this->userEmail)->first();

            if ($userTrashed) $userTrashed->forceDelete();

            $data = [
                'name' => $this->userName,
                'email' => $this->userEmail,
                'phone_number' => $this->userPhoneNumber,
                'engineer_admin' => $this->userEngineerAdmin
            ];
            
            if ($this->userPassword) $data['password'] = Hash::make($this->userPassword);

            $user = User::find($this->userIdEdit);
            $user->update($data);

            DB::commit();

            $this->resetExcept('type', 'title', 'descriptionPage');
            $this->dispatch('toast-event', 'Atualizado com sucesso!', 'success');
        }
        catch(Exception $e) {
            DB::rollBack();

            $this->dispatch(
                'toast-event',
                'Não foi possivel editar o '.substr(strtolower($this->title), 0, strlen($this->title) - 1).'. '.$e->getMessage(),
                'error'
            );
        }
    }

    public function modalSubmit() {
        if ($this->userIdEdit) return $this->updateUser();

        $this->storeUser();
    }

    public function render()
    {
        return view('livewire.components.views.tables-users', [
            'users' => User::where('type', $this->type)->paginate(12)
        ]);
    }
}
