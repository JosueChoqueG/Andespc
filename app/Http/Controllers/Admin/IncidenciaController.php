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

    public function formulario()
    {
        return view('admin.incidencias.formulario');
    }

    public function guardar(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|string',
            'modulo' => 'required|string',
            'problema' => 'required|string',
            'descripcion' => 'required|string',
            'solucion' => 'required|string',
            'usuario_afectado' => 'required|string',
            'agencia' => 'required|string',
            'sub_agencia' => 'nullable|string',
            'prioridad' => 'required|in:Alta,Media,Baja',
            'estado' => 'required|in:Pendiente,Derivado,Atendido',
        ]);

        $data['atendido_por'] = Auth::id();
        Incidencia::create($data);

        // ✅ Redirección con sesión
        return redirect()->route('admin.incidencias.listado')->with([
            'success' => 'Incidencia registrada.',
            'show_loading' => true
        ]);
    }

    public function listado(Request $request)
    {
        $query = Incidencia::with('atendidoPor')->latest();
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        $incidencias = $query->get();
        return view('admin.incidencias.listado', compact('incidencias'));
    }

    // ✅ Método de exportación usando PhpSpreadsheet directamente
    public function exportarExcel(Request $request)
    {
        $query = Incidencia::with('atendidoPor')->latest();
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        $incidencias = $query->get();
        $filename = 'incidencias_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return new StreamedResponse(function() use ($incidencias) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Incidencias');

            // === ENCABEZADOS ===
            $headers = [
                'ID', 'Tipo', 'Módulo', 'Problema', 'Descripción', 'Solución',
                'Usuario Afectado', 'Agencia', 'Sub Agencia', 'Prioridad',
                'Estado', 'Atendido Por', 'Fecha Registro'
            ];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            // Estilo encabezados
            $sheet->getStyle('A1:M1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2C3E50'],
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ]);

            // === DATOS ===
            $row = 2;
            foreach ($incidencias as $inc) {
                $sheet->setCellValue('A' . $row, $inc->id);
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
                $sheet->setCellValue('M' . $row, $inc->created_at->format('d/m/Y H:i:s'));
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