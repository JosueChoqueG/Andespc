<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Oficina;
use App\Models\Tipoequipo;
use App\Models\Modelo;
use App\Models\Hardware;
use App\Models\SistemaOperativo;
use App\Models\Responsable;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function create()
    {
        return view('admin.equipos.create', [
            'oficinas' => Oficina::with('agencia')->get(),
            'tipos' => Tipoequipo::all(),
            'modelos' => Modelo::with('marca')->get(),
            'hardwares' => Hardware::all(),
            'sistemas' => SistemaOperativo::all(),
            'responsables' => Responsable::all(),
        ]);
    }

    public function edit(Equipo $equipo)
    {
        return view('admin.equipos.edit', [
            'equipo' => $equipo,
            'oficinas' => Oficina::with('agencia')->get(),
            'tipos' => Tipoequipo::all(),
            'modelos' => Modelo::with('marca')->get(),
            'hardwares' => Hardware::all(),
            'sistemas' => SistemaOperativo::all(),
            'responsables' => Responsable::all(),
        ]);
    }

    public function index()
    {
        $equipos = Equipo::with([
            'oficina.agencia',
            'tipo',
            'modelo.marca',
            'hardware',
            'sistemaOperativo',
            'responsable'
        ])->get();

        return view('admin.equipos.index', compact('equipos'));
    }

    public function show(Equipo $equipo)
    {
        $equipo->load([
            'oficina.agencia',
            'tipo',
            'modelo.marca',
            'hardware',
            'sistemaOperativo',
            'responsable'
        ]);

        return view('admin.equipos.show', compact('equipo'));
    }
}

