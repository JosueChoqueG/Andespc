<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    protected $fillable = ['id_agencia','codigo_agencia', 'nombre_agencia'];

    public function oficinas()
    {
        return $this->hasMany(Oficina::class);
    }
}
