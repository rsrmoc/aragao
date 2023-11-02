<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_obra',
        'nome',
        'tipo'
    ];

    public function lastMessage() {
        return $this->hasOne(ChatMessage::class, 'id_chat')->orderBy('created_at', 'desc')->with('usuario', 'imagem');
    }

    public function unviewedMessages() {
        return $this->hasMany(ChatMessage::class, 'id_chat')->where('id_usuario', '<>', Auth::user()->id)->doesntHave('visualizada');
    }
}
