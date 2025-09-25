<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hardware;
use Illuminate\Http\Request;

class HardwareController extends Controller
{
    /**
     * Listar todos los registros.
     */
    public function index()
    {
        $hardwares = Hardware::all();
        return view('admin.hardwares.index', compact('hardwares'));
    }

    /**
     * Mostrar formulario para crear.
     */
    public function create()
    {
        return view('admin.hardwares.create');
    }

    /**
     * Guardar nuevo hardware.
     */
    public function store(Request $request)
    {
        $request->validate([
            'procesador' => 'nullable|string|max:100',
            'ram_gb' => 'required|integer|min:1',
            'almacenamiento_gb' => 'required|integer|min:1',
            'tipo_almacenamiento' => 'required|in:HDD,SSD,NVMe',
        ], [
            'ram_gb.required' => 'La memoria RAM es obligatoria.',
            'ram_gb.integer' => 'La RAM debe ser un número entero (GB).',
            'almacenamiento_gb.required' => 'La capacidad de almacenamiento es obligatoria.',
            'tipo_almacenamiento.in' => 'El tipo debe ser HDD, SSD o NVMe.'
        ]);

        Hardware::create($request->all());

        return redirect()->route('hardwares.index')
            ->with('success', 'Hardware creado correctamente.');
    }

    /**
     * Mostrar detalles.
     */
    public function show(Hardware $hardware)
    {
        return view('admin.hardwares.show', compact('hardware'));
    }

    /**
     * Mostrar formulario para editar.
     */
    public function edit(Hardware $hardware)
    {
        return view('admin.hardwares.edit', compact('hardware'));
    }

    /**
     * Actualizar registro.
     */
    public function update(Request $request, Hardware $hardware)
    {
        $request->validate([
            'procesador' => 'nullable|string|max:100',
            'ram_gb' => 'required|integer|min:1',
            'almacenamiento_gb' => 'required|integer|min:1',
            'tipo_almacenamiento' => 'required|in:HDD,SSD,NVMe',
        ]);

        $hardware->update($request->all());

        return redirect()->route('hardwares.index')
            ->with('success', 'Hardware actualizado correctamente.');
    }

    /**
     * Eliminar registro.
     */
    public function destroy(Hardware $hardware)
    {
        $hardware->delete();

        return redirect()->route('hardwares.index')
            ->with('success', 'Hardware eliminado correctamente.');
    }
}