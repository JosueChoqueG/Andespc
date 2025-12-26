<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Oficina;
use App\Models\Tipoequipo;
use App\Models\Hardware;
use App\Models\Modelo;
use App\Models\Sistemaoperativo;
use App\Models\Responsable;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::with(['oficina', 'tipoequipo', 'hardware', 'modelo', 'sistemaoperativo', 'responsable'])->paginate(13);
        return view('admin.equipos.index', compact('equipos'));
    }

    public function create()
    {
        $oficinas = Oficina::all();
        $tipos = Tipoequipo::all();
        $hardwares = Hardware::all();
        $modelos = Modelo::all();
        $sistemas = Sistemaoperativo::all();
        $responsables = Responsable::all();

        return view('admin.equipos.create', compact('oficinas','tipos','hardwares','modelos','sistemas','responsables'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'nombre_dispositivo' => 'required|string|max:100',
            'numero_serie' => 'nullable|string|max:100|unique:equipos',
            'direccion_ip' => 'nullable|ip',
            'estado_equipo' => 'required|in:Activo,Inactivo,Baja',
            'oficina_id' => 'required|exists:oficinas,id',
            'tipoequipo_id' => 'required|exists:tipoequipos,id',
            'hardware_id' => 'required|exists:hardwares,id',
            'modelo_id' => 'required|exists:modelos,id',
            'sistemaoperativo_id' => 'required|exists:sistemaoperativos,id',
            'responsable_id' => 'nullable|exists:responsables,id',
        ]);

        Equipo::create($request->all());

        return redirect()->route('equipos.index')->with('success','Equipo creado correctamente.');
    }

    public function show(Equipo $equipo)
    {
        return view('admin.equipos.show', compact('equipo'));
    }

    public function edit(Equipo $equipo)
    {
        $oficinas = Oficina::all();
        $tipoequipos = Tipoequipo::all();
        $hardwares = Hardware::all();
        $modelos = Modelo::all();
        $sistemas = Sistemaoperativo::all();
        $responsables = Responsable::all();

        return view('admin.equipos.edit', compact('equipo','oficinas','tipoequipos','hardwares','modelos','sistemas','responsables'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $request->validate([
            'nombre_dispositivo' => 'required|string|max:100',
            'numero_serie' => 'nullable|string|max:100|unique:equipos,numero_serie,' . $equipo->id,
            'direccion_ip' => 'nullable|ip',
            'estado_equipo' => 'required|in:Activo,Inactivo,Baja',
            'oficina_id' => 'required|exists:oficinas,id',
            'tipoequipo_id' => 'required|exists:tipoequipos,id',
            'hardware_id' => 'required|exists:hardwares,id',
            'modelo_id' => 'required|exists:modelos,id',
            'sistemaoperativo_id' => 'required|exists:sistemaoperativos,id',
            'responsable_id' => 'nullable|exists:responsables,id',
        ]);

        $equipo->update($request->all());

        return redirect()->route('equipos.index')->with('success','Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return redirect()->route('equipos.index')->with('success','Equipo eliminado correctamente.');
    }
}
