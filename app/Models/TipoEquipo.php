<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipoequipo extends Model
{
    protected $table = 'tipoequipos';
    protected $fillable = ['nombre_tipo'];

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }
}
