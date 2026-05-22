@extends('layouts.app')

@section('title', 'Detalle de Mantenimiento de Contadora')
@section('header', 'Detalle de Mantenimiento de Contadora de Billetes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="bi bi-tools"></i> Mantenimiento del {{ date('d/m/Y', strtotime($mantenimiento->fecha_mantenimiento)) }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.contabilletes.show', $mantenimiento->contabillete_id) }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver a la Ficha
                        </a>
                        <a href="{{ route('admin.contabilletes.hoja-vida-mantenimiento', $mantenimiento->id) }}" class="btn btn-light btn-sm text-info">
                            <i class="bi bi-file-earmark-text"></i> Hoja de Vida
                        </a>
                        <a href="{{ route('admin.contabilletes.hoja-vida-mantenimiento.pdf', $mantenimiento->id) }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-file-pdf"></i> Hoja de Vida PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Información del Equipo</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Marca/Modelo:</th>
                            <td>{{ $mantenimiento->contabillete->marca_contabilletes }} {{ $mantenimiento->contabillete->modelo_contabilletes }}</td>
                        </tr>
                        <tr>
                            <th>Serie:</th>
                            <td>{{ $mantenimiento->contabillete->serie_contabilletes }}</td>
                        </tr>
                        <tr>
                            <th>Ubicación:</th>
                            <td>{{ $mantenimiento->contabillete->oficina->nombre_oficina ?? 'N/A' }}</td>
                        </tr>
                    </table>

                    <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2">Detalles del Mantenimiento</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Tipo Mantenimiento:</th>
                            <td>
                                <span class="badge bg-{{ $mantenimiento->tipo_mantenimiento == 'Preventivo' ? 'success' : 'danger' }}">
                                    {{ $mantenimiento->tipo_mantenimiento }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha:</th>
                            <td>{{ date('d/m/Y', strtotime($mantenimiento->fecha_mantenimiento)) }}</td>
                        </tr>
                        <tr>
                            <th>Trabajos Realizados:</th>
                            <td>
                                <ul class="mb-0">
                                    @foreach($mantenimiento->descripcion_array as $trabajo)
                                        <li>{{ $trabajo }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @if($mantenimiento->observacion_mantenimiento)
                        <tr>
                            <th>Observaciones Mantenimiento:</th>
                            <td>{{ $mantenimiento->observacion_mantenimiento }}</td>
                        </tr>
                        @endif
                    </table>

                    @if($mantenimiento->fallas_detectadas || $mantenimiento->fallas_solucion)
                    <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2">Incidencias y Fallas</h5>
                    <table class="table table-bordered">
                        @if($mantenimiento->fecha_fallas)
                        <tr>
                            <th width="30%">Fecha de Reporte Falla:</th>
                            <td>{{ date('d/m/Y', strtotime($mantenimiento->fecha_fallas)) }}</td>
                        </tr>
                        @endif
                        @if($mantenimiento->fallas_detectadas)
                        <tr>
                            <th width="30%">Fallas Detectadas:</th>
                            <td>{{ $mantenimiento->fallas_detectadas }}</td>
                        </tr>
                        @endif
                        @if($mantenimiento->fallas_solucion)
                        <tr>
                            <th>Solución Aplicada:</th>
                            <td>{{ $mantenimiento->fallas_solucion }}</td>
                        </tr>
                        @endif
                    </table>
                    @endif

                    @if($mantenimiento->observacion_general)
                    <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2">Observaciones Generales y Recomendaciones</h5>
                    <p class="alert alert-secondary">
                        {{ $mantenimiento->observacion_general }}
                    </p>
                    @endif
                </div>
                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="{{ route('admin.mantenimientos-contabillete.edit', $mantenimiento->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar Mantenimiento
                    </a>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="bi bi-trash"></i> Eliminar Mantenimiento
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" action="{{ route('admin.mantenimientos-contabillete.destroy', $mantenimiento->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function confirmDelete() {
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
            document.getElementById('delete-form').submit();
        }
    });
}
</script>
@endpush
@endsection
