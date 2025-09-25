<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\Oficina;
use Illuminate\Http\Request;

class OficinaController extends Controller
{
    /**
     * Listar todas las oficinas con su agencia.
     */
    public function index()
    {
        $oficinas = Oficina::with('agencia')->get();
        return view('admin.oficinas.index', compact('oficinas'));
    }

    /**
     * Mostrar formulario para crear.
     */
    public function create()
    {
        $agencias = Agencia::all();
        return view('admin.oficinas.create', compact('agencias'));
    }

    /**
     * Guardar nueva oficina.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_oficina' => 'required|string|max:100',
            'ubicacion_equipo' => 'nullable|string|max:255',
            'id_agencia' => 'required|exists:agencias,id_agencia',
        ]);

        Oficina::create($request->all());

        return redirect()->route('oficinas.index')
            ->with('success', 'Oficina creada correctamente.');
    }

    /**
     * Mostrar detalles.
     */
    public function show(Oficina $oficina)
    {
        $oficina->load('agencia');
        return view('admin.oficinas.show', compact('oficina'));
    }

    /**
     * Mostrar formulario para editar.
     */
    public function edit(Oficina $oficina)
    {
        $agencias = Agencia::all();
        return view('admin.oficinas.edit', compact('oficina', 'agencias'));
    }

    /**
     * Actualizar oficina.
     */
    public function update(Request $request, Oficina $oficina)
    {
        $request->validate([
            'nombre_oficina' => 'required|string|max:100',
            'ubicacion_equipo' => 'nullable|string|max:255',
            'id_agencia' => 'required|exists:agencia,id_agencia',
        ]);

        $oficina->update($request->all());

        return redirect()->route('oficinas.index')
            ->with('success', 'Oficina actualizada correctamente.');
    }

    /**
     * Eliminar oficina.
     */
    public function destroy(Oficina $oficina)
    {
        $oficina->delete();

        return redirect()->route('oficinas.index')
            ->with('success', 'Oficina eliminada correctamente.');
    }
}
