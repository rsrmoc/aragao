<?php

namespace App\Livewire\Components\Views\Obras;

use App\Models\Funcionario;
use App\Models\ObrasFuncionario;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class EtapasTabFuncionarios extends Component
{
    public $obra;
    public $funcionarioEncontrado = null;
    public $inputsDisabled = true;
    public $modal = false;

    public $inputsFuncionario = [
        'nome' => null,
        'cpf' => null,
        'rg' => null,
        'telefone' => null
    ];

    public $inputsFuncionarioObra = [
        'funcao' => null,
        'conselho' => null
    ];

    public function pesquisaFuncionarioInputs() {
        $this->validate(
            rules: [
                'inputsFuncionario.cpf' => 'required|string|formato_cpf|cpf'
            ],
            attributes: [
                'inputsFuncionario.cpf' => 'cpf'
            ]
        );

        try {
            $funcionario = Funcionario::firstWhere('cpf', $this->inputsFuncionario['cpf']);

            if (!$funcionario) {
                $this->inputsDisabled = false;
                $this->funcionarioEncontrado = null;
                return;
            }

            $this->funcionarioEncontrado = $funcionario;
            $this->inputsFuncionario['nome'] = $funcionario->nome;
            $this->inputsFuncionario['rg'] = $funcionario->rg;
            $this->inputsFuncionario['telefone'] = $funcionario->telefone;
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Houve um erro na busca. '.$e->getMessage());
        }
    }

    public function saveFuncionario() {
        $this->validate(
            rules: [
                'inputsFuncionario.nome' => 'required|string',
                'inputsFuncionario.cpf' => 'required|string|formato_cpf|cpf',
                'inputsFuncionario.rg' => 'required|numeric',
                'inputsFuncionario.telefone' => 'required|string|celular_com_ddd',
                'inputsFuncionarioObra.funcao' => 'required|string',
                'inputsFuncionarioObra.conselho' => 'required|string'
            ],
            attributes: [
                'inputsFuncionario.nome' => 'nome',
                'inputsFuncionario.cpf' => 'cpf',
                'inputsFuncionario.rg' => 'rg',
                'inputsFuncionario.telefone' => 'telefone',
                'inputsFuncionarioObra.funcao' => 'função',
                'inputsFuncionarioObra.conselho' => 'conselho'
            ]
        );

        if (ObrasFuncionario::firstWhere(['id_obra' => $this->obra, 'id_funcionario' => $this->funcionarioEncontrado?->id])) {
            return $this->addError('inputsFuncionario.cpf', 'Um funcionário com esse cpf já foi cadastrado!');
        }

        try {
            if (!$this->funcionarioEncontrado) {
                $this->funcionarioEncontrado = Funcionario::create($this->inputsFuncionario);
            }

            ObrasFuncionario::create(array_merge($this->inputsFuncionarioObra, [
                'id_obra' => $this->obra,
                'id_funcionario' => $this->funcionarioEncontrado->id
            ]));

            $this->resetExcept('obra');
            $this->dispatch('toast-event', 'Funcionário cadastrado!', 'success');
        }
        catch(Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel cadastrar o funcionário. '.$e->getMessage(), 'error');
        }
    }

    public function delFuncionario($idObraFuncionario) {
        try {
            ObrasFuncionario::find($idObraFuncionario)->delete();
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel excluir o funcionário. '.$e->getMessage(), 'error');
        }
    }

    public function render()
    {
        $obrasFuncionarios = ObrasFuncionario::with('funcionario')->where('id_obra', $this->obra)->get();

        return view('livewire.components.views.obras.etapas-tab-funcionarios', compact('obrasFuncionarios'));
    }
}
