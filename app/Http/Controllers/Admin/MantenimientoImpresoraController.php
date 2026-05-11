<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Impresora;
use App\Models\MantenimientoImpresora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MantenimientoImpresoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MantenimientoImpresora::with('impresora');

        // Filtros
        if ($request->has('impresora_id')) {
            $query->where('impresora_id', $request->impresora_id);
        }

        if ($request->has('tipo_mantenimiento')) {
            $query->where('tipo_mantenimiento', $request->tipo_mantenimiento);
        }

        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('fecha_mantenimiento', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $mantenimientos = $query->orderBy('fecha_mantenimiento', 'desc')
            ->paginate(15);

        $impresoras = Impresora::all();

        return view('admin.mantenimientos-impresora.index', compact('mantenimientos', 'impresoras'));
    }

    /**
     * Show the form for creating a new maintenance record.
     */
    public function create(Impresora $impresora)
    {
        $ultimoMantenimiento = $impresora->mantenimientos()->latest()->first();
        $tecnico = \Illuminate\Support\Facades\Auth::user()->name ?? 'Josue Choque Gomez';
        
        return view('admin.mantenimientos-impresora.create', compact('impresora', 'ultimoMantenimiento', 'tecnico'));
    }

    /**
     * Store a newly created maintenance record.
     */
    public function store(Request $request, Impresora $impresora)
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
        $data['impresora_id'] = $impresora->id;

        $mantenimiento = MantenimientoImpresora::create($data);

        // Actualizar estado de la impresora si se desea
        $impresora->update([
            'estado_impresora' => $request->filled('fallas_detectadas') ? 'REGULAR' : $impresora->estado_impresora
        ]);

        if ($request->has('generar_hoja_vida')) {
            return redirect()->route('admin.impresoras.hoja-vida-mantenimiento', $mantenimiento);
        }

        return redirect()->route('admin.impresoras.show', $impresora->id)
            ->with('success', 'Mantenimiento registrado correctamente');
    }

    /**
     * Generar hoja de vida basada en un mantenimiento específico (Vista HTML)
     */
    public function generarHojaVida(MantenimientoImpresora $mantenimiento)
    {
        $impresora = $mantenimiento->impresora;
        $tecnico = $mantenimiento->tecnico_nombre;
        
        $historialMantenimientos = $impresora->mantenimientos()
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $fallasHistorial = $impresora->mantenimientos()
            ->whereNotNull('fallas_detectadas')
            ->where('fallas_detectadas', '!=', '')
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();

        return view('admin.impresoras.hoja-vida', compact(
            'impresora', 
            'mantenimiento', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
    }

    /**
     * Descargar hoja de vida de mantenimiento específico en PDF
     */
    public function descargarHojaVidaMantenimientoPDF(MantenimientoImpresora $mantenimiento)
    {
        $impresora = $mantenimiento->impresora;
        $tecnico = $mantenimiento->tecnico_nombre;
        
        $historialMantenimientos = $impresora->mantenimientos()
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $fallasHistorial = $impresora->mantenimientos()
            ->whereNotNull('fallas_detectadas')
            ->where('fallas_detectadas', '!=', '')
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.impresoras.hoja-vida-pdf', compact(
            'impresora', 
            'mantenimiento', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Hoja-Vida-Impresora-{$impresora->serie_impresora}-" . $mantenimiento->fecha_mantenimiento->format('Y-m-d') . ".pdf");
    }

    /**
     * Display the specified maintenance record.
     */
    public function show($id)
    {
        $mantenimiento = MantenimientoImpresora::with('impresora')
            ->findOrFail($id);

        return view('admin.mantenimientos-impresora.show', compact('mantenimiento'));
    }

    /**
     * Show the form for editing the specified maintenance record.
     */
    public function edit($id)
    {
        $mantenimiento = MantenimientoImpresora::with('impresora')
            ->findOrFail($id);
        
        $impresoras = Impresora::all();

        return view('admin.mantenimientos-impresora.edit', compact('mantenimiento', 'impresoras'));
    }

    /**
     * Update the specified maintenance record.
     */
    public function update(Request $request, $id)
    {
        $mantenimiento = MantenimientoImpresora::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'impresora_id' => 'required|exists:impresoras,id',
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

        return redirect()->route('admin.impresoras.show', $mantenimiento->impresora_id)
            ->with('success', 'Mantenimiento actualizado correctamente');
    }

    /**
     * Remove the specified maintenance record.
     */
    public function destroy($id)
    {
        $mantenimiento = MantenimientoImpresora::findOrFail($id);
        $impresoraId = $mantenimiento->impresora_id;
        
        $mantenimiento->delete();

        return redirect()->route('admin.impresoras.show', $impresoraId)
            ->with('success', 'Mantenimiento eliminado correctamente');
    }

    /**
     * Get maintenance history for a specific printer.
     */
    public function historial($impresoraId)
    {
        $impresora = Impresora::findOrFail($impresoraId);
        
        $mantenimientos = MantenimientoImpresora::where('impresora_id', $impresoraId)
            ->orderBy('fecha_mantenimiento', 'desc')
            ->get();

        $estadisticas = [
            'total' => $mantenimientos->count(),
            'preventivos' => $mantenimientos->where('tipo_mantenimiento', 'Preventivo')->count(),
            'correctivos' => $mantenimientos->where('tipo_mantenimiento', 'Correctivo')->count(),
            'con_fallas' => $mantenimientos->whereNotNull('fallas_detectadas')->where('fallas_detectadas', '!=', '')->count(),
            'ultimo_mantenimiento' => $mantenimientos->first(),
        ];

        return view('admin.mantenimientos-impresora.historial', compact('impresora', 'mantenimientos', 'estadisticas'));
    }

    /**
     * Export maintenance records to Excel.
     */
    public function exportarExcel(Request $request)
    {
        $query = MantenimientoImpresora::with('impresora');

        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('fecha_mantenimiento', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->has('tipo_mantenimiento')) {
            $query->where('tipo_mantenimiento', $request->tipo_mantenimiento);
        }

        $mantenimientos = $query->orderBy('fecha_mantenimiento', 'desc')->get();

        // Generar Excel (puedes usar Maatwebsite\Excel)
        // return Excel::download(new MantenimientosExport($mantenimientos), 'mantenimientos.xlsx');
        
        // Por ahora redirigir con los datos
        return view('admin.mantenimientos-impresora.export', compact('mantenimientos'));
    }
}