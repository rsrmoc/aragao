<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Obras extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_usuario',
        'nome',
        'dt_inicio',
        'dt_termino',
        'dt_previsao_termino',
        'valor',
        'valor_saldo',
        'endereco_rua',
        'endereco_bairro',
        'endereco_numero',
        'endereco_cidade',
        'endereco_uf',
        'endereco_cep'
    ];

    protected $appends = ['status'];

    public function getStatusAttribute() {
        $status = 'Concluida';
        $dtPrevisao = Carbon::parse($this->dt_previsao_termino);

        if (now()->gt($dtPrevisao) && !$this->dt_termino) $status = 'Atrasada';
        else if (now()->lt($dtPrevisao) && !$this->dt_termino) $status = 'Em andamento';

        return $status;
    }
}
