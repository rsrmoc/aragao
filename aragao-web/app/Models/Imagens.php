<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Imagens extends Model
{
    use HasFactory;

    protected $fillable = [
        'tabela_type',
        'tabela_id',
        'tipo',
        'tamanho',
        'url'
    ];
    
    public function tabela(): MorphTo
    {
        return $this->morphTo();
    }
}
