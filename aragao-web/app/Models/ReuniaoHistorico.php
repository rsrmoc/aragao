<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReuniaoHistorico extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_reuniao',
        'id_usuario',
        'situacao'
    ];

    public function usuario() {
        return $this->hasOne(User::class, 'id', 'id_usuario');
    }
}
