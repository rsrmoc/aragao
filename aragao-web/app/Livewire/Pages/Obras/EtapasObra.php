<?php

namespace App\Livewire\Pages\Obras;

use App\Models\Obras;
use App\Models\ObrasEtapas;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EtapasObra extends Component
{
    public Obras $obra;

    public $modal = false;
    public $etapaIdEdit = null;
    public $inputsEtapa = [
        'nome' => null,
        'porc_etapa' => 0,
        'porc_geral' => 0,
        'concluida' => false
    ];

    protected function rules() {
        return [
            'inputsEtapa.nome' => 'required|string',
            'inputsEtapa.porc_etapa' => 'required|integer|min:0|max:100',
            'inputsEtapa.porc_geral' => 'required|integer|min:0|max:100',
            'inputsEtapa.concluida' => 'required|boolean'
        ];
    }

    protected $validationAttributes = [
        'inputsEtapa.nome' => 'nome',
        'inputsEtapa.porc_etapa' => 'porcentagem da etapa',
        'inputsEtapa.porc_geral' => 'porcentagem geral',
        'inputsEtapa.concluida' => 'concluida'
    ];

    public function salvarEtapa() {
        if ($this->etapaIdEdit) return $this->editEtapa();

        $this->addEtapa();
    }

    public function editEtapa() {
        $this->validate();

        try {
            $etapa = ObrasEtapas::find($this->etapaIdEdit);
            $etapa->update($this->inputsEtapa);

            $this->resetExcept('obra');
            $this->dispatch('toast-event', 'Etapa atualizada!', 'success');
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel salvar as alterações. '.$e->getMessage(), 'error');
        }
    }

    public function addEtapa() {
        $this->validate();

        try {
            $data = $this->inputsEtapa;
            $data['id_obra'] = $this->obra->id;
            $data['id_usuario'] = Auth::user()->id;
            ObrasEtapas::create($data);

            $this->resetExcept('obra');
            $this->dispatch('toast-event', 'Etapa criada!', 'success');
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel criar a etapa. '.$e->getMessage(), 'error');
        }
    }

    public function delEtapa($etapaId) {
        try {
            ObrasEtapas::find($etapaId)->delete();

            $this->dispatch('toast-event', 'Etapa excluída!', 'success');
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel excluir a etapa. '.$e->getMessage(), 'error');
        }
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $etapas = ObrasEtapas::where('id_obra', $this->obra->id)->orderBy('created_at', 'desc')->get();
        $porcGeral = ObrasEtapas::where('id_obra', $this->obra->id)->sum('porc_geral');

        return view('livewire.pages.obras.etapas-obra', compact('etapas', 'porcGeral'));
    }
}
