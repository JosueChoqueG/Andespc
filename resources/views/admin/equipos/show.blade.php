@extends('layouts.app')

@section('title', 'Detalles del Equipo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5><i class="bi bi-pc-display"></i> Detalles del Equipo: {{ $equipo->nombre_dispositivo }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Nombre</th><td>{{ $equipo->nombre_dispositivo }}</td></tr>
                    <tr><th>Número de Serie</th><td>{{ $equipo->numero_serie ?? 'N/A' }}</td></tr>
                    <tr><th>Dirección IP</th><td>{{ $equipo->direccion_ip ?? 'N/A' }}</td></tr>
                    <tr><th>Oficina</th>
                        <td>
                            {{ optional($equipo->oficina)->nombre_oficina ?? 'N/A' }} 
                            ({{ optional($equipo->oficina?->agencia)->nombre_agencia ?? 'N/A' }})
                        </td>
                    </tr>
                    <tr><th>Tipo de Equipo</th><td>{{ optional($equipo->tipo)->nombre_tipo ?? 'N/A' }}</td></tr>
                    <tr><th>Modelo</th>
                        <td>
                            {{ optional($equipo->modelo)->nombre_modelo ?? 'N/A' }} 
                            ({{ optional($equipo->modelo?->marca)->nombre_marca ?? 'N/A' }})
                        </td>
                    </tr>
                    <tr><th>Hardware</th>
                        <td>
                            @if($equipo->hardware)
                                {{ $equipo->hardware->procesador }} | 
                                {{ $equipo->hardware->ram_gb }}GB RAM | 
                                {{ $equipo->hardware->almacenamiento_gb }}GB {{ $equipo->hardware->tipo_almacenamiento }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr><th>Sistema Operativo</th>
                        <td>
                            @if($equipo->sistemaOperativo)
                                {{ $equipo->sistemaOperativo->nombre_so }} 
                                {{ $equipo->sistemaOperativo->edicion }} 
                                ({{ $equipo->sistemaOperativo->version }})
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr><th>Responsable</th><td>{{ optional($equipo->responsable)->nombre_responsable ?? 'N/A' }}</td></tr>
                    <tr><th>Estado</th>
                        <td>
                            <span class="badge bg-{{ $equipo->estado_equipo == 'Activo' ? 'success' : ($equipo->estado_equipo == 'Inactivo' ? 'warning' : 'danger') }}">
                                {{ $equipo->estado_equipo }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>Fecha Adquisición</th><td>{{ $equipo->fecha_adquisicion?->format('d/m/Y') ?? 'N/A' }}</td></tr>
                    <tr><th>Último Mantenimiento</th><td>{{ $equipo->fecha_mantenimiento?->format('d/m/Y') ?? 'N/A' }}</td></tr>
                    <tr><th>Depreciación Anual</th><td>{{ $equipo->depreciacion_anual ?? 'N/A' }}%</td></tr>
                    <tr><th>Antivirus</th><td>{{ $equipo->antivirus ?? 'N/A' }}</td></tr>
                    <tr><th>VPN Cusco</th><td>{{ $equipo->vpn_cusco }}</td></tr>
                    <tr><th>VPN Abancay</th><td>{{ $equipo->vpn_abancay }}</td></tr>
                    <tr><th>Programas Instalados</th><td><pre class="bg-light p-2 rounded">{{ $equipo->programas_instalados ?? 'N/A' }}</pre></td></tr>
                    <tr><th>Licencias</th><td><pre class="bg-light p-2 rounded">{{ $equipo->licencias ?? 'N/A' }}</pre></td></tr>
                    <tr><th>Copias de Seguridad</th><td><pre class="bg-light p-2 rounded">{{ $equipo->copias_seguridad ?? 'N/A' }}</pre></td></tr>
                    <tr><th>Observaciones</th><td><pre class="bg-light p-2 rounded">{{ $equipo->observacion ?? 'N/A' }}</pre></td></tr>
                </table>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-warning me-2">Editar</a>
                    <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
