<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->enum('opciones', ['1', '2']); // Select
            $table->enum('genero', ['masculino', 'femenino']); // Radio
            $table->boolean('acepto'); // Checkbox
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
