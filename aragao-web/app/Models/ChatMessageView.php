<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_message',
        'id_usuario'
    ];
}
