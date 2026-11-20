<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IncidenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalIncidencias = Incidencia::count();
        return view('admin.dashboard', compact('totalIncidencias'));
    }

    public function create()
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.incidencias.partials.modal-create')->render()
            ]);
        }
        return redirect()->route('admin.incidencias.listado');
    }

    public function guardar(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|string',
            'modulo' => 'required|string',
            'problema' => 'required|string',
            'descripcion' => 'required|string',
            'solucion' => 'required|string',
            'usuario_afectado' => 'required|string|max:255',
            'agencia' => 'required|string|max:255',
            'sub_agencia' => 'nullable|string|max:255',
            'prioridad' => 'required|in:Alta,Media,Baja',
            'estado' => 'required|in:Pendiente,Derivado,Atendido',
        ]);

        $data['atendido_por'] = Auth::id();
        $incidencia = Incidencia::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Incidencia registrada con éxito en la bitácora.',
                'incidencia' => $incidencia
            ]);
        }

        return redirect()->route('admin.incidencias.listado')->with([
            'success' => 'Incidencia #' . $incidencia->id . ' registrada con éxito en la bitácora.',
            'show_loading' => true
        ]);
    }

    public function listado(Request $request)
    {
        // 📊 KPIs Globales
        $totalGeneral = Incidencia::count();
        $totalAtendidos = Incidencia::where('estado', 'Atendido')->count();
        $totalPendientes = Incidencia::where('estado', 'Pendiente')->count();
        $totalDerivados = Incidencia::where('estado', 'Derivado')->count();
        $porcentajeResolucion = $totalGeneral > 0 ? round(($totalAtendidos / $totalGeneral) * 100, 1) : 0;

        // 🔍 Query con filtros
        $query = Incidencia::with('atendidoPor')->latest();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                if (is_numeric($s)) {
                    $q->orWhere('id', $s);
                }
                $q->orWhere('usuario_afectado', 'like', "%{$s}%")
                  ->orWhere('problema', 'like', "%{$s}%")
                  ->orWhere('descripcion', 'like', "%{$s}%")
                  ->orWhere('solucion', 'like', "%{$s}%")
                  ->orWhere('agencia', 'like', "%{$s}%")
                  ->orWhere('sub_agencia', 'like', "%{$s}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        if ($request->filled('agencia')) {
            $query->where('agencia', $request->agencia);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $perPage = $request->input('per_page', 20);
        $incidencias = $query->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return view('admin.incidencias.partials.table', compact('incidencias'))->render();
        }

        // Lista de agencias para el filtro
        $agenciasList = Incidencia::select('agencia')
            ->distinct()
            ->whereNotNull('agencia')
            ->where('agencia', '!=', '')
            ->orderBy('agencia')
            ->pluck('agencia');

        return view('admin.incidencias.listado', compact(
            'incidencias',
            'totalGeneral',
            'totalAtendidos',
            'totalPendientes',
            'totalDerivados',
            'porcentajeResolucion',
            'agenciasList'
        ));
    }

    public function show(Incidencia $incidencia)
    {
        $incidencia->load('atendidoPor');

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.incidencias.partials.modal-show', compact('incidencia'))->render()
            ]);
        }

        return view('admin.incidencias.show', compact('incidencia'));
    }

    public function edit(Incidencia $incidencia)
    {
        $incidencia->load('atendidoPor');

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.incidencias.partials.modal-edit', compact('incidencia'))->render()
            ]);
        }

        return view('admin.incidencias.edit', compact('incidencia'));
    }

    public function update(Request $request, Incidencia $incidencia)
    {
        $data = $request->validate([
            'tipo' => 'required|string',
            'modulo' => 'required|string',
            'problema' => 'required|string',
            'descripcion' => 'required|string',
            'solucion' => 'required|string',
            'usuario_afectado' => 'required|string|max:255',
            'agencia' => 'required|string|max:255',
            'sub_agencia' => 'nullable|string|max:255',
            'prioridad' => 'required|in:Alta,Media,Baja',
            'estado' => 'required|in:Pendiente,Derivado,Atendido',
        ]);

        $incidencia->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Incidencia #' . $incidencia->id . ' actualizada exitosamente.',
                'incidencia' => $incidencia
            ]);
        }

        return redirect()->route('admin.incidencias.listado')->with([
            'success' => 'Incidencia #' . $incidencia->id . ' actualizada exitosamente.'
        ]);
    }

    public function cambiarEstado(Request $request, Incidencia $incidencia)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,Derivado,Atendido'
        ]);

        $incidencia->estado = $request->estado;
        if ($request->filled('solucion')) {
            $incidencia->solucion = $request->solucion;
        }
        $incidencia->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado de Incidencia #' . $incidencia->id . ' actualizado a: ' . $incidencia->estado
        ]);
    }

    // ✅ Método de exportación usando PhpSpreadsheet con todos los filtros aplicados
    public function exportarExcel(Request $request)
    {
        $query = Incidencia::with('atendidoPor')->latest();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                if (is_numeric($s)) {
                    $q->orWhere('id', $s);
                }
                $q->orWhere('usuario_afectado', 'like', "%{$s}%")
                  ->orWhere('problema', 'like', "%{$s}%")
                  ->orWhere('descripcion', 'like', "%{$s}%")
                  ->orWhere('solucion', 'like', "%{$s}%")
                  ->orWhere('agencia', 'like', "%{$s}%")
                  ->orWhere('sub_agencia', 'like', "%{$s}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('agencia')) {
            $query->where('agencia', $request->agencia);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $incidencias = $query->get();
        $filename = 'bitacora_incidencias_' . now()->format('Y-m-d_H-i') . '.xlsx';

        return new StreamedResponse(function() use ($incidencias) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Bitácora TI');

            // === ENCABEZADOS ===
            $headers = [
                'Nº Ticket', 'Tipo', 'Módulo', 'Problema', 'Descripción de la Falla', 'Solución / Procedimiento Aplicado',
                'Usuario Afectado', 'Agencia', 'Sub Agencia / Área', 'Prioridad',
                'Estado', 'Especialista Asignado', 'Fecha y Hora de Registro'
            ];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            // Estilo encabezados
            $sheet->getStyle('A1:M1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1D2D44'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // === DATOS ===
            $row = 2;
            foreach ($incidencias as $inc) {
                $sheet->setCellValue('A' . $row, '#' . str_pad($inc->id, 5, '0', STR_PAD_LEFT));
                $sheet->setCellValue('B' . $row, ucfirst($inc->tipo));
                $sheet->setCellValue('C' . $row, ucfirst($inc->modulo));
                $sheet->setCellValue('D' . $row, $inc->problema);
                $sheet->setCellValue('E' . $row, $inc->descripcion);
                $sheet->setCellValue('F' . $row, $inc->solucion);
                $sheet->setCellValue('G' . $row, $inc->usuario_afectado);
                $sheet->setCellValue('H' . $row, $inc->agencia);
                $sheet->setCellValue('I' . $row, $inc->sub_agencia ?? '-');
                $sheet->setCellValue('J' . $row, $inc->prioridad);
                $sheet->setCellValue('K' . $row, $inc->estado);
                $sheet->setCellValue('L' . $row, $inc->atendidoPor?->name ?? 'No asignado');
                $sheet->setCellValue('M' . $row, $inc->created_at ? $inc->created_at->format('d/m/Y H:i:s') : '-');

                // Color según estado
                $estadoColor = match($inc->estado) {
                    'Atendido' => 'D4EDDA',
                    'Pendiente' => 'F8D7DA',
                    'Derivado' => 'FFF3CD',
                    default => 'E2E3E5'
                };
                $sheet->getStyle('K' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $estadoColor],
                    ],
                    'font' => ['bold' => true]
                ]);

                $row++;
            }

            // === AUTOAJUSTE DE COLUMNAS ===
            foreach (range('A', 'M') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // === DESCARGAR ===
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
