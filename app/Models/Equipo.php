<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipos';
    protected $casts = [
        'fecha_adquisicion' => 'date',
        'fecha_mantenimiento' => 'date',
    ];

    protected $fillable = [
        'nombre_dispositivo',
        'numero_serie',
        'direccion_ip',
        'fecha_adquisicion',
        'estado_equipo',
        'fecha_mantenimiento',
        'observacion',
        'copias_seguridad',
        'depreciacion_anual',
        'programas_instalados',
        'licencias',
        'vpn_cusco',
        'vpn_abancay',
        'antivirus',
        'oficina_id',
        'tipoequipo_id',
        'hardware_id',
        'modelo_id',
        'sistemaoperativo_id',
        'responsable_id',
    ];

    // Relaciones
    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }

    public function tipoequipo()
    {
        return $this->belongsTo(Tipoequipo::class);
    }

    public function hardware()
    {
        return $this->belongsTo(Hardware::class);
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    public function sistemaoperativo()
    {
        return $this->belongsTo(Sistemaoperativo::class);
    }

    public function responsable()
    {
        return $this->belongsTo(Responsable::class);
    }
    // Relación con mantenimientos
    public function mantenimientos()
    {
        return $this->hasMany(MantenimientoPc::class)->orderBy('fecha_mantenimiento', 'desc');
    }

    // Último mantenimiento
    public function ultimoMantenimiento()
    {
        return $this->hasOne(MantenimientoPc::class)->latestOfMany('fecha_mantenimiento');
    }

    // Accesor para obtener el nombre completo del hardware
    public function getHardwareCompletoAttribute()
    {
        if (!$this->hardware) return 'N/A';
        
        return sprintf("%s | %dGB RAM | %dGB %s",
            $this->hardware->procesador ?? 'N/A',
            $this->hardware->ram_gb ?? 0,
            $this->hardware->almacenamiento_gb ?? 0,
            $this->hardware->tipo_almacenamiento ?? 'N/A'
        );
    }

    // Accesor para obtener el SO completo
    public function getSistemaOperativoCompletoAttribute()
    {
        if (!$this->sistemaoperativo) return 'N/A';
        
        return sprintf("%s %s (%s)",
            $this->sistemaoperativo->nombre_so ?? '',
            $this->sistemaoperativo->edicion ?? '',
            $this->sistemaoperativo->version ?? ''
        );
    }

    // Accesor para la marca a través del modelo
    public function getMarcaAttribute()
    {
        return $this->modelo->marca->nombre_marca ?? 'N/A';
    }

    // Accesor para el nombre del modelo
    public function getNombreModeloAttribute()
    {
        return $this->modelo->nombre_modelo ?? 'N/A';
    }

    // Accesor para el tipo de equipo
    public function getTipoEquipoNombreAttribute()
    {
        return $this->tipoequipo->nombre_tipo ?? 'N/A';
    }
}
