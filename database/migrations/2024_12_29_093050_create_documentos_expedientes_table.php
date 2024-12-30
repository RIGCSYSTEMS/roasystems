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
        Schema::create('documentos_expedientes', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->unsignedBigInteger('expediente_id');
            $table->foreign('expediente_id')
            ->references('id')
            ->on('expedientes')
            ->onDelete('cascade');
            $table->unsignedBigInteger('etapa_id');
            $table->foreign('etapa_id')
            ->references('id')
            ->on('etapas_expedientes')
            ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->unsignedBigInteger('tipo_documento_expediente_id');
            $table->foreign('tipo_documento_expediente_id')
                ->references('id')
                ->on('tipos_documentos_expedientes')
                ->onDelete('cascade');

            // Campos
            $table->string('nombre'); // Nombre del documento
            $table->string('ruta'); // Ruta del archivo
            $table->enum('estado', ['Pendiente', 'En Revisión', 'Aceptado', 'Rechazado', 'Obsoleto'])
            ->default('Pendiente'); // Estado inicial por defecto
            $table->enum('formato',['PDF', 'IMAGEN']); // Tipo de documento (ej: PDF, Imagen)
            $table->boolean('validado')->default(false); // Validación del abogado
            $table->text('descripcion')->nullable(); // Información adicional
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_expedientes');
    }
};
