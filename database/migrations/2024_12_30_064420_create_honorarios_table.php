<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('honorarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expediente_id');
            $table->decimal('monto_total_expediente', 10, 2);
            $table->decimal('monto_adicional', 10, 2)->default(0);
            $table->decimal('monto_total_a_pagar', 10, 2);
            $table->decimal('total_abonos', 10, 2)->default(0);
            $table->decimal('saldo_pendiente', 10, 2);
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado'])->default('pendiente');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('expediente_id')
                  ->references('id')
                  ->on('expedientes')
                  ->onDelete('cascade');
            $table->foreign('usuario_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('honorarios');
    }
};
