@extends('layouts.app')

@section('title', 'Listado de Equipos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-pc-display"></i> Equipos Registrados</h2>
    <a href="{{ route('equipos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Equipo
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <strong><i class="bi bi-list-check"></i> Lista de Equipos</strong>
        <!-- Buscador -->
        <form action="{{ route('equipos.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Buscar..." value="{{ request('search') }}">
            <button class="btn btn-sm btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Dispositivo</th>
                        <th>Serie</th>
                        <th>IP</th>
                        <th>Oficina</th>
                        <th>Tipo</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipos as $equipo)
                        <tr>
                            <td>{{ $equipo->nombre_dispositivo }}</td>
                            <td>{{ $equipo->numero_serie ?? 'N/A' }}</td>
                            <td>{{ $equipo->direccion_ip ?? 'N/A' }}</td>
                            <td>{{ $equipo->oficina->nombre_oficina ?? 'Sin oficina' }}</td>
                            <td>{{ $equipo->tipoequipo->nombre_tipo ?? 'Sin tipo' }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $equipo->estado_equipo == 'Activo' ? 'success' : ($equipo->estado_equipo == 'Inactivo' ? 'warning' : 'danger') }}">
                                    {{ $equipo->estado_equipo }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este equipo?')">
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
                            <td colspan="7" class="text-center text-muted">
                                <i class="bi bi-exclamation-circle"></i> No hay equipos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-3">
            {{ $equipos->links() }}
        </div>
    </div>
</div>
@endsection
