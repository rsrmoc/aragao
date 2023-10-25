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
        'imagem'
    ];

    protected $appends = [
        'url'
    ];
    
    public function getUrlAttribute()
    {
        return "data:image/{$this->tipo};base64,{$this->imagem}";
    }

    public function tabela(): MorphTo
    {
        return $this->morphTo();
    }
}
