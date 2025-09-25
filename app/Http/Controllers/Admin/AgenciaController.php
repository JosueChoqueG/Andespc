<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use Illuminate\Http\Request;

class AgenciaController extends Controller
{
    public function index()
    {
        $agencias = Agencia::all();
        return view('admin.agencias.index', compact('agencias'));
    }

    public function create()
    {
        return view('admin.agencias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo_agencia' => 'required|unique:agencias',
            'nombre_agencia' => 'required|string|max:100',
        ]);

        Agencia::create($request->all());
        return redirect()->route('agencias.index')->with('success', 'Agencia creada.');
    }

   public function edit(Agencia $agencia)
    {
        return view('admin.agencias.edit', compact('agencia'));
    }

    public function update(Request $request, Agencia $agencia)
    {
        $request->validate([
            'codigo_agencia' => 'required|unique:agencias,codigo_agencia,' . $agencia->id,
            'nombre_agencia' => 'required|string|max:100',
        ]);

        $agencia->update($request->all());
        return redirect()->route('agencias.index')->with('success', 'Agencia actualizada.');
    }

    public function destroy(Agencia $agencia)
    {
        $agencia->delete();
        return redirect()->route('agencias.index')->with('success', 'Agencia eliminada.');
    }

}
