<?php

namespace App\Livewire\Components\Views\Obras;

use App\Models\Imagens;
use App\Models\ObrasProjetos;
use App\Services\Helpers\ImagensService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class EtapasTabProjetos extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $obra;
    public $modal = false;
    public $editId = null;

    public $inputs = [
        'titulo' => null,
        'descricao' => null
    ];

    public $inputsImages = [];

    protected $rules = [
        'inputs.titulo' => 'required|string',
        'inputs.descricao' => 'required|string',
        'inputsImages' => 'nullable|array',
        'inputsImages.*' => 'mimes:jpg,png,webp,jpeg,pdf|max:51200'
    ];

    protected $validationAttributes = [
        'inputs.titulo' => 'titulo',
        'inputs.descricao' => 'descrição',
        'inputsImages' => 'imagem',
        'inputsImages.*' => 'imagem #:position',
    ];

    function saveProjeto() {
        if ($this->editId) return $this->editProjeto();

        $this->addProjeto();
    }

    function addProjeto() {
        $this->validate();

        try {
            DB::beginTransaction();
            
            $data = $this->inputs;
            $data['id_obra'] = $this->obra;
            $data['id_usuario'] = Auth::user()->id;

            $projeto = ObrasProjetos::create($data);

            if (count($this->inputsImages) >= 1) {
                foreach ($this->inputsImages as $image) {
                    $dados = [
                        'tabela_id' => $projeto->id,
                        'tabela_type' => 'App\Models\ObrasProjetos',
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
            $this->dispatch('toast-event', 'Projeto adicionado!', 'success');
        }
        catch(Exception $e) {
            DB::rollBack();

            $this->dispatch('toast-event', $e->getMessage(), 'error');
        }
    }

    function editProjeto() {
        $this->validate();

        try {
            DB::beginTransaction();

            $data = $this->inputs;

            $projeto = ObrasProjetos::find($this->editId);
            $projeto->update($data);

            if (count($this->inputsImages) >= 1) {
                foreach ($this->inputsImages as $image) {
                    $dados = [
                        'tabela_id' => $projeto->id,
                        'tabela_type' => 'App\Models\ObrasProjetos',
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
            $this->dispatch('toast-event', 'Projeto atualizado!', 'success');
        }
        catch(Exception $e) {
            DB::rollBack();

            $this->dispatch('toast-event', $e->getMessage(), 'error');
        }
    }

    public function excluirProjeto(int $idProjeto) {
        try {
            ObrasProjetos::find($idProjeto)->delete();

            $this->dispatch('toast-event', 'Projeto excluido!', 'success');
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

            $this->dispatch('toast-event', 'Arquivo excluido!', 'success');
        }
        catch(Exception $e) {
            $this->dispatch('toast-event', $e->getMessage(), 'error');
        }
    }

    public function render()
    {
        $projetos = ObrasProjetos::with(['imagens', 'usuario'])->where('id_obra', $this->obra)->orderBy('created_at', 'desc')->paginate(12);

        return view('livewire.components.views.obras.etapas-tab-projetos', compact('projetos'));
    }
}
