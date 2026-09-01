<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Oficina;
use App\Models\Agencia;
use App\Models\TipoEquipo;
use App\Models\Hardware;
use App\Models\Modelo;
use App\Models\SistemaOperativo;
use App\Models\Responsable;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        // Si se solicitan sugerencias vía AJAX / parámetro
        if ($request->has('autocomplete') || $request->routeIs('equipos.sugerencias')) {
            return $this->sugerencias($request);
        }

        $query = Equipo::with([
            'oficina',
            'oficina.agencia',
            'tipoEquipo',
            'hardware',
            'modelo.marca',
            'sistemaOperativo',
            'responsable'
        ]);

        // 🔎 Buscar en múltiples campos (serie, dispositivo, IP, MAC, responsable, marca, modelo, tipo)
        $searchTerm = trim($request->input('serie') ?? $request->input('search') ?? '');
        if ($searchTerm !== '') {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('numero_serie', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nombre_dispositivo', 'like', '%' . $searchTerm . '%')
                  ->orWhere('direccion_ip', 'like', '%' . $searchTerm . '%')
                  ->orWhere('direccion_mac', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('responsable', function ($r) use ($searchTerm) {
                      $r->where('nombre_responsable', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('modelo.marca', function ($m) use ($searchTerm) {
                      $m->where('nombre_marca', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('modelo', function ($m) use ($searchTerm) {
                      $m->where('nombre_modelo', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('tipoEquipo', function ($t) use ($searchTerm) {
                      $t->where('nombre_tipo', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // 🏢 Filtrar por oficina
        $oficinaId = $request->input('oficina') ?? $request->input('oficina_id');
        if (!empty($oficinaId)) {
            $query->where('oficina_id', $oficinaId);
        }

        // 🏛️ Filtrar por agencia
        $agenciaId = $request->input('agencia') ?? $request->input('agencia_id');
        if (!empty($agenciaId)) {
            $query->whereHas('oficina', function ($q) use ($agenciaId) {
                $q->where('agencia_id', $agenciaId);
            });
        }

        // ⚡ Filtrar por estado
        if ($request->filled('estado_equipo')) {
            $query->where('estado_equipo', $request->estado_equipo);
        }

        $equipos = $query->orderBy('id', 'desc')->paginate(12)->withQueryString();

        // Necesario para los selects de los filtros
        $oficinas = Oficina::orderBy('nombre_oficina')->get();
        $agencias = Agencia::orderBy('nombre_agencia')->get();

        if ($request->ajax()) {
            return view('admin.equipos.partials.table', compact('equipos'))->render();
        }

        return view('admin.equipos.index', compact('equipos', 'oficinas', 'agencias'));
    }

    /**
     * Devuelve coincidencias en JSON para autocompletado en tiempo real.
     */
    public function sugerencias(Request $request)
    {
        $term = trim($request->input('q') ?? $request->input('serie') ?? $request->input('search') ?? '');
        if ($term === '') {
            return response()->json([]);
        }

        $query = Equipo::with([
            'oficina.agencia',
            'modelo.marca',
            'responsable',
            'tipoEquipo'
        ]);

        $query->where(function ($q) use ($term) {
            $q->where('numero_serie', 'like', "%{$term}%")
              ->orWhere('nombre_dispositivo', 'like', "%{$term}%")
              ->orWhere('direccion_ip', 'like', "%{$term}%")
              ->orWhere('direccion_mac', 'like', "%{$term}%")
              ->orWhereHas('responsable', function ($r) use ($term) {
                  $r->where('nombre_responsable', 'like', "%{$term}%");
              })
              ->orWhereHas('modelo.marca', function ($m) use ($term) {
                  $m->where('nombre_marca', 'like', "%{$term}%");
              })
              ->orWhereHas('modelo', function ($m) use ($term) {
                  $m->where('nombre_modelo', 'like', "%{$term}%");
              })
              ->orWhereHas('tipoEquipo', function ($t) use ($term) {
                  $t->where('nombre_tipo', 'like', "%{$term}%");
              });
        });

        // Filtrar por oficina / agencia si están presentes en la petición
        if ($request->filled('oficina')) {
            $query->where('oficina_id', $request->oficina);
        }
        if ($request->filled('agencia')) {
            $query->whereHas('oficina', fn($q) => $q->where('agencia_id', $request->agencia));
        }
        if ($request->filled('estado_equipo')) {
            $query->where('estado_equipo', $request->estado_equipo);
        }

        $equipos = $query->take(8)->get();

        $resultados = $equipos->map(function ($equipo) {
            $serie = $equipo->numero_serie ?: 'S/N';
            $nombre = $equipo->nombre_dispositivo ?: 'Equipo';
            $marca = $equipo->modelo?->marca?->nombre_marca ?? '';
            $modelo = $equipo->modelo?->nombre_modelo ?? '';
            $marcaModelo = trim($marca . ' ' . $modelo);
            $responsable = $equipo->responsable?->nombre_responsable ?? 'Sin asignar';
            $oficina = $equipo->oficina?->nombre_oficina ?? 'Sin oficina';

            return [
                'id'          => $equipo->id,
                'serie'       => $equipo->numero_serie,
                'value'       => $equipo->numero_serie ?: $equipo->nombre_dispositivo,
                'nombre'      => $nombre,
                'marcaModelo' => $marcaModelo,
                'responsable' => $responsable,
                'oficina'     => $oficina,
                'estado'      => $equipo->estado_equipo,
            ];
        });

        return response()->json($resultados);
    }

    public function create()
    {
        $oficinas = Oficina::all();
        $tipos = TipoEquipo::all();
        $hardwares = Hardware::all();
        $modelos = Modelo::all();
        $sistemas = SistemaOperativo::all();
        $responsables = Responsable::all();

        return view('admin.equipos.create', compact('oficinas', 'tipos', 'hardwares', 'modelos', 'sistemas', 'responsables'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre_dispositivo' => 'required|string|max:100',
            'numero_serie' => 'nullable|string|max:100|unique:equipos',
            'direccion_ip' => 'nullable|ip',
            'direccion_mac' => 'nullable|string|max:17',
            'fecha_adquisicion' => 'nullable|date',
            'estado_equipo' => 'required|in:Operativo,Operativo con Observacion,En mantenimiento,Fuera de servicio,De baja',
            'fecha_mantenimiento' => 'nullable|date',
            'oficina_id' => 'required|exists:oficinas,id',
            'tipoequipo_id' => 'required|exists:tipoequipos,id',
            'hardware_id' => 'required|exists:hardwares,id',
            'modelo_id' => 'required|exists:modelos,id',
            'sistemaoperativo_id' => 'required|exists:sistemaoperativos,id',
            'responsable_id' => 'nullable|exists:responsables,id',
            'vpn_cusco' => 'nullable|in:Sí,No',
            'vpn_abancay' => 'nullable|in:Sí,No',
            'antivirus' => 'nullable|string|max:100',
            'depreciacion_anual' => 'nullable|numeric|min:0|max:100',
            'programas_instalados' => 'nullable|string',
            'licencias' => 'nullable|string',
            'copias_seguridad' => 'nullable|string',
            'observacion' => 'nullable|string',
        ]);

        Equipo::create($request->validated());

        return redirect()->route('equipos.index')->with('success', 'Equipo creado correctamente.');
    }

    public function show(Equipo $equipo)
    {
        return view('admin.equipos.show', compact('equipo'));
    }

    public function edit(Equipo $equipo)
    {
        $oficinas = Oficina::all();
        $tipoequipos = TipoEquipo::all();
        $hardwares = Hardware::all();
        $modelos = Modelo::all();
        $sistemas = SistemaOperativo::all();
        $responsables = Responsable::all();

        return view('admin.equipos.edit', compact('equipo', 'oficinas', 'tipoequipos', 'hardwares', 'modelos', 'sistemas', 'responsables'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $request->validate([
            'nombre_dispositivo' => 'required|string|max:100',
            'numero_serie' => 'nullable|string|max:100|unique:equipos,numero_serie,' . $equipo->id,
            'direccion_ip' => 'nullable|ip',
            'direccion_mac' => 'nullable|string|max:17',
            'fecha_adquisicion' => 'nullable|date',
            'estado_equipo' => 'required|in:Operativo,Operativo con Observacion,En mantenimiento,Fuera de servicio,De baja',
            'fecha_mantenimiento' => 'nullable|date',
            'oficina_id' => 'required|exists:oficinas,id',
            'tipoequipo_id' => 'required|exists:tipoequipos,id',
            'hardware_id' => 'required|exists:hardwares,id',
            'modelo_id' => 'required|exists:modelos,id',
            'sistemaoperativo_id' => 'required|exists:sistemaoperativos,id',
            'responsable_id' => 'nullable|exists:responsables,id',
            'vpn_cusco' => 'nullable|in:Sí,No',
            'vpn_abancay' => 'nullable|in:Sí,No',
            'antivirus' => 'nullable|string|max:100',
            'depreciacion_anual' => 'nullable|numeric|min:0|max:100',
            'programas_instalados' => 'nullable|string',
            'licencias' => 'nullable|string',
            'copias_seguridad' => 'nullable|string',
            'observacion' => 'nullable|string',
        ]);

        $equipo->update($request->validated());

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado correctamente.');
    }

    public function exportarExcel(Request $request)
    {
        $query = Equipo::with([
            'oficina.agencia',
            'tipoEquipo',
            'hardware',
            'modelo.marca',
            'sistemaOperativo',
            'responsable',
        ]);

        $searchTerm = trim($request->input('serie') ?? $request->input('search') ?? '');
        if ($searchTerm !== '') {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('numero_serie', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nombre_dispositivo', 'like', '%' . $searchTerm . '%')
                  ->orWhere('direccion_ip', 'like', '%' . $searchTerm . '%')
                  ->orWhere('direccion_mac', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('responsable', fn($r) => $r->where('nombre_responsable', 'like', '%' . $searchTerm . '%'))
                  ->orWhereHas('modelo.marca', fn($m) => $m->where('nombre_marca', 'like', '%' . $searchTerm . '%'))
                  ->orWhereHas('modelo', fn($m) => $m->where('nombre_modelo', 'like', '%' . $searchTerm . '%'))
                  ->orWhereHas('tipoEquipo', fn($t) => $t->where('nombre_tipo', 'like', '%' . $searchTerm . '%'));
            });
        }
        $oficinaId = $request->input('oficina') ?? $request->input('oficina_id');
        if (!empty($oficinaId)) {
            $query->where('oficina_id', $oficinaId);
        }
        $agenciaId = $request->input('agencia') ?? $request->input('agencia_id');
        if (!empty($agenciaId)) {
            $query->whereHas('oficina', fn($q) => $q->where('agencia_id', $agenciaId));
        }
        if ($request->filled('estado_equipo')) {
            $query->where('estado_equipo', $request->estado_equipo);
        }

        $equipos = $query->orderBy('id', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Equipos');

        // ── Cabeceras ──────────────────────────────────────────────────────────
        $columnas = [
            'A' => 'CÓDIGO AGENCIA',
            'B' => 'NOMBRE DE AGENCIA',
            'C' => 'NOMBRE DE OFICINA',
            'D' => 'NOMBRE EQUIPO',
            'E' => 'SERIE',
            'F' => 'MARCA',
            'G' => 'MODELO',
            'H' => 'TIPO',
            'I' => 'SISTEMA OPERATIVO',
            'J' => 'IP',
            'K' => 'MAC',
            'L' => 'FECHA DE COMPRA',
            'M' => 'FECHA MANT. PC',
            'N' => 'RESPONSABLE',
            'O' => 'ESTADO',
            'P' => 'CPU / PROCESADOR',
            'Q' => 'RAM (GB)',
            'R' => 'DISCO / ALMACENAMIENTO',
            'S' => 'ANTIVIRUS',
        ];

        foreach ($columnas as $col => $titulo) {
            $celda = $col . '1';
            $sheet->setCellValue($celda, $titulo);
            $sheet->getStyle($celda)->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F6FEB']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCCCCCC']]],
            ]);
        }

        // ── Filas de datos ─────────────────────────────────────────────────────
        $fila = 2;
        foreach ($equipos as $equipo) {
            $datos = [
                'A' => $equipo->oficina?->agencia?->codigo_agencia ?? 'N/A',
                'B' => $equipo->oficina?->agencia?->nombre_agencia  ?? 'N/A',
                'C' => $equipo->oficina?->nombre_oficina             ?? 'N/A',
                'D' => $equipo->nombre_dispositivo                   ?? 'N/A',
                'E' => $equipo->numero_serie                         ?? 'N/A',
                'F' => $equipo->modelo?->marca?->nombre_marca        ?? 'N/A',
                'G' => $equipo->modelo?->nombre_modelo               ?? 'N/A',
                'H' => $equipo->tipoEquipo?->nombre_tipo             ?? 'N/A',
                'I' => $equipo->sistema_operativo_completo           ?? 'N/A',
                'J' => $equipo->direccion_ip                         ?? 'N/A',
                'K' => $equipo->direccion_mac                        ?? 'N/A',
                'L' => $equipo->fecha_adquisicion?->format('d/m/Y')  ?? 'N/A',
                'M' => $equipo->fecha_mantenimiento?->format('d/m/Y') ?? 'N/A',
                'N' => $equipo->responsable?->nombre_responsable     ?? 'N/A',
                'O' => $equipo->estado_equipo                        ?? 'N/A',
                'P' => $equipo->hardware?->procesador                ?? 'N/A',
                'Q' => $equipo->hardware?->ram_gb                    ?? 'N/A',
                'R' => ($equipo->hardware?->almacenamiento_gb ?? 'N/A')
                        . ' ' . ($equipo->hardware?->tipo_almacenamiento ?? ''),
                'S' => $equipo->antivirus                            ?? 'N/A',
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

        // ── Ancho automático de columnas ───────────────────────────────────────
        foreach (array_keys($columnas) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ── Descarga ───────────────────────────────────────────────────────────
        $writer   = new Xlsx($spreadsheet);
        $fileName = 'Reporte_Equipos_' . now()->format('Ymd_His') . '.xlsx';
        $tmpFile  = tempnam(sys_get_temp_dir(), 'equipo_excel_');
        $writer->save($tmpFile);

        return response()->download($tmpFile, $fileName)->deleteFileAfterSend(true);
    }
}
