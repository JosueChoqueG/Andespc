<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Impresora extends Model
{
    use HasFactory;

    protected $table = 'impresoras';

    protected $fillable = [
        'oficina_id',
        'tipo_impresora',
        'marca_impresora',
        'modelo_impresora',
        'fecha_adquisicion',
        'serie_impresora',
        'responsable_id',
        'tipo_conexion',
        'nombre_host',
        'direccion_ip',
        'estado_impresora',
        'velocidad_impresion',
        'modelo_consumible',
        'tipo_consumible',
        'cantidad_impresion',
        'capacidad_impresion',
        'cantidad_escaneo'
    ];

    protected $casts = [
        'id' => 'integer',
        'oficina_id' => 'integer',
        'responsable_id' => 'integer',
        'fecha_adquisicion' => 'date',
        'cantidad_impresion' => 'integer',
        'capacidad_impresion' => 'integer',
        'cantidad_escaneo' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Constantes para los ENUMs
    const TIPO_CONEXION = [
        'USB', 'WIFI', 'ETHERNET', 'WIFI-DIRECT'
    ];

    const ESTADO_IMPRESORA = [
        'OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE', 'DE BAJA'
    ];

    // Relaciones
    public function oficina(): BelongsTo
    {
        return $this->belongsTo(Oficina::class, 'oficina_id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Resposable::class, 'responsable_id');
    }

    public function mantenimientos(): HasMany
    {
        return $this->hasMany(MantenimientoImpresora::class, 'impresora_id');
    }

    // Scopes para búsquedas comunes
    public function scopeActivos($query)
    {
        return $query->whereIn('estado_impresora', ['OPTIMO', 'BUENO', 'REGULAR']);
    }

    public function scopeOperativos($query)
    {
        return $query->where('estado_impresora', 'OPTIMO');
    }

    public function scopeDeBaja($query)
    {
        return $query->where('estado_impresora', 'DE BAJA');
    }

    public function scopePorOficina($query, $oficinaId)
    {
        return $query->where('oficina_id', $oficinaId);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado_impresora', $estado);
    }

    public function scopePorTipoConexion($query, $tipoConexion)
    {
        return $query->where('tipo_conexion', $tipoConexion);
    }

    // Métodos útiles
    public function isActiva(): bool
    {
        return in_array($this->estado_impresora, ['OPTIMO', 'BUENO', 'REGULAR']);
    }

    public function isOptima(): bool
    {
        return $this->estado_impresora === 'OPTIMO';
    }

    public function isDeBaja(): bool
    {
        return $this->estado_impresora === 'DE BAJA';
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
        return $this->hasOne(MantenimientoImpresora::class, 'impresora_id')
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

        return $estados[$this->estado_impresora] ?? $this->estado_impresora;
    }

    // Accessor para tipo de conexión
    public function getTipoConexionTextoAttribute(): string
    {
        $tipos = [
            'USB' => 'USB',
            'WIFI' => 'WiFi',
            'ETHERNET' => 'Ethernet',
            'WIFI-DIRECT' => 'WiFi Direct'
        ];

        return $tipos[$this->tipo_conexion] ?? $this->tipo_conexion;
    }
}