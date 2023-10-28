<?php

namespace App\Livewire\Pages\Chat;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Chat extends Component
{
    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        return view('livewire.pages.chat.chat');
    }
}
