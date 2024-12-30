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
            $table->unsignedBigInteger('tipo_documento_id');
            $table->foreign('tipo_documento_id')
                ->references('id')
                ->on('tipos_documentos')
                ->onDelete('cascade');
            $table->unsignedBigInteger('client_id');
                $table->foreign('client_id')
                    ->references('id')
                    ->on('clients')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');        
            $table->enum('formato', ['PDF', 'IMAGEN']);
            $table->enum('estado', ['Pendiente', 'En Revisión', 'Aceptado', 'Rechazado', 'Obsoleto'])
            ->default('Pendiente'); // Estado inicial por defecto
            $table->string('ruta')->nullable();
            $table->string('observaciones')->nullable();

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
