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
        Schema::create('call', function (Blueprint $table) {
            $table->id();
            $table->string('fecha');
            $table->string('nombre');
            $table->string('telefono');
            $table->string('aboagado');
            $table->string('motivo');
            $table->string('nota');
            $table->string('requiere_accion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call');
    }
};
