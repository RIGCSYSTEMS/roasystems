<?php

namespace App\Models;

use App\Http\Controllers\DocumentoExpedienteController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\DocumentoExpediente;

class EtapaExpediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'expediente_id',
        'nombre',
        'completada',
    ];

    public function documentos()
    {
        return $this->hasMany(DocumentoExpediente::class, 'etapa_id');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }

    public function verificarCompletada()
    {
        $documentosPendientes = $this->documentos()->where('validado', false)->count();
        if ($documentosPendientes === 0) {
            $this->completada = true;
            $this->save();
        }
    }
}