<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SistemaOperativo;
use Illuminate\Http\Request;

class SistemaOperativoController extends Controller
{
    public function index()
    {
        $sistemas = SistemaOperativo::all();
        return view('admin.sistemas.index', compact('sistemas'));
    }

    public function create()
    {
        return view('admin.sistemas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_so' => 'required|string|max:50',
            'edicion' => 'nullable|string|max:50',
            'version' => 'nullable|string|max:20',
        ]);

        SistemaOperativo::create($request->all());

        return redirect()->route('sistemas.index')
            ->with('success', 'Sistema operativo creado correctamente.');
    }

    public function show(SistemaOperativo $sistema)
    {
        return view('admin.sistemas.show', compact('sistema'));
    }

    public function edit(SistemaOperativo $sistema)
    {
        return view('admin.sistemas.edit', compact('sistema'));
    }

    public function update(Request $request, SistemaOperativo $sistema)
    {
        $request->validate([
            'nombre_so' => 'required|string|max:50',
            'edicion' => 'nullable|string|max:50',
            'version' => 'nullable|string|max:20',
        ]);

        $sistema->update($request->all());

        return redirect()->route('sistemas.index')
            ->with('success', 'Sistema operativo actualizado correctamente.');
    }

    public function destroy(SistemaOperativo $sistema)
    {
        $sistema->delete();

        return redirect()->route('sistemas.index')
            ->with('success', 'Sistema operativo eliminado correctamente.');
    }
}