<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Responsable;
use App\Models\Oficina;
use App\Models\Agencia;
use Illuminate\Http\Request;

class ResponsableController extends Controller
{
    public function index(Request $request)
    {
        $query = Responsable::with(['oficina.agencia']);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('nombre_responsable', 'LIKE', "%{$s}%")
                  ->orWhere('cargo', 'LIKE', "%{$s}%")
                  ->orWhereHas('oficina', function($qOfi) use ($s) {
                      $qOfi->where('nombre_oficina', 'LIKE', "%{$s}%")
                           ->orWhereHas('agencia', function($qAg) use ($s) {
                               $qAg->where('nombre_agencia', 'LIKE', "%{$s}%");
                           });
                  });
            });
        }

        if ($request->filled('oficina_id')) {
            $query->where('oficina_id', $request->oficina_id);
        }

        if ($request->filled('cargo')) {
            $query->where('cargo', $request->cargo);
        }

        $responsables = $query->orderBy('nombre_responsable')->paginate(25)->withQueryString();
        $allResponsables = Responsable::orderBy('nombre_responsable')->get();
        $oficinas = Oficina::with('agencia')->orderBy('nombre_oficina')->get();
        $cargos = Responsable::whereNotNull('cargo')->where('cargo', '!=', '')->distinct()->orderBy('cargo')->pluck('cargo');
        
        return view('admin.responsables.index', compact('responsables', 'allResponsables', 'oficinas', 'cargos'));
    }

    public function create()
    {
        $oficinas = Oficina::with('agencia')->orderBy('nombre_oficina')->get();
        $cargosComunes = [
            'Administrador',
            'Asesor Financiero',
            'Asistente de Admisión',
            'Cajero(a)',
            'Jefe de Operaciones',
            'Soporte TI',
            'Gerente de Agencia',
            'Auxiliar de Operaciones'
        ];
        return view('admin.responsables.create', compact('oficinas', 'cargosComunes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_responsable' => 'required|string|max:100|unique:responsables,nombre_responsable',
            'cargo' => 'nullable|string|max:150',
            'oficina_id' => 'nullable|exists:oficinas,id',
        ]);

        Responsable::create($validated);

        return redirect()->route('responsables.index')
            ->with('success', 'Responsable registrado correctamente.');
    }

    public function show(Responsable $responsable)
    {
        $responsable->load(['oficina.agencia', 'equipos', 'impresoras', 'termicas', 'contabilletes']);
        return view('admin.responsables.show', compact('responsable'));
    }

    public function edit(Responsable $responsable)
    {
        $oficinas = Oficina::with('agencia')->orderBy('nombre_oficina')->get();
        $cargosComunes = [
            'Administrador',
            'Asesor Financiero',
            'Asistente de Admisión',
            'Cajero(a)',
            'Jefe de Operaciones',
            'Soporte TI',
            'Gerente de Agencia',
            'Auxiliar de Operaciones'
        ];
        return view('admin.responsables.edit', compact('responsable', 'oficinas', 'cargosComunes'));
    }

    public function update(Request $request, Responsable $responsable)
    {
        $validated = $request->validate([
            'nombre_responsable' => 'required|string|max:100|unique:responsables,nombre_responsable,' . $responsable->id,
            'cargo' => 'nullable|string|max:150',
            'oficina_id' => 'nullable|exists:oficinas,id',
        ]);

        $responsable->update($validated);

        return redirect()->route('responsables.index')
            ->with('success', 'Responsable actualizado correctamente.');
    }

    public function destroy(Responsable $responsable)
    {
        $responsable->delete();

        return redirect()->route('responsables.index')
            ->with('success', 'Responsable eliminado correctamente.');
    }
}
