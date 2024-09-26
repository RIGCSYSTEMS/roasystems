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
            $table->id();
            $table->string('nombre_de_cliente');
            $table->longText('otros_nombres_de_cliente');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('profesion');
            $table->string('pais');
            $table->string('despacho');
            $table->string('tipo_de_expediente');
            $table->string('lenguaje');
            $table->string('honorarios');
            $table->string('fecha_de_apertura');
            $table->string('estatus');
            $table->longText('observaciones');
            $table->string('numero_de_expediente');
            $table->string('permiso_de_trabajo');
            $table->string('IUC');
            $table->string('ubicacion_del_despacho');
            $table->string('fecha_de_cierre');
            $table->string('cierre_del_numero_de_caja');
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
