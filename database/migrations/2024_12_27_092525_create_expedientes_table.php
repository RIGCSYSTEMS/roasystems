<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        //Codigo para eliminar completamente la tabla expedientes
        // if (!Schema::hasTable('expedientes'))
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');
            $table->unsignedBigInteger('tipo_expediente_id'); // Tipo de expediente
            $table->foreign('tipo_expediente_id')
            ->references('id')
            ->on('tipos_expedientes')
            ->onDelete('cascade');
            $table->date('fecha_de_apertura');
            $table->enum('estatus_del_expediente', ['Abierto', 'Cerrado', 'Pendiente', 'Cancelado'])->default('Pendiente');
            $table->enum('prioridad',['Urgente','Normal'])->default('Normal');
            $table->date('fecha_de_cierre')->nullable();
            $table->string('numero_de_dossier')->unique();
            $table->string('despacho')->nullable();
            $table->string('abogado')->nullable();
            $table->date('plazo_fda')->nullable();
            $table->string('boite'); 
            $table->integer('progreso')->default(0);
            $table->timestamps();


        });
    }

    public function down()
    {
        Schema::dropIfExists('expedientes');
    }
};
