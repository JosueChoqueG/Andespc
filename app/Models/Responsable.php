<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
    protected $table = 'responsables';

    protected $fillable = [
        'dni',
        'nombre_responsable',
        'cargo',
        'oficina_id'
    ];

    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'oficina_id');
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function impresoras()
    {
        return $this->hasMany(Impresora::class, 'responsable_id');
    }

    public function termicas()
    {
        return $this->hasMany(Termica::class, 'responsable_id');
    }

    public function contabilletes()
    {
        return $this->hasMany(Contabillete::class, 'responsable_id');
    }
}
