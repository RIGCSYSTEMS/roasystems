<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'tipos_documentos';

    protected $fillable = ['nombre', 'descripcion'];

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
