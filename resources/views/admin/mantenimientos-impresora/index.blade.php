@extends('layouts.app')

@section('title', 'Mantenimientos de Impresoras')
@section('header', 'Gestión de Mantenimientos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tools"></i> Listado de Mantenimientos
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.mantenimientos-impresora.create', '') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Mantenimiento
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="impresora_id" class="form-control">
                                    <option value="">Todas las impresoras</option>
                                    @foreach($impresoras as $imp)
                                        <option value="{{ $imp->id }}" {{ request('impresora_id') == $imp->id ? 'selected' : '' }}>
                                            {{ $imp->marca_impresora }} {{ $imp->modelo_impresora }} ({{ $imp->serie_impresora }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="tipo_mantenimiento" class="form-control">
                                    <option value="">Todos los tipos</option>
                                    <option value="Preventivo" {{ request('tipo_mantenimiento') == 'Preventivo' ? 'selected' : '' }}>Preventivo</option>
                                    <option value="Correctivo" {{ request('tipo_mantenimiento') == 'Correctivo' ? 'selected' : '' }}>Correctivo</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="fecha_inicio" class="form-control" placeholder="Fecha inicio" value="{{ request('fecha_inicio') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="fecha_fin" class="form-control" placeholder="Fecha fin" value="{{ request('fecha_fin') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="{{ route('admin.mantenimientos-impresora.index') }}" class="btn btn-secondary">Limpiar</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Impresora</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Fallas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mantenimientos as $mantenimiento)
                                <tr>
                                    <td>{{ $mantenimiento->id }}</td>
                                    <td>{{ date('d/m/Y', strtotime($mantenimiento->fecha_mantenimiento)) }}</td>
                                    <td>
                                        {{ $mantenimiento->impresora->marca_impresora ?? 'N/A' }} 
                                        {{ $mantenimiento->impresora->modelo_impresora ?? '' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $mantenimiento->tipo_mantenimiento == 'Preventivo' ? 'success' : 'danger' }}">
                                            {{ $mantenimiento->tipo_mantenimiento }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($mantenimiento->descripcion_mantenimiento, 50) }}</td>
                                    <td>
                                        @if($mantenimiento->fallas_detectadas)
                                            <span class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> Sí
                                            </span>
                                        @else
                                            <span class="text-success">
                                                <i class="fas fa-check"></i> No
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.mantenimientos-impresora.show', $mantenimiento->id) }}" 
                                               class="btn btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.mantenimientos-impresora.edit', $mantenimiento->id) }}" 
                                               class="btn btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    title="Eliminar"
                                                    onclick="confirmDelete({{ $mantenimiento->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $mantenimiento->id }}" 
                                              action="{{ route('admin.mantenimientos-impresora.destroy', $mantenimiento->id) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay mantenimientos registrados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $mantenimientos->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    Swal.fire({
        title: '¿Eliminar mantenimiento?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush
@endsection