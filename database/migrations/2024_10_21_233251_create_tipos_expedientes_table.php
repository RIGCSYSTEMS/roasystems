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
        $tipos = ['asilo', 'appel', 'permanente', 'erar', 'apadrinamiento', 'humanitaria', 'temporal'];

        foreach ($tipos as $tipo) {
            Schema::create($tipo . '_expedientes', function (Blueprint $table) use ($tipo) {
                $table->id();
                $table->foreignId('expediente_id')->constrained('expedientes')->onDelete('cascade');
                $table->date('fecha');
                $table->date('fecha_de_recepcion');
                $table->text('observaciones')->nullable();
                $table->string('tiempo');
                $table->string('persona_responsible');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        $tipos = ['asilo', 'appel', 'permanente', 'erar', 'apadrinamiento', 'humanitaria', 'temporal'];

        foreach ($tipos as $tipo) {
            Schema::dropIfExists($tipo . '_expedientes');
        }
    }
};