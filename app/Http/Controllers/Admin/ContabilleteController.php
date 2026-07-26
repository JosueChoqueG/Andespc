<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contabillete;
use App\Models\Oficina;
use App\Models\Responsable;
use App\Models\Agencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ContabilleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contabillete::with(['oficina.agencia', 'responsable']);

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
            $query->where('serie_contabilletes', 'LIKE', "%{$request->serie}%");
        }

        if ($request->filled('estado_contabilletes')) {
            $query->where('estado_contabilletes', $request->estado_contabilletes);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('marca_contabilletes', 'LIKE', "%{$search}%")
                  ->orWhere('modelo_contabilletes', 'LIKE', "%{$search}%")
                  ->orWhere('serie_contabilletes', 'LIKE', "%{$search}%");
            });
        }

        $contabilletes = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $oficinas = Oficina::orderBy('nombre_oficina')->get();
        $agencias = Agencia::orderBy('nombre_agencia')->get();
        $responsables = Responsable::all();

        return view('admin.contabilletes.index', compact('contabilletes', 'oficinas', 'agencias', 'responsables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $oficinas = Oficina::all();
        $responsables = Responsable::all();
        
        return view('admin.contabilletes.create', compact('oficinas', 'responsables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oficina_id' => 'required|exists:oficinas,id',
            'tipo_contabilletes' => 'required|string|max:50',
            'marca_contabilletes' => 'required|string|max:50',
            'modelo_contabilletes' => 'required|string|max:50',
            'serie_contabilletes' => 'required|string|max:50|unique:contabilletes,serie_contabilletes',
            'responsable_id' => 'nullable|exists:responsables,id',
            'estado_contabilletes' => 'nullable|in:OPTIMO,BUENO,REGULAR,DEFICIENTE,DE BAJA',
            'fecha_adquisicion' => 'nullable|date',
            'velocidad_contabilletes' => 'nullable|string|max:15',
            'capacidad_tolva' => 'nullable|integer|min:0',
            'capacidad_bandeja' => 'nullable|integer|min:0',
            'tipo_deteccion' => 'nullable|string|max:50',
            'pantalla_contabilletes' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Contabillete::create($request->all());

        return redirect()->route('admin.contabilletes.index')
            ->with('success', 'Contadora de billetes registrada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contabillete = Contabillete::with(['oficina', 'responsable', 'mantenimientos'])
            ->findOrFail($id);

        return view('admin.contabilletes.show', compact('contabillete'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contabillete = Contabillete::findOrFail($id);
        $oficinas = Oficina::all();
        $responsables = Responsable::all();

        return view('admin.contabilletes.edit', compact('contabillete', 'oficinas', 'responsables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contabillete = Contabillete::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'oficina_id' => 'required|exists:oficinas,id',
            'tipo_contabilletes' => 'required|string|max:50',
            'marca_contabilletes' => 'required|string|max:50',
            'modelo_contabilletes' => 'required|string|max:50',
            'serie_contabilletes' => 'required|string|max:50|unique:contabilletes,serie_contabilletes,' . $id,
            'responsable_id' => 'nullable|exists:responsables,id',
            'estado_contabilletes' => 'nullable|in:OPTIMO,BUENO,REGULAR,DEFICIENTE,DE BAJA',
            'fecha_adquisicion' => 'nullable|date',
            'velocidad_contabilletes' => 'nullable|string|max:15',
            'capacidad_tolva' => 'nullable|integer|min:0',
            'capacidad_bandeja' => 'nullable|integer|min:0',
            'tipo_deteccion' => 'nullable|string|max:50',
            'pantalla_contabilletes' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $contabillete->update($request->all());

        return redirect()->route('admin.contabilletes.index')
            ->with('success', 'Contadora de billetes actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contabillete = Contabillete::findOrFail($id);
        
        // Verificar si tiene mantenimientos
        if ($contabillete->mantenimientos()->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar la contadora de billetes porque tiene mantenimientos asociados');
        }
        
        $contabillete->delete();

        return redirect()->route('admin.contabilletes.index')
            ->with('success', 'Contadora de billetes eliminada correctamente');
    }

    /**
     * Generar hoja de vida de la contadora de billetes (Vista HTML)
     */
    public function hojaVida($id)
    {
        $contabillete = Contabillete::with(['oficina', 'responsable', 'mantenimientos'])
            ->findOrFail($id);
            
        $tecnico = \Illuminate\Support\Facades\Auth::user()->name ?? 'Josue Choque Gomez';
        
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
        $contabillete = Contabillete::with(['oficina', 'responsable', 'mantenimientos'])
            ->findOrFail($id);
            
        $tecnico = \Illuminate\Support\Facades\Auth::user()->name ?? 'Josue Choque Gomez';
        
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
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Hoja-Vida-Contadora-{$contabillete->serie_contabilletes}-" . date('Y-m-d') . ".pdf");
    }

    public function exportarExcel(Request $request)
    {
        $query = Contabillete::with(['oficina.agencia', 'responsable']);

        if ($request->filled('oficina_id')) {
            $query->where('oficina_id', $request->oficina_id);
        }
        if ($request->filled('agencia_id')) {
            $query->whereHas('oficina', fn($q) => $q->where('agencia_id', $request->agencia_id));
        }
        if ($request->filled('serie')) {
            $query->where('serie_contabilletes', 'LIKE', "%{$request->serie}%");
        }
        if ($request->filled('estado_contabilletes')) {
            $query->where('estado_contabilletes', $request->estado_contabilletes);
        }

        $contabilletes = $query->orderBy('created_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Contadoras');

        $columnas = [
            'A' => 'CÓDIGO AGENCIA',
            'B' => 'NOMBRE DE AGENCIA',
            'C' => 'NOMBRE DE OFICINA',
            'D' => 'TIPO',
            'E' => 'SERIE',
            'F' => 'MARCA',
            'G' => 'MODELO',
            'H' => 'FECHA DE COMPRA',
            'I' => 'RESPONSABLE',
            'J' => 'ESTADO',
            'K' => 'VELOCIDAD',
            'L' => 'CAPACIDAD TOLVA',
            'M' => 'CAPACIDAD BANDEJA',
            'N' => 'TIPO DETECCIÓN',
            'O' => 'PANTALLA',
        ];

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF16A34A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCCCCCC']]],
        ];

        foreach ($columnas as $col => $titulo) {
            $sheet->setCellValue($col . '1', $titulo);
            $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
        }

        $fila = 2;
        foreach ($contabilletes as $cont) {
            $datos = [
                'A' => $cont->oficina?->agencia?->codigo_agencia   ?? 'N/A',
                'B' => $cont->oficina?->agencia?->nombre_agencia    ?? 'N/A',
                'C' => $cont->oficina?->nombre_oficina              ?? 'N/A',
                'D' => $cont->tipo_contabilletes                    ?? 'N/A',
                'E' => $cont->serie_contabilletes                   ?? 'N/A',
                'F' => $cont->marca_contabilletes                   ?? 'N/A',
                'G' => $cont->modelo_contabilletes                  ?? 'N/A',
                'H' => $cont->fecha_adquisicion?->format('d/m/Y')   ?? 'N/A',
                'I' => $cont->responsable?->nombre_responsable      ?? 'N/A',
                'J' => $cont->estado_contabilletes                  ?? 'N/A',
                'K' => $cont->velocidad_contabilletes               ?? 'N/A',
                'L' => $cont->capacidad_tolva                       ?? 'N/A',
                'M' => $cont->capacidad_bandeja                     ?? 'N/A',
                'N' => $cont->tipo_deteccion                        ?? 'N/A',
                'O' => $cont->pantalla_contabilletes                ?? 'N/A',
            ];
            foreach ($datos as $col => $valor) {
                $sheet->setCellValue($col . $fila, $valor);
                $sheet->getStyle($col . $fila)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCCCCCC']]],
                    'fill'    => $fila % 2 === 0
                        ? ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF0FDF4']]
                        : ['fillType' => Fill::FILL_NONE],
                ]);
            }
            $fila++;
        }

        foreach (array_keys($columnas) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $fileName = 'Reporte_Contadoras_' . now()->format('Ymd_His') . '.xlsx';
        $tmpFile  = tempnam(sys_get_temp_dir(), 'cont_excel_');
        $writer->save($tmpFile);

        return response()->download($tmpFile, $fileName)->deleteFileAfterSend(true);
    }
}
