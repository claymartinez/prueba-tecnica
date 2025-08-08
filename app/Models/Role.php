<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre'];

    public function empleados(): BelongsToMany
    {
        return $this->belongsToMany(Empleado::class, 'empleado_rol', 'rol_id', 'empleado_id');
    }
}
