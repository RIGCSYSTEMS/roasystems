<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentos extends Model
{
    use HasFactory;

    public $incrementing = false;

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