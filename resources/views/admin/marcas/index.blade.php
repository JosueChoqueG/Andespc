@extends('layouts.app')

@section('title', 'Marcas')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Marcas</h2>
    <a href="{{ route('marcas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Marca
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped table-hover align-middle">
    <thead class="table-light">
        <tr>
            <!-- <th>ID</th> -->
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($marcas as $marca)
            <tr>
                <!-- <td>{{ $marca->id_marca }}</td> -->
                <td>{{ $marca->nombre_marca }}</td>
                <td>
                    <a href="{{ route('marcas.show', $marca) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('marcas.edit', $marca) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('marcas.destroy', $marca) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta marca?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">No hay marcas registradas.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection