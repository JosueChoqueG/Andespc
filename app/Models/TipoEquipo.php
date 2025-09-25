<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEquipo extends Model
{
    
    protected $fillable = ['nombre_tipo'];

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }
}
