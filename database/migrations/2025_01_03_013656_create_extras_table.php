<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('extras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('honorario_id');
            $table->string('concepto');
            $table->decimal('monto', 10, 2);
            $table->decimal('gst_rate', 5, 3);
            $table->decimal('qst_rate', 5, 3);
            $table->decimal('impuestos', 10, 2);
            $table->date('fecha');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('honorario_id')
                  ->references('id')
                  ->on('honorarios')
                  ->onDelete('cascade');
            $table->foreign('usuario_id')
                  ->references('id')
                  ->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('extras');
    }
};