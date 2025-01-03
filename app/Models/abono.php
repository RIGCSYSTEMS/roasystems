<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;

    protected $fillable = [
        'honorario_id',
        'fecha',
        'factura',
        'monto',
        'metodo_pago',
        'usuario_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function honorario()
    {
        return $this->belongsTo(Honorario::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($abono) {
            $abono->honorario->actualizarTotales();
        });

        static::updated(function ($abono) {
            $abono->honorario->actualizarTotales();
        });

        static::deleted(function ($abono) {
            $abono->honorario->actualizarTotales();
        });
    }
}