<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Responsable;
use Illuminate\Http\Request;

class ResponsableController extends Controller
{
    public function index(Request $request)
    {
        $query = Responsable::query();

        if ($request->filled('search')) {
            $query->where('nombre_responsable', 'LIKE', '%' . $request->search . '%');
        }

        $responsables = $query->get();
        $allResponsables = Responsable::orderBy('nombre_responsable')->get();
        
        return view('admin.responsables.index', compact('responsables', 'allResponsables'));
    }

    public function create()
    {
        return view('admin.responsables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_responsable' => 'required|string|max:100|unique:responsables,nombre_responsable',
        ]);

        Responsable::create($request->all());

        return redirect()->route('responsables.index')
            ->with('success', 'Responsable creado correctamente.');
    }

    public function show(Responsable $responsable)
    {
        return view('admin.responsables.show', compact('responsable'));
    }

    public function edit(Responsable $responsable)
    {
        return view('admin.responsables.edit', compact('responsable'));
    }

    public function update(Request $request, Responsable $responsable)
    {
        $request->validate([
            'nombre_responsable' => 'required|string|max:100|unique:responsables,nombre_responsable,' . $responsable->id,
        ]);

        $responsable->update($request->all());

        return redirect()->route('responsables.index')
            ->with('success', 'Responsable actualizado correctamente.');
    }

    public function destroy(Responsable $responsable)
    {
        $responsable->delete();

        return redirect()->route('responsables.index')
            ->with('success', 'Responsable eliminado correctamente.');
    }
}