<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Impresora;
use App\Models\Termica;
use App\Models\Contabillete;
use App\Models\Incidencia;
use App\Models\ServicioInternet;
use App\Models\Agencia;
use App\Models\Oficina;
use App\Models\MantenimientoPc;
use App\Models\MantenimientoImpresora;
use App\Models\MantenimientoTermica;
use App\Models\MantenimientoContabillete;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        // 1. Métricas Principales (KPIs)
        $totalEquipos = Equipo::count();
        $equiposOperativos = Equipo::where('estado_equipo', 'like', '%Operativo%')->where('estado_equipo', 'not like', '%Observacion%')->count();
        $equiposObservacion = Equipo::where('estado_equipo', 'like', '%Observacion%')->count();
        $equiposBaja = Equipo::whereIn('estado_equipo', ['De baja', 'Fuera de servicio'])->count();

        $totalImpresoras = Impresora::count();
        $totalTermicas = Termica::count();
        $totalContabilletes = Contabillete::count();
        $totalServicios = ServicioInternet::count();
        $totalAgencias = Agencia::count();
        $totalOficinas = Oficina::count();

        // 2. Métricas de Incidencias
        $totalIncidencias = Incidencia::count();
        $incidenciasAtendidas = Incidencia::where('estado', 'Atendido')->count();
        $incidenciasPendientes = Incidencia::where('estado', 'Pendiente')->count();
        $incidenciasDerivadas = Incidencia::where('estado', 'Derivado')->count();
        $tasaResolucion = $totalIncidencias > 0 ? round(($incidenciasAtendidas / $totalIncidencias) * 100, 1) : 0;

        // 3. Total Mantenimientos
        $totalMantenimientos = MantenimientoPc::count() 
            + MantenimientoImpresora::count() 
            + MantenimientoTermica::count() 
            + MantenimientoContabillete::count();

        // 4. Datos para Gráficos
        // A) Equipos por Estado
        $equiposPorEstado = Equipo::selectRaw('estado_equipo, COUNT(*) as total')
            ->whereNotNull('estado_equipo')
            ->groupBy('estado_equipo')
            ->orderByDesc('total')
            ->pluck('total', 'estado_equipo')
            ->toArray();

        // B) Incidencias por Estado
        $incidenciasPorEstado = Incidencia::selectRaw('estado, COUNT(*) as total')
            ->whereNotNull('estado')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        // C) Incidencias por Tipo (Software, Hardware, Conectividad)
        $incidenciasPorTipo = Incidencia::selectRaw('tipo, COUNT(*) as total')
            ->whereNotNull('tipo')
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->pluck('total', 'tipo')
            ->toArray();

        // D) Top Módulos con más incidencias
        $topModulos = Incidencia::selectRaw('modulo, COUNT(*) as total')
            ->whereNotNull('modulo')
            ->groupBy('modulo')
            ->orderByDesc('total')
            ->take(5)
            ->pluck('total', 'modulo')
            ->toArray();

        // E) Tendencia Mensual (Últimos 6 meses)
        $mesesData = [];
        for ($i = 5; $i >= 0; $i--) {
            $mesDate = Carbon::now()->subMonths($i);
            $key = $mesDate->format('Y-m');
            $mesesData[$key] = [
                'label' => ucfirst($mesDate->locale('es')->translatedFormat('M Y')),
                'total' => 0
            ];
        }

        $mesesDb = Incidencia::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total")
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        foreach ($mesesDb as $mes => $count) {
            if (isset($mesesData[$mes])) {
                $mesesData[$mes]['total'] = $count;
            }
        }

        $tendenciaLabels = array_column($mesesData, 'label');
        $tendenciaTotales = array_column($mesesData, 'total');

        // 5. Listas de Atención y Actividad
        // A) Incidencias que requieren atención (Pendientes y Derivadas prioritarias)
        $incidenciasAtencion = Incidencia::with('atendidoPor')
            ->whereIn('estado', ['Pendiente', 'Derivado'])
            ->latest()
            ->take(6)
            ->get();

        // B) Actividad Reciente General
        $incidenciasRecientes = Incidencia::with('atendidoPor')
            ->latest()
            ->take(6)
            ->get();

        // C) Top Agencias con mayor demanda
        $topAgencias = Incidencia::selectRaw('agencia, COUNT(*) as total')
            ->whereNotNull('agencia')
            ->groupBy('agencia')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalEquipos',
            'equiposOperativos',
            'equiposObservacion',
            'equiposBaja',
            'totalImpresoras',
            'totalTermicas',
            'totalContabilletes',
            'totalServicios',
            'totalAgencias',
            'totalOficinas',
            'totalIncidencias',
            'incidenciasAtendidas',
            'incidenciasPendientes',
            'incidenciasDerivadas',
            'tasaResolucion',
            'totalMantenimientos',
            'equiposPorEstado',
            'incidenciasPorEstado',
            'incidenciasPorTipo',
            'topModulos',
            'tendenciaLabels',
            'tendenciaTotales',
            'incidenciasAtencion',
            'incidenciasRecientes',
            'topAgencias'
        ));
    }
}
