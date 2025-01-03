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
            $table->decimal('monto_total_expediente', 10, 2);
            $table->decimal('monto_adicional', 10, 2)->default(0);
            $table->decimal('monto_total_a_pagar', 10, 2);
            $table->decimal('total_abonos', 10, 2)->default(0);
            $table->decimal('saldo_pendiente', 10, 2);
            $table->enum('estado',['pendiente','pagado','cancelado',])->default('pendiente');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honorarios');
    }
};
