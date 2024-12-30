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
        Schema::create('etapas_expedientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expediente_id');
            $table->string('nombre'); // Nombre de la etapa (ej: Admisión, Historia)
            $table->boolean('completada')->default(false); // Estado de la etapa
            $table->timestamps();

            // Relaciones
            $table->foreign('expediente_id')->references('id')->on('expedientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapa_expedientes');
    }
};
