<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');                 // varchar(255) NOT NULL
            $table->string('email')->unique();        // varchar(255) NOT NULL + UNIQUE
            $table->char('sexo', 1);                  // 'M' | 'F'
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->boolean('boletin')->default(false); // 0/1, opcional => default 0
            $table->text('descripcion');              // NOT NULL
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
