<?php

namespace App\Livewire\Pages\Obras;

use App\Models\Chat;
use App\Models\Obras as ModelsObras;
use App\Models\ObrasUsuarios;
use App\Services\Helpers\MoneyService;
use App\Services\Helpers\StatesService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Obras extends Component
{
    use WithPagination;

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
        'descricao_completa' => null,
        'valor' => null
    ];
    public $obraIdEdit;

    public $states;
    public $modal = false;

    public $filter;
    public $filterNome;

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
            'inputsAdd.valor' => 'required|currency'
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
        'inputsAdd.valor' => 'valor'
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
            DB::beginTransaction();

            $data = $this->inputsAdd;
            $data['id_usuario'] = Auth::user()->id;
            $data['valor'] = MoneyService::formatToDB($data['valor']);

            $obra = ModelsObras::create($data);

            Chat::create([
                'id_obra' => $obra->id,
                'nome' => $obra->nome,
                'tipo' => 'group'
            ]);

            DB::commit();

            $this->reset();
            $this->dispatch('toast-event', 'Obra cadastrada!', 'success');
        }
        catch (Exception $e) {
            DB::rollBack();

            $this->dispatch('toast-event', 'Não foi possivel cadastrar a obra. '.$e->getMessage(), 'error');
        }
    }

    public function editObra() {
        $this->validate();

        try {
            $data = $this->inputsAdd;
            $data['valor'] = MoneyService::formatToDB($data['valor']);

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

    public function filterObras() {
        $this->resetPage();
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        if (Auth::user()->type !== 'admin' && !Auth::user()->engineer_admin) {
            $idsObrasUsuario = ObrasUsuarios::where('id_usuario', Auth::user()->id)->get('id_obra');
            $obras = ModelsObras::whereIn('id', $idsObrasUsuario);
        }
        else {
            $obras = ModelsObras::orderBy('created_at', 'desc');
        }

        if($this->filterNome){
            $obras = $obras->where('nome', 'like', '%'.$this->filterNome.'%');
        }

        $obras = $obras->paginate(12);

        return view('livewire.pages.obras.obras', compact('obras'));
    }
}
