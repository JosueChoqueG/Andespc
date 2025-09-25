<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    protected $table = 'oficinas';
    protected $fillable = ['nombre_oficina', 'ubicacion_equipo', 'id_agencia'];

    public function agencia()
    {
        return $this->belongsTo(Agencia::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }
}