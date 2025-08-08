<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'opciones',
        'genero',
        'acepto',
    ];

    protected $casts = [
        'acepto' => 'boolean',
    ];
}
