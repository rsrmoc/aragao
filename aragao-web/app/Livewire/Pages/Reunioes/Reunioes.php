<?php

namespace App\Livewire\Pages\Reunioes;

use App\Models\Obras;
use App\Models\ObrasUsuarios;
use App\Models\ReuniaoHistorico;
use App\Models\Reunioes as ModelsReunioes;
use App\Models\ReunioesUsuarios;
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
    public $participantes = [];

    public $reuniaoIdEdit = null;
    public $obrasUsuario = [];
    public $modal = false;
    public $modalInfo = false;

    public $modalConteudo = false;
    public $inputConteudoReuniao = null;

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

        if (count($this->participantes) == 0) return $this->dispatch('toast-event', 'Selecione no mínimo um participante.', 'error');

        try {
            DB::beginTransaction();

            $data = $this->inputs;
            $data['id_usuario_solicitante'] = Auth::user()->id;

            $reuniao = ModelsReunioes::create($data);
            ReuniaoHistorico::create([
                'id_reuniao' => $reuniao->id,
                'id_usuario' => Auth::user()->id,
            ]);

            ReunioesUsuarios::create([
                'id_obra' => $data['id_obra'],
                'id_reuniao' => $reuniao->id,
                'id_usuario' => Auth::user()->id
            ]);

            foreach ($this->participantes as $value) {
                ReunioesUsuarios::create([
                    'id_obra' => $data['id_obra'],
                    'id_reuniao' => $reuniao->id,
                    'id_usuario' => $value
                ]);
            }

            DB::commit();
            
            $this->resetExcept('obrasUsuario');
            $this->dispatch('reset-all');
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
            DB::beginTransaction();

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

            $adicionados = array_diff($this->participantes, array_column($reuniao->participantes->toArray(), 'id_usuario'));
            foreach ($adicionados as $idUsuario) {
                ReunioesUsuarios::create([
                    'id_obra' => $reuniao->id_obra,
                    'id_reuniao' => $reuniao->id,
                    'id_usuario' => $idUsuario
                ]);
            }

            $excluidos = array_diff(array_column($reuniao->participantes->toArray(), 'id_usuario'), $this->participantes);
            foreach ($excluidos as $idUsuario) {
                ReunioesUsuarios::firstWhere([
                    'id_obra' => $reuniao->id_obra,
                    'id_reuniao' => $reuniao->id,
                    'id_usuario' => $idUsuario
                ])->delete();
            }


            DB::commit();

            $this->resetExcept('obrasUsuario');
            $this->dispatch('reset-all');
            $this->dispatch('toast-event', 'Reunião atualizada!', 'success');
        }
        catch(Exception $e) {
            DB::rollBack();

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
        $this->dispatch('reset-all');
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
        $this->dispatch('reset-all');
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
        $this->dispatch('reset-all');
    }

    public function salvarConteudoReuniao() {
        $reuniao = ModelsReunioes::find($this->reuniaoIdEdit);
        $reuniao->update([
            'situacao' => 'conteudo_pendente',
            'conteudo' => $this->inputConteudoReuniao
        ]);

        ReuniaoHistorico::create([
            'id_reuniao' => $reuniao->id,
            'id_usuario' => Auth::user()->id,
            'situacao' => 'conteudo_pendente'
        ]);

        $this->resetExcept('obrasUsuario');
        $this->dispatch('reset-all');
    }

    public function confirmarConteudo($idReuniao) {
        $reuniao = ModelsReunioes::find($idReuniao);
        $reuniao->update(['situacao' => 'concluida']);

        ReuniaoHistorico::create([
            'id_reuniao' => $reuniao->id,
            'id_usuario' => Auth::user()->id,
            'situacao' => 'conteudo_confirmado'
        ]);

        $this->resetExcept('obrasUsuario');
        $this->dispatch('reset-all');
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $reunioes = Auth::user()->type == 'admin' || Auth::user()->engineer_admin
            ? ModelsReunioes::with('historico')->orderBy('created_at', 'desc')->paginate(12)
            : ModelsReunioes::with(['historico', 'participantes'])
                ->whereIn('id_obra', ReunioesUsuarios::where('id_usuario', Auth::user()->id)->get('id_obra'))
                ->orderBy('created_at', 'desc')
                ->paginate(12);

        $usuariosPorObra = ObrasUsuarios::with('usuario')->where('id_obra', $this->inputs['id_obra'])
            ->where('id_usuario', '<>', Auth::user()->id)
            ->get();

        return view('livewire.pages.reunioes.reunioes', compact('reunioes', 'usuariosPorObra'));
    }
}
