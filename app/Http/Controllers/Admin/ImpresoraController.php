<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Impresora;
use App\Models\Oficina;
use App\Models\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImpresoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Impresora::with(['oficina', 'responsable']);

        // Filtros
        if ($request->has('oficina_id')) {
            $query->where('oficina_id', $request->oficina_id);
        }

        if ($request->has('estado_impresora')) {
            $query->where('estado_impresora', $request->estado_impresora);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('marca_impresora', 'LIKE', "%{$search}%")
                  ->orWhere('modelo_impresora', 'LIKE', "%{$search}%")
                  ->orWhere('serie_impresora', 'LIKE', "%{$search}%")
                  ->orWhere('nombre_host', 'LIKE', "%{$search}%");
            });
        }

        $impresoras = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $oficinas = Oficina::all();
        $responsables = Responsable::all();

        return view('admin.impresoras.index', compact('impresoras', 'oficinas', 'responsables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $oficinas = Oficina::all();
        $responsables = Responsable::all();
        
        return view('admin.impresoras.create', compact('oficinas', 'responsables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oficina_id' => 'required|exists:oficinas,id',
            'tipo_impresora' => 'required|string|max:100',
            'marca_impresora' => 'required|string|max:100',
            'modelo_impresora' => 'required|string|max:100',
            'serie_impresora' => 'required|string|max:100|unique:impresoras,serie_impresora',
            'responsable_id' => 'nullable|exists:responsables,id',
            'tipo_conexion' => 'nullable|in:USB,WIFI,ETHERNET,WIFI-DIRECT',
            'direccion_ip' => 'nullable|ip',
            'estado_impresora' => 'nullable|in:OPTIMO,BUENO,REGULAR,DEFICIENTE,DE BAJA',
            'fecha_adquisicion' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Impresora::create($request->all());

        return redirect()->route('admin.impresoras.index')
            ->with('success', 'Impresora registrada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $impresora = Impresora::with(['oficina', 'responsable', 'mantenimientos'])
            ->findOrFail($id);

        return view('admin.impresoras.show', compact('impresora'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $impresora = Impresora::findOrFail($id);
        $oficinas = Oficina::all();
        $responsables = Responsable::all();

        return view('admin.impresoras.edit', compact('impresora', 'oficinas', 'responsables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $impresora = Impresora::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'oficina_id' => 'required|exists:oficinas,id',
            'tipo_impresora' => 'required|string|max:100',
            'marca_impresora' => 'required|string|max:100',
            'modelo_impresora' => 'required|string|max:100',
            'serie_impresora' => 'required|string|max:100|unique:impresoras,serie_impresora,' . $id,
            'responsable_id' => 'nullable|exists:responsables,id',
            'tipo_conexion' => 'nullable|in:USB,WIFI,ETHERNET,WIFI-DIRECT',
            'direccion_ip' => 'nullable|ip',
            'estado_impresora' => 'nullable|in:OPTIMO,BUENO,REGULAR,DEFICIENTE,DE BAJA',
            'fecha_adquisicion' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $impresora->update($request->all());

        return redirect()->route('admin.impresoras.index')
            ->with('success', 'Impresora actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $impresora = Impresora::findOrFail($id);
        
        // Verificar si tiene mantenimientos
        if ($impresora->mantenimientos()->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar la impresora porque tiene mantenimientos asociados');
        }
        
        $impresora->delete();

        return redirect()->route('admin.impresoras.index')
            ->with('success', 'Impresora eliminada correctamente');
    }
}
