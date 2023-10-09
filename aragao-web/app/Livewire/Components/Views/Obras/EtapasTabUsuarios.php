<?php

namespace App\Livewire\Components\Views\Obras;

use App\Models\ObrasUsuarios;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class EtapasTabUsuarios extends Component
{
    public string $type;
    public int $obra;

    public $funcoes = [
        'responsavel' => 'Responsável',
        'responsavel_tecnico' => 'Responsável técnico'
    ];

    public $modal = false;

    public $user = null;
    public $funcao = "";

    public function storeUser() {
        ObrasUsuarios::create([
            'id_obra' => $this->obra,
            'id_usuario' => $this->user,
            'tipo' => $this->funcao
        ]);

        $this->reset('modal', 'user', 'funcao');
        $this->dispatch('toast-event', 'Adicionado com sucesso!', 'success');
    }

    public function delUser($obraUsuarioId) {
        try {
            ObrasUsuarios::find($obraUsuarioId)->delete();

            $this->dispatch('toast-event', 'Excluido!', 'success');
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivl excluir. '.$e->getMessage(), 'error');
        }
    }

    public function render()
    {
        $usuariosAtribuidos = ObrasUsuarios::whereHas('usuario', fn(Builder $query) => $query->where('type', $this->type))
            ->where('id_obra', $this->obra)->get();
        $usuarios = User::where('type', $this->type)->get();

        return view('livewire.components.views.obras.etapas-tab-usuarios', compact('usuarios', 'usuariosAtribuidos'));
    }
}
