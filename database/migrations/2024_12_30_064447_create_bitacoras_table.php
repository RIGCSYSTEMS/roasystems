<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('expediente_id')->constrained('expedientes')->onDelete('cascade');
            $table->enum('categoria', [
                'Actualizacion de informacion',
                'Carga de documento',
                'Comunicacion con el cliente',
                'Audiencia',
                'Revision',
                'Otro'
            ]);
            $table->text('descripcion');
            $table->time('tiempo_empleado')->nullable();
            $table->timestamp('fecha_y_hora_del_evento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora');
    }
};