@extends('layouts.app')

@section('title', 'Detalle del Equipo - ' . $equipo->nombre_dispositivo)

@section('content')
<div class="container-fluid py-2">
    {{-- Header con Barra Superior y Acciones --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle shadow-sm" title="Volver al listado">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                <i class="bi bi-pc-display fs-4"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h5 class="mb-0 fw-bold text-dark">{{ $equipo->nombre_dispositivo }}</h5>
                    @php
                        $estado = trim($equipo->estado_equipo);
                        $badgeConfig = [
                            'Operativo' => ['bg' => 'success', 'icon' => 'bi-check-circle', 'text' => 'Operativo'],
                            'Operativo con Observacion' => ['bg' => 'info text-dark', 'icon' => 'bi-exclamation-circle', 'text' => 'Operativo c/ Obs.'],
                            'En mantenimiento' => ['bg' => 'warning text-dark', 'icon' => 'bi-tools', 'text' => 'En mantenimiento'],
                            'Fuera de servicio' => ['bg' => 'danger', 'icon' => 'bi-slash-circle', 'text' => 'Fuera de servicio'],
                            'De baja' => ['bg' => 'secondary', 'icon' => 'bi-archive', 'text' => 'De baja'],
                        ][$estado] ?? ['bg' => 'dark', 'icon' => 'bi-question-circle', 'text' => $estado];
                    @endphp
                    <span class="badge bg-{{ $badgeConfig['bg'] }} rounded-pill px-3 py-1">
                        <i class="bi {{ $badgeConfig['icon'] }} me-1"></i>{{ $badgeConfig['text'] }}
                    </span>
                </div>
                <div class="text-muted small mt-1">
                    <span class="me-3"><i class="bi bi-upc-scan me-1 text-primary"></i>S/N: <strong>{{ $equipo->numero_serie ?: 'N/A' }}</strong></span>
                    <span class="me-3"><i class="bi bi-building me-1 text-primary"></i>{{ $equipo->oficina->nombre_oficina ?? 'Sin oficina' }}</span>
                    <span><i class="bi bi-person me-1 text-primary"></i>{{ $equipo->responsable->nombre_responsable ?? 'Sin asignar' }}</span>
                </div>
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.equipos.hoja-vida', $equipo) }}" class="btn btn-sm btn-outline-info shadow-sm" title="Ver Hoja de Vida" target="_blank">
                <i class="bi bi-file-earmark-text me-1"></i>Hoja de Vida
            </a>
            <a href="{{ route('admin.equipos.hoja-vida.pdf', $equipo) }}" class="btn btn-sm btn-outline-danger shadow-sm" title="Descargar PDF">
                <i class="bi bi-file-pdf me-1"></i>PDF
            </a>
            <a href="{{ route('admin.mantenimientos-pc.create', $equipo) }}" class="btn btn-sm btn-outline-primary shadow-sm" title="Registrar Mantenimiento">
                <i class="bi bi-tools me-1"></i>Mantenimiento
            </a>
            <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-sm btn-warning shadow-sm" title="Editar Equipo">
                <i class="bi bi-pencil me-1"></i>Editar
            </a>
        </div>
    </div>

    {{-- Cuadrícula Compacta de 2 Columnas --}}
    <div class="row g-3">
        {{-- Columna Izquierda --}}
        <div class="col-lg-6">
            {{-- Tarjeta 1: Hardware y Rendimiento --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light border-0 py-2 d-flex align-items-center">
                    <i class="bi bi-cpu text-primary me-2"></i>
                    <span class="fw-semibold small text-uppercase">Hardware y Rendimiento</span>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                                <span class="text-muted small"><i class="bi bi-cpu me-1"></i>Procesador</span>
                                <span class="fw-bold text-dark small text-end">{{ $equipo->hardware->procesador ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block mb-1"><i class="bi bi-memory me-1"></i>Memoria RAM</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary fs-6 fw-bold">
                                    {{ $equipo->hardware->ram_gb ?? 0 }} GB RAM
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block mb-1"><i class="bi bi-device-hdd me-1"></i>Almacenamiento</span>
                                <span class="badge bg-success bg-opacity-10 text-success fs-6 fw-bold">
                                    {{ $equipo->hardware->almacenamiento_gb ?? 0 }} GB {{ $equipo->hardware->tipo_almacenamiento ?? '' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                                <span class="text-muted small"><i class="bi bi-windows me-1"></i>Sistema Operativo</span>
                                <span class="fw-semibold text-dark small text-end">
                                    {{ $equipo->sistema_operativo_completo }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta 2: Red y Conectividad --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light border-0 py-2 d-flex align-items-center">
                    <i class="bi bi-hdd-network text-info me-2"></i>
                    <span class="fw-semibold small text-uppercase">Red y Conectividad</span>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block"><i class="bi bi-diagram-3 me-1"></i>Dirección IP</span>
                                <span class="font-monospace fw-bold text-dark">{{ $equipo->direccion_ip ?: 'No asignada' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block"><i class="bi bi-ethernet me-1"></i>Dirección MAC</span>
                                <span class="font-monospace fw-bold text-dark small">{{ $equipo->direccion_mac ?: 'No asignada' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-2 rounded bg-light text-center">
                                <span class="text-muted small d-block mb-1">VPN Cusco</span>
                                <span class="badge bg-{{ $equipo->vpn_cusco === 'Sí' ? 'success' : 'secondary' }}">
                                    {{ $equipo->vpn_cusco ?: 'No' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-2 rounded bg-light text-center">
                                <span class="text-muted small d-block mb-1">VPN Abancay</span>
                                <span class="badge bg-{{ $equipo->vpn_abancay === 'Sí' ? 'success' : 'secondary' }}">
                                    {{ $equipo->vpn_abancay ?: 'No' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-2 rounded bg-light text-center">
                                <span class="text-muted small d-block mb-1">Antivirus</span>
                                <span class="badge bg-info text-dark text-truncate d-block" title="{{ $equipo->antivirus ?: 'No registrado' }}">
                                    {{ $equipo->antivirus ?: 'No registrado' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha --}}
        <div class="col-lg-6">
            {{-- Tarjeta 3: Asignación y Ubicación --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light border-0 py-2 d-flex align-items-center">
                    <i class="bi bi-building text-warning me-2"></i>
                    <span class="fw-semibold small text-uppercase">Ubicación y Asignación</span>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block"><i class="bi bi-building me-1"></i>Oficina</span>
                                <span class="fw-bold text-dark small">{{ $equipo->oficina->nombre_oficina ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block"><i class="bi bi-geo-alt me-1"></i>Agencia</span>
                                <span class="fw-bold text-dark small">{{ $equipo->oficina->agencia->nombre_agencia ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block"><i class="bi bi-person me-1"></i>Responsable</span>
                                <span class="fw-bold text-dark small">{{ $equipo->responsable->nombre_responsable ?? 'Sin asignar' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block"><i class="bi bi-laptop me-1"></i>Tipo & Modelo</span>
                                <span class="fw-bold text-dark small">
                                    {{ $equipo->tipoEquipo->nombre_tipo ?? 'N/A' }} / {{ $equipo->modelo->marca->nombre_marca ?? '' }} {{ $equipo->modelo->nombre_modelo ?? '' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block">Adquisición</span>
                                <span class="small fw-semibold text-dark">{{ $equipo->fecha_adquisicion ? $equipo->fecha_adquisicion->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block">Últ. Mantenimiento</span>
                                <span class="small fw-semibold text-dark">{{ $equipo->fecha_mantenimiento ? $equipo->fecha_mantenimiento->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted small d-block">Depreciación</span>
                                <span class="small fw-semibold text-dark">{{ $equipo->depreciacion_anual ? $equipo->depreciacion_anual . '%' : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta 4: Software, Licencias y Observaciones --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light border-0 py-2 d-flex align-items-center">
                    <i class="bi bi-journal-text text-secondary me-2"></i>
                    <span class="fw-semibold small text-uppercase">Software y Observaciones</span>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light h-100">
                                <span class="text-muted small fw-semibold d-block mb-1"><i class="bi bi-app me-1 text-primary"></i>Programas Instalados</span>
                                <div class="small text-secondary" style="max-height: 75px; overflow-y: auto; white-space: pre-line;">{{ $equipo->programas_instalados ?: 'Ninguno registrado' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light h-100">
                                <span class="text-muted small fw-semibold d-block mb-1"><i class="bi bi-key me-1 text-warning"></i>Licencias</span>
                                <div class="small text-secondary" style="max-height: 75px; overflow-y: auto; white-space: pre-line;">{{ $equipo->licencias ?: 'Ninguna registrada' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light h-100">
                                <span class="text-muted small fw-semibold d-block mb-1"><i class="bi bi-shield-check me-1 text-success"></i>Copias de Seguridad</span>
                                <div class="small text-secondary" style="max-height: 75px; overflow-y: auto; white-space: pre-line;">{{ $equipo->copias_seguridad ?: 'Ninguna registrada' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2 rounded bg-light h-100">
                                <span class="text-muted small fw-semibold d-block mb-1"><i class="bi bi-chat-left-text me-1 text-info"></i>Observaciones</span>
                                <div class="small text-secondary" style="max-height: 75px; overflow-y: auto; white-space: pre-line;">{{ $equipo->observacion ?: 'Sin observaciones' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Historial de Mantenimientos Recientes --}}
    @if($equipo->mantenimientos && $equipo->mantenimientos->count() > 0)
    <div class="card shadow-sm border-0 mt-1 mb-3">
        <div class="card-header bg-light border-0 py-2 d-flex justify-content-between align-items-center">
            <span class="fw-semibold small text-uppercase">
                <i class="bi bi-clock-history me-2 text-primary"></i>Historial de Mantenimientos ({{ $equipo->mantenimientos->count() }})
            </span>
            <a href="{{ route('admin.mantenimientos-pc.historial', $equipo) }}" class="btn btn-sm btn-link text-decoration-none p-0 fw-medium small">
                Ver historial completo <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th class="ps-3 py-2">Fecha</th>
                        <th class="py-2">Tipo</th>
                        <th class="py-2">Descripción</th>
                        <th class="py-2">Técnico</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @foreach($equipo->mantenimientos->take(3) as $mant)
                    <tr>
                        <td class="ps-3 fw-medium">{{ $mant->fecha_mantenimiento ? $mant->fecha_mantenimiento->format('d/m/Y') : 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $mant->tipo_mantenimiento === 'Preventivo' ? 'success' : 'warning text-dark' }}">
                                {{ $mant->tipo_mantenimiento }}
                            </span>
                        </td>
                        <td class="text-truncate" style="max-width: 400px;">{{ $mant->descripcion_mantenimiento }}</td>
                        <td class="text-muted">{{ $mant->user->name ?? 'Técnico' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
