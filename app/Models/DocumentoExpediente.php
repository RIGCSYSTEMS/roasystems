<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoExpediente extends Model
{
    use HasFactory;

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
}