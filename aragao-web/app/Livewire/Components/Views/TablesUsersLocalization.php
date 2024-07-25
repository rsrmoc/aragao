<?php

namespace App\Livewire\Components\Views;

use App\Models\Rastreamentos;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class TablesUsersLocalization extends Component
{
    use WithPagination;

    public int $user;
    public $title;
    public $descriptionPage;
    public $filterDataHoraInicio;
    public $filterDataHoraFim;

    public $modalAdd = false;

    public function mount() {
        $this->title = 'Localização de Usuários';
        $this->descriptionPage = 'Aqui você pode visualizar o histórico de localização do usuário.';
    }

    public function filterLocalization(){

    }

    public function render()
    {
        $locais = Rastreamentos::where('id_usuario', $this->user)->orderBy('id', 'desc');

        if($this->filterDataHoraInicio){
            $locais->where('created_at', '>=', $this->filterDataHoraInicio);
        }

        if($this->filterDataHoraFim){
            $locais->where('created_at', '<=', $this->filterDataHoraFim);
        }

        $locais = $locais->paginate(12);

        return view('livewire.components.views.tables-users-localization', [
            'locais' => $locais
        ]);
    }
}
