<?php

namespace App\Livewire\Pages\Obras;

use App\Models\Obras;
use App\Models\ObrasEtapas;
use App\Services\Helpers\MoneyService;
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
        'dt_inicio' => null,
        'dt_previsao' => null,
        'dt_termino' => null,
        'dt_vencimento' => null,
        'quitada' => false,
        'descricao_completa' => null,
        'status' => 'iniciar'
    ];

    protected function rules() {
        return [
            'inputsEtapa.nome' => 'required|string',
            'inputsEtapa.porc_etapa' => 'required|integer|min:0|max:100',
            'inputsEtapa.porc_geral' => 'required|currency|min:0|max:100',
            'inputsEtapa.dt_inicio' => 'required|date_format:Y-m-d',
            'inputsEtapa.dt_previsao' => 'required|date_format:Y-m-d',
            'inputsEtapa.dt_termino' => 'nullable|date_format:Y-m-d',
            'inputsEtapa.dt_vencimento' => 'required|date_format:Y-m-d',
            'inputsEtapa.quitada' => 'required|boolean',
            'inputsEtapa.descricao_completa' => 'nullable|string',
            'inputsEtapa.status' => 'required|string'
        ];
    }

    protected $validationAttributes = [
        'inputsEtapa.nome' => 'nome',
        'inputsEtapa.porc_etapa' => 'porcentagem da etapa',
        'inputsEtapa.porc_geral' => 'porcentagem geral',
        'inputsEtapa.dt_inicio' => 'data de inicio',
        'inputsEtapa.dt_previsao' => 'data de previsão',
        'inputsEtapa.dt_termino' => 'data de termino',
        'inputsEtapa.dt_vencimento' => 'data de vencimento',
        'inputsEtapa.quitada' => 'etapa quitada',
        'inputsEtapa.descricao_completa' => 'descrição completa da obra',
        'inputsEtapa.status' => 'status'
    ];

    public function salvarEtapa() {
        if ($this->etapaIdEdit) return $this->editEtapa();

        $this->addEtapa();
    }

    public function editEtapa() {
        $this->validate();

        try {
            $data = $this->inputsEtapa;

            if ($data['status'] == 'concluida') {
                $data['porc_etapa'] = 100;
                $data['concluida'] = true;
            }

            $etapa = ObrasEtapas::find($this->etapaIdEdit);
            $etapa->update($data);

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
        $porcGeral = ObrasEtapas::where('id_obra', $this->obra->id)->get()->sum('insidencia_executada');

        $sumIncidencia = ObrasEtapas::where('id_obra', $this->obra->id)->get()->sum('porc_geral');
        $sumValorEtap = ObrasEtapas::where('id_obra', $this->obra->id)->get()->sum('valor_etapa');
        $sumValorGasto = ObrasEtapas::where('id_obra', $this->obra->id)->get()->sum('valor_gasto');

        return view('livewire.pages.obras.etapas-obra', compact('etapas', 'porcGeral', 'sumIncidencia', 'sumValorEtap', 'sumValorGasto'));
    }
}
