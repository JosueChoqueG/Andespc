<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Impresora;
use App\Models\Oficina;
use App\Models\Responsable;
use App\Models\Agencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ImpresoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('autocomplete') || $request->routeIs('admin.impresoras.sugerencias')) {
            return $this->sugerencias($request);
        }

        $query = Impresora::with(['oficina.agencia', 'responsable', 'ultimoMantenimiento']);

        // Filtro por término de búsqueda (multicampo)
        $searchTerm = trim($request->input('serie') ?? $request->input('search') ?? '');
        if ($searchTerm !== '') {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('serie_impresora', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('marca_impresora', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('modelo_impresora', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nombre_host', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('direccion_ip', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tipo_conexion', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('responsable', function ($r) use ($searchTerm) {
                      $r->where('nombre_responsable', 'LIKE', "%{$searchTerm}%");
                  })
                  ->orWhereHas('oficina', function ($o) use ($searchTerm) {
                      $o->where('nombre_oficina', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Filtro por oficina
        $oficinaId = $request->input('oficina_id') ?? $request->input('oficina');
        if (!empty($oficinaId)) {
            $query->where('oficina_id', $oficinaId);
        }

        // Filtro por agencia
        $agenciaId = $request->input('agencia_id') ?? $request->input('agencia');
        if (!empty($agenciaId)) {
            $query->whereHas('oficina', function ($q) use ($agenciaId) {
                $q->where('agencia_id', $agenciaId);
            });
        }

        // Filtro por estado
        if ($request->filled('estado_impresora')) {
            $query->where('estado_impresora', $request->estado_impresora);
        }

        $impresoras = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        
        $oficinas = Oficina::orderBy('nombre_oficina')->get();
        $agencias = Agencia::orderBy('nombre_agencia')->get();
        $responsables = Responsable::all();

        if ($request->ajax()) {
            return view('admin.impresoras.partials.table', compact('impresoras'))->render();
        }

        return view('admin.impresoras.index', compact('impresoras', 'oficinas', 'agencias', 'responsables'));
    }

    /**
     * Devuelve sugerencias de autocompletado para impresoras en tiempo real.
     */
    public function sugerencias(Request $request)
    {
        $term = trim($request->input('q') ?? $request->input('serie') ?? $request->input('search') ?? '');
        if ($term === '') {
            return response()->json([]);
        }

        $query = Impresora::with(['oficina.agencia', 'responsable']);

        $query->where(function ($q) use ($term) {
            $q->where('serie_impresora', 'LIKE', "%{$term}%")
              ->orWhere('marca_impresora', 'LIKE', "%{$term}%")
              ->orWhere('modelo_impresora', 'LIKE', "%{$term}%")
              ->orWhere('nombre_host', 'LIKE', "%{$term}%")
              ->orWhere('direccion_ip', 'LIKE', "%{$term}%")
              ->orWhereHas('responsable', fn($r) => $r->where('nombre_responsable', 'LIKE', "%{$term}%"))
              ->orWhereHas('oficina', fn($o) => $o->where('nombre_oficina', 'LIKE', "%{$term}%"));
        });

        $oficinaId = $request->input('oficina_id') ?? $request->input('oficina');
        if (!empty($oficinaId)) {
            $query->where('oficina_id', $oficinaId);
        }
        $agenciaId = $request->input('agencia_id') ?? $request->input('agencia');
        if (!empty($agenciaId)) {
            $query->whereHas('oficina', fn($q) => $q->where('agencia_id', $agenciaId));
        }
        if ($request->filled('estado_impresora')) {
            $query->where('estado_impresora', $request->estado_impresora);
        }

        $impresoras = $query->take(8)->get();

        $resultados = $impresoras->map(function ($impresora) {
            $marcaModelo = trim(($impresora->marca_impresora ?? '') . ' ' . ($impresora->modelo_impresora ?? ''));
            return [
                'id'          => $impresora->id,
                'serie'       => $impresora->serie_impresora,
                'value'       => $impresora->serie_impresora ?: $marcaModelo,
                'nombre'      => $marcaModelo ?: 'Impresora',
                'marcaModelo' => $marcaModelo,
                'ip'          => $impresora->direccion_ip,
                'responsable' => $impresora->responsable?->nombre_responsable ?? 'Sin asignar',
                'oficina'     => $impresora->oficina?->nombre_oficina ?? 'Sin oficina',
                'estado'      => $impresora->estado_impresora,
            ];
        });

        return response()->json($resultados);
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
            'direccion_ip' => 'nullable|string|max:50',
            'nombre_host' => 'nullable|string|max:100',
            'estado_impresora' => 'nullable|in:OPTIMO,BUENO,REGULAR,DEFICIENTE,DE BAJA',
            'fecha_adquisicion' => 'nullable|date',
            'velocidad_impresion' => 'nullable|string|max:50',
            'modelo_consumible' => 'nullable|string|max:100',
            'tipo_consumible' => 'nullable|string|max:100',
            'cantidad_impresion' => 'nullable|integer',
            'capacidad_impresion' => 'nullable|integer',
            'cantidad_escaneo' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Impresora::create($request->validated());

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
            'direccion_ip' => 'nullable|string|max:50',
            'nombre_host' => 'nullable|string|max:100',
            'estado_impresora' => 'nullable|in:OPTIMO,BUENO,REGULAR,DEFICIENTE,DE BAJA',
            'fecha_adquisicion' => 'nullable|date',
            'velocidad_impresion' => 'nullable|string|max:50',
            'modelo_consumible' => 'nullable|string|max:100',
            'tipo_consumible' => 'nullable|string|max:100',
            'cantidad_impresion' => 'nullable|integer',
            'capacidad_impresion' => 'nullable|integer',
            'cantidad_escaneo' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $impresora->update($request->validated());

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

    /**
     * Generar hoja de vida de la impresora (Vista HTML)
     */
    public function hojaVida($id)
    {
        $impresora = Impresora::with(['oficina', 'responsable', 'mantenimientos'])
            ->findOrFail($id);
            
        $tecnico = \Illuminate\Support\Facades\Auth::user()->name ?? 'Josue Choque Gomez';
        
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
        $impresora = Impresora::with(['oficina', 'responsable', 'mantenimientos'])
            ->findOrFail($id);
            
        $tecnico = \Illuminate\Support\Facades\Auth::user()->name ?? 'Josue Choque Gomez';
        
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
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Hoja-Vida-Impresora-{$impresora->serie_impresora}-" . date('Y-m-d') . ".pdf");
    }
    
    public function exportarExcel(Request $request)
    {
        $query = Impresora::with(['oficina.agencia', 'responsable']);

        $searchTerm = trim($request->input('serie') ?? $request->input('search') ?? '');
        if ($searchTerm !== '') {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('serie_impresora', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('marca_impresora', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('modelo_impresora', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nombre_host', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('direccion_ip', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tipo_conexion', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('responsable', fn($r) => $r->where('nombre_responsable', 'LIKE', "%{$searchTerm}%"))
                  ->orWhereHas('oficina', fn($o) => $o->where('nombre_oficina', 'LIKE', "%{$searchTerm}%"));
            });
        }
        $oficinaId = $request->input('oficina_id') ?? $request->input('oficina');
        if (!empty($oficinaId)) {
            $query->where('oficina_id', $oficinaId);
        }
        $agenciaId = $request->input('agencia_id') ?? $request->input('agencia');
        if (!empty($agenciaId)) {
            $query->whereHas('oficina', fn($q) => $q->where('agencia_id', $agenciaId));
        }
        if ($request->filled('estado_impresora')) {
            $query->where('estado_impresora', $request->estado_impresora);
        }

        $impresoras = $query->orderBy('created_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Impresoras');

        $columnas = [
            'A' => 'CÓDIGO AGENCIA',
            'B' => 'NOMBRE DE AGENCIA',
            'C' => 'NOMBRE DE OFICINA',
            'D' => 'TIPO',
            'E' => 'SERIE',
            'F' => 'MARCA',
            'G' => 'MODELO',
            'H' => 'IP',
            'I' => 'TIPO CONEXIÓN',
            'J' => 'FECHA DE COMPRA',
            'K' => 'RESPONSABLE',
            'L' => 'ESTADO',
            'M' => 'VELOCIDAD IMPRESIÓN',
            'N' => 'MODELO CONSUMIBLE',
            'O' => 'TIPO CONSUMIBLE',
        ];

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F6FEB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCCCCCC']]],
        ];

        foreach ($columnas as $col => $titulo) {
            $sheet->setCellValue($col . '1', $titulo);
            $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
        }

        $fila = 2;
        foreach ($impresoras as $imp) {
            $datos = [
                'A' => $imp->oficina?->agencia?->codigo_agencia ?? 'N/A',
                'B' => $imp->oficina?->agencia?->nombre_agencia ?? 'N/A',
                'C' => $imp->oficina?->nombre_oficina           ?? 'N/A',
                'D' => $imp->tipo_impresora                    ?? 'N/A',
                'E' => $imp->serie_impresora                   ?? 'N/A',
                'F' => $imp->marca_impresora                   ?? 'N/A',
                'G' => $imp->modelo_impresora                  ?? 'N/A',
                'H' => $imp->direccion_ip                      ?? 'N/A',
                'I' => $imp->tipo_conexion                     ?? 'N/A',
                'J' => $imp->fecha_adquisicion?->format('d/m/Y') ?? 'N/A',
                'K' => $imp->responsable?->nombre_responsable  ?? 'N/A',
                'L' => $imp->estado_impresora                  ?? 'N/A',
                'M' => $imp->velocidad_impresion               ?? 'N/A',
                'N' => $imp->modelo_consumible                 ?? 'N/A',
                'O' => $imp->tipo_consumible                   ?? 'N/A',
            ];
            foreach ($datos as $col => $valor) {
                $sheet->setCellValue($col . $fila, $valor);
                $sheet->getStyle($col . $fila)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCCCCCC']]],
                    'fill'    => $fila % 2 === 0
                        ? ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF0F6FF']]
                        : ['fillType' => Fill::FILL_NONE],
                ]);
            }
            $fila++;
        }

        foreach (array_keys($columnas) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $fileName = 'Reporte_Impresoras_' . now()->format('Ymd_His') . '.xlsx';
        $tmpFile  = tempnam(sys_get_temp_dir(), 'imp_excel_');
        $writer->save($tmpFile);

        return response()->download($tmpFile, $fileName)->deleteFileAfterSend(true);
    }
}
