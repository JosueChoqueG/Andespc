<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Oficina;
use App\Models\Tipoequipo;
use App\Models\Modelo;
use App\Models\Hardware;
use App\Models\SistemaOperativo;
use App\Models\Responsable;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function create()
    {
        return view('admin.equipos.create', [
            'oficinas' => Oficina::with('agencia')->get(),
            'tipos' => Tipoequipo::all(),
            'modelos' => Modelo::with('marca')->get(),
            'hardwares' => Hardware::all(),
            'sistemas' => SistemaOperativo::all(),
            'responsables' => Responsable::all(),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre_dispositivo'   => 'required|string|max:100',
            'numero_serie'         => 'nullable|string|max:100',
            'direccion_ip'         => 'nullable|string|max:45',
            'fecha_adquisicion'    => 'nullable|date',
            'estado_equipo'        => 'required|in:Activo,Inactivo,Baja',
            'fecha_mantenimiento'  => 'nullable|date',
            'observacion'          => 'nullable|string',
            'copias_seguridad'     => 'nullable|string',
            'depreciacion_anual'   => 'nullable|numeric',
            'programas_instalados' => 'nullable|string',
            'licencias'            => 'nullable|string',
            'vpn_cusco'            => 'required|in:Sí,No',
            'vpn_abancay'          => 'required|in:Sí,No',
            'antivirus'            => 'nullable|string|max:100',
            'oficina_id'           => 'required|exists:oficinas,id',
            'tipo_equipo_id'       => 'required|exists:tipoequipos,id',
            'hardware_id'          => 'required|exists:hardwares,id',
            'modelo_id'            => 'required|exists:modelos,id',
            'sistema_operativo_id' => 'required|exists:sistemas_operativos,id',
            'responsable_id'       => 'nullable|exists:responsables,id',
        ]);

        Equipo::create($request->all());

        return redirect()->route('equipos.index')
                        ->with('success', 'Equipo creado correctamente.');
    }


    public function edit(Equipo $equipo)
    {
        return view('admin.equipos.edit', [
            'equipo' => $equipo,
            'oficinas' => Oficina::with('agencia')->get(),
            'tipos' => Tipoequipo::all(),
            'modelos' => Modelo::with('marca')->get(),
            'hardwares' => Hardware::all(),
            'sistemas' => SistemaOperativo::all(),
            'responsables' => Responsable::all(),
        ]);
    }

    public function index()
    {
        $equipos = Equipo::with([
            'oficina.agencia',
            'tipo',
            'modelo.marca',
            'hardware',
            'sistemaOperativo',
            'responsable'
        ])->get();

        return view('admin.equipos.index', compact('equipos'));
    }

    public function show(Equipo $equipo)
    {
        $equipo->load([
            'oficina.agencia',
            'tipo',
            'modelo.marca',
            'hardware',
            'sistemaOperativo',
            'responsable'
        ]);

        return view('admin.equipos.show', compact('equipo'));
    }
}

