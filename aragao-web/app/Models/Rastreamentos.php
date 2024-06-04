<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rastreamentos extends Model
{
    use HasFactory, SoftDeletes;

    public const INTERVALO_CAPTURA = 5; // Minutos

    protected $fillable = [
        'latitude',
        'longitude',
        'endereco',
        'id_usuario',
    ];

}
