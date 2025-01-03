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
            $table->string('familia')->nullable()->nullable();
            $table->date('fecha_de_nacimiento');
            $table->enum('genero',['masculino', 'femenino', 'otro']);
            $table->enum('estado_civil',['soltero', 'casado', 'divorciado', 'viudo', 'otro']);
            $table->string('pais');
            $table->date('llegada a canada')->nullable();
            $table->enum('punto de acceso', ['aerpuerto', 'terrestre', 'maritimo', 'otro']);
            $table->string('pasaporte')->nullable();
            $table->string('estatus');
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('profesion')->nullable();
            $table->string('lenguaje')->nullable();
            $table->string('permiso_de_trabajo')->nullable();
            $table->string('iuc')->nullable();
            $table->text('observaciones');
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
