<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['nombre' => 'Profesional de proyectos - Desarrollador', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Gerente estratégico',                      'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Auxiliar administrativo',                  'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
