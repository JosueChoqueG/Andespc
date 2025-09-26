<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SistemaOperativo;
use Illuminate\Http\Request;

class SistemaoperativoController extends Controller
{
    public function index()
    {
        $sistemaoperativos = Sistemaoperativo::all();
        return view('admin.sistemaoperativos.index', compact('sistemaoperativos'));
    }

    public function create()
    {
        return view('admin.sistemaoperativos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_so' => 'required|string|max:50',
            'edicion' => 'nullable|string|max:50',
            'version' => 'nullable|string|max:20',
        ]);

        Sistemaoperativo::create($request->all());

        return redirect()->route('sistemaoperativos.index')
            ->with('success', 'Sistema operativo creado correctamente.');
    }

    public function show(Sistemaoperativo $sistemaoperativos)
    {
        return view('admin.sistemaoperativos.show', compact('sistemaoperativo'));
    }

    public function edit(SistemaOperativo $sistemaoperativos)
    {
        return view('admin.sistemaoperativos.edit', compact('sistemaoperativo'));
    }

    public function update(Request $request, Sistemaoperativo $sistemaoperativos)
    {
        $request->validate([
            'nombre_so' => 'required|string|max:50',
            'edicion' => 'nullable|string|max:50',
            'version' => 'nullable|string|max:20',
        ]);

        $sistemaoperativos->update($request->all());

        return redirect()->route('sistemaoperativos.index')
            ->with('success', 'Sistema operativo actualizado correctamente.');
    }

    public function destroy(Sistemaoperativo $sistemaoperativos)
    {
        $sistemaoperativos->delete();

        return redirect()->route('sistemaoperativos.index')
            ->with('success', 'Sistema operativo eliminado correctamente.');
    }
}