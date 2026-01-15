<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $fillable = [
        'tipo',
        'modulo',
        'problema',
        'descripcion',
        'solucion',
        'usuario_afectado',
        'agencia',
        'sub_agencia',
        'prioridad',
        'estado',
        'atendido_por'
    ];

    public function atendidoPor()
    {
        return $this->belongsTo(\App\Models\User::class, 'atendido_por');
    }
}