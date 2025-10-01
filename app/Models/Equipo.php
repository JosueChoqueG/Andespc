<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'nombre_dispositivo', 'numero_serie', 'direccion_ip', 'fecha_adquisicion',
        'estado_equipo', 'fecha_mantenimiento', 'observacion', 'copias_seguridad',
        'depreciacion_anual', 'programas_instalados', 'licencias', 'vpn_cusco',
        'vpn_abancay', 'antivirus', 'oficina_id', 'tipo_equipo_id', 'hardware_id',
        'modelo_id', 'sistema_operativo_id', 'responsable_id'
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
        'fecha_mantenimiento' => 'date',
    ];

    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'oficina_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipoequipo::class, 'tipo_equipo_id');
    }

    public function hardware()
    {
        return $this->belongsTo(Hardware::class, 'hardware_id');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'modelo_id');
    }

    public function sistemaOperativo()
    {
        return $this->belongsTo(SistemaOperativo::class, 'sistema_operativo_id');
    }

    public function responsable()
    {
        return $this->belongsTo(Responsable::class, 'responsable_id');
    }
}
