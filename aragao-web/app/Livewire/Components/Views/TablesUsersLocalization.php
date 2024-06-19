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

    public $modalAdd = false;

    public function mount() {
        $this->title = 'Localização de Usuários';
        $this->descriptionPage = 'Aqui você pode visualizar o histórico de localização do usuário.';
    }

    public function render()
    {
        return view('livewire.components.views.tables-users-localization', [
            'locais' => Rastreamentos::where('id_usuario', $this->user)->orderBy('id', 'desc')->paginate(12)
        ]);
    }
}
