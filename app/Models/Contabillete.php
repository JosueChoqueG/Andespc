<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contabillete extends Model
{
    use HasFactory;

    protected $table = 'contabilletes';

    protected $fillable = [
        'oficina_id',
        'tipo_contabilletes',
        'marca_contabilletes',
        'modelo_contabilletes',
        'fecha_adquisicion',
        'serie_contabilletes',
        'responsable_id',
        'velocidad_contabilletes',
        'capacidad_tolva',
        'capacidad_bandeja',
        'tipo_deteccion',
        'pantalla_contabilletes',
        'estado_contabilletes'
    ];

    protected $casts = [
        'id' => 'integer',
        'oficina_id' => 'integer',
        'responsable_id' => 'integer',
        'fecha_adquisicion' => 'date',
        'capacidad_tolva' => 'integer',
        'capacidad_bandeja' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Constantes para los ENUMs
    const ESTADO_CONTABILLETE = [
        'OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE', 'DE BAJA'
    ];

    // Relaciones
    public function oficina(): BelongsTo
    {
        return $this->belongsTo(Oficina::class, 'oficina_id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Responsable::class, 'responsable_id');
    }

    public function mantenimientos(): HasMany
    {
        return $this->hasMany(MantenimientoContabillete::class, 'contabillete_id');
    }

    // Scopes para búsquedas comunes
    public function scopeActivos($query)
    {
        return $query->whereIn('estado_contabilletes', ['OPTIMO', 'BUENO', 'REGULAR']);
    }

    public function scopeOperativos($query)
    {
        return $query->where('estado_contabilletes', 'OPTIMO');
    }

    public function scopeDeBaja($query)
    {
        return $query->where('estado_contabilletes', 'DE BAJA');
    }

    public function scopePorOficina($query, $oficinaId)
    {
        return $query->where('oficina_id', $oficinaId);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado_contabilletes', $estado);
    }

    // Métodos útiles
    public function isActivo(): bool
    {
        return in_array($this->estado_contabilletes, ['OPTIMO', 'BUENO', 'REGULAR']);
    }

    public function isOptimo(): bool
    {
        return $this->estado_contabilletes === 'OPTIMO';
    }

    public function isDeBaja(): bool
    {
        return $this->estado_contabilletes === 'DE BAJA';
    }

    public function ultimoMantenimiento()
    {
        return $this->hasOne(MantenimientoContabillete::class, 'contabillete_id')
            ->latest('fecha_mantenimiento');
    }

    // Accessor para mostrar estado en español
    public function getEstadoTextoAttribute(): string
    {
        $estados = [
            'OPTIMO' => 'Óptimo',
            'BUENO' => 'Bueno',
            'REGULAR' => 'Regular',
            'DEFICIENTE' => 'Deficiente',
            'DE BAJA' => 'De Baja'
        ];

        return $estados[$this->estado_contabilletes] ?? $this->estado_contabilletes;
    }
}
