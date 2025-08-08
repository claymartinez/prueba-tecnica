<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AreaSeeder::class,
            RoleSeeder::class,
            EmpleadoSeeder::class, // opcional
        ]);
    }
}
