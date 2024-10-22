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
            $table->foreignId('cliente_id')->constrained('client')->onDelete('cascade');
            $table->date('fecha_de_apertura');
            $table->string('estatus');
            $table->date('fecha_de_cierre')->nullable();
            $table->string('numero_de_dossier');
            $table->string('despacho');
            $table->string('abogado');
            $table->decimal('honorarios', 10, 2);
            $table->string('tipo');
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
