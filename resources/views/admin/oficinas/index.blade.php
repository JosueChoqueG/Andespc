@extends('layouts.app')

@section('title', 'Oficinas')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Oficinas</h2>
    <a href="{{ route('oficinas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Oficina
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<table class="table table-striped table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Agencia</th>
            <th>Ubicación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($oficinas as $oficina)
            <tr>
                <td>{{ $oficina->id_oficina }}</td>
                <td>{{ $oficina->nombre_oficina }}</td>
                <td>{{ $oficina->agencia->nombre_agencia ?? 'N/A' }}</td>
                <td>{{ $oficina->ubicacion_equipo ?? 'No especificada' }}</td>
                <td>
                    <a href="{{ route('oficinas.show', $oficina) }}" class="btn btn-sm btn-info" title="Ver">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('oficinas.edit', $oficina) }}" class="btn btn-sm btn-warning" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('oficinas.destroy', $oficina) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta oficina? Esta acción no se puede deshacer.')">
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
                <td colspan="5" class="text-center text-muted">No hay oficinas registradas.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection