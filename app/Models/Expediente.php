<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'fecha_de_apertura',
        'estatus_del_expediente',
        'fecha_de_cierre',
        'numero_de_dossier',
        'despacho',
        'abogado',
        'honorarios',
        'tipo'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
