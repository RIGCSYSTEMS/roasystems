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
            // $table->unsignedBigInteger('id');

            // $table->increments('id');
            $table->id();
            
            $table->string ('nombre_de_documento')->nullable();
            $table->string ('tipo_de_documento')->nullable();
            $table->foreignId('client_id')
                ->constrained()
                ->onDelete('cascade');

            // $table->integer('client_id')->unsigned(); // Relación con la tabla CLIENTES
            // $table->foreign('client_id')->references('id')->on('client')->onDelete('cascade');  //

            



            //Clasificación de uno a uno
            // $table->string('identificacion')->nullable();
            // $table->string('pasaporte')->nullable();
            // $table->string('permiso_de_trabajo')->nullable();
            // $table->string('hoja_marron')->nullable();
            // $table->string('pruebas')->nullable();
            // $table->string('historia')->nullable();
            // $table->string('residencia_permanente')->nullable();
            // $table->string('caq')->nullable();
            // $table->string('extras')->nullable();
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
