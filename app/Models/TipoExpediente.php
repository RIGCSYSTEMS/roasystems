<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoExpediente extends Model
{
    use HasFactory;

    protected $table = 'tipos_expedientes';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }
}