<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentoExpediente extends Model
{
    // Nombre de la tabla
    protected $table = 'tipos_documentos_expedientes';

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