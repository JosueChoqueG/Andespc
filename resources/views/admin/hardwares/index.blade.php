@extends('layouts.app')

@section('title', 'Hardware Registrado')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Configuraciones de Hardware</h2>
    <a href="{{ route('hardwares.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Hardware
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
            <tr>
                <!-- <th>ID</th> -->
                <th>Procesador</th>
                <th>RAM</th>
                <th>Almacenamiento</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hardwares as $hw)
                <tr>
                    <td>{{ $hw->id_hardware }}</td>
                    <td>{{ $hw->procesador ?? 'N/A' }}</td>
                    <td>{{ $hw->ram_gb }} GB</td>
                    <td>{{ $hw->almacenamiento_gb }} GB</td>
                    <td>
                        <span class="badge bg-{{ $hw->tipo_almacenamiento == 'SSD' ? 'success' : ($hw->tipo_almacenamiento == 'NVMe' ? 'primary' : 'secondary') }}">
                            {{ $hw->tipo_almacenamiento }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('hardwares.show', $hw) }}" class="btn btn-sm btn-info" title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('hardwares.edit', $hw) }}" class="btn btn-sm btn-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('hardwares.destroy', $hw) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este hardware? Esta acción no se puede deshacer.')">
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
                    <td colspan="6" class="text-center text-muted">No hay configuraciones de hardware registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection