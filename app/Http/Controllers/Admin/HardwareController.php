<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hardware;
use Illuminate\Http\Request;

class HardwareController extends Controller
{
    /**
     * Mostrar lista de hardware.
     */
    public function index()
    {
        $hardwares = Hardware::all();
        return view('admin.hardwares.index', compact('hardwares'));
    }

    /**
     * Mostrar formulario de creación.
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
            'tipo_almacenamiento.in' => 'El tipo debe ser HDD, SSD o NVMe.',
        ]);

        Hardware::create($request->only(['procesador', 'ram_gb', 'almacenamiento_gb', 'tipo_almacenamiento']));

        return redirect()->route('hardwares.index')
            ->with('success', 'Hardware creado correctamente.');
    }

    /**
     * Mostrar detalles de un hardware.
     */
    public function show(Hardware $hardware)
    {
        return view('admin.hardwares.show', compact('hardware'));
    }

    /**
     * Mostrar formulario para editar hardware.
     */
    public function edit(Hardware $hardware)
    {
        return view('admin.hardwares.edit', compact('hardware'));
    }

    /**
     * Actualizar un hardware existente.
     */
    public function update(Request $request, Hardware $hardware)
    {
        $request->validate([
            'procesador' => 'nullable|string|max:100',
            'ram_gb' => 'required|integer|min:1',
            'almacenamiento_gb' => 'required|integer|min:1',
            'tipo_almacenamiento' => 'required|in:HDD,SSD,NVMe',
        ]);

        $hardware->update($request->only(['procesador', 'ram_gb', 'almacenamiento_gb', 'tipo_almacenamiento']));

        return redirect()->route('hardwares.index')
            ->with('success', 'Hardware actualizado correctamente.');
    }

    /**
     * Eliminar un hardware.
     */
    public function destroy(Hardware $hardware)
    {
        $hardware->delete();

        return redirect()->route('hardwares.index')
            ->with('success', 'Hardware eliminado correctamente.');
    }
}
