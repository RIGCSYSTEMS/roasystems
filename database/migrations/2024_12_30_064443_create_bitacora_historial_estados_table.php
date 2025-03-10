<?php
// Archivo: database/migrations/2023_03_10_000004_create_bitacora_historial_estados_table.php

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
        Schema::create('bitacora_historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bitacora_id')->constrained('bitacoras')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->string('accion');
            $table->timestamp('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_historial_estados');
    }
};