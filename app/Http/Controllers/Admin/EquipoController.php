<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Oficina;
use App\Models\Agencia;
use App\Models\TipoEquipo;
use App\Models\Hardware;
use App\Models\Modelo;
use App\Models\SistemaOperativo;
use App\Models\Responsable;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipo::with([
            'oficina',
            'oficina.agencia',
            'tipoEquipo',
            'hardware',
            'modelo.marca',
            'sistemaOperativo',
            'responsable'
        ]);

        // 🔎 Buscar por nombre del dispositivo
        if ($request->filled('search')) {
            $query->where('nombre_dispositivo', 'like', '%' . $request->search . '%');
        }

        // 🔎 Buscar por número de serie
        if ($request->filled('serie')) {
            $query->where('numero_serie', 'like', '%' . $request->serie . '%');
        }

        // 🏢 Filtrar por oficina
        if ($request->filled('oficina')) {
            $query->where('oficina_id', $request->oficina);
        }
        // Filtrar por agencia
        if ($request->filled('agencia')) {
            $query->whereHas('oficina', function ($q) use ($request) {
                $q->where('agencia_id', $request->agencia);
            });
        }

        $equipos = $query->paginate(12)->withQueryString();

        // Necesario para el select del filtro
        $oficinas = Oficina::all();
        $agencias = Agencia::all();

        if ($request->ajax()) {
            return view('admin.equipos.partials.table', compact('equipos'))->render();
        }

        return view('admin.equipos.index', compact('equipos', 'oficinas', 'agencias'));
    }

    public function create()
    {
        $oficinas = Oficina::all();
        $tipos = TipoEquipo::all();
        $hardwares = Hardware::all();
        $modelos = Modelo::all();
        $sistemas = SistemaOperativo::all();
        $responsables = Responsable::all();

        return view('admin.equipos.create', compact('oficinas', 'tipos', 'hardwares', 'modelos', 'sistemas', 'responsables'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre_dispositivo' => 'required|string|max:100',
            'numero_serie' => 'nullable|string|max:100|unique:equipos',
            'direccion_ip' => 'nullable|ip',
            'direccion_mac' => 'nullable|string|max:17',
            'fecha_adquisicion' => 'nullable|date',
            'estado_equipo' => 'required|in:Operativo,Operativo con Observacion,En mantenimiento,Fuera de servicio,De baja',
            'fecha_mantenimiento' => 'nullable|date',
            'oficina_id' => 'required|exists:oficinas,id',
            'tipoequipo_id' => 'required|exists:tipoequipos,id',
            'hardware_id' => 'required|exists:hardwares,id',
            'modelo_id' => 'required|exists:modelos,id',
            'sistemaoperativo_id' => 'required|exists:sistemaoperativos,id',
            'responsable_id' => 'nullable|exists:responsables,id',
            'vpn_cusco' => 'nullable|in:Sí,No',
            'vpn_abancay' => 'nullable|in:Sí,No',
            'antivirus' => 'nullable|string|max:100',
            'depreciacion_anual' => 'nullable|numeric|min:0|max:100',
            'programas_instalados' => 'nullable|string',
            'licencias' => 'nullable|string',
            'copias_seguridad' => 'nullable|string',
            'observacion' => 'nullable|string',
        ]);

        Equipo::create($request->all());

        return redirect()->route('equipos.index')->with('success', 'Equipo creado correctamente.');
    }

    public function show(Equipo $equipo)
    {
        return view('admin.equipos.show', compact('equipo'));
    }

    public function edit(Equipo $equipo)
    {
        $oficinas = Oficina::all();
        $tipoequipos = TipoEquipo::all();
        $hardwares = Hardware::all();
        $modelos = Modelo::all();
        $sistemas = SistemaOperativo::all();
        $responsables = Responsable::all();

        return view('admin.equipos.edit', compact('equipo', 'oficinas', 'tipoequipos', 'hardwares', 'modelos', 'sistemas', 'responsables'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $request->validate([
            'nombre_dispositivo' => 'required|string|max:100',
            'numero_serie' => 'nullable|string|max:100|unique:equipos,numero_serie,' . $equipo->id,
            'direccion_ip' => 'nullable|ip',
            'direccion_mac' => 'nullable|string|max:17',
            'fecha_adquisicion' => 'nullable|date',
            'estado_equipo' => 'required|in:Operativo,Operativo con Observacion,En mantenimiento,Fuera de servicio,De baja',
            'fecha_mantenimiento' => 'nullable|date',
            'oficina_id' => 'required|exists:oficinas,id',
            'tipoequipo_id' => 'required|exists:tipoequipos,id',
            'hardware_id' => 'required|exists:hardwares,id',
            'modelo_id' => 'required|exists:modelos,id',
            'sistemaoperativo_id' => 'required|exists:sistemaoperativos,id',
            'responsable_id' => 'nullable|exists:responsables,id',
            'vpn_cusco' => 'nullable|in:Sí,No',
            'vpn_abancay' => 'nullable|in:Sí,No',
            'antivirus' => 'nullable|string|max:100',
            'depreciacion_anual' => 'nullable|numeric|min:0|max:100',
            'programas_instalados' => 'nullable|string',
            'licencias' => 'nullable|string',
            'copias_seguridad' => 'nullable|string',
            'observacion' => 'nullable|string',
        ]);

        $equipo->update($request->all());

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado correctamente.');
    }
}
