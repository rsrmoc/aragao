<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObrasUsuarios extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_obra',
        'id_usuario',
        'tipo'
    ];

    public function usuario() {
        return $this->hasOne(User::class, 'id', 'id_usuario');
    }

    public function obra() {
        return $this->hasOne(Obras::class, 'id_obra');
    }
}
