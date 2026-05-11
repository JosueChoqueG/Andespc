@extends('layouts.app')

@section('title', 'Historial de Mantenimientos')
@section('header', 'Estadísticas de Mantenimiento')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-chart-line"></i> Estadísticas de la Impresora
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.impresoras.show', $impresora->id) }}" class="btn btn-default btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver a Impresora
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $estadisticas['total'] }}</h3>
                                    <p>Total Mantenimientos</p>
                                </div>
                                <div class="icon">
                                    <i class="bi bi-tools"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $estadisticas['preventivos'] }}</h3>
                                    <p>Mantenimientos Preventivos</p>
                                </div>
                                <div class="icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $estadisticas['correctivos'] }}</h3>
                                    <p>Mantenimientos Correctivos</p>
                                </div>
                                <div class="icon">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $estadisticas['con_fallas'] }}</h3>
                                    <p>Con Fallas Detectadas</p>
                                </div>
                                <div class="icon">
                                    <i class="bi bi-bug"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($estadisticas['ultimo_mantenimiento'])
                    <div class="alert alert-info">
                        <i class="bi bi-calendar-alt"></i> 
                        <strong>Último mantenimiento:</strong> 
                        {{ date('d/m/Y', strtotime($estadisticas['ultimo_mantenimiento']->fecha_mantenimiento)) }} - 
                        {{ $estadisticas['ultimo_mantenimiento']->tipo_mantenimiento }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-clock-history"></i> Historial Completo
                    </h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Fallas Detectadas</th>
                                <th>Solución</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mantenimientos as $mantenimiento)
                            <tr>
                                <td>{{ $mantenimiento->id }}</td>
                                <td>{{ date('d/m/Y', strtotime($mantenimiento->fecha_mantenimiento)) }}</td>
                                <td>
                                    <span class="badge bg-{{ $mantenimiento->tipo_mantenimiento == 'Preventivo' ? 'success' : 'danger' }}">
                                        {{ $mantenimiento->tipo_mantenimiento }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($mantenimiento->descripcion_mantenimiento, 50) }}</td>
                                <td>
                                    @if($mantenimiento->fallas_detectadas)
                                        <span class="text-danger">{{ Str::limit($mantenimiento->fallas_detectadas, 40) }}</span>
                                    @else
                                        <span class="text-muted">Ninguna</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mantenimiento->fallas_solucion)
                                        {{ Str::limit($mantenimiento->fallas_solucion, 40) }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.mantenimientos-impresora.show', $mantenimiento->id) }}" 
                                           class="btn btn-info" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.mantenimientos-impresora.edit', $mantenimiento->id) }}" 
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
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay mantenimientos registrados
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear formulario dinámicamente
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('admin.mantenimientos-impresora.destroy', ':id') }}".replace(':id', id);
            
            let csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";
            
            let method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            
            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection