<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Carbon\Carbon;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_de_cliente',
        'familia',
        'fecha_de_nacimiento',
        'genero',
        'estado_civil',
        'pais',
        'llegada_a_canada',
        'punto_de_acceso',
        'pasaporte',
        'estatus',
        'direccion',
        'telefono',
        'email',
        'profesion',
        'lenguaje',
        'permiso_de_trabajo',
        'iuc',
        'observaciones'
    ];

    protected $casts = [
        'fecha_de_nacimiento' => 'datetime:Y-m-d',
        'llegada_a_canada' => 'datetime:Y-m-d',
        'familia' => 'array',
    ];

    // Mutadores para asegurar el formato correcto de las fechas
    public function setFechaDeNacimientoAttribute($value)
    {
        $this->attributes['fecha_de_nacimiento'] = $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function setLlegadaACanadaAttribute($value)
    {
        $this->attributes['llegada_a_canada'] = $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    // Accessors para obtener las fechas en el formato correcto
    public function getFechaDeNacimientoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getLlegadaACanadaAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function bitacoras(): HasManyThrough
    {
        return $this->hasManyThrough(
            Bitacora::class,
            Expediente::class,
            'client_id',
            'expediente_id'
        );
    }

    public function getTotalHonorariosAttribute()
    {
        return $this->expedientes()
            ->join('honorarios', 'expedientes.id', '=', 'honorarios.expediente_id')
            ->sum('honorarios.monto_total_expediente');
    }

    public function getTotalPagadoAttribute()
    {
        return $this->expedientes()
            ->join('honorarios', 'expedientes.id', '=', 'honorarios.expediente_id')
            ->sum('honorarios.total_abonos');
    }

    public function getSaldoPendienteAttribute()
    {
        return $this->total_honorarios - $this->total_pagado;
    }
}