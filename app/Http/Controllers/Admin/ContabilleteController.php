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
        if ($request->has('autocomplete') || $request->routeIs('admin.contabilletes.sugerencias')) {
            return $this->sugerencias($request);
        }

        $query = Contabillete::with(['oficina.agencia', 'responsable', 'ultimoMantenimiento']);

        // Filtro por término de búsqueda (multicampo)
        $searchTerm = trim($request->input('serie') ?? $request->input('search') ?? '');
        if ($searchTerm !== '') {
            $terms = explode(' ', $searchTerm);
            foreach ($terms as $term) {
                $term = trim($term);
                if (empty($term)) continue;

                $query->where(function ($q) use ($term) {
                    $q->where('serie_contabilletes', 'LIKE', "%{$term}%")
                      ->orWhere('marca_contabilletes', 'LIKE', "%{$term}%")
                      ->orWhere('modelo_contabilletes', 'LIKE', "%{$term}%")
                      ->orWhere('tipo_contabilletes', 'LIKE', "%{$term}%")
                      ->orWhere('tipo_deteccion', 'LIKE', "%{$term}%")
                      ->orWhere('pantalla_contabilletes', 'LIKE', "%{$term}%")
                      ->orWhereHas('responsable', function ($r) use ($term) {
                          $r->where('nombre_responsable', 'LIKE', "%{$term}%");
                      })
                      ->orWhereHas('oficina', function ($o) use ($term) {
                          $o->where('nombre_oficina', 'LIKE', "%{$term}%");
                      });
                });
            }
        }

        // Filtros de oficina y agencia
        $oficinaId = $request->input('oficina_id') ?? $request->input('oficina');
        if (!empty($oficinaId)) {
            $query->where('oficina_id', $oficinaId);
        }

        $agenciaId = $request->input('agencia_id') ?? $request->input('agencia');
        if (!empty($agenciaId)) {
            $query->whereHas('oficina', function ($q) use ($agenciaId) {
                $q->where('agencia_id', $agenciaId);
            });
        }

        // Filtro por estado
        if ($request->filled('estado_contabilletes')) {
            $query->where('estado_contabilletes', $request->estado_contabilletes);
        }

        $contabilletes = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        
        $oficinas = Oficina::orderBy('nombre_oficina')->get();
        $agencias = Agencia::orderBy('nombre_agencia')->get();
        $responsables = Responsable::all();

        if ($request->ajax()) {
            return view('admin.contabilletes.partials.table', compact('contabilletes'))->render();
        }

        return view('admin.contabilletes.index', compact('contabilletes', 'oficinas', 'agencias', 'responsables'));
    }

    /**
     * Devuelve sugerencias de autocompletado para contadoras de billetes en tiempo real.
     */
    public function sugerencias(Request $request)
    {
        $term = trim($request->input('q') ?? $request->input('serie') ?? $request->input('search') ?? '');
        if ($term === '') {
            return response()->json([]);
        }

        $query = Contabillete::with(['oficina.agencia', 'responsable']);

        $terms = explode(' ', $term);
        foreach ($terms as $t) {
            $t = trim($t);
            if (empty($t)) continue;

            $query->where(function ($q) use ($t) {
                $q->where('serie_contabilletes', 'LIKE', "%{$t}%")
                  ->orWhere('marca_contabilletes', 'LIKE', "%{$t}%")
                  ->orWhere('modelo_contabilletes', 'LIKE', "%{$t}%")
                  ->orWhere('tipo_contabilletes', 'LIKE', "%{$t}%")
                  ->orWhereHas('responsable', fn($r) => $r->where('nombre_responsable', 'LIKE', "%{$t}%"))
                  ->orWhereHas('oficina', fn($o) => $o->where('nombre_oficina', 'LIKE', "%{$t}%"));
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
        if ($request->filled('estado_contabilletes')) {
            $query->where('estado_contabilletes', $request->estado_contabilletes);
        }

        $contabilletes = $query->take(8)->get();

        $resultados = $contabilletes->map(function ($contabillete) {
            $marcaModelo = trim(($contabillete->marca_contabilletes ?? '') . ' ' . ($contabillete->modelo_contabilletes ?? ''));
            return [
                'id'          => $contabillete->id,
                'serie'       => $contabillete->serie_contabilletes,
                'value'       => $contabillete->serie_contabilletes . ($marcaModelo ? ' - ' . $marcaModelo : ''),
                'nombre'      => $marcaModelo ?: 'Contadora de Billetes',
                'marcaModelo' => $marcaModelo,
                'responsable' => $contabillete->responsable?->nombre_responsable ?? 'Sin asignar',
                'oficina'     => $contabillete->oficina?->nombre_oficina ?? 'Sin oficina',
                'estado'      => $contabillete->estado_contabilletes,
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

        Contabillete::create($validator->validated());

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

        $contabillete->update($validator->validated());

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
        $query = Contabillete::with(['oficina.agencia', 'responsable', 'ultimoMantenimiento']);

        // Filtro por término de búsqueda (multicampo)
        $searchTerm = trim($request->input('serie') ?? $request->input('search') ?? '');
        if ($searchTerm !== '') {
            $terms = explode(' ', $searchTerm);
            foreach ($terms as $term) {
                $term = trim($term);
                if (empty($term)) continue;

                $query->where(function ($q) use ($term) {
                    $q->where('serie_contabilletes', 'LIKE', "%{$term}%")
                      ->orWhere('marca_contabilletes', 'LIKE', "%{$term}%")
                      ->orWhere('modelo_contabilletes', 'LIKE', "%{$term}%")
                      ->orWhere('tipo_contabilletes', 'LIKE', "%{$term}%")
                      ->orWhere('tipo_deteccion', 'LIKE', "%{$term}%")
                      ->orWhere('pantalla_contabilletes', 'LIKE', "%{$term}%")
                      ->orWhereHas('responsable', function ($r) use ($term) {
                          $r->where('nombre_responsable', 'LIKE', "%{$term}%");
                      })
                      ->orWhereHas('oficina', function ($o) use ($term) {
                          $o->where('nombre_oficina', 'LIKE', "%{$term}%");
                      });
                });
            }
        }

        $oficinaId = $request->input('oficina_id') ?? $request->input('oficina');
        if (!empty($oficinaId)) {
            $query->where('oficina_id', $oficinaId);
        }
        $agenciaId = $request->input('agencia_id') ?? $request->input('agencia');
        if (!empty($agenciaId)) {
            $query->whereHas('oficina', fn($q) => $q->where('agencia_id', $agenciaId));
        }
        if ($request->filled('estado_contabilletes')) {
            $query->where('estado_contabilletes', $request->estado_contabilletes);
        }

        $contabilletes = $query->orderBy('id', 'desc')->get();

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

