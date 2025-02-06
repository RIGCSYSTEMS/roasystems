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
            $table->enum('nombre',['admision', 'historia', 'pruebas', 'audiencia', 'cierre']); // Nombre de la etapa (ej: Admisión, Historia)
            $table->integer('porcentaje')->default(0);
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
        Schema::dropIfExists('etapas_expedientes');
    }
};
