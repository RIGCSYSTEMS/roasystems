<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

class Client extends Model
{
    use HasFactory, SoftDeletes, Prunable;

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
        'fecha_de_nacimiento' => 'date:Y-m-d',
        'llegada_a_canada' => 'date:Y-m-d',
        'familia' => 'array',
    ];

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
        return $this->hasManyThrough(Bitacora::class, Expediente::class);
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

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subYears(7));
    }
}