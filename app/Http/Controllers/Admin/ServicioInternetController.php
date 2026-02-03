<?php

namespace App\Http\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Http\Controllers\Controller;
use App\Models\ServicioInternet;
use App\Models\Oficina;
use Illuminate\Http\Request;

class ServicioInternetController extends Controller
{
    public function index(Request $request)
    {
        $query = ServicioInternet::with('oficina');

        if ($request->filled('nombre_proveedor')) {
            $query->where('nombre_proveedor', 'like', '%' . $request->nombre_proveedor . '%');
        }

        if ($request->filled('oficina_id')) {
            $query->where('oficina_id', $request->oficina_id);
        }

        $servicios = $query->paginate(10);
        $oficinas  = Oficina::all();

        return view('admin.servicios-internet.index', compact('servicios', 'oficinas'));
    }

    public function create()
    {
        $oficinas = Oficina::all();
        return view('admin.servicios-internet.create', compact('oficinas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'oficina_id'        => 'required|exists:oficinas,id',
            'direccion'         => 'nullable|string',
            'coordenada'         => 'nullable|string|max:100',
            'megas_contratado'   => 'required|string|max:50',
            'tipo_instalacion'   => 'required|in:Fibra óptica,Radio enlace,RPC,Starlink',
            'nombre_proveedor'   => 'required|string|max:100',
            'telefono_proveedor' => 'nullable|string|max:15',
            'contrasena_router'  => 'nullable|string|max:100',
            'nombre_wifi'        => 'nullable|string|max:100',
            'contrasena_wifi'    => 'nullable|string|max:100',
            'direccion_ip'       => 'nullable|ip',
        ]);

        ServicioInternet::create($request->only([
            'oficina_id',
            'direccion',
            'coordenada',
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
            'tipo_instalacion'  => 'required|in:Fibra óptica,Radio enlace,RPC,Starlink',
            'direccion_ip'      => 'nullable|ip',
        ]);

        $servicio->update($request->only([
            'oficina_id',
            'direccion',
            'coordenada',
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
    public function exportExcel()
    {
        $servicios = ServicioInternet::with('oficina')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // ======================
        // ENCABEZADOS
        // ======================
        $headers = [
            'A1' => 'OFICINA',
            'B1' => 'DIRECCIÓN',
            'C1' => 'COORDENADAS',
            'D1' => 'MEGAS',
            'E1' => 'TIPO INSTALACIÓN',
            'F1' => 'PROVEEDOR',
            'G1' => 'TELÉFONO',
            'H1' => 'NOMBRE WIFI',
            'I1' => 'CONTRASEÑA WIFI',
            'J1' => 'IP'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // DATOS
        $row = 2;

        foreach ($servicios as $s) {
            $sheet->setCellValue('A' . $row, $s->oficina->nombre_oficina ?? '');
            $sheet->setCellValue('B' . $row, $s->direccion);
            $sheet->setCellValue('C' . $row, $s->coordenada);
            $sheet->setCellValue('D' . $row, $s->megas_contratado);
            $sheet->setCellValue('E' . $row, $s->tipo_instalacion);
            $sheet->setCellValue('F' . $row, $s->nombre_proveedor);
            $sheet->setCellValue('G' . $row, $s->telefono_proveedor);
            $sheet->setCellValue('H' . $row, $s->nombre_wifi);
            $sheet->setCellValue('I' . $row, $s->contrasena_wifi);
            $sheet->setCellValue('J' . $row, $s->direccion_ip);

            $row++;
        }

        // AUTOAJUSTE DE COLUMNAS
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // DESCARGA
        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="servicios_internet.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}