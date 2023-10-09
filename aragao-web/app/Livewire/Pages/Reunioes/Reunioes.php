<?php

namespace App\Livewire\Pages\Reunioes;

use App\Models\Obras;
use App\Models\ObrasUsuarios;
use App\Models\ReuniaoHistorico;
use App\Models\Reunioes as ModelsReunioes;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Reunioes extends Component
{
    public $inputs = [
        'id_obra' => null,
        'assunto' => null,
        'dt_reuniao' => null,
        'descricao' => null
    ];

    public $reuniaoIdEdit = null;
    public $obrasUsuario = [];
    public $modal = false;
    public $modalInfo = false;

    public function rules() {
        return [
            'inputs.id_obra' => 'required|integer|exists:obras,id',
            'inputs.assunto' => 'required|string',
            'inputs.dt_reuniao' => 'required|string',
            'inputs.descricao' => 'nullable|string',
        ];
    }

    public function mount() {
        $obrasUsuairo = ObrasUsuarios::where('id_usuario', Auth::user()->id)->get('id_obra');
        $this->obrasUsuario = Auth::user()->type == 'admin' ? Obras::all(): Obras::whereIn('id', $obrasUsuairo)->get();
    }

    public function saveReuniao() {
        if ($this->reuniaoIdEdit) return $this->editReuniao();

        $this->addReuniao();
    }

    public function addReuniao() {
        $this->validate();

        try {
            DB::beginTransaction();

            $data = $this->inputs;
            $data['id_usuario_solicitante'] = Auth::user()->id;

            $reuniao = ModelsReunioes::create($data);
            ReuniaoHistorico::create([
                'id_reuniao' => $reuniao->id,
                'id_usuario' => Auth::user()->id,
            ]);

            DB::commit();
            
            $this->resetExcept('obrasUsuario');
            $this->dispatch('toast-event', 'Reunião agendada!', 'success');
        }
        catch(Exception $e) {
            DB::rollBack();

            $this->dispatch('toast-event', 'Não foi possivel agendar a reunião. '.$e->getMessage(), 'error');
        }
    }

    public function editReuniao() {
        $this->validate();

        try {
            $reuniao = ModelsReunioes::find($this->reuniaoIdEdit);
            $reuniao->update($this->inputs);

            if (key_exists('dt_reuniao', $reuniao->getChanges()) && in_array($reuniao->situacao, ['agendada', 'confirmada', 'adiada', 'negada']))
            {
                $reuniao->update(['situacao' => 'adiada']);
                ReuniaoHistorico::create([
                    'id_reuniao' => $reuniao->id,
                    'id_usuario' => Auth::user()->id,
                    'situacao' => 'adiada'
                ]);
            }

            $this->resetExcept('obrasUsuario');
            $this->dispatch('toast-event', 'Reunião atualizada!', 'success');
        }
        catch(Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel atualizar a reunião. '.$e->getMessage(), 'error');
        }
    }

    public function excluirReuniao($idReuniao) {
        try {
            ModelsReunioes::find($idReuniao)->delete();

            $this->dispatch('toast-event', 'Reunião excluída!', 'success');
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel excluir a reunião. '.$e->getMessage(), 'error');
        }
    }

    public function reuniaoSituacao(string $situacao) {
        $reuniao = ModelsReunioes::find($this->reuniaoIdEdit);
        $reuniao->update(['situacao' => $situacao]);

        ReuniaoHistorico::create([
            'id_reuniao' => $reuniao->id,
            'id_usuario' => Auth::user()->id,
            'situacao' => $situacao
        ]);

        $this->resetExcept('obrasUsuario');
    }

    public function confirmarReuniao($idReuniao) {
        $reuniao = ModelsReunioes::find($idReuniao);
        $reuniao->update([
            'id_usuario_confirmacao' => Auth::user()->id,
            'situacao' => 'confirmada'
        ]);

        ReuniaoHistorico::create([
            'id_reuniao' => $reuniao->id,
            'id_usuario' => Auth::user()->id,
            'situacao' => 'confirmada'
        ]);

        $this->resetExcept('obrasUsuario');
    }

    public function negarReuniao($idReuniao) {
        $reuniao = ModelsReunioes::find($idReuniao);
        $reuniao->update(['situacao' => 'negada']);

        ReuniaoHistorico::create([
            'id_reuniao' => $reuniao->id,
            'id_usuario' => Auth::user()->id,
            'situacao' => 'negada'
        ]);

        $this->resetExcept('obrasUsuario');
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $reunioes = Auth::user()->type == 'admin'
            ? ModelsReunioes::with('historico')->orderBy('created_at', 'desc')->paginate(12)
            : ModelsReunioes::with('historico')->whereIn('id_obra', ObrasUsuarios::where('id_usuario', Auth::user()->id)->get('id_obra'))
                ->orderBy('created_at', 'desc')->paginate(12);

        return view('livewire.pages.reunioes.reunioes', compact('reunioes'));
    }
}
