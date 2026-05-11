<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MantenimientoImpresora extends Model
{
    use HasFactory;

    protected $table = 'mantenimiento_impresora';

    protected $fillable = [
        'impresora_id',
        'fecha_mantenimiento',
        'tipo_mantenimiento',
        'descripcion_mantenimiento',
        'observacion_mantenimiento',
        'fecha_fallas',
        'fallas_detectadas',
        'fallas_solucion',
        'observacion_general'
    ];

    protected $casts = [
        'id' => 'integer',
        'impresora_id' => 'integer',
        'fecha_mantenimiento' => 'date',
        'fecha_fallas' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Constantes para los ENUMs
    const TIPO_MANTENIMIENTO = [
        'Preventivo', 'Correctivo'
    ];

    // Relaciones
    public function impresora(): BelongsTo
    {
        return $this->belongsTo(Impresora::class, 'impresora_id');
    }

    // Scopes
    public function scopePreventivos($query)
    {
        return $query->where('tipo_mantenimiento', 'Preventivo');
    }

    public function scopeCorrectivos($query)
    {
        return $query->where('tipo_mantenimiento', 'Correctivo');
    }

    public function scopePorFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_mantenimiento', [$fechaInicio, $fechaFin]);
    }

    public function scopeUltimosMeses($query, $meses = 6)
    {
        return $query->where('fecha_mantenimiento', '>=', now()->subMonths($meses));
    }

    public function scopeConFallas($query)
    {
        return $query->whereNotNull('fallas_detectadas')
            ->where('fallas_detectadas', '!=', '');
    }

    // Métodos útiles
    public function esPreventivo(): bool
    {
        return $this->tipo_mantenimiento === 'Preventivo';
    }

    public function esCorrectivo(): bool
    {
        return $this->tipo_mantenimiento === 'Correctivo';
    }

    public function tieneFallas(): bool
    {
        return !empty($this->fallas_detectadas);
    }

    public function tieneSolucion(): bool
    {
        return !empty($this->fallas_solucion);
    }

    // Accesor para obtener descripción como array
    public function getDescripcionArrayAttribute()
    {
        return array_filter(explode("\n", $this->descripcion_mantenimiento));
    }

    // Accesor para obtener fallas como array
    public function getFallasArrayAttribute()
    {
        return array_filter(explode("\n", $this->fallas_detectadas ?? ''));
    }

    // Accesor para obtener el nombre del técnico
    public function getTecnicoNombreAttribute()
    {
        // Si tuviera user_id, podríamos usar $this->user->name
        return 'Josue Choque Gomez'; // Valor por defecto según requerimiento anterior
    }

    public function getTipoMantenimientoColorAttribute(): string
    {
        return $this->esPreventivo() ? 'success' : 'warning';
    }

    public function getTipoMantenimientoBadgeAttribute(): string
    {
        $badges = [
            'Preventivo' => 'bg-success',
            'Correctivo' => 'bg-danger'
        ];

        return $badges[$this->tipo_mantenimiento] ?? 'bg-secondary';
    }
}