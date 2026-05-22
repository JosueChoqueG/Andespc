<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contabillete;
use App\Models\MantenimientoContabillete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class MantenimientoContabilleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MantenimientoContabillete::with('contabillete');

        // Filtros
        if ($request->has('contabillete_id') && $request->filled('contabillete_id')) {
            $query->where('contabillete_id', $request->contabillete_id);
        }

        if ($request->has('tipo_mantenimiento') && $request->filled('tipo_mantenimiento')) {
            $query->where('tipo_mantenimiento', $request->tipo_mantenimiento);
        }

        if ($request->has('fecha_inicio') && $request->filled('fecha_inicio') && $request->has('fecha_fin') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_mantenimiento', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $mantenimientos = $query->orderBy('fecha_mantenimiento', 'desc')
            ->paginate(15);

        $contabilletes = Contabillete::all();

        return view('admin.mantenimientos-contabillete.index', compact('mantenimientos', 'contabilletes'));
    }

    /**
     * Show the form for creating a new maintenance record.
     */
    public function create(Contabillete $contabillete)
    {
        $ultimoMantenimiento = $contabillete->mantenimientos()->latest()->first();
        $tecnico = \Illuminate\Support\Facades\Auth::user()->name ?? 'Josue Choque Gomez';
        
        return view('admin.mantenimientos-contabillete.create', compact('contabillete', 'ultimoMantenimiento', 'tecnico'));
    }

    /**
     * Store a newly created maintenance record.
     */
    public function store(Request $request, Contabillete $contabillete)
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
        $data['contabillete_id'] = $contabillete->id;

        $mantenimiento = MantenimientoContabillete::create($data);

        // Actualizar estado de la contadora si se detectaron fallas
        $contabillete->update([
            'estado_contabilletes' => $request->filled('fallas_detectadas') ? 'REGULAR' : $contabillete->estado_contabilletes
        ]);

        if ($request->has('generar_hoja_vida')) {
            return redirect()->route('admin.contabilletes.hoja-vida-mantenimiento', $mantenimiento);
        }

        return redirect()->route('admin.contabilletes.show', $contabillete->id)
            ->with('success', 'Mantenimiento registrado correctamente');
    }

    /**
     * Generar hoja de vida basada en un mantenimiento específico (Vista HTML)
     */
    public function generarHojaVida(MantenimientoContabillete $mantenimiento)
    {
        $contabillete = $mantenimiento->contabillete;
        $tecnico = $mantenimiento->tecnico_nombre;
        
        $historialMantenimientos = $contabillete->mantenimientos()
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $fallasHistorial = $contabillete->mantenimientos()
            ->whereNotNull('fallas_detectadas')
            ->where('fallas_detectadas', '!=', '')
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();

        return view('admin.contabilletes.hoja-vida', compact(
            'contabillete', 
            'mantenimiento', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
    }

    /**
     * Descargar hoja de vida de mantenimiento específico en PDF
     */
    public function descargarHojaVidaMantenimientoPDF(MantenimientoContabillete $mantenimiento)
    {
        $contabillete = $mantenimiento->contabillete;
        $tecnico = $mantenimiento->tecnico_nombre;
        
        $historialMantenimientos = $contabillete->mantenimientos()
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $fallasHistorial = $contabillete->mantenimientos()
            ->whereNotNull('fallas_detectadas')
            ->where('fallas_detectadas', '!=', '')
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $pdf = Pdf::loadView('admin.contabilletes.hoja-vida-pdf', compact(
            'contabillete', 
            'mantenimiento', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Hoja-Vida-Contadora-{$contabillete->serie_contabilletes}-" . $mantenimiento->fecha_mantenimiento->format('Y-m-d') . ".pdf");
    }

    /**
     * Display the specified maintenance record.
     */
    public function show($id)
    {
        $mantenimiento = MantenimientoContabillete::with('contabillete')
            ->findOrFail($id);

        return view('admin.mantenimientos-contabillete.show', compact('mantenimiento'));
    }

    /**
     * Show the form for editing the specified maintenance record.
     */
    public function edit($id)
    {
        $mantenimiento = MantenimientoContabillete::with('contabillete')
            ->findOrFail($id);
        
        $contabilletes = Contabillete::all();

        return view('admin.mantenimientos-contabillete.edit', compact('mantenimiento', 'contabilletes'));
    }

    /**
     * Update the specified maintenance record.
     */
    public function update(Request $request, $id)
    {
        $mantenimiento = MantenimientoContabillete::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'contabillete_id' => 'required|exists:contabilletes,id',
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

        return redirect()->route('admin.contabilletes.show', $mantenimiento->contabillete_id)
            ->with('success', 'Mantenimiento actualizado correctamente');
    }

    /**
     * Remove the specified maintenance record.
     */
    public function destroy($id)
    {
        $mantenimiento = MantenimientoContabillete::findOrFail($id);
        $contabilleteId = $mantenimiento->contabillete_id;
        
        $mantenimiento->delete();

        return redirect()->route('admin.contabilletes.show', $contabilleteId)
            ->with('success', 'Mantenimiento eliminado correctamente');
    }

    /**
     * Get maintenance history for a specific bill counter.
     */
    public function historial($contabilleteId)
    {
        $contabillete = Contabillete::findOrFail($contabilleteId);
        
        $mantenimientos = MantenimientoContabillete::where('contabillete_id', $contabilleteId)
            ->orderBy('fecha_mantenimiento', 'desc')
            ->get();

        $estadisticas = [
            'total' => $mantenimientos->count(),
            'preventivos' => $mantenimientos->where('tipo_mantenimiento', 'Preventivo')->count(),
            'correctivos' => $mantenimientos->where('tipo_mantenimiento', 'Correctivo')->count(),
            'con_fallas' => $mantenimientos->whereNotNull('fallas_detectadas')->where('fallas_detectadas', '!=', '')->count(),
            'ultimo_mantenimiento' => $mantenimientos->first(),
        ];

        return view('admin.mantenimientos-contabillete.historial', compact('contabillete', 'mantenimientos', 'estadisticas'));
    }
}
