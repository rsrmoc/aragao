<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrasEtapas extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $hidden = [
        'obra'
    ];

    protected $fillable = [
        'id_obra',
        'id_usuario',
        'nome',
        'porc_etapa',
        'porc_geral', // insidencia
        'concluida',
        'dt_inicio',
        'dt_previsao',
        'dt_termino',
        'dt_vencimento',
        'quitada',
        'descricao_completa',
        'status'
    ];
    
    protected $casts = [
        'concluida' => 'boolean',
        'quitada' => 'boolean'
    ];

    protected $append = [
        'valor_etapa',
        'valor_gasto',
        'insidencia_executada'
    ];

    public function obra() {
        return $this->hasOne(Obras::class, 'id', 'id_obra');
    }


    public function getInsidenciaExecutadaAttribute() {
        return $this->porc_etapa / 100 * $this->porc_geral;
    }

    public function getValorEtapaAttribute() {
        return $this->obra->valor * $this->porc_geral / 100;
    }

    public function getValorGastoAttribute() {
        return $this->valor_etapa * $this->porc_etapa / 100;
    }
}
