<?php

namespace App\Livewire\Components\Views\Obras;

use App\Models\Chat;
use App\Models\ChatUsuario;
use App\Models\ObrasUsuarios;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EtapasTabUsuarios extends Component
{
    public string $type;
    public int $obra;

    public $funcoes = [
        'responsavel' => 'Responsável',
        'responsavel_tecnico' => 'Responsável técnico',
        'arquiteto' => 'Arquiteto'
    ];

    public $modal = false;

    public $user = null;
    public $funcao = "";

    public function storeUser() {
        try {
            DB::beginTransaction();

            ObrasUsuarios::create([
                'id_obra' => $this->obra,
                'id_usuario' => $this->user,
                'tipo' => $this->funcao
            ]);

            ChatUsuario::create([
                'id_chat' => Chat::firstWhere('id_obra', $this->obra)->id,
                'id_usuario' => $this->user,
            ]);

            DB::commit();
    
            $this->reset('modal', 'user', 'funcao');
            $this->dispatch('toast-event', 'Adicionado com sucesso!', 'success');
        }
        catch (Exception $e) {
            DB::rollBack();

            $this->dispatch('toast-event', 'Não foi possivl adicionar. '.$e->getMessage(), 'error');
        }
    }

    public function delUser($obraUsuarioId) {
        try {
            $obraUsuario = ObrasUsuarios::find($obraUsuarioId);

            ChatUsuario::firstWhere([
                'id_chat' => Chat::firstWhere('id_obra', $this->obra)->id,
                'id_usuario' => $obraUsuario->id_usuario,
            ])->delete();

            $obraUsuario->delete();

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
        $usuarios = User::where('type', $this->type)->whereNotIn('id', array_column($usuariosAtribuidos->toArray(), 'id_usuario'))->get();

        return view('livewire.components.views.obras.etapas-tab-usuarios', compact('usuarios', 'usuariosAtribuidos'));
    }
}
