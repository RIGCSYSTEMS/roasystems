<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    // Nombre de la tabla
    protected $table = 'tipos_documentos';

    // Campos que se pueden llenar de forma masiva
    protected $fillable = ['nombre'];

    /**
     * Relación: Un tipo de documento puede estar asociado a muchos documentos.
     */
    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
