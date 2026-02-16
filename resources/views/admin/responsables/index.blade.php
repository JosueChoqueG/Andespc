@extends('layouts.app')

@section('title', 'Responsables')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Responsables</h2>
    <a href="{{ route('responsables.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Responsable
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped table-hover">
    <thead class="table-light">
        <tr>
            <!-- <th>ID</th> -->
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($responsables as $resp)
            <tr>
                <!-- <td>{{ $resp->id_responsable }}</td> -->
                <td>{{ $resp->nombre_responsable }}</td>
                <td>
                    <a href="{{ route('responsables.show', $resp) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('responsables.edit', $resp) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('responsables.destroy', $resp) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este responsable?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">No hay responsables registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection