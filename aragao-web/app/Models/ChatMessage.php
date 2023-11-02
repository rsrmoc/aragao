<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_chat',
        'id_usuario',
        'mensagem'
    ];

    public function usuario() {
        return $this->hasOne(User::class, 'id', 'id_usuario');
    }

    public function visualizada() {
        return $this->hasOne(ChatMessageView::class, 'id_message')->where('id_usuario', Auth::user()->id);
    }

    public function imagem(): MorphOne {
        return $this->morphOne(Imagens::class, 'tabela');
    }
}
