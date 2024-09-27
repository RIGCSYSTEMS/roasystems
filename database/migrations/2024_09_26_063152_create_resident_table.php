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
        Schema::create('resident', function (Blueprint $table) {
            $table->id();
            $table->string('id_cliente');
            $table->string('fecha_de_demanda');
            $table->string('tipo_de_demanda');
            $table->string('usuario');
            $table->string('password');
            $table->longText('preguntas_respuestas');
            $table->string('despacho');
            $table->string('abogado');
            $table->string('confirmacion');
            $table->longText('notas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident');
    }
};
