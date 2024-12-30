<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Honorario extends Model
{
    use HasFactory;

    protected $fillable = [
        'expediente_id',
        'factura',
        'fecha',
        'monto',
        'estado',
        'metodo_de_pago',
        'descripcion',
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }
}
