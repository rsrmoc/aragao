<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reunioes extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_obra',
        'id_usuario_solicitante',
        'id_usuario_confirmacao',
        'assunto',
        'descricao',
        'dt_reuniao',
        'dt_confirmacao',
        'situacao',
        'conteudo'
    ];

    public function historico() {
        return $this->hasMany(ReuniaoHistorico::class, 'id_reuniao')->with('usuario');
    }

    public function participantes() {
        return $this->hasMany(ReunioesUsuarios::class, 'id_reuniao');
    }
}
