<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hardware extends Model
{
    protected $table = 'hardwares';
    protected $fillable = ['procesador', 'ram_gb', 'almacenamiento_gb', 'tipo_almacenamiento'];

    public function equipo()
    {
        return $this->hasOne(Equipo::class);
    }
}
