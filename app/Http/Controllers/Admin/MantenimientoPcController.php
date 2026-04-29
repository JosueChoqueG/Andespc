<?php
// app/Http/Controllers/Admin/MantenimientoPcController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\MantenimientoPc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class MantenimientoPcController extends Controller
{
    /**
     * Opciones comunes para DomPDF
     */
    private function getPdfOptions()
    {
        return [
            'dpi' => 96,
            'defaultFont' => 'DejaVu Sans', // Soporta tildes, ñ, etc.
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => false,
            'isJavascriptEnabled' => false,
        ];
    }

    /**
     * Obtener ruta absoluta del logo compatible con DomPDF
     */
    private function getLogoPath()
    {
        $logoPath = public_path('logo.jpeg');
        
        // Si no existe en public/, buscar en storage/
        if (!File::exists($logoPath)) {
            $logoPath = storage_path('app/public/logo.jpeg');
        }
        
        // Fallback: retornar ruta base64 si la imagen no existe
        if (!File::exists($logoPath)) {
            return null;
        }
        
        return $logoPath;
    }

    /**
     * Mostrar formulario para crear un nuevo mantenimiento
     */
    public function create(Equipo $equipo)
    {
        $ultimoMantenimiento = $equipo->mantenimientos()->latest()->first();
        $tecnico = Auth::user()->name ?? 'Josue Choque Gomez';
        
        return view('admin.mantenimientos-pc.create', compact('equipo', 'ultimoMantenimiento', 'tecnico'));
    }

    /**
     * Guardar un nuevo mantenimiento
     */
    public function store(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'tipo_mantenimiento' => 'required|in:Preventivo,Correctivo',
            'fecha_mantenimiento' => 'required|date',
            'descripcion_mantenimiento' => 'required|string',
            'fallas_encontradas' => 'nullable|string',
            'soluciones_aplicadas' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $validated['equipo_id'] = $equipo->id;
        $validated['user_id'] = Auth::id();

        $mantenimiento = MantenimientoPc::create($validated);

        $equipo->update([
            'fecha_mantenimiento' => $request->fecha_mantenimiento,
            'observacion' => $request->observaciones
        ]);

        if ($request->has('generar_hoja_vida')) {
            return redirect()->route('admin.equipos.hoja-vida-mantenimiento', $mantenimiento);
        }

        return redirect()->route('admin.equipos.index', $equipo)
            ->with('success', 'Mantenimiento registrado exitosamente.');
    }

    /**
     * Mostrar detalles de un mantenimiento
     */
    public function show(MantenimientoPc $mantenimiento)
    {
        $equipo = $mantenimiento->equipo;
        return view('admin.mantenimientos-pc.show', compact('mantenimiento', 'equipo'));
    }

    /**
     * Mostrar historial de mantenimientos de un equipo
     */
    public function historial(Equipo $equipo)
    {
        $mantenimientos = $equipo->mantenimientos()->paginate(10);
        return view('admin.mantenimientos-pc.historial', compact('equipo', 'mantenimientos'));
    }

    /**
     * Generar hoja de vida basada en un mantenimiento específico (Vista HTML)
     */
    public function generarHojaVida(MantenimientoPc $mantenimiento)
    {
        $equipo = $mantenimiento->equipo;
        $tecnico = $mantenimiento->tecnico_nombre;
        
        $historialMantenimientos = $equipo->mantenimientos()
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $fallasHistorial = $equipo->mantenimientos()
            ->whereNotNull('fallas_encontradas')
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();

        return view('admin.equipos.hoja-vida', compact(
            'equipo', 
            'mantenimiento', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
    }

    /**
     * Generar hoja de vida general del equipo (Vista HTML)
     */
    public function generarHojaVidaEquipo(Equipo $equipo)
    {
        $tecnico = Auth::user()->name ?? 'Josue Choque Gomez';
        
        $historialMantenimientos = $equipo->mantenimientos()
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $fallasHistorial = $equipo->mantenimientos()
            ->whereNotNull('fallas_encontradas')
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $ultimoMantenimiento = $equipo->mantenimientos()->latest()->first();

        return view('admin.equipos.hoja-vida', compact(
            'equipo', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial',
            'ultimoMantenimiento'
        ));
    }

    /**
     * Descargar hoja de vida en PDF (Equipo general)
     */
    public function descargarHojaVidaPDF(Equipo $equipo)
    {
        $tecnico = Auth::user()->name ?? 'Josue Choque Gomez';
        
        $historialMantenimientos = $equipo->mantenimientos()
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $fallasHistorial = $equipo->mantenimientos()
            ->whereNotNull('fallas_encontradas')
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $ultimoMantenimiento = $equipo->mantenimientos()->latest()->first();
        $logoPath = $this->getLogoPath();

        $pdf = PDF::loadView('admin.equipos.hoja-vida-pdf', compact(
            'equipo', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial',
            'ultimoMantenimiento',
            'logoPath'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions($this->getPdfOptions());
        
        return $pdf->download("Hoja-Vida-{$equipo->nombre_dispositivo}-" . date('Y-m-d') . ".pdf");
    }

    /**
     * Descargar hoja de vida de mantenimiento específico en PDF
     */
    public function descargarHojaVidaMantenimientoPDF(MantenimientoPc $mantenimiento)
    {
        $equipo = $mantenimiento->equipo;
        $tecnico = $mantenimiento->tecnico_nombre;
        
        $historialMantenimientos = $equipo->mantenimientos()
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $fallasHistorial = $equipo->mantenimientos()
            ->whereNotNull('fallas_encontradas')
            ->orderBy('fecha_mantenimiento', 'desc')
            ->take(10)
            ->get();
            
        $logoPath = $this->getLogoPath();

        $pdf = PDF::loadView('admin.equipos.hoja-vida-pdf', compact(
            'equipo', 
            'mantenimiento', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial',
            'logoPath'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions($this->getPdfOptions());
        
        return $pdf->download("Hoja-Vida-{$equipo->nombre_dispositivo}-" . $mantenimiento->fecha_mantenimiento->format('Y-m-d') . ".pdf");
    }
}