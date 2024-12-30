<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('honorarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->onDelete('cascade');
            $table->string('factura');
            $table->date('fecha');
            $table->decimal('monto', 10, 2);
            $table->string('estado')->default('pendiente');
            $table->string('metodo_de_pago')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honorarios');
    }
};
