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
            $table->foreignId('client_id')->constrained('client')->onDelete('cascade');
            $table->string('identificacion')->nullable();
            $table->string('pasaporte')->nullable();
            $table->string('permiso_de_trabajo')->nullable();
            $table->string('hoja_marron')->nullable();
            $table->string('pruebas')->nullable();
            $table->text('historia')->nullable();
            $table->string('residencia_permanente')->nullable();
            $table->string('caq')->nullable();
            $table->string('extras')->nullable();
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
