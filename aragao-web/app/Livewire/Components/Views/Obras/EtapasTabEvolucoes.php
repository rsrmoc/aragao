<?php

namespace App\Livewire\Components\Views\Obras;

use App\Models\Imagens;
use App\Models\ObrasEtapas;
use App\Models\ObrasEvolucoes;
use App\Services\Helpers\ImagensService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class EtapasTabEvolucoes extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $obra;
    public $modal = false;
    public $editId = null;

    public $inputs = [
        'id_etapa' => null,
        'dt_evolucao' => null,
        'descricao' => null
    ];

    public $inputsImages = [];

    protected $rules = [
        'inputs.id_etapa' => 'required|integer|exists:obras_etapas,id',
        'inputs.dt_evolucao' => 'required|string|date_format:Y-m-d',
        'inputs.descricao' => 'required|string',
        'inputsImages' => 'nullable|array',
        'inputsImages.*' => 'image|max:51200'
    ];

    protected $validationAttributes = [
        'inputs.id_etapa' => 'etapa',
        'inputs.dt_evolucao' => 'data da evolução',
        'inputs.descricao' => 'descrição',
        'inputsImages' => 'imagem',
        'inputsImages.*' => 'imagem #:position',
    ];

    function saveEvolucao() {
        if ($this->editId) return $this->editEvolucao();

        $this->addEvolucao();
    }

    function addEvolucao() {
        $this->validate();

        try {
            DB::beginTransaction();
            
            $data = $this->inputs;
            $data['id_obra'] = $this->obra;
            $data['id_usuario'] = Auth::user()->id;

            $evolucao = ObrasEvolucoes::create($data);

            if (count($this->inputsImages) >= 1) {
                foreach ($this->inputsImages as $image) {
                    $dados = [
                        'tabela_id' => $evolucao->id,
                        'tabela_type' => 'App\Models\ObrasEvolucoes',
                        'tipo' => $image->extension(),
                        'tamanho' => $image->getSize(),
                        'imagem' => base64_encode(file_get_contents($image->getRealPath()))
                    ];
                    ImagensService::upload($dados);
                }
            }

            DB::commit();

            $this->resetExcept('obra');
            $this->dispatch('clear-file-input');
            $this->dispatch('toast-event', 'Evolução adicionada!', 'success');
        }
        catch(Exception $e) {
            DB::rollBack();

            $this->dispatch('toast-event', $e->getMessage(), 'error');
        }
    }

    function editEvolucao() {
        $this->validate();

        try {
            DB::beginTransaction();

            $data = $this->inputs;

            $evolucao = ObrasEvolucoes::find($this->editId);
            $evolucao->update($data);

            if (count($this->inputsImages) >= 1) {
                foreach ($this->inputsImages as $image) {
                    $dados = [
                        'tabela_id' => $evolucao->id,
                        'tabela_type' => 'App\Models\ObrasEvolucoes',
                        'tipo' => $image->extension(),
                        'tamanho' => $image->getSize(),
                        'imagem' => base64_encode(file_get_contents($image->getRealPath()))
                    ];

                    ImagensService::upload($dados);
                }
            }

            DB::commit();

            $this->resetExcept('obra');
            $this->dispatch('clear-file-input');
            $this->dispatch('toast-event', 'Evolução atualizada!', 'success');
        }
        catch(Exception $e) {
            DB::rollBack();

            $this->dispatch('toast-event', $e->getMessage(), 'error');
        }
    }

    public function excluirEvolucao(int $idEvolucao) {
        try {
            ObrasEvolucoes::find($idEvolucao)->delete();

            $this->dispatch('toast-event', 'Evolução excluida!', 'success');
        }
        catch(Exception $e) {
            $this->dispatch('toast-event', $e->getMessage(), 'error');
        }
    }

    public function excluirImagem(int $idImagem) {
        try {
            $imagem = Imagens::find($idImagem);
            $caminhoImagem = str_replace('storage', 'public', $imagem->url);
            Storage::delete($caminhoImagem);
            $imagem->delete();
            $this->resetExcept('obra');

            $this->dispatch('toast-event', 'Imagem excluida!', 'success');
        }
        catch(Exception $e) {
            $this->dispatch('toast-event', $e->getMessage(), 'error');
        }
    }

    public function render()
    {
        $etapas = ObrasEtapas::where('id_obra', $this->obra)->orderBy('created_at', 'desc')->get();
        $evolucoes = ObrasEvolucoes::with(['imagens', 'etapa', 'usuario'])->where('id_obra', $this->obra)->orderBy('created_at', 'desc')->paginate(12);

        return view('livewire.components.views.obras.etapas-tab-evolucoes', compact('etapas', 'evolucoes'));
    }
}
