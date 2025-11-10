<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::all();
        return view('admin.marcas.index', compact('marcas'));
    }

    public function create()
    {
        return view('admin.marcas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_marca' => 'required|string|max:50|unique:marcas,nombre_marca',
        ]);

        Marca::create($request->all());

        return redirect()->route('marcas.index')
            ->with('success', 'Marca creada correctamente.');
    }

    public function show(Marca $marca)
    {
        return view('admin.marcas.show', compact('marca'));
    }

    public function edit(Marca $marca)
    {
        return view('admin.marcas.edit', compact('marca'));
    }

    public function update(Request $request, Marca $marca)
    {
        $request->validate([
            'nombre_marca' => 'required|string|max:50|unique:marcas,nombre_marca,' . $marca->id,
        ]);

        $marca->update($request->all());

        return redirect()->route('marcas.index')
            ->with('success', 'Marca actualizada correctamente.');
    }

    public function destroy(Marca $marca)
    {
        $marca->delete();

        return redirect()->route('marcas.index')
            ->with('success', 'Marca eliminada correctamente.');
    }
}