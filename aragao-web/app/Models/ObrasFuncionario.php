<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObrasFuncionario extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_funcionario',
        'id_obra',
        'funcao',
        'conselho'
    ];

    public function funcionario() {
        return $this->hasOne(Funcionario::class, 'id', 'id_funcionario');
    }
}
