<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Impresora;
use App\Models\Oficina;
use Illuminate\Http\Request;

class ImpresoraController extends Controller
{
    public function index(Request $request)
    {
        $query = Impresora::with('oficina.agencia');

        if ($request->filled('serie')) {
            $query->where('serie', 'like', '%' . $request->serie . '%');
        }

        $impresoras = $query->paginate(10)->withQueryString();

        return view('admin.impresoras.index', compact('impresoras'));
    }

    public function create()
    {
        $oficinas = Oficina::with('agencia')->get();
        return view('admin.impresoras.create', compact('oficinas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'oficina_id' => 'required',
            'marca' => 'required',
            'modelo' => 'required',
            'serie' => 'required|unique:impresoras',
        ]);

        Impresora::create($request->all());

        return redirect()
            ->route('admin.impresoras.index')
            ->with('success','Impresora registrada');
    }

    public function show(Impresora $impresora)
    {
        $impresora->load('mantenimientos');
        return view('admin.impresoras.show', compact('impresora'));
    }

    public function edit(Impresora $impresora)
    {
        $oficinas = Oficina::with('agencia')->get();
        return view('admin.impresoras.edit', compact('impresora','oficinas'));
    }

    public function update(Request $request, Impresora $impresora)
    {
        $request->validate([
            'serie' => 'required|unique:impresoras,serie,' . $impresora->id
        ]);

        $impresora->update($request->all());

        return redirect()
            ->route('admin.impresoras.index')
            ->with('success','Actualizado');
    }

    public function destroy(Impresora $impresora)
    {
        $impresora->delete();
        return back()->with('success','Eliminado');
    }
}
