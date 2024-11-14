<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        //Codigo para eliminar completamente la tabla expedientes
        //if (!Schema::hasTable('expedientes'))
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->date('fecha_de_apertura');
            $table->string('estatus_del_expediente');
            $table->date('fecha_de_cierre')->nullable();
            $table->string('numero_de_dossier');
            $table->string('despacho');
            $table->string('abogado');
            $table->decimal('honorarios', 10, 2);
            $table->string('tipo');
            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')
                ->on('client')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expedientes');
    }
};