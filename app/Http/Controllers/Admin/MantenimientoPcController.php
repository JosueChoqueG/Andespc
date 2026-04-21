<?php
// app/Http/Controllers/Admin/MantenimientoPcController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\MantenimientoPc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MantenimientoPcController extends Controller
{
    public function create(Equipo $equipo)
    {
        $ultimoMantenimiento = $equipo->ultimoMantenimiento;
        $tecnico = Auth::user()->name ?? 'Josue Choque Gomez';
        
        return view('admin.mantenimientos-pc.create', compact('equipo', 'ultimoMantenimiento', 'tecnico'));
    }

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

        // Actualizar la fecha de último mantenimiento en el equipo
        $equipo->update([
            'fecha_mantenimiento' => $request->fecha_mantenimiento,
            'observacion' => $request->observaciones
        ]);

        if ($request->has('generar_hoja_vida')) {
            return redirect()->route('admin.equipos.hoja-vida-mantenimiento', $mantenimiento);
        }

        return redirect()->route('equipos.show', $equipo)
            ->with('success', 'Mantenimiento registrado exitosamente.');
    }

    public function show(MantenimientoPc $mantenimiento)
    {
        $equipo = $mantenimiento->equipo;
        return view('admin.mantenimientos-pc.show', compact('mantenimiento', 'equipo'));
    }

    public function historial(Equipo $equipo)
    {
        $mantenimientos = $equipo->mantenimientos()->paginate(10);
        return view('admin.mantenimientos-pc.historial', compact('equipo', 'mantenimientos'));
    }

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
            
        $ultimoMantenimiento = $equipo->ultimoMantenimiento;

        return view('admin.equipos.hoja-vida', compact(
            'equipo', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial',
            'ultimoMantenimiento'
        ));
    }

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
            
        $ultimoMantenimiento = $equipo->ultimoMantenimiento;

        $pdf = PDF::loadView('admin.equipos.hoja-vida-pdf', compact(
            'equipo', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial',
            'ultimoMantenimiento'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Hoja-Vida-{$equipo->nombre_dispositivo}-" . date('Y-m-d') . ".pdf");
    }

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

        $pdf = PDF::loadView('admin.equipos.hoja-vida-pdf', compact(
            'equipo', 
            'mantenimiento', 
            'tecnico', 
            'historialMantenimientos',
            'fallasHistorial'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Hoja-Vida-{$equipo->nombre_dispositivo}-" . $mantenimiento->fecha_mantenimiento->format('Y-m-d') . ".pdf");
    }
}