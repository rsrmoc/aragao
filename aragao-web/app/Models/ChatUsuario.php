<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatUsuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_chat',
        'id_usuario'
    ];
}
