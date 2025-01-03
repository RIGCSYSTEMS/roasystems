<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audiencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained()->onDelete('cascade');
            $table->dateTime('fecha_hora');
            $table->string('tipo_audiencia');
            $table->text('descripcion')->nullable();
            $table->string('lugar');
            $table->enum('estado', ['Programada', 'Realizada', 'Cancelada', 'Pendiente'])->default('Programada');
            $table->string('responsable')->nullable();
            $table->text('notas_internas')->nullable();
            $table->text('resultado')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audiencias');
    }
};