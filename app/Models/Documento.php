<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'identificacion',
        'pasaporte',
        'permiso_de_trabajo',
        'hoja_marron',
        'pruebas',
        'historia',
        'residencia_permanente',
        'caq',
        'extras'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
