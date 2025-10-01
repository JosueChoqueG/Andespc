<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
    protected $table = 'responsables';
    protected $fillable = ['nombre_responsable'];

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }
}
