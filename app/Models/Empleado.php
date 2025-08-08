<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Empleado extends Model
{
    protected $fillable = [
        'nombre',
        'email',
        'sexo',
        'area_id',
        'boletin',
        'descripcion'
    ];

    protected $casts = [
        'boletin' => 'boolean',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'empleado_rol', 'empleado_id', 'rol_id');
    }
}
