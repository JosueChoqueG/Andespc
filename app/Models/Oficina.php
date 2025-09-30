<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    protected $fillable = ['nombre_oficina', 'ubicacion_equipo', 'agencia_id'];

    public function agencia()
    {
        return $this->belongsTo(Agencia::class, 'agencia_id');
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }
}
