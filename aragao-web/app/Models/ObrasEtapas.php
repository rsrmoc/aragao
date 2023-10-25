<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrasEtapas extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'id_obra',
        'id_usuario',
        'nome',
        'porc_etapa',
        'porc_geral',
        'concluida',
        'dt_inicio',
        'dt_previsao',
        'dt_termino',
        'dt_vencimento',
        'valor',
        'quitada',
        'descricao_completa'
    ];
    
    protected $casts = [
        'concluida' => 'boolean',
        'quitada' => 'boolean'
    ];

    protected $appends = [
        'insidencia'
    ];

    public function getInsidenciaAttribute() {
        return ($this->porc_etapa * $this->porc_geral) / 100;
    }
}
