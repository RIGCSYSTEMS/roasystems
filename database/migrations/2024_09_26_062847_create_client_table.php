<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_de_cliente');
            $table->string('familia')->nullable();
            $table->string('fecha_de_nacimiento');
            $table->string('genero');
            $table->string('estado_civil');
            $table->string('pais');
            $table->string('pasaporte');
            $table->string('estatus');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('profesion');
            $table->string('lenguaje');
            $table->string('permiso_de_trabajo')->nullable();
            $table->string('iuc')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
