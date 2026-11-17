{{-- Modal Show: Detalles del Equipo (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-pc-display text-white small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-white fs-6 d-block lh-sm">{{ $equipo->nombre_dispositivo }}</span>
            <span class="text-white-50" style="font-size: 0.75rem;">S/N: <strong>{{ $equipo->numero_serie ?: 'N/A' }}</strong> &bull; {{ $equipo->oficina->nombre_oficina ?? 'Sin oficina' }}</span>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        @php
            $badgeConfig = [
                'Operativo' => ['bg' => 'success', 'icon' => 'bi-check-circle'],
                'Operativo con Observacion' => ['bg' => 'info text-dark', 'icon' => 'bi-exclamation-circle'],
                'En mantenimiento' => ['bg' => 'warning text-dark', 'icon' => 'bi-tools'],
                'Fuera de servicio' => ['bg' => 'danger', 'icon' => 'bi-slash-circle'],
                'De baja' => ['bg' => 'secondary', 'icon' => 'bi-archive'],
            ][trim($equipo->estado_equipo)] ?? ['bg' => 'dark', 'icon' => 'bi-question-circle'];
        @endphp
        <span class="badge bg-{{ $badgeConfig['bg'] }} rounded-pill px-2 py-1" style="font-size: 0.75rem;">
            <i class="bi {{ $badgeConfig['icon'] }} me-1"></i>{{ $equipo->estado_equipo }}
        </span>
        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="modal-body p-2" style="max-height: 75vh; overflow-y: auto; font-size: 0.82rem;">
    <div class="row g-2">
        {{-- Hardware y Rendimiento --}}
        <div class="col-md-6">
            <div class="border rounded-2 p-2 h-100 bg-white">
                <div class="d-flex align-items-center text-primary fw-semibold mb-1" style="font-size: 0.78rem;">
                    <i class="bi bi-cpu me-1"></i> HARDWARE Y RENDIMIENTO
                </div>
                <div class="d-flex justify-content-between align-items-center p-1.5 rounded bg-light mb-1">
                    <span class="text-muted"><i class="bi bi-cpu me-1"></i>CPU:</span>
                    <span class="fw-bold text-dark text-truncate ms-2" title="{{ $equipo->hardware->procesador ?? 'N/A' }}">{{ $equipo->hardware->procesador ?? 'N/A' }}</span>
                </div>
                <div class="row g-1 mb-1 text-center">
                    <div class="col-6">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">RAM</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $equipo->hardware->ram_gb ?? 0 }} GB</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">Disco</span>
                            <span class="badge bg-success bg-opacity-10 text-success fw-bold">{{ $equipo->hardware->almacenamiento_gb ?? 0 }} GB {{ $equipo->hardware->tipo_almacenamiento ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center p-1.5 rounded bg-light">
                    <span class="text-muted"><i class="bi bi-windows me-1"></i>S.O.:</span>
                    <span class="fw-semibold text-dark text-truncate ms-2" title="{{ $equipo->sistema_operativo_completo }}">{{ $equipo->sistema_operativo_completo }}</span>
                </div>
            </div>
        </div>

        {{-- Red y Conectividad --}}
        <div class="col-md-6">
            <div class="border rounded-2 p-2 h-100 bg-white">
                <div class="d-flex align-items-center text-info fw-semibold mb-1" style="font-size: 0.78rem;">
                    <i class="bi bi-hdd-network me-1"></i> RED Y CONECTIVIDAD
                </div>
                <div class="row g-1 mb-1">
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">IP</span>
                            <span class="font-monospace fw-bold text-dark">{{ $equipo->direccion_ip ?: 'No asignada' }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">MAC</span>
                            <span class="font-monospace fw-bold text-dark text-truncate d-block">{{ $equipo->direccion_mac ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row g-1 text-center">
                    <div class="col-4">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">VPN Cusco</span>
                            <span class="badge bg-{{ $equipo->vpn_cusco === 'Sí' ? 'success' : 'secondary' }}" style="font-size: 0.7rem;">{{ $equipo->vpn_cusco ?: 'No' }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">VPN Abancay</span>
                            <span class="badge bg-{{ $equipo->vpn_abancay === 'Sí' ? 'success' : 'secondary' }}" style="font-size: 0.7rem;">{{ $equipo->vpn_abancay ?: 'No' }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">Antivirus</span>
                            <span class="badge bg-info text-dark text-truncate d-block" style="font-size: 0.7rem;">{{ $equipo->antivirus ?: 'N/R' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ubicación y Asignación --}}
        <div class="col-md-6">
            <div class="border rounded-2 p-2 h-100 bg-white">
                <div class="d-flex align-items-center text-warning fw-semibold mb-1" style="font-size: 0.78rem;">
                    <i class="bi bi-building me-1"></i> ASIGNACIÓN Y UBICACIÓN
                </div>
                <div class="row g-1 mb-1">
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">Oficina / Agencia</span>
                            <span class="fw-semibold text-dark text-truncate d-block">{{ $equipo->oficina->nombre_oficina ?? 'N/A' }} ({{ $equipo->oficina->agencia->nombre_agencia ?? 'N/A' }})</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">Responsable</span>
                            <span class="fw-semibold text-dark text-truncate d-block">{{ $equipo->responsable->nombre_responsable ?? 'Sin asignar' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row g-1 text-center">
                    <div class="col-4">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">Adquisición</span>
                            <span class="fw-medium text-dark">{{ $equipo->fecha_adquisicion ? $equipo->fecha_adquisicion->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">Últ. Mant.</span>
                            <span class="fw-medium text-dark">{{ $equipo->fecha_mantenimiento ? $equipo->fecha_mantenimiento->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-1 rounded bg-light">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">Deprec.</span>
                            <span class="fw-medium text-dark">{{ $equipo->depreciacion_anual ? $equipo->depreciacion_anual . '%' : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Software y Observaciones --}}
        <div class="col-md-6">
            <div class="border rounded-2 p-2 h-100 bg-white">
                <div class="d-flex align-items-center text-secondary fw-semibold mb-1" style="font-size: 0.78rem;">
                    <i class="bi bi-journal-text me-1"></i> SOFTWARE Y NOTAS
                </div>
                <div class="row g-1">
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block fw-semibold" style="font-size: 0.7rem;"><i class="bi bi-app text-primary me-1"></i>Programas</span>
                            <div class="text-secondary text-truncate" style="max-height: 38px;">{{ $equipo->programas_instalados ?: 'Ninguno' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block fw-semibold" style="font-size: 0.7rem;"><i class="bi bi-key text-warning me-1"></i>Licencias</span>
                            <div class="text-secondary text-truncate" style="max-height: 38px;">{{ $equipo->licencias ?: 'Ninguna' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block fw-semibold" style="font-size: 0.7rem;"><i class="bi bi-shield-check text-success me-1"></i>Backups</span>
                            <div class="text-secondary text-truncate" style="max-height: 38px;">{{ $equipo->copias_seguridad ?: 'Ninguno' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1.5 rounded bg-light">
                            <span class="text-muted d-block fw-semibold" style="font-size: 0.7rem;"><i class="bi bi-chat-left-text text-info me-1"></i>Observaciones</span>
                            <div class="text-secondary text-truncate" style="max-height: 38px;">{{ $equipo->observacion ?: 'Sin observaciones' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Historial Reciente --}}
    @if($equipo->mantenimientos && $equipo->mantenimientos->count() > 0)
    <div class="border rounded-2 p-2 mt-2 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="fw-semibold text-primary" style="font-size: 0.78rem;">
                <i class="bi bi-clock-history me-1"></i> ÚLTIMOS MANTENIMIENTOS ({{ $equipo->mantenimientos->count() }})
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.78rem;">
                <thead class="table-light">
                    <tr>
                        <th class="py-1 px-2" style="width: 85px;">Fecha</th>
                        <th class="py-1 px-2" style="width: 95px;">Tipo</th>
                        <th class="py-1 px-2">Descripción</th>
                        <th class="py-1 px-2" style="width: 120px;">Técnico</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipo->mantenimientos->take(3) as $mant)
                    <tr>
                        <td class="py-1 px-2 fw-medium">{{ $mant->fecha_mantenimiento ? $mant->fecha_mantenimiento->format('d/m/Y') : 'N/A' }}</td>
                        <td class="py-1 px-2">
                            <span class="badge bg-{{ $mant->tipo_mantenimiento === 'Preventivo' ? 'success' : 'warning text-dark' }} px-1.5 py-0.5" style="font-size: 0.68rem;">{{ $mant->tipo_mantenimiento }}</span>
                        </td>
                        <td class="py-1 px-2 text-truncate" style="max-width: 250px;">{{ $mant->descripcion_mantenimiento }}</td>
                        <td class="py-1 px-2 text-muted text-truncate">{{ $mant->user->name ?? 'Técnico' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<div class="modal-footer bg-light py-1.5 px-3">
    <a href="{{ route('admin.equipos.hoja-vida', $equipo) }}" class="btn btn-sm btn-outline-info py-1 px-2" target="_blank" style="font-size: 0.78rem;">
        <i class="bi bi-file-earmark-text me-1"></i>Hoja de Vida
    </a>
    <a href="{{ route('admin.equipos.hoja-vida.pdf', $equipo) }}" class="btn btn-sm btn-outline-danger py-1 px-2" target="_blank" style="font-size: 0.78rem;">
        <i class="bi bi-file-pdf me-1"></i>PDF
    </a>
    <button type="button" class="btn btn-sm btn-secondary py-1 px-2.5 ms-auto" data-bs-dismiss="modal" style="font-size: 0.78rem;">
        <i class="bi bi-x-circle me-1"></i>Cerrar
    </button>
</div>
