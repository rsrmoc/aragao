<?php

namespace App\Livewire\Pages\Obras;

use App\Models\Obras as ModelsObras;
use App\Models\ObrasUsuarios;
use App\Services\Helpers\MoneyService;
use App\Services\Helpers\StatesService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Obras extends Component
{
    public $inputsAdd = [
        'nome' => null,
        'dt_inicio' => null,
        'dt_termino' => null,
        'dt_previsao_termino' => null,
        'endereco_rua' => null,
        'endereco_bairro' => null,
        'endereco_numero' => null,
        'endereco_cidade' => null,
        'endereco_uf' => null,
        'endereco_cep' => null,
        'tipo_recurso' => null,
        'descricao_completa' => null
    ];
    public $obraIdEdit;

    public $states;
    public $modal = false;

    public function rules() {
        return [
            'inputsAdd.nome' => 'required|string',
            'inputsAdd.dt_inicio' => 'required|date_format:Y-m-d',
            'inputsAdd.dt_termino' => 'nullable|date_format:Y-m-d',
            'inputsAdd.dt_previsao_termino' => 'required|date_format:Y-m-d',
            'inputsAdd.endereco_rua' => 'required|string',
            'inputsAdd.endereco_bairro' => 'required|string',
            'inputsAdd.endereco_numero' => 'required|integer',
            'inputsAdd.endereco_cidade' => 'required|string',
            'inputsAdd.endereco_uf' => 'required|string|uf',
            'inputsAdd.endereco_cep' => 'required|string|formato_cep',
            'inputsAdd.tipo_recurso' => 'nullable|string|in:proprio,financiamento_caixa',
            'inputsAdd.descricao_completa' => 'nullable|string',
        ];
    }

    protected $validationAttributes = [
        'inputsAdd.nome' => 'nome',
        'inputsAdd.dt_inicio' => 'data de inicio',
        'inputsAdd.dt_termino' => 'data de termino',
        'inputsAdd.dt_previsao_termino' => 'data previsão de termino',
        'inputsAdd.endereco_rua' => 'rua',
        'inputsAdd.endereco_bairro' => 'bairro',
        'inputsAdd.endereco_numero' => 'numero',
        'inputsAdd.endereco_cidade' => 'cidade',
        'inputsAdd.endereco_uf' => 'estado',
        'inputsAdd.endereco_cep' => 'cep',
        'inputsAdd.tipo_recurso' => 'tipo de recurso',
        'inputsAdd.descricao_completa' => 'descrição completa da obra',
    ];

    public function __construct() {
        $this->states = StatesService::$states;
    }

    public function salvarObra() {
        if ($this->obraIdEdit) return $this->editObra();

        $this->addObra();
    }

    public function addObra() {
        $this->validate();

        try {
            $data = $this->inputsAdd;
            $data['id_usuario'] = Auth::user()->id;

            ModelsObras::create($data);

            $this->reset();
            $this->dispatch('toast-event', 'Obra cadastrada!', 'success');
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel cadastrar a obra. '.$e->getMessage(), 'error');
        }
    }

    public function editObra() {
        $this->validate();

        try {
            $data = $this->inputsAdd;

            $obra = ModelsObras::find($this->obraIdEdit);
            $obra->update($data);

            $this->reset();
            $this->dispatch('toast-event', 'Obra atualizada!', 'success');
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel atualizar a obra. '.$e->getMessage(), 'error');
        }
    }

    public function delObra($obraId) {
        try {
            ModelsObras::find($obraId)->delete();
            $this->dispatch('toast-event', 'Obra excluida!', 'success');
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel excluir a obra. '.$e->getMessage(), 'error');
        }
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        if (Auth::user()->type !== 'admin') {
            $idsObrasUsuario = ObrasUsuarios::where('id_usuario', Auth::user()->id)->get('id_obra');
            $obras = ModelsObras::whereIn('id', $idsObrasUsuario)->paginate(12);
        }
        else {
            $obras = ModelsObras::orderBy('created_at', 'desc')->paginate(12);
        }

        return view('livewire.pages.obras.obras', compact('obras'));
    }
}
