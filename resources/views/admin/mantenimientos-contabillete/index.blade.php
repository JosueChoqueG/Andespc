@extends('layouts.app')

@section('title', 'Mantenimientos de Contadoras de Billetes')
@section('header', 'Gestión de Mantenimientos de Contadoras')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-tools"></i> Listado de Mantenimientos de Contadoras
                    </h3>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <select name="contabillete_id" class="form-control select2">
                                    <option value="">Todas las contadoras</option>
                                    @foreach($contabilletes as $cont)
                                        <option value="{{ $cont->id }}" {{ request('contabillete_id') == $cont->id ? 'selected' : '' }}>
                                            {{ $cont->marca_contabilletes }} {{ $cont->modelo_contabilletes }} ({{ $cont->serie_contabilletes }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <select name="tipo_mantenimiento" class="form-control">
                                    <option value="">Todos los tipos</option>
                                    <option value="Preventivo" {{ request('tipo_mantenimiento') == 'Preventivo' ? 'selected' : '' }}>Preventivo</option>
                                    <option value="Correctivo" {{ request('tipo_mantenimiento') == 'Correctivo' ? 'selected' : '' }}>Correctivo</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <input type="date" name="fecha_inicio" class="form-control" placeholder="Fecha inicio" value="{{ request('fecha_inicio') }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <input type="date" name="fecha_fin" class="form-control" placeholder="Fecha fin" value="{{ request('fecha_fin') }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="{{ route('admin.mantenimientos-contabillete.index') }}" class="btn btn-secondary">Limpiar</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Contadora de Billetes</th>
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
                                        {{ $mantenimiento->contabillete->marca_contabilletes ?? 'N/A' }} 
                                        {{ $mantenimiento->contabillete->modelo_contabilletes ?? '' }}
                                        <small class="text-muted d-block">Serie: {{ $mantenimiento->contabillete->serie_contabilletes ?? '' }}</small>
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
                                                <i class="bi bi-exclamation-triangle-fill"></i> Sí
                                            </span>
                                        @else
                                            <span class="text-success">
                                                <i class="bi bi-check-circle-fill"></i> No
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.mantenimientos-contabillete.show', $mantenimiento->id) }}" 
                                               class="btn btn-info" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.mantenimientos-contabillete.edit', $mantenimiento->id) }}" 
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
                                              action="{{ route('admin.mantenimientos-contabillete.destroy', $mantenimiento->id) }}" 
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
