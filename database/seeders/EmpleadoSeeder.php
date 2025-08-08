<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Empleado;
use App\Models\Role;
use Illuminate\Database\Seeder;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        $areas = Area::pluck('id', 'nombre');
        $roles = Role::pluck('id', 'nombre');

        $e1 = Empleado::create([
            'nombre' => 'Gladys Fernandez',          // sin tildes (según requerimiento)
            'email' => 'gfernandez@example.com',
            'sexo' => 'F',
            'area_id' => $areas['Ventas'] ?? $areas->first(),
            'boletin' => 1,
            'descripcion' => 'Experiencia en ventas y atención al cliente.',
        ]);
        $e1->roles()->sync([$roles['Auxiliar administrativo'] ?? $roles->first()]);

        $e2 = Empleado::create([
            'nombre' => 'Felipe Gomez',
            'email' => 'fgomez@example.com',
            'sexo' => 'M',
            'area_id' => $areas['Calidad'] ?? $areas->first(),
            'boletin' => 0,
            'descripcion' => 'Especialista en aseguramiento de calidad.',
        ]);
        $e2->roles()->sync([$roles['Gerente estratégico'] ?? $roles->first()]);

        $e3 = Empleado::create([
            'nombre' => 'Adriana Loaiza',
            'email' => 'aloaiza@example.com',
            'sexo' => 'F',
            'area_id' => $areas['Producción'] ?? $areas->first(),
            'boletin' => 1,
            'descripcion' => 'Experiencia en procesos de producción.',
        ]);
        $e3->roles()->sync([$roles['Profesional de proyectos - Desarrollador'] ?? $roles->first()]);
    }
}
