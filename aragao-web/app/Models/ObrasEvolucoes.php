<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrasEvolucoes extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_obra',
        'id_etapa',
        'dt_evolucao',
        'descricao'
    ];

    protected static function boot() {
        parent::boot();

        static::deleting(function($evolucao) {
            $evolucao->imagens()->delete();
        });
    }

    public function etapa() {
        return $this->hasOne(ObrasEtapas::class, 'id', 'id_etapa');
    }

    public function imagens(): MorphMany
    {
        return $this->morphMany(Imagens::class, 'tabela');
    }
}
