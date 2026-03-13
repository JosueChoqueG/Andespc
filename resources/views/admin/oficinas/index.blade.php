@extends('layouts.app')

@section('title', 'Oficinas')

@section('content')

<div>
    <h2>Oficinas</h2>
    <a href="{{ route('oficinas.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Nueva Oficina 
    </a>
</div>
<div class="card shadow-sm d-inline-block">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle w-auto">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Agencia</th>
                        <th class="text-center text-nowrap">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($oficinas as $oficina)
                        <tr>
                            <td>{{ $oficina->nombre_oficina }}</td>
                            <td>{{ $oficina->agencia->nombre_agencia ?? 'N/A' }}</td>

                            <td class="text-center text-nowrap">

                                <a href="{{ route('oficinas.show', $oficina) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('oficinas.edit', $oficina) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('oficinas.destroy', $oficina) }}" method="POST" class="d-inline-block"
                                    onsubmit="return confirm('¿Eliminar esta oficina? Esta acción no se puede deshacer.')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                No hay oficinas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection