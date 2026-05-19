@extends('layouts.app')

@section('title', 'Detalle de Impresora Térmica')
@section('header', 'Detalle de Impresora Térmica')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-info-circle"></i> Información General
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.termicas.hoja-vida', $termica->id) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-file-earmark-text"></i> Hoja de Vida
                        </a>
                        <a href="{{ route('admin.termicas.hoja-vida.pdf', $termica->id) }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('admin.termicas.edit', $termica->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="{{ route('admin.mantenimientos-termica.create', $termica->id) }}" class="btn btn-primary btn-sm">
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
                            <td><strong>{{ $termica->serie_termica }}</strong></td>
                        </tr>
                        <tr>
                            <th>Marca / Modelo:</th>
                            <td>{{ $termica->marca_termica }} {{ $termica->modelo_termica }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de Impresora:</th>
                            <td>{{ $termica->tipo_termica }}</td>
                        </tr>
                        <tr>
                            <th>Oficina:</th>
                            <td>{{ $termica->oficina->nombre_oficina ?? 'N/A' }} 
                                @if($termica->oficina && $termica->oficina->agencia)
                                    ({{ $termica->oficina->agencia->nombre_agencia }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Responsable:</th>
                            <td>{{ $termica->responsable->nombre_responsable ?? 'No asignado' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha Adquisición:</th>
                            <td>{{ $termica->fecha_adquisicion ? date('d/m/Y', strtotime($termica->fecha_adquisicion)) : 'No registrada' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-ethernet"></i> Conectividad y Estado
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Tipo de Conexión:</th>
                            <td>
                                @php
                                    $iconos = [
                                        'USB' => 'fab fa-usb',
                                        'WI-FI' => 'fas fa-wifi',
                                        'ETHERNET' => 'fas fa-network-wired',
                                        'SERIAL' => 'fas fa-plug',
                                        'BLUETOOTH' => 'fab fa-bluetooth'
                                    ];
                                @endphp
                                <i class="{{ $iconos[$termica->tipo_conexion] ?? 'fas fa-plug' }}"></i>
                                {{ $termica->tipo_conexion }}
                            </td>
                        </tr>
                        <tr>
                            <th>Nombre Host:</th>
                            <td>{{ $termica->nombre_host ?? 'No configurado' }}</td>
                        </tr>
                        <tr>
                            <th>Dirección IP:</th>
                            <td><code>{{ $termica->direccion_ip ?? 'No asignada' }}</code></td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @php
                                    $estado = strtoupper(trim($termica->estado_termica));
                                    $badgeClass = [
                                        'OPTIMO' => 'success',
                                        'BUENO' => 'info',
                                        'REGULAR' => 'warning',
                                        'DEFICIENTE' => 'danger',
                                        'DE BAJA' => 'secondary'
                                    ][$estado] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeClass }} badge-lg">
                                    {{ $termica->estado_termica }}
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-graph-up"></i> Estadísticas de Uso y Consumibles
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="50%">Velocidad de Impresión:</th>
                            <td>{{ $termica->velocidad_impresion ?? 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <th>Modelo Consumible:</th>
                            <td>{{ $termica->modelo_consumible ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Tipo Consumible:</th>
                            <td>{{ $termica->tipo_consumible ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Metros Impresos / Cortes:</th>
                            <td>{{ number_format($termica->cantidad_impresion ?? 0, 0, ',', '.') }} m / cortes</td>
                        </tr>
                        <tr>
                            <th>Vida Útil Teórica (Capacidad):</th>
                            <td>{{ $termica->capacidad_impresion ? number_format($termica->capacidad_impresion, 0, ',', '.') . ' Km' : 'No especificada' }}</td>
                        </tr>
                        @if($termica->capacidad_impresion && $termica->capacidad_impresion > 0)
                        <tr>
                            <th>Porcentaje de Vida Útil Consumido:</th>
                            <td>
                                @php
                                    $capacidadMetros = $termica->capacidad_impresion * 1000;
                                    $porcentaje = ($termica->cantidad_impresion / $capacidadMetros) * 100;
                                    $barClass = $porcentaje < 50 ? 'success' : ($porcentaje < 80 ? 'warning' : 'danger');
                                @endphp
                                <div class="progress">
                                    <div class="progress-bar bg-{{ $barClass }}" 
                                         style="width: {{ min($porcentaje, 100) }}%">
                                        {{ round($porcentaje, 1) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-pie-chart"></i> Resumen de Mantenimientos
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $totalMant = $termica->mantenimientos->count();
                        $preventivos = $termica->mantenimientos->where('tipo_mantenimiento', 'Preventivo')->count();
                        $correctivos = $termica->mantenimientos->where('tipo_mantenimiento', 'Correctivo')->count();
                        $ultimoMant = $termica->mantenimientos->first();
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
                        <a href="{{ route('admin.mantenimientos-termica.historial', $termica->id) }}" class="btn btn-info btn-sm">
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
                            @forelse($termica->mantenimientos as $mantenimiento)
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
                                        <i class="bi bi-info-circle"></i> No hay mantenimientos registrados para esta impresora térmica
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
<form id="delete-form" action="{{ route('admin.termicas.destroy', $termica->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete() {
    Swal.fire({
        title: '¿Eliminar impresora térmica?',
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
            form.action = "{{ route('admin.mantenimientos-termica.destroy', ':id') }}".replace(':id', id);
            
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
