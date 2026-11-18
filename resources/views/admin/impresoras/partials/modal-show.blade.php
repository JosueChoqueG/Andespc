{{-- Modal Show: Detalles de Impresora (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-printer text-white small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-white fs-6 d-block lh-sm">{{ $impresora->marca_impresora }} {{ $impresora->modelo_impresora }}</span>
            <span class="text-white-50" style="font-size: 0.95rem;">S/N: <strong>{{ $impresora->serie_impresora ?: 'N/A' }}</strong> &bull; {{ $impresora->oficina->nombre_oficina ?? 'Sin oficina' }}</span>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        @php
            $estado = strtoupper(trim($impresora->estado_impresora));
            $badgeClass = [
                'OPTIMO' => 'success',
                'BUENO' => 'info text-dark',
                'REGULAR' => 'warning text-dark',
                'DEFICIENTE' => 'danger',
                'DE BAJA' => 'secondary'
            ][$estado] ?? 'dark';
        @endphp
        <span class="badge bg-{{ $badgeClass }} rounded-pill px-2 py-1" style="font-size: 0.95rem;">
            {{ $impresora->estado_impresora }}
        </span>
        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="modal-body p-3" style="max-height: 82vh; overflow-y: auto; font-size: 1rem;">
    <div class="row g-2.5">
        {{-- Especificaciones y Clasificación --}}
        <div class="col-md-6">
            <div class="border rounded-2 p-2 h-100 bg-white">
                <div class="d-flex align-items-center text-primary fw-semibold mb-1" style="font-size: 0.95rem;">
                    <i class="bi bi-info-circle me-1"></i> ESPECIFICACIONES GENERALES
                </div>
                <div class="row g-1 mb-1">
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Tipo de Impresora</span>
                            <span class="fw-bold text-dark">{{ $impresora->tipo_impresora ?: 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Velocidad</span>
                            <span class="fw-bold text-dark">{{ $impresora->velocidad_impresion ?: 'No especificada' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row g-1 mb-1">
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Modelo Consumible</span>
                            <span class="fw-semibold text-dark text-truncate d-block">{{ $impresora->modelo_consumible ?: 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Tipo Consumible</span>
                            <span class="fw-semibold text-dark text-truncate d-block">{{ $impresora->tipo_consumible ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center p-1.5 rounded bg-light">
                    <span class="text-muted"><i class="bi bi-calendar3 me-1"></i>Adquisición:</span>
                    <span class="fw-semibold text-dark">{{ $impresora->fecha_adquisicion ? date('d/m/Y', strtotime($impresora->fecha_adquisicion)) : 'No registrada' }}</span>
                </div>
            </div>
        </div>

        {{-- Conectividad y Red --}}
        <div class="col-md-6">
            <div class="border rounded-2 p-2 h-100 bg-white">
                <div class="d-flex align-items-center text-info fw-semibold mb-1" style="font-size: 0.95rem;">
                    <i class="bi bi-hdd-network me-1"></i> CONECTIVIDAD Y RED
                </div>
                <div class="row g-1 mb-1">
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Tipo Conexión</span>
                            <span class="badge bg-secondary py-1 px-2">{{ $impresora->tipo_conexion ?: 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Nombre Host</span>
                            <span class="fw-semibold text-dark text-truncate d-block">{{ $impresora->nombre_host ?: 'No configurado' }}</span>
                        </div>
                    </div>
                </div>
                <div class="p-1.5 rounded bg-light mb-1">
                    <span class="text-muted d-block" style="font-size: 0.85rem;">Dirección IP</span>
                    <span class="font-monospace fw-bold text-dark">{{ $impresora->direccion_ip ?: 'No asignada' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-1.5 rounded bg-light">
                    <span class="text-muted"><i class="bi bi-tools me-1"></i>Mantenimientos Realizados:</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $impresora->cantidad_mantenimientos ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Ubicación y Responsable --}}
        <div class="col-md-6">
            <div class="border rounded-2 p-2 h-100 bg-white">
                <div class="d-flex align-items-center text-warning fw-semibold mb-1" style="font-size: 0.95rem;">
                    <i class="bi bi-building me-1"></i> ASIGNACIÓN Y UBICACIÓN
                </div>
                <div class="row g-1">
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Oficina / Agencia</span>
                            <span class="fw-semibold text-dark text-truncate d-block">{{ $impresora->oficina->nombre_oficina ?? 'N/A' }} ({{ $impresora->oficina->agencia->nombre_agencia ?? 'N/A' }})</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Responsable</span>
                            <span class="fw-semibold text-dark text-truncate d-block">{{ $impresora->responsable->nombre_responsable ?? 'Sin asignar' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estadísticas de Uso y Contador --}}
        <div class="col-md-6">
            <div class="border rounded-2 p-2 h-100 bg-white">
                <div class="d-flex align-items-center text-secondary fw-semibold mb-1" style="font-size: 0.95rem;">
                    <i class="bi bi-graph-up me-1"></i> CONTADORES DE USO
                </div>
                <div class="row g-1 text-center mb-1">
                    <div class="col-4">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Pág. Impresas</span>
                            <span class="fw-bold text-dark">{{ number_format($impresora->cantidad_impresion ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Escaneos</span>
                            <span class="fw-bold text-dark">{{ number_format($impresora->cantidad_escaneo ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.85rem;">Capacidad</span>
                            <span class="fw-bold text-dark">{{ number_format($impresora->capacidad_impresion ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @if($impresora->capacidad_impresion && $impresora->capacidad_impresion > 0)
                    @php
                        $porcentaje = ($impresora->cantidad_impresion / $impresora->capacidad_impresion) * 100;
                        $barClass = $porcentaje < 50 ? 'success' : ($porcentaje < 80 ? 'warning' : 'danger');
                    @endphp
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-{{ $barClass }}" style="width: {{ min($porcentaje, 100) }}%">
                            {{ round($porcentaje, 0) }}%
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Últimos Mantenimientos --}}
    @if($impresora->mantenimientos && $impresora->mantenimientos->count() > 0)
    <div class="border rounded-2 p-2 mt-2 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="fw-semibold text-primary" style="font-size: 0.95rem;">
                <i class="bi bi-clock-history me-1"></i> ÚLTIMOS MANTENIMIENTOS ({{ $impresora->mantenimientos->count() }})
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.95rem;">
                <thead class="table-light">
                    <tr>
                        <th class="py-1 px-2" style="width: 85px;">Fecha</th>
                        <th class="py-1 px-2" style="width: 95px;">Tipo</th>
                        <th class="py-1 px-2">Descripción</th>
                        <th class="py-1 px-2" style="width: 120px;">Técnico</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($impresora->mantenimientos->take(3) as $mant)
                    <tr>
                        <td class="py-1 px-2 fw-medium">{{ $mant->fecha_mantenimiento ? $mant->fecha_mantenimiento->format('d/m/Y') : 'N/A' }}</td>
                        <td class="py-1 px-2">
                            <span class="badge bg-{{ $mant->tipo_mantenimiento === 'Preventivo' ? 'success' : 'warning text-dark' }} px-1.5 py-0.5" style="font-size: 0.82rem;">{{ $mant->tipo_mantenimiento }}</span>
                        </td>
                        <td class="py-1 px-2 text-truncate" style="max-width: 250px;">{{ $mant->descripcion_mantenimiento }}</td>
                        <td class="py-1 px-2 text-muted text-truncate">{{ $mant->tecnico_nombre ?? 'Técnico' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<div class="modal-footer bg-light py-1.5 px-3">
    <a href="{{ route('admin.impresoras.hoja-vida', $impresora->id) }}" class="btn btn-sm btn-outline-info py-1 px-2" target="_blank" style="font-size: 0.95rem;">
        <i class="bi bi-file-earmark-text me-1"></i>Hoja de Vida
    </a>
    <a href="{{ route('admin.impresoras.hoja-vida.pdf', $impresora->id) }}" class="btn btn-sm btn-outline-danger py-1 px-2" target="_blank" style="font-size: 0.95rem;">
        <i class="bi bi-file-pdf me-1"></i>PDF
    </a>
    <button type="button" class="btn btn-sm btn-secondary py-1 px-2.5 ms-auto" data-bs-dismiss="modal" style="font-size: 0.95rem;">
        <i class="bi bi-x-circle me-1"></i>Cerrar
    </button>
</div>
