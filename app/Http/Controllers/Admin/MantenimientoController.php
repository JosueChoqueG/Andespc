<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mantenimiento;
use App\Models\Impresora;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function create(Impresora $impresora)
    {
        return view('admin.mantenimientos.create', compact('impresora'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'impresora_id' => 'required|exists:impresoras,id',
            'tipo_error' => 'required',
            'descripcion' => 'nullable',
            'fecha_envio' => 'nullable|date',
            'fecha_retorno' => 'nullable|date',
        ]);

        Mantenimiento::create($request->all());

        return redirect()
            ->route('admin.impresoras.show', $request->impresora_id)
            ->with('success', 'Mantenimiento registrado');
    }
}
