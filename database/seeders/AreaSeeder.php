<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        // Catálogo base
        Area::insert([
            ['nombre' => 'Ventas',          'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Calidad',         'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Producción',      'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Administración',  'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
