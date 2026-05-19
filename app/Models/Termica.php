<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Termica extends Model
{
    use HasFactory;

    protected $table = 'termicas';

    protected $fillable = [
        'oficina_id',
        'tipo_termica',
        'marca_termica',
        'modelo_termica',
        'fecha_adquisicion',
        'serie_termica',
        'responsable_id',
        'tipo_conexion',
        'nombre_host',
        'direccion_ip',
        'estado_termica',
        'velocidad_impresion',
        'modelo_consumible',
        'tipo_consumible',
        'cantidad_impresion',
        'capacidad_impresion'
    ];

    protected $casts = [
        'id' => 'integer',
        'oficina_id' => 'integer',
        'responsable_id' => 'integer',
        'fecha_adquisicion' => 'date',
        'cantidad_impresion' => 'integer',
        'capacidad_impresion' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Constantes para los ENUMs
    const TIPO_CONEXION = [
        'USB', 'ETHERNET', 'SERIAL', 'WI-FI', 'BLUETOOTH'
    ];

    const ESTADO_TERMICA = [
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
        return $this->hasMany(MantenimientoTermica::class, 'termica_id');
    }

    // Scopes para búsquedas comunes
    public function scopeActivos($query)
    {
        return $query->whereIn('estado_termica', ['OPTIMO', 'BUENO', 'REGULAR']);
    }

    public function scopeOperativos($query)
    {
        return $query->where('estado_termica', 'OPTIMO');
    }

    public function scopeDeBaja($query)
    {
        return $query->where('estado_termica', 'DE BAJA');
    }

    public function scopePorOficina($query, $oficinaId)
    {
        return $query->where('oficina_id', $oficinaId);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado_termica', $estado);
    }

    public function scopePorTipoConexion($query, $tipoConexion)
    {
        return $query->where('tipo_conexion', $tipoConexion);
    }

    // Métodos útiles
    public function isActiva(): bool
    {
        return in_array($this->estado_termica, ['OPTIMO', 'BUENO', 'REGULAR']);
    }

    public function isOptima(): bool
    {
        return $this->estado_termica === 'OPTIMO';
    }

    public function isDeBaja(): bool
    {
        return $this->estado_termica === 'DE BAJA';
    }

    public function getPorcentajeVidaUtil(): ?float
    {
        if (!$this->capacidad_impresion || $this->capacidad_impresion <= 0) {
            return null;
        }

        return round(($this->cantidad_impresion / $this->capacidad_impresion) * 100, 2);
    }

    public function ultimoMantenimiento()
    {
        return $this->hasOne(MantenimientoTermica::class, 'termica_id')
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

        return $estados[$this->estado_termica] ?? $this->estado_termica;
    }

    // Accessor para tipo de conexión
    public function getTipoConexionTextoAttribute(): string
    {
        $tipos = [
            'USB' => 'USB',
            'ETHERNET' => 'Ethernet',
            'SERIAL' => 'Serial',
            'WI-FI' => 'Wi-Fi',
            'BLUETOOTH' => 'Bluetooth'
        ];

        return $tipos[$this->tipo_conexion] ?? $this->tipo_conexion;
    }
}
