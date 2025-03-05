<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

class DocumentoExpediente extends Model
{
    use HasFactory, softDeletes, Prunable;

    protected $table = 'documentos_expedientes';

    protected $fillable = [
        'expediente_id',
        'etapa_id',
        'nombre',
        'ruta',
        'estado',
        'formato',
        'tipo',
        'validado',
        'observaciones',
    ];

    public function etapa()
    {
        return $this->belongsTo(EtapaExpediente::class, 'etapa_id');
    }

    public function expedientes()
    {
        return $this->belongsTo(Expediente::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tipodocumentoexpediente()
    {
        return $this->belongsTo(TipoDocumentoExpediente::class, 'tipo_documento_expediente_id');
    }

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subYears(7));
    }
}