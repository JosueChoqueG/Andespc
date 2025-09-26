<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $fillable = ['nombre_modelo', 'id_marca'];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }
}
