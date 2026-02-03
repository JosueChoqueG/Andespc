<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioInternet extends Model
{
    use HasFactory;

    protected $table = 'servicios_internet';

    protected $fillable = [
    'oficina_id','direccion','coordenada','megas_contratado',
    'tipo_instalacion','nombre_proveedor','telefono_proveedor',
    'contrasena_router','nombre_wifi','contrasena_wifi','direccion_ip'
    ];

    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }
}