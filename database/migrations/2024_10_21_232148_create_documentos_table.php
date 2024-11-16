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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->enum('nombre_de_documento', [
                'IDENTIFICACION',
                'PASAPORTE',
                'PERMISO DE TRABAJO',
                'HOJA MARRON',
                'PRUEBAS',
                'HISTORIA',
                'RESIDENCIA PERMANENTE',
                'CAQ',
                'EXTRAS'
            ]);
            $table->enum('tipo_de_documento', ['PDF', 'IMAGEN']);
            $table->string('imagen_url')->nullable();
            $table->string('observaciones')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
