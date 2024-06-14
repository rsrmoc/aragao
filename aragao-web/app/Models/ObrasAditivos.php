<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrasAditivos extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_obra',
        'titulo',
        'id_obra',
        'id_etapa',
        'dt_aditivo',
        'descricao',
        'id_usuario'
    ];

    protected static function boot() {
        parent::boot();

        static::deleting(function($evolucao) {
            $evolucao->imagens()->delete();
        });
    }

    public function imagens(): MorphMany
    {
        return $this->morphMany(Imagens::class, 'tabela');
    }

    public function usuario() {
        return $this->hasOne(User::class, 'id', 'id_usuario');
    }
}
