<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\Oficina;
use Illuminate\Http\Request;

class OficinaController extends Controller
{
    public function index()
    {
        $oficinas = Oficina::with('agencia')->get();
        return view('admin.oficinas.index', compact('oficinas'));
    }

    public function create()
    {
        $agencias = Agencia::all();
        return view('admin.oficinas.create', compact('agencias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_oficina' => 'required|string|max:100',
            'agencia_id' => 'required|exists:agencias,id',
        ]);

        Oficina::create($request->only(['nombre_oficina', 'agencia_id']));

        return redirect()->route('oficinas.index')
            ->with('success', 'Oficina creada correctamente.');
    }

    public function show(Oficina $oficina)
    {
        $oficina->load('agencia');
        return view('admin.oficinas.show', compact('oficina'));
    }

    public function edit(Oficina $oficina)
    {
        $agencias = Agencia::all();
        return view('admin.oficinas.edit', compact('oficina', 'agencias'));
    }

    public function update(Request $request, Oficina $oficina)
    {
        $request->validate([
            'nombre_oficina' => 'required|string|max:100',
            'agencia_id' => 'required|exists:agencias,id',
        ]);

        $oficina->update($request->only(['nombre_oficina', 'agencia_id']));

        return redirect()->route('oficinas.index')
            ->with('success', 'Oficina actualizada correctamente.');
    }

    public function destroy(Oficina $oficina)
    {
        $oficina->delete();

        return redirect()->route('oficinas.index')
            ->with('success', 'Oficina eliminada correctamente.');
    }
}
