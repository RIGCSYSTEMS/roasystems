<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Expediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'tipo_expediente_id',
        'fecha_de_apertura',
        'estatus_del_expediente',
        'prioridad',
        'fecha_de_cierre',
        'numero_de_dossier',
        'despacho',
        'abogado',
        'plazo_fda',
        'boite',
        'progreso'
    ];

    protected $casts = [
        'fecha_de_apertura' => 'date',
        'fecha_de_cierre' => 'date',
        'plazo_fda' => 'date',
        'progreso' => 'integer'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    
    public function expedientes(): HasMany
    {
        return $this->hasMany(DocumentoExpediente::class);
    }

    public function tipoExpediente(): BelongsTo
    {
        return $this->belongsTo(TipoExpediente::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($expediente) {
            $expediente->etapas()->create([
                'nombre' => 'admision',
                'completada' => false,
                'porcentaje' => 10
            ]);
        });
    }

    public function etapas()
    {
        return $this->hasMany(EtapaExpediente::class);
    }

    public function actualizarProgreso()
    {
        $this->progreso = $this->etapas()->sum('porcentaje');
        $this->save();

        if ($this->etapas()->where('nombre', 'cierre')->where('completada', true)->exists()) {
            $this->estatus_del_expediente = 'Cerrado';
            $this->fecha_de_cierre = now();
            $this->save();
        }
    }

    public function bitacoras(): HasMany
    {
        return $this->hasMany(Bitacora::class);
    }

    public function audiencias(): HasMany
    {
        return $this->hasMany(Audiencia::class);
    }

    // Cambiamos el nombre de la relación para que coincida
    public function honorarios(): HasOne
    {
        return $this->hasOne(Honorario::class);
    }

    public function calcularProgreso(): int
    {
        $totalEtapas = $this->etapas()->count();
        if ($totalEtapas === 0) {
            return 0;
        }
        
        $etapasCompletadas = $this->etapas()->where('completada', true)->count();
        return (int)(($etapasCompletadas / $totalEtapas) * 100);
    }

    // public function actualizarProgreso(): void
    // {
    //     $this->progreso = $this->calcularProgreso();
    //     $this->save();
    // }
}