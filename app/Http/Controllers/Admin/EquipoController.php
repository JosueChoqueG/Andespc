<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function create()
    {
        return view('admin.equipos.create', [
            'oficinas' => Oficina::with('agencias')->get(),
            'tipos' => TipoEquipo::all(),
            'modelos' => Modelo::with('marca')->get(),
            'hardwares' => Hardware::all(),
            'sistemas' => SistemaOperativo::all(),
            'responsables' => Responsable::all(),
        ]);
    }

    public function edit(Equipo $equipos)
    {
        return view('admin.equipos.edit', [
            'equipo' => $equipos,
            'oficinas' => Oficina::with('agencias')->get(),
            'tipos' => TipoEquipo::all(),
            'modelos' => Modelo::with('marca')->get(),
            'hardwares' => Hardware::all(),
            'sistemas' => SistemaOperativo::all(),
            'responsables' => Responsable::all(),
        ]);
    }

    public function index()
    {
        $equipos = Equipo::with(['oficina.agencia', 'tipo', 'modelo.marca', 'hardware', 'sistemaOperativo', 'responsable'])->get();
        return view('admin.equipos.index', compact('equipos'));
    }

    public function show(Equipo $equipos)
    {
        $equipos->load(['oficina.agencia', 'tipo', 'modelo.marca', 'hardware', 'sistemaOperativo', 'responsable']);
        return view('admin.equipos.show', compact('equipos'));
    }
}
