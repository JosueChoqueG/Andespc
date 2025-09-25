<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoEquipo;
use Illuminate\Http\Request;

class TipoEquipoController extends Controller
{
    public function index()
    {
        $tipos = TipoEquipo::all();
        return view('admin.tipos.index', compact('tipos'));
    }

    public function create()
    {
        return view('admin.tipos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_tipo' => 'required|string|max:50|unique:tipo_equipos,nombre_tipo',
        ]);

        TipoEquipo::create($request->all());

        return redirect()->route('tipos.index')
            ->with('success', 'Tipo de equipo creado correctamente.');
    }

    public function show(TipoEquipo $tipos)
    {
        return view('admin.tipos.show', compact('tipos'));
    }

    public function edit(TipoEquipo $tipos)
    {
        return view('admin.tipos.edit', compact('tipos'));
    }

    public function update(Request $request, TipoEquipo $tipo)
    {
        $request->validate([
            'nombre_tipo' => 'required|string|max:50|unique:tipo_equipos,nombre_tipo,' . $tipo->id_tipo . ',id_tipo',
        ]);

        $tipo->update($request->all());

        return redirect()->route('tipos.index')
            ->with('success', 'Tipo de equipo actualizado correctamente.');
    }

    public function destroy(TipoEquipo $tipo)
    {
        $tipo->delete();

        return redirect()->route('tipos.index')
            ->with('success', 'Tipo de equipo eliminado correctamente.');
    }
}
