<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Puedes agregar más middlewares si necesitas, ej:
        // $this->middleware('verified'); // si usas verificación de email
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

        return redirect()->route('admin.incidencias.listado')->with('success', 'Incidencia registrada.');
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
}