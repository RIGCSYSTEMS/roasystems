<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tipo_expediente',
        'numero_dossier',
        'estado',
        'prioridad',
        'fecha_apertura',
        'fecha_cierre',
        'progreso',
    ];

    public function etapas()
    {
        return $this->hasMany(EtapaExpediente::class);
    }

    public function calcularProgreso()
    {
        $totalEtapas = $this->etapas()->count();
        $etapasCompletadas = $this->etapas()->where('completada', true)->count();

        return $totalEtapas === 0 ? 0 : ($etapasCompletadas / $totalEtapas) * 100;
    }
}
