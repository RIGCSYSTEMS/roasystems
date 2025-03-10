<?php
// Archivo: database/migrations/2023_03_10_000002_create_bitacoras_table.php

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
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('tipo', ['normal', 'seguimiento']);
            $table->string('titulo');
            $table->foreignId('categoria_id')->constrained('bitacora_categorias');
            $table->text('descripcion');
            $table->integer('tiempo_dedicado')->nullable();
            $table->enum('estado', ['completado', 'en_proceso', 'pendiente']);
            $table->date('fecha_limite')->nullable();
            $table->enum('prioridad_fecha', ['normal', 'importante', 'critica'])->nullable();
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamp('fecha_reactivacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};