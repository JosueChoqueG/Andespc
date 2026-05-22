@extends('layouts.app')

@section('title', 'Detalle de Contadora de Billetes')
@section('header', 'Detalle de Contadora de Billetes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-info-circle"></i> Información General
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.contabilletes.hoja-vida', $contabillete->id) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-file-earmark-text"></i> Hoja de Vida
                        </a>
                        <a href="{{ route('admin.contabilletes.hoja-vida.pdf', $contabillete->id) }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('admin.contabilletes.edit', $contabillete->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="{{ route('admin.mantenimientos-contabillete.create', $contabillete->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-tools"></i> Nuevo Mantenimiento
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Número de Serie:</th>
                            <td><strong>{{ $contabillete->serie_contabilletes }}</strong></td>
                        </tr>
                        <tr>
                            <th>Marca / Modelo:</th>
                            <td>{{ $contabillete->marca_contabilletes }} {{ $contabillete->modelo_contabilletes }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de Equipo:</th>
                            <td>{{ $contabillete->tipo_contabilletes }}</td>
                        </tr>
                        <tr>
                            <th>Oficina:</th>
                            <td>{{ $contabillete->oficina->nombre_oficina ?? 'N/A' }} 
                                @if($contabillete->oficina && $contabillete->oficina->agencia)
                                    ({{ $contabillete->oficina->agencia->nombre_agencia }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Responsable:</th>
                            <td>{{ $contabillete->responsable->nombre_responsable ?? 'No asignado' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha Adquisición:</th>
                            <td>{{ $contabillete->fecha_adquisicion ? date('d/m/Y', strtotime($contabillete->fecha_adquisicion)) : 'No registrada' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-cpu"></i> Especificaciones Técnicas y Estado
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Velocidad de Conteo:</th>
                            <td>{{ $contabillete->velocidad_contabilletes ?? 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <th>Capacidad Tolva (Hoppers):</th>
                            <td>{{ $contabillete->capacidad_tolva ? $contabillete->capacidad_tolva . ' billetes' : 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <th>Capacidad Bandeja (Stacker):</th>
                            <td>{{ $contabillete->capacidad_bandeja ? $contabillete->capacidad_bandeja . ' billetes' : 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <th>Detección de Falsos:</th>
                            <td>{{ $contabillete->tipo_deteccion ?? 'No configurada' }}</td>
                        </tr>
                        <tr>
                            <th>Tipo Pantalla:</th>
                            <td>{{ $contabillete->pantalla_contabilletes ?? 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <th>Estado Actual:</th>
                            <td>
                                @php
                                    $estado = strtoupper(trim($contabillete->estado_contabilletes));
                                    $badgeClass = [
                                        'OPTIMO' => 'success',
                                        'BUENO' => 'info',
                                        'REGULAR' => 'warning',
                                        'DEFICIENTE' => 'danger',
                                        'DE BAJA' => 'secondary'
                                    ][$estado] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeClass }} badge-lg">
                                    {{ $contabillete->estado_contabilletes }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-pie-chart"></i> Resumen de Mantenimientos
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $totalMant = $contabillete->mantenimientos->count();
                        $preventivos = $contabillete->mantenimientos->where('tipo_mantenimiento', 'Preventivo')->count();
                        $correctivos = $contabillete->mantenimientos->where('tipo_mantenimiento', 'Correctivo')->count();
                        $ultimoMant = $contabillete->mantenimientos->first();
                    @endphp
                    <table class="table table-bordered">
                        <tr>
                            <th width="50%">Total Mantenimientos:</th>
                            <td><span class="badge bg-primary badge-lg">{{ $totalMant }}</span></td>
                        </tr>
                        <tr>
                            <th>Mantenimientos Preventivos:</th>
                            <td><span class="badge bg-success">{{ $preventivos }}</span></td>
                        </tr>
                        <tr>
                            <th>Mantenimientos Correctivos:</th>
                            <td><span class="badge bg-danger">{{ $correctivos }}</span></td>
                        </tr>
                        @if($ultimoMant)
                        <tr>
                            <th>Último Mantenimiento:</th>
                            <td>{{ date('d/m/Y', strtotime($ultimoMant->fecha_mantenimiento)) }}</td>
                        </tr>
                        <tr>
                            <th>Tipo último mantenimiento:</th>
                            <td>{{ $ultimoMant->tipo_mantenimiento }}</td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="2" class="text-center text-muted">Sin mantenimientos registrados</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-clock-history"></i> Historial de Mantenimientos
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.mantenimientos-contabillete.historial', $contabillete->id) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-chart-line"></i> Ver Estadísticas
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Fallas Detectadas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contabillete->mantenimientos as $mantenimiento)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($mantenimiento->fecha_mantenimiento)) }}</td>
                                <td>
                                    <span class="badge bg-{{ $mantenimiento->tipo_mantenimiento == 'Preventivo' ? 'success' : 'danger' }}">
                                        {{ $mantenimiento->tipo_mantenimiento }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($mantenimiento->descripcion_mantenimiento, 60) }}</td>
                                <td>
                                    @if($mantenimiento->fallas_detectadas)
                                        {{ Str::limit($mantenimiento->fallas_detectadas, 40) }}
                                    @else
                                        <span class="text-muted">Ninguna</span>
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
                                                 onclick="confirmDeleteMantenimiento({{ $mantenimiento->id }})">
                                             <i class="bi bi-trash"></i>
                                         </button>
                                     </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle"></i> No hay mantenimientos registrados para esta contadora
                                    </div>
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
@endsection

@push('scripts')
<form id="delete-form" action="{{ route('admin.contabilletes.destroy', $contabillete->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete() {
    Swal.fire({
        title: '¿Eliminar contadora de billetes?',
        text: "Esta acción no se puede deshacer. Si tiene mantenimientos asociados, no podrá ser eliminada.",
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

function confirmDeleteMantenimiento(id) {
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
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('admin.mantenimientos-contabillete.destroy', ':id') }}".replace(':id', id);
            
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
