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
        Schema::create('follow', function (Blueprint $table) {
            $table->id();
            $table->string('id_client');
            $table->string('fecha');
            $table->longText('comentarios');
            $table->string('fecha_recepcion');
            $table->string('fecha_limite');
            $table->string('tiempo');
            $table->string('estatus');
            $table->string('persona_responsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow');
    }
};
