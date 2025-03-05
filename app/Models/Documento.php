<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

class Documento extends Model
{
    use HasFactory, SoftDeletes, Prunable;


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
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subYears(7));
    }
    
}