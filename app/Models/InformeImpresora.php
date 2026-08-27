<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformeImpresora extends Model
{
    use HasFactory;

    protected $table = 'informes_impresora';

    protected $fillable = [
        'impresora_id',
        'fecha_informe',
        'tecnico_nombre',
        'tipo_informe',
        'incidencias',
        'procedimientos',
        'descripcion_problema',
        'diagnostico',
        'acciones_realizadas',
        'contador_copias',
        'imagen_01_path',
        'imagen_01_caption',
        'imagen_02_path',
        'imagen_02_caption',
        'recomendaciones',
        'estado_equipo',
        'en_garantia',
        'observaciones',
    ];

    protected $casts = [
        'id'              => 'integer',
        'impresora_id'    => 'integer',
        'fecha_informe'   => 'date',
        'contador_copias' => 'integer',
        'en_garantia'     => 'boolean',
        'procedimientos'  => 'array',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    const TIPOS_INFORME = [
        'Atasco de Papel',
        'Mantenimiento',
        'Falla',
        'Revisión',
        'Otro',
    ];

    const ESTADOS_EQUIPO = [
        'OPTIMO'     => 'Óptimo',
        'BUENO'      => 'Bueno',
        'REGULAR'    => 'Regular',
        'DEFICIENTE' => 'Deficiente',
    ];

    // Relaciones
    public function impresora(): BelongsTo
    {
        return $this->belongsTo(Impresora::class, 'impresora_id');
    }

    // Accessors
    public function getEstadoTextoAttribute(): string
    {
        return self::ESTADOS_EQUIPO[$this->estado_equipo] ?? $this->estado_equipo;
    }

    public function getEstadoBadgeAttribute(): string
    {
        $badges = [
            'OPTIMO'     => 'success',
            'BUENO'      => 'primary',
            'REGULAR'    => 'warning',
            'DEFICIENTE' => 'danger',
        ];
        return $badges[$this->estado_equipo] ?? 'secondary';
    }

    public function getTipoBadgeAttribute(): string
    {
        $badges = [
            'Atasco de Papel' => 'danger',
            'Mantenimiento'   => 'success',
            'Falla'           => 'warning',
            'Revisión'        => 'info',
            'Otro'            => 'secondary',
        ];
        return $badges[$this->tipo_informe] ?? 'secondary';
    }

    // Scopes
    public function scopePorImpresora($query, $impresoraId)
    {
        return $query->where('impresora_id', $impresoraId);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_informe', $tipo);
    }
}
