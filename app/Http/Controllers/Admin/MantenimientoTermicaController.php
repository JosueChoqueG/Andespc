<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Termica;
use App\Models\MantenimientoTermica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class MantenimientoTermicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MantenimientoTermica::with('termica');

        // Filtros
        if ($request->has('termica_id') && $request->filled('termica_id')) {
            $query->where('termica_id', $request->termica_id);
        }

        if ($request->has('tipo_mantenimiento') && $request->filled('tipo_mantenimiento')) {
            $query->where('tipo_mantenimiento', $request->tipo_mantenimiento);
        }

        if ($request->has('fecha_inicio') && $request->filled('fecha_inicio') && $request->has('fecha_fin') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_mantenimiento', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $mantenimientos = $query->orderBy('fecha_mantenimiento', 'desc')
            ->paginate(15);

        $termicas = Termica::all();

        return view('admin.mantenimientos-termica.index', compact('mantenimientos', 'termicas'));
    }

    /**
     * Show the form for creating a new maintenance record.
     */
    public function create(Termica $termica)
    {
        $ultimoMantenimiento = $termica->mantenimientos()->latest()->first();
        $tecnico = \Illuminate\Support\Facades\Auth::user()->name ?? 'Josue Choque Gomez';
        
        return view('admin.mantenimientos-termica.create', compact('termica', 'ultimoMantenimiento', 'tecnico'));
    }

    /**
     * Store a newly created maintenance record.
     */
    public function store(Request $request, Termica $termica)
    {
        $validator = Validator::make($request->all(), [
            'fecha_mantenimiento' => 'required|date',
            'tipo_mantenimiento' => 'required|in:Preventivo,Correctivo',
            'descripcion_mantenimiento' => 'required|string',
            'observacion_mantenimiento' => 'nullable|string',
            'fecha_fallas' => 'nullable|date',
            'fallas_detectadas' => 'nullable|string',
            'fallas_solucion' => 'nullable|string',
            'observacion_general' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['termica_id'] = $termica->id;

        $mantenimiento = MantenimientoTermica::create($data);

        // Actualizar estado de la impresora si se detectaron fallas
        $termica->update([
            'estado_termica' => $request->filled('fallas_detectadas') ? 'REGULAR' : $termica->estado_termica
        ]);

        if ($request->has('generar_hoja_vida')) {
            return redirect()->route('admin.termicas.hoja-vida-mantenimiento', $mantenimiento);
        }

        return redirect()->route('admin.termicas.show', $termica->id)
            ->with('success', 'Mantenimiento registrado correctamente');
    }

    /**
     * Generar hoja de vida basada en un mantenimiento específico (Vista HTML)
     */
    public function generarHojaVida(MantenimientoTermica $mantenimiento)
    {
        $termica = $mantenimiento->termica;
        $tecnico = $mantenimiento->tecnico_nombre;
        
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
            'mantenimiento', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
    }

    /**
     * Descargar hoja de vida de mantenimiento específico en PDF
     */
    public function descargarHojaVidaMantenimientoPDF(MantenimientoTermica $mantenimiento)
    {
        $termica = $mantenimiento->termica;
        $tecnico = $mantenimiento->tecnico_nombre;
        
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
            'mantenimiento', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Hoja-Vida-Termica-{$termica->serie_termica}-" . $mantenimiento->fecha_mantenimiento->format('Y-m-d') . ".pdf");
    }

    /**
     * Display the specified maintenance record.
     */
    public function show($id)
    {
        $mantenimiento = MantenimientoTermica::with('termica')
            ->findOrFail($id);

        return view('admin.mantenimientos-termica.show', compact('mantenimiento'));
    }

    /**
     * Show the form for editing the specified maintenance record.
     */
    public function edit($id)
    {
        $mantenimiento = MantenimientoTermica::with('termica')
            ->findOrFail($id);
        
        $termicas = Termica::all();

        return view('admin.mantenimientos-termica.edit', compact('mantenimiento', 'termicas'));
    }

    /**
     * Update the specified maintenance record.
     */
    public function update(Request $request, $id)
    {
        $mantenimiento = MantenimientoTermica::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'termica_id' => 'required|exists:termicas,id',
            'fecha_mantenimiento' => 'required|date',
            'tipo_mantenimiento' => 'required|in:Preventivo,Correctivo',
            'descripcion_mantenimiento' => 'required|string',
            'observacion_mantenimiento' => 'nullable|string',
            'fecha_fallas' => 'nullable|date',
            'fallas_detectadas' => 'nullable|string',
            'fallas_solucion' => 'nullable|string',
            'observacion_general' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mantenimiento->update($request->all());

        return redirect()->route('admin.termicas.show', $mantenimiento->termica_id)
            ->with('success', 'Mantenimiento actualizado correctamente');
    }

    /**
     * Remove the specified maintenance record.
     */
    public function destroy($id)
    {
        $mantenimiento = MantenimientoTermica::findOrFail($id);
        $termicaId = $mantenimiento->termica_id;
        
        $mantenimiento->delete();

        return redirect()->route('admin.termicas.show', $termicaId)
            ->with('success', 'Mantenimiento eliminado correctamente');
    }

    /**
     * Get maintenance history for a specific printer.
     */
    public function historial($termicaId)
    {
        $termica = Termica::findOrFail($termicaId);
        
        $mantenimientos = MantenimientoTermica::where('termica_id', $termicaId)
            ->orderBy('fecha_mantenimiento', 'desc')
            ->get();

        $estadisticas = [
            'total' => $mantenimientos->count(),
            'preventivos' => $mantenimientos->where('tipo_mantenimiento', 'Preventivo')->count(),
            'correctivos' => $mantenimientos->where('tipo_mantenimiento', 'Correctivo')->count(),
            'con_fallas' => $mantenimientos->whereNotNull('fallas_detectadas')->where('fallas_detectadas', '!=', '')->count(),
            'ultimo_mantenimiento' => $mantenimientos->first(),
        ];

        return view('admin.mantenimientos-termica.historial', compact('termica', 'mantenimientos', 'estadisticas'));
    }
}
