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
        Schema::create('client', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_de_cliente');
            $table->string('otros_nombres_de_cliente')->nullable();
            $table->string('estatus');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('profesion');
            $table->string('pais');
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
