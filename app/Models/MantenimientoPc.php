<?php
// app/Models/MantenimientoPc.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MantenimientoPc extends Model
{
    use HasFactory;

    protected $table = 'mantenimiento_pc';  // ← Cambiado a singular
    
    protected $fillable = [
        'equipo_id',
        'user_id',
        'tipo_mantenimiento',
        'fecha_mantenimiento',
        'descripcion_mantenimiento',
        'fallas_encontradas',
        'soluciones_aplicadas',
        'observaciones',
    ];

    protected $casts = [
        'fecha_mantenimiento' => 'date',
    ];

    // Relación con Equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accesor para obtener descripción como array
    public function getDescripcionArrayAttribute()
    {
        return array_filter(explode("\n", $this->descripcion_mantenimiento));
    }

    // Accesor para obtener fallas como array
    public function getFallasArrayAttribute()
    {
        return array_filter(explode("\n", $this->fallas_encontradas ?? ''));
    }
    
    // Accesor para obtener el nombre del técnico
    public function getTecnicoNombreAttribute()
    {
        return $this->user ? $this->user->name : 'Josue Choque Gomez';
    }
    
    // Scope para mantenimientos preventivos
    public function scopePreventivos($query)
    {
        return $query->where('tipo_mantenimiento', 'Preventivo');
    }
    
    // Scope para mantenimientos correctivos
    public function scopeCorrectivos($query)
    {
        return $query->where('tipo_mantenimiento', 'Correctivo');
    }
}