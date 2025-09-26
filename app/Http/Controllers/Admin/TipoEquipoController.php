<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tipoequipo;
use Illuminate\Http\Request;

class TipoequipoController extends Controller
{
    public function index()
    {
        $tipoequipos = Tipoequipo::all();
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

        Tipoequipo::create($request->all());

        return redirect()->route('tipoequipos.index')
            ->with('success', 'Tipo de equipo creado correctamente.');
    }

    public function show(Tipoequipo $tipoequipo)
    {
        return view('admin.tipoequipos.show', compact('tipoequipo'));
    }

    public function edit(Tipoequipo $tipoequipo)
    {
        return view('admin.tipoequipos.edit', compact('tipoequipo'));
    }

    public function update(Request $request, Tipoequipo $tipoequipo)
{
    $request->validate([
        'nombre_tipo' => 'required|string|max:50|unique:tipoequipos,nombre_tipo,' . $tipoequipo->id,
    ]);

    $tipoequipo->update($request->all());

    return redirect()->route('tipoequipos.index')
        ->with('success', 'Tipo de equipo actualizado correctamente.');
}

    public function destroy(Tipoequipo $tipoequipo)
    {
        $tipoequipo->delete();

        return redirect()->route('tipoequipos.index')
            ->with('success', 'Tipo de equipo eliminado correctamente.');
    }
}
