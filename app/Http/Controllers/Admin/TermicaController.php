<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Termica;
use App\Models\Oficina;
use App\Models\Responsable;
use App\Models\Agencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class TermicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Termica::with(['oficina.agencia', 'responsable']);

        // Filtros
        if ($request->filled('oficina_id')) {
            $query->where('oficina_id', $request->oficina_id);
        }

        if ($request->filled('agencia_id')) {
            $query->whereHas('oficina', function($q) use ($request) {
                $q->where('agencia_id', $request->agencia_id);
            });
        }

        if ($request->filled('serie')) {
            $query->where('serie_termica', 'LIKE', "%{$request->serie}%");
        }

        if ($request->filled('estado_termica')) {
            $query->where('estado_termica', $request->estado_termica);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('marca_termica', 'LIKE', "%{$search}%")
                  ->orWhere('modelo_termica', 'LIKE', "%{$search}%")
                  ->orWhere('serie_termica', 'LIKE', "%{$search}%")
                  ->orWhere('nombre_host', 'LIKE', "%{$search}%");
            });
        }

        $termicas = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $oficinas = Oficina::orderBy('nombre_oficina')->get();
        $agencias = Agencia::orderBy('nombre_agencia')->get();
        $responsables = Responsable::all();

        return view('admin.termicas.index', compact('termicas', 'oficinas', 'agencias', 'responsables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $oficinas = Oficina::all();
        $responsables = Responsable::all();
        
        return view('admin.termicas.create', compact('oficinas', 'responsables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oficina_id' => 'required|exists:oficinas,id',
            'tipo_termica' => 'required|string|max:50',
            'marca_termica' => 'required|string|max:50',
            'modelo_termica' => 'required|string|max:50',
            'serie_termica' => 'required|string|max:50|unique:termicas,serie_termica',
            'responsable_id' => 'nullable|exists:responsables,id',
            'tipo_conexion' => 'nullable|in:USB,ETHERNET,SERIAL,WI-FI,BLUETOOTH',
            'direccion_ip' => 'nullable|string|max:45',
            'nombre_host' => 'nullable|string|max:50',
            'estado_termica' => 'nullable|in:OPTIMO,BUENO,REGULAR,DEFICIENTE,DE BAJA',
            'fecha_adquisicion' => 'nullable|date',
            'velocidad_impresion' => 'nullable|string|max:15',
            'modelo_consumible' => 'nullable|string|max:50',
            'tipo_consumible' => 'nullable|string|max:50',
            'cantidad_impresion' => 'nullable|integer|min:0',
            'capacidad_impresion' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Termica::create($request->all());

        return redirect()->route('admin.termicas.index')
            ->with('success', 'Impresora térmica registrada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $termica = Termica::with(['oficina', 'responsable', 'mantenimientos'])
            ->findOrFail($id);

        return view('admin.termicas.show', compact('termica'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $termica = Termica::findOrFail($id);
        $oficinas = Oficina::all();
        $responsables = Responsable::all();

        return view('admin.termicas.edit', compact('termica', 'oficinas', 'responsables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $termica = Termica::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'oficina_id' => 'required|exists:oficinas,id',
            'tipo_termica' => 'required|string|max:50',
            'marca_termica' => 'required|string|max:50',
            'modelo_termica' => 'required|string|max:50',
            'serie_termica' => 'required|string|max:50|unique:termicas,serie_termica,' . $id,
            'responsable_id' => 'nullable|exists:responsables,id',
            'tipo_conexion' => 'nullable|in:USB,ETHERNET,SERIAL,WI-FI,BLUETOOTH',
            'direccion_ip' => 'nullable|string|max:45',
            'nombre_host' => 'nullable|string|max:50',
            'estado_termica' => 'nullable|in:OPTIMO,BUENO,REGULAR,DEFICIENTE,DE BAJA',
            'fecha_adquisicion' => 'nullable|date',
            'velocidad_impresion' => 'nullable|string|max:15',
            'modelo_consumible' => 'nullable|string|max:50',
            'tipo_consumible' => 'nullable|string|max:50',
            'cantidad_impresion' => 'nullable|integer|min:0',
            'capacidad_impresion' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $termica->update($request->all());

        return redirect()->route('admin.termicas.index')
            ->with('success', 'Impresora térmica actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $termica = Termica::findOrFail($id);
        
        // Verificar si tiene mantenimientos
        if ($termica->mantenimientos()->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar la impresora térmica porque tiene mantenimientos asociados');
        }
        
        $termica->delete();

        return redirect()->route('admin.termicas.index')
            ->with('success', 'Impresora térmica eliminada correctamente');
    }

    /**
     * Generar hoja de vida de la impresora (Vista HTML)
     */
    public function hojaVida($id)
    {
        $termica = Termica::with(['oficina', 'responsable', 'mantenimientos'])
            ->findOrFail($id);
            
        $tecnico = \Illuminate\Support\Facades\Auth::user()->name ?? 'Josue Choque Gomez';
        
        $historialMantenimientos = $termica->mantenimientos()
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $fallasHistorial = $termica->mantenimientos()
            ->whereNotNull('fallas_detectadas')
            ->where('fallas_detectadas', '!=', '')
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();

        return view('admin.termicas.hoja-vida', compact(
            'termica', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
    }

    /**
     * Descargar hoja de vida en PDF
     */
    public function descargarHojaVidaPDF($id)
    {
        $termica = Termica::with(['oficina', 'responsable', 'mantenimientos'])
            ->findOrFail($id);
            
        $tecnico = \Illuminate\Support\Facades\Auth::user()->name ?? 'Josue Choque Gomez';
        
        $historialMantenimientos = $termica->mantenimientos()
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $fallasHistorial = $termica->mantenimientos()
            ->whereNotNull('fallas_detectadas')
            ->where('fallas_detectadas', '!=', '')
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $pdf = Pdf::loadView('admin.termicas.hoja-vida-pdf', compact(
            'termica', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Hoja-Vida-Termica-{$termica->serie_termica}-" . date('Y-m-d') . ".pdf");
    }
}
