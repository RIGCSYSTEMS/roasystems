<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    use HasFactory;


    protected $fillable = [
        'client_id',
        'tipo_documento_id',
        'user_id',
        'formato',
        'estado',
        'ruta',
        'observaciones'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}