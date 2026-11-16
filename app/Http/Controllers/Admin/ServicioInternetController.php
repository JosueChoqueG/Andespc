<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicioInternet;
use App\Models\Oficina;
use App\Models\Agencia;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServicioInternetController extends Controller
{
    public function index(Request $request)
    {
        $query = ServicioInternet::with(['oficina.agencia']);

        // Filtro por término de búsqueda (multicampo)
        $searchTerm = trim($request->input('search') ?? $request->input('q') ?? '');
        if ($searchTerm !== '') {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nombre_proveedor', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('telefono_proveedor', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('megas_contratado', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('direccion_ip', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nombre_wifi', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tipo_instalacion', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('oficina', function ($o) use ($searchTerm) {
                      $o->where('nombre_oficina', 'LIKE', "%{$searchTerm}%")
                        ->orWhereHas('agencia', function ($a) use ($searchTerm) {
                            $a->where('nombre_agencia', 'LIKE', "%{$searchTerm}%");
                        });
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

        // Filtro por tipo de instalación
        if ($request->filled('tipo_instalacion')) {
            $query->where('tipo_instalacion', $request->tipo_instalacion);
        }

        $servicios = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $oficinas  = Oficina::orderBy('nombre_oficina')->get();
        $agencias  = Agencia::orderBy('nombre_agencia')->get();

        if ($request->ajax()) {
            return view('admin.servicios-internet.partials.table', compact('servicios'))->render();
        }

        return view('admin.servicios-internet.index', compact('servicios', 'oficinas', 'agencias'));
    }

    public function create()
    {
        $oficinas = Oficina::all();
        return view('admin.servicios-internet.create', compact('oficinas'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'oficina_id'        => 'required|exists:oficinas,id',
            'megas_contratado'   => 'required|string|max:50',
            'tipo_instalacion'   => 'required|in:Fibra óptica,Radioenlace,RPC,Starlink',
            'nombre_proveedor'   => 'required|string|max:100',
            'telefono_proveedor' => 'nullable|string|max:15',
            'contrasena_router'  => 'nullable|string|max:100',
            'nombre_wifi'        => 'nullable|string|max:100',
            'contrasena_wifi'    => 'nullable|string|max:100',
            'direccion_ip'       => 'nullable|ip',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.servicios-internet.index')
                ->withErrors($validator)
                ->withInput();
        }

        ServicioInternet::create($request->only([
            'oficina_id',
            'megas_contratado',
            'tipo_instalacion',
            'nombre_proveedor',
            'telefono_proveedor',
            'contrasena_router',
            'nombre_wifi',
            'contrasena_wifi',
            'direccion_ip',
        ]));

        return redirect()->route('admin.servicios-internet.index')
            ->with('success', 'Servicio registrado correctamente');
    }

    public function edit(ServicioInternet $servicio)
    {
        $oficinas = Oficina::all();
        return view('admin.servicios-internet.edit', compact('servicio', 'oficinas'));
    }

    public function update(Request $request, ServicioInternet $servicio)
    {
        $request->validate([
            'oficina_id'        => 'required|exists:oficinas,id',
            'megas_contratado'   => 'required|string|max:50',
            'tipo_instalacion'   => 'required|in:Fibra óptica,Radioenlace,RPC,Starlink',
            'nombre_proveedor'   => 'required|string|max:100',
            'telefono_proveedor' => 'nullable|string|max:15',
            'contrasena_router'  => 'nullable|string|max:100',
            'nombre_wifi'        => 'nullable|string|max:100',
            'contrasena_wifi'    => 'nullable|string|max:100',
            'direccion_ip'       => 'nullable|ip',
        ]);

        $servicio->update($request->only([
            'oficina_id',
            'megas_contratado',
            'tipo_instalacion',
            'nombre_proveedor',
            'telefono_proveedor',
            'contrasena_router',
            'nombre_wifi',
            'contrasena_wifi',
            'direccion_ip',
        ]));

        return redirect()->route('admin.servicios-internet.index')
            ->with('success', 'Servicio actualizado correctamente');
    }

    public function destroy(ServicioInternet $servicio)
    {
        $servicio->delete();

        return redirect()
            ->route('admin.servicios-internet.index')
            ->with('success', 'Servicio eliminado');
    }
    public function exportExcel(Request $request)
    {
        $query = ServicioInternet::with(['oficina.agencia']);

        $searchTerm = trim($request->input('search') ?? $request->input('q') ?? '');
        if ($searchTerm !== '') {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nombre_proveedor', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('telefono_proveedor', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('megas_contratado', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('direccion_ip', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nombre_wifi', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tipo_instalacion', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('oficina', function ($o) use ($searchTerm) {
                      $o->where('nombre_oficina', 'LIKE', "%{$searchTerm}%")
                        ->orWhereHas('agencia', function ($a) use ($searchTerm) {
                            $a->where('nombre_agencia', 'LIKE', "%{$searchTerm}%");
                        });
                  });
            });
        }

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

        if ($request->filled('tipo_instalacion')) {
            $query->where('tipo_instalacion', $request->tipo_instalacion);
        }

        $servicios = $query->orderBy('id', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Servicios Internet');

        $columnas = [
            'A' => 'CÓDIGO AGENCIA',
            'B' => 'AGENCIA',
            'C' => 'OFICINA',
            'D' => 'PROVEEDOR',
            'E' => 'TELÉFONO PROVEEDOR',
            'F' => 'TIPO DE SERVICIO',
            'G' => 'MEGAS (MBPS)',
            'H' => 'DIRECCIÓN IP',
            'I' => 'NOMBRE WIFI (SSID)',
            'J' => 'CONTRASEÑA WIFI',
            'K' => 'CONTRASEÑA ROUTER',
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
        foreach ($servicios as $s) {
            $datos = [
                'A' => $s->oficina?->agencia?->codigo_agencia ?? 'N/A',
                'B' => $s->oficina?->agencia?->nombre_agencia ?? 'N/A',
                'C' => $s->oficina?->nombre_oficina           ?? 'N/A',
                'D' => $s->nombre_proveedor                  ?? 'N/A',
                'E' => $s->telefono_proveedor                ?? 'N/A',
                'F' => $s->tipo_instalacion                  ?? 'N/A',
                'G' => $s->megas_contratado                  ?? 'N/A',
                'H' => $s->direccion_ip                      ?? 'N/A',
                'I' => $s->nombre_wifi                       ?? 'N/A',
                'J' => $s->contrasena_wifi                   ?? 'N/A',
                'K' => $s->contrasena_router                 ?? 'N/A',
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

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Reporte_Servicios_Internet_' . now()->format('Ymd_His') . '.xlsx';
        $tmpFile  = tempnam(sys_get_temp_dir(), 'serv_internet_');
        $writer->save($tmpFile);

        return response()->download($tmpFile, $fileName)->deleteFileAfterSend(true);
    }
}