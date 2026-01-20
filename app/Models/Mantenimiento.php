<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'impresora_id',
        'tipo_error',
        'fecha_incidencia',
        'fecha_envio_mantenimiento',
        'fecha_retorno_mantenimiento',
        'contador',
        'estado_garantia',
        'observacion'
    ];

    public function impresora()
    {
        return $this->belongsTo(Impresora::class);
    }
}
