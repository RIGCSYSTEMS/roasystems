<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extra extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'honorario_id',
        'concepto',
        'monto',
        'gst_rate',
        'qst_rate',
        'impuestos',
        'fecha',
        'usuario_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
        'gst_rate' => 'decimal:3',
        'qst_rate' => 'decimal:3',
        'impuestos' => 'decimal:2',
    ];

    public function honorario()
    {
        return $this->belongsTo(Honorario::class);
    }
}