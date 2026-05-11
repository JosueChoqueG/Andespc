<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoEquipo;
use Illuminate\Http\Request;

class TipoEquipoController extends Controller
{
    public function index()
    {
        $tipoequipos = TipoEquipo::all();
        return view('admin.tipoequipos.index', compact('tipoequipos'));
    }

    public function create()
    {
        return view('admin.tipoequipos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_tipo' => 'required|string|max:50|unique:tipoequipos,nombre_tipo',
        ]);

        TipoEquipo::create($request->only('nombre_tipo'));

        return redirect()->route('tipoequipos.index')
            ->with('success', 'Tipo de equipo creado correctamente.');
    }

    public function show(TipoEquipo $tipoequipo)
    {
        return view('admin.tipoequipos.show', compact('tipoequipo'));
    }

    public function edit(TipoEquipo $tipoequipo)
    {
        return view('admin.tipoequipos.edit', compact('tipoequipo'));
    }

    public function update(Request $request, TipoEquipo $tipoequipo)
    {
        $request->validate([
            'nombre_tipo' => 'required|string|max:50|unique:tipoequipos,nombre_tipo,' . $tipoequipo->id,
        ]);

        $tipoequipo->update($request->only('nombre_tipo'));

        return redirect()->route('tipoequipos.index')
            ->with('success', 'Tipo de equipo actualizado correctamente.');
    }

    public function destroy(TipoEquipo $tipoequipo)
    {
        $tipoequipo->delete();

        return redirect()->route('tipoequipos.index')
            ->with('success', 'Tipo de equipo eliminado correctamente.');
    }
}
