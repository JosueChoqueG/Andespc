<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sistemaoperativo extends Model
{
    protected $table = 'sistemaoperativos';
    protected $fillable = ['nombre_so', 'edicion', 'version'];

    public function equipo()
    {
        return $this->hasOne(Equipo::class);
    }
}
