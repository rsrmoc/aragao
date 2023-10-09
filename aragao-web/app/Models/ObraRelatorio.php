<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObraRelatorio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_obra',
        'id_usuario',
        'filename'
    ];
}
