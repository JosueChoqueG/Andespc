@extends('layouts.app')

@section('title', 'Detalle de Impresora')
@section('header', 'Detalle de Impresora')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Información General
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.impresoras.edit', $impresora->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('admin.mantenimientos-impresora.create', $impresora->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-tools"></i> Nuevo Mantenimiento
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Número de Serie:</th>
                            <td><strong>{{ $impresora->serie_impresora }}</strong></td>
                        </tr>
                        <tr>
                            <th>Marca / Modelo:</th>
                            <td>{{ $impresora->marca_impresora }} {{ $impresora->modelo_impresora }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de Impresora:</th>
                            <td>{{ $impresora->tipo_impresora }}</td>
                        </tr>
                        <tr>
                            <th>Oficina:</th>
                            <td>{{ $impresora->oficina->nombre_oficina ?? 'N/A' }} 
                                @if($impresora->oficina && $impresora->oficina->agencia)
                                    ({{ $impresora->oficina->agencia->nombre_agencia }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Responsable:</th>
                            <td>{{ $impresora->responsable->nombre_responsable ?? 'No asignado' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha Adquisición:</th>
                            <td>{{ $impresora->fecha_adquisicion ? date('d/m/Y', strtotime($impresora->fecha_adquisicion)) : 'No registrada' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-network-wired"></i> Configuración de Red
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Tipo de Conexión:</th>
                            <td>
                                @php
                                    $iconos = [
                                        'USB' => 'fa-usb',
                                        'WIFI' => 'fa-wifi',
                                        'ETHERNET' => 'fa-network-wired',
                                        'WIFI-DIRECT' => 'fa-wifi'
                                    ];
                                @endphp
                                <i class="fas {{ $iconos[$impresora->tipo_conexion] ?? 'fa-plug' }}"></i>
                                {{ $impresora->tipo_conexion }}
                            </td>
                        </tr>
                        <tr>
                            <th>Nombre Host:</th>
                            <td>{{ $impresora->nombre_host ?? 'No configurado' }}</td>
                        </tr>
                        <tr>
                            <th>Dirección IP:</th>
                            <td><code>{{ $impresora->direccion_ip ?? 'No asignada' }}</code></td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @php
                                    $badgeClass = [
                                        'OPTIMO' => 'success',
                                        'BUENO' => 'info',
                                        'REGULAR' => 'warning',
                                        'DEFICIENTE' => 'danger',
                                        'DE BAJA' => 'secondary'
                                    ][$impresora->estado_impresora] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badgeClass }} badge-lg">
                                    {{ $impresora->estado_impresora }}
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
                        <i class="fas fa-chart-line"></i> Estadísticas de Uso
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="50%">Velocidad de Impresión:</th>
                            <td>{{ $impresora->velocidad_impresion ?? 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <th>Modelo Consumible:</th>
                            <td>{{ $impresora->modelo_consumible ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Tipo Consumible:</th>
                            <td>{{ $impresora->tipo_consumible ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Cantidad Impresa:</th>
                            <td>{{ number_format($impresora->cantidad_impresion ?? 0, 0, ',', '.') }} páginas</td>
                        </tr>
                        <tr>
                            <th>Capacidad Total:</th>
                            <td>{{ number_format($impresora->capacidad_impresion ?? 0, 0, ',', '.') }} páginas</td>
                        </tr>
                        @if($impresora->capacidad_impresion && $impresora->capacidad_impresion > 0)
                        <tr>
                            <th>Porcentaje de Vida Útil:</th>
                            <td>
                                @php
                                    $porcentaje = ($impresora->cantidad_impresion / $impresora->capacidad_impresion) * 100;
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
                        <i class="fas fa-chart-pie"></i> Resumen de Mantenimientos
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $totalMant = $impresora->mantenimientos->count();
                        $preventivos = $impresora->mantenimientos->where('tipo_mantenimiento', 'Preventivo')->count();
                        $correctivos = $impresora->mantenimientos->where('tipo_mantenimiento', 'Correctivo')->count();
                        $ultimoMant = $impresora->mantenimientos->first();
                    @endphp
                    <table class="table table-bordered">
                        <tr>
                            <th width="50%">Total Mantenimientos:</th>
                            <td><span class="badge badge-primary badge-lg">{{ $totalMant }}</span></td>
                        </tr>
                        <tr>
                            <th>Mantenimientos Preventivos:</th>
                            <td><span class="badge badge-success">{{ $preventivos }}</span></td>
                        </tr>
                        <tr>
                            <th>Mantenimientos Correctivos:</th>
                            <td><span class="badge badge-danger">{{ $correctivos }}</span></td>
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
                        <i class="fas fa-history"></i> Historial de Mantenimientos
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.mantenimientos-impresora.historial', $impresora->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-line"></i> Ver Estadísticas
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
                            @forelse($impresora->mantenimientos as $mantenimiento)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($mantenimiento->fecha_mantenimiento)) }}</td>
                                <td>
                                    <span class="badge badge-{{ $mantenimiento->tipo_mantenimiento == 'Preventivo' ? 'success' : 'danger' }}">
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
                                        <a href="{{ route('admin.mantenimientos-impresora.show', $mantenimiento->id) }}" 
                                           class="btn btn-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.mantenimientos-impresora.edit', $mantenimiento->id) }}" 
                                           class="btn btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </tr>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle"></i> No hay mantenimientos registrados para esta impresora
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