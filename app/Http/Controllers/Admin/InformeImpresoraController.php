<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Impresora;
use App\Models\InformeImpresora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InformeImpresoraController extends Controller
{
    /**
     * Muestra el historial de todos los informes.
     */
    public function index(Request $request)
    {
        $query = InformeImpresora::with('impresora');

        if ($request->filled('impresora_id')) {
            $query->where('impresora_id', $request->impresora_id);
        }

        if ($request->filled('tipo_informe')) {
            $query->where('tipo_informe', $request->tipo_informe);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_informe', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $informes    = $query->orderBy('fecha_informe', 'desc')->paginate(15);
        $impresoras  = Impresora::orderBy('serie_impresora')->get();

        return view('admin.informe-impresora.index', compact('informes', 'impresoras'));
    }

    /**
     * Muestra el formulario para crear un nuevo informe.
     */
    public function create(Impresora $impresora)
    {
        $tecnico = Auth::user()->name ?? 'Josue Choque Gomez';

        return view('admin.informe-impresora.create', compact('impresora', 'tecnico'));
    }

    /**
     * Guarda el informe en la base de datos.
     */
    public function store(Request $request, Impresora $impresora)
    {
        $validator = Validator::make($request->all(), [
            'fecha_informe'       => 'required|date',
            'tecnico_nombre'      => 'required|string|max:150',
            'tipo_informe'        => 'required|in:' . implode(',', InformeImpresora::TIPOS_INFORME),
            'incidencias'         => 'required|string',
            'procedimientos'      => 'nullable|array',
            'procedimientos.*'    => 'nullable|string|max:500',
            'descripcion_problema'=> 'nullable|string',
            'diagnostico'         => 'nullable|string',
            'acciones_realizadas' => 'nullable|string',
            'contador_copias'     => 'nullable|integer|min:0',
            'imagen_01'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'imagen_01_caption'   => 'nullable|string|max:255',
            'imagen_02'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'imagen_02_caption'   => 'nullable|string|max:255',
            'recomendaciones'     => 'nullable|string',
            'estado_equipo'       => 'required|in:OPTIMO,BUENO,REGULAR,DEFICIENTE',
            'en_garantia'         => 'boolean',
            'observaciones'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['imagen_01', 'imagen_02']);
        $data['impresora_id'] = $impresora->id;
        $data['en_garantia']  = $request->boolean('en_garantia');

        // Filtrar procedimientos vacíos
        if (isset($data['procedimientos']) && is_array($data['procedimientos'])) {
            $data['procedimientos'] = array_values(array_filter($data['procedimientos'], fn($p) => trim($p) !== ''));
        }

        // Subida de imágenes
        if ($request->hasFile('imagen_01')) {
            $data['imagen_01_path'] = $request->file('imagen_01')->store('informes/impresoras', 'public');
        }
        if ($request->hasFile('imagen_02')) {
            $data['imagen_02_path'] = $request->file('imagen_02')->store('informes/impresoras', 'public');
        }

        $informe = InformeImpresora::create($data);

        return redirect()->route('admin.informes-impresora.show', $informe->id)
            ->with('success', 'Informe registrado correctamente.');
    }

    /**
     * Muestra un informe específico.
     */
    public function show(InformeImpresora $informesImpresora)
    {
        $informesImpresora->load('impresora.oficina');
        return view('admin.informe-impresora.show', ['informe' => $informesImpresora]);
    }

    /**
     * Muestra el historial de informes de una impresora específica.
     */
    public function historial(Impresora $impresora)
    {
        $informes = InformeImpresora::where('impresora_id', $impresora->id)
            ->orderBy('fecha_informe', 'desc')
            ->get();

        $estadisticas = [
            'total'           => $informes->count(),
            'atascos'         => $informes->where('tipo_informe', 'Atasco de Papel')->count(),
            'fallas'          => $informes->where('tipo_informe', 'Falla')->count(),
            'mantenimientos'  => $informes->where('tipo_informe', 'Mantenimiento')->count(),
            'ultimo_informe'  => $informes->first(),
        ];

        return view('admin.informe-impresora.historial', compact('impresora', 'informes', 'estadisticas'));
    }

    /**
     * Elimina un informe.
     */
    public function destroy(InformeImpresora $informesImpresora)
    {
        $impresoraId = $informesImpresora->impresora_id;

        // Eliminar imágenes del storage
        if ($informesImpresora->imagen_01_path) {
            Storage::disk('public')->delete($informesImpresora->imagen_01_path);
        }
        if ($informesImpresora->imagen_02_path) {
            Storage::disk('public')->delete($informesImpresora->imagen_02_path);
        }

        $informesImpresora->delete();

        return redirect()->route('admin.informes-impresora.index')
            ->with('success', 'Informe eliminado correctamente.');
    }

    /**
     * Muestra la vista web imprimible del informe.
     */
    public function imprimir(InformeImpresora $informesImpresora)
    {
        $informesImpresora->load('impresora.oficina', 'impresora.responsable');
        return view('admin.informe-impresora.imprimir', ['informe' => $informesImpresora]);
    }

    /**
     * Descarga el informe en PDF.
     */
    public function descargarPdf(InformeImpresora $informesImpresora)
    {
        $informesImpresora->load('impresora.oficina', 'impresora.responsable');

        // Ruta absoluta al logo para que DomPDF pueda embebarlo
        $logoPath = public_path('img/andes.png');
        if (!is_file($logoPath)) {
            $logoPath = null;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.informe-impresora.pdf',
            [
                'informe'  => $informesImpresora,
                'logoPath' => $logoPath,
            ]
        );

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Informe-IMP-'
            . str_pad($informesImpresora->id, 4, '0', STR_PAD_LEFT)
            . '-' . $informesImpresora->impresora->serie_impresora
            . '-' . $informesImpresora->fecha_informe->format('Y-m-d')
            . '.pdf';

        return $pdf->download($filename);
    }
}

