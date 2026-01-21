@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">
        <i class="bi bi-printer"></i> Gestión de Impresoras
    </h5>

    <a href="{{ route('admin.impresoras.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Impresora
    </a>
</div>
<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <strong><i class="bi bi-list-check"></i> Lista de Impresoras</strong>
        <form method="GET" action="{{ route('admin.impresoras.index') }}">
            <div class="input-group" style="max-width: 300px;">
                <input type="text"
                    name="serie"
                    class="form-control"
                    placeholder="Buscar..."
                    value="{{ request('serie') }}">

                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Agencia</th>
                        <th>Oficina</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Serie</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($impresoras as $i)
                    <tr>
                        <td>{{ $i->oficina->agencia->nombre_agencia }}</td>
                        <td>{{ $i->oficina->nombre_oficina }}</td>
                        <td class="text-uppercase">{{ $i->marca }}</td>
                        <td>{{ $i->modelo }}</td>
                        <td><span class="badge bg-secondary">{{ $i->serie }}</span></td>
                        <td>
                            <span class="badge 
                                {{ $i->estado_equipo == 'OPERATIVO' ? 'bg-success' : 
                                ($i->estado_equipo == 'INACTIVO' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ $i->estado_equipo }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.impresoras.show',$i) }}" 
                            class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.impresoras.edit',$i) }}" 
                            class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No se encontraron impresoras
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{ $impresoras->links() }}
@endsection
