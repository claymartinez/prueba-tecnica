<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empleado_rol', function (Blueprint $table) {
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('rol_id');

            $table->foreign('empleado_id')->references('id')->on('empleados')->cascadeOnDelete();
            $table->foreign('rol_id')->references('id')->on('roles')->cascadeOnDelete();

            $table->primary(['empleado_id', 'rol_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('empleado_rol');
    }
};
