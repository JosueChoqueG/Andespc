<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipos'; // Nombre exacto de la tabla en la BD
    protected $fillable = [
        'nombre_dispositivo', 'numero_serie', 'direccion_ip', 'fecha_adquisicion',
        'estado_equipo', 'fecha_mantenimiento', 'observacion', 'copias_seguridad',
        'depreciacion_anual', 'programas_instalados', 'licencias', 'vpn_cusco',
        'vpn_abancay', 'antivirus', 'id_oficina', 'id_tipo', 'id_hardware',
        'id_modelo', 'id_so', 'id_responsable'
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
        'fecha_mantenimiento' => 'date',
    ];

    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }

    public function tipo()
    {
        return $this->belongsTo(TipoEquipo::class, 'id_tipo');
    }

    public function hardware()
    {
        return $this->belongsTo(Hardware::class);
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    public function sistemaOperativo()
    {
        return $this->belongsTo(SistemaOperativo::class, 'id_so');
    }

    public function responsable()
    {
        return $this->belongsTo(Responsable::class);
    }
}
