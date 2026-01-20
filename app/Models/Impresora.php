<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impresora extends Model
{
    use HasFactory;

    protected $fillable = [
        'oficina_id',
        'marca',
        'modelo',
        'serie',
        'fecha_compra',
        'nombre_host',
        'direccion_ip',
        'estado_equipo',
        'ubicacion_actual'
    ];

    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }

    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class);
    }
}
