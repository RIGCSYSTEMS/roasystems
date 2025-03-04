<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Honorario extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expediente_id',
        'monto_total_expediente',
        'monto_adicional',
        'monto_total_a_pagar',
        'total_abonos',
        'saldo_pendiente',
        'estado',
        'descripcion',
        'usuario_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($honorario) {
            $honorario->monto_total_a_pagar = $honorario->monto_total_expediente + $honorario->monto_adicional;
            $honorario->saldo_pendiente = $honorario->monto_total_a_pagar;
        });

        static::updating(function ($honorario) {
            if ($honorario->isDirty(['monto_total_expediente', 'monto_adicional'])) {
                $honorario->monto_total_a_pagar = $honorario->monto_total_expediente + $honorario->monto_adicional;
            }
            $honorario->saldo_pendiente = $honorario->monto_total_a_pagar - $honorario->total_abonos;
        });
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }

    public function abonos()
    {
        return $this->hasMany(Abono::class);
    }

    public function extras()
    {
        return $this->hasMany(Extra::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function actualizarTotales()
    {
        $this->total_abonos = $this->abonos()->sum('monto');
        $this->saldo_pendiente = $this->monto_total_a_pagar - $this->total_abonos;
        $this->save();
    }
}