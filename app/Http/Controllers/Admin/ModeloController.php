<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    public function index()
    {
        $modelos = Modelo::with('marca')->get();
        return view('admin.modelos.index', compact('modelos'));
    }

    public function create()
    {
        $marcas = Marca::all();
        return view('admin.modelos.create', compact('marcas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_modelo' => 'required|string|max:100',
            'marca_id' => 'required|exists:marcas,id',
        ]);

        Modelo::create($request->all());
        //Modelo::create($request->only(['nombre_modelo', 'marca_id']));

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo creado correctamente.');
    }

    public function show(Modelo $modelo)
    {
        $modelo->load('marca');
        return view('admin.modelos.show', compact('modelo'));
    }

    public function edit(Modelo $modelo)
    {
        $marcas = Marca::all();
        return view('admin.modelos.edit', compact('modelo', 'marcas'));
    }

    public function update(Request $request, Modelo $modelo)
    {
        $request->validate([
            'nombre_modelo' => 'required|string|max:100',
            'marca_id' => 'required|exists:marcas,id',
        ]);
        
        $modelo->update($request->all());
        //$modelo->update($request->only(['nombre_modelo', 'marca_id']));

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo actualizado correctamente.');
    }

    public function destroy(Modelo $modelo)
    {
        $modelo->delete();

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo eliminado correctamente.');
    }
}
