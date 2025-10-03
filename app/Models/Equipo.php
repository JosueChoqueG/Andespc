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
}
