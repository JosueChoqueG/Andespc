@extends('layouts.app')

@section('title', 'Mantenimientos de Impresoras Térmicas')
@section('header', 'Gestión de Mantenimientos Térmicos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tools"></i> Listado de Mantenimientos Térmicos
                    </h3>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="termica_id" class="form-control select2">
                                    <option value="">Todas las térmicas</option>
                                    @foreach($termicas as $term)
                                        <option value="{{ $term->id }}" {{ request('termica_id') == $term->id ? 'selected' : '' }}>
                                            {{ $term->marca_termica }} {{ $term->modelo_termica }} ({{ $term->serie_termica }})
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
                                <a href="{{ route('admin.mantenimientos-termica.index') }}" class="btn btn-secondary">Limpiar</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Impresora Térmica</th>
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
                                        {{ $mantenimiento->termica->marca_termica ?? 'N/A' }} 
                                        {{ $mantenimiento->termica->modelo_termica ?? '' }}
                                        <small class="text-muted d-block">Serie: {{ $mantenimiento->termica->serie_termica ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $mantenimiento->tipo_mantenimiento == 'Preventivo' ? 'success' : 'danger' }}">
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
                                            <a href="{{ route('admin.mantenimientos-termica.show', $mantenimiento->id) }}" 
                                               class="btn btn-info" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.mantenimientos-termica.edit', $mantenimiento->id) }}" 
                                               class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    title="Eliminar"
                                                    onclick="confirmDelete({{ $mantenimiento->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $mantenimiento->id }}" 
                                              action="{{ route('admin.mantenimientos-termica.destroy', $mantenimiento->id) }}" 
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
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    }
});

function confirmDelete(id) {
    Swal.fire({
        title: '¿Eliminar mantenimiento?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush
@endsection
