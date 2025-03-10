<?php
// Archivo: database/migrations/2023_03_10_000003_create_bitacora_actualizaciones_table.php

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
        Schema::create('bitacora_actualizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bitacora_id')->constrained('bitacoras')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('categoria_id')->constrained('bitacora_categorias');
            $table->text('descripcion');
            $table->integer('tiempo_dedicado')->nullable();
            $table->enum('estado', ['completado', 'en_proceso', 'pendiente', 'comentario']);
            $table->boolean('es_comentario')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_actualizaciones');
    }
};