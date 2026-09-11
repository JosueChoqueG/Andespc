<div class="table-responsive shadow-sm rounded-3 border bg-white">
    <div class="table-responsive">
<table class="table table-hover align-middle mb-0">
        <thead class="table-light text-nowrap">
            <tr>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Dispositivo / SN</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Clasificación</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Asignación</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Conexión</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold text-center">Estado</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            @forelse($impresoras as $impresora)
            <tr>
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center text-primary me-3" style="width: 42px; height: 42px;">
                            <i class="bi bi-printer fs-5"></i>
                        </div>
                        <div>
                            <span class="fw-bold text-dark d-block mb-1">{{ $impresora->marca_impresora }}</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-upc-scan me-1"></i> {{ $impresora->serie_impresora ?? 'N/A' }}</span>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="d-block fw-medium text-dark mb-1">Impresora</span>
                    <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-tags me-1"></i> 
                        {{ $impresora->modelo_impresora ?? 'N/A' }}
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center text-muted small mb-1">
                        <i class="bi bi-geo-alt me-2"></i>
                        <span class="fw-medium text-dark">{{ $impresora->oficina?->nombre_oficina ?? 'N/A' }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-muted">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-1">
                            @php
                                $iconos = [
                                    'USB' => 'bi-usb-symbol',
                                    'WIFI' => 'bi-wifi',
                                    'ETHERNET' => 'bi-diagram-3',
                                    'WIFI-DIRECT' => 'bi-wifi'
                                ];
                            @endphp
                            <i class="bi {{ $iconos[$impresora->tipo_conexion] ?? 'bi-plug' }} me-2"></i> 
                            {{ $impresora->tipo_conexion }}
                        </div>
                        <div class="small text-muted">
                            <i class="bi bi-hdd-network me-1"></i> {{ $impresora->direccion_ip ?? 'N/A' }}
                        </div>
                    </div>
                </td>
                
                @php
                    $estado = strtoupper(trim($impresora->estado_impresora));
                    $badgeClass = [
                        'OPTIMO' => 'success',
                        'BUENO' => 'info',
                        'REGULAR' => 'warning',
                        'DEFICIENTE' => 'danger',
                        'DE BAJA' => 'secondary'
                    ][$estado] ?? 'dark';
                @endphp

                <td class="px-4 py-3 text-center">
                    <span class="badge bg-{{ $badgeClass }} rounded-pill px-3 py-2 fw-medium shadow-sm">
                        {{ $impresora->estado_impresora }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="btn-group shadow-sm" role="group">
                        <a href="{{ route('admin.impresoras.show', $impresora->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.impresoras.edit', $impresora->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('admin.mantenimientos-impresora.create', $impresora->id) }}" target="_blank" 
                           class="btn btn-sm btn-outline-primary" title="Registrar Mantenimiento">
                            <i class="bi bi-tools"></i>
                        </a>
                        <a href="{{ route('admin.informes-impresora.create', $impresora->id) }}" target="_blank" 
                           class="btn btn-sm btn-outline-success" title="Nuevo Informe">
                            <i class="bi bi-file-earmark-text"></i> 
                        </a>
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-printer text-secondary" style="font-size: 2.5rem;"></i>
                            </div>
                            <h5 class="fw-medium text-dark">No hay impresoras registradas</h5>
                            <p class="mb-0">No se encontraron registros que coincidan con la búsqueda.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

<!-- Paginación Bootstrap -->
<div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
    <div class="text-muted small mb-2 mb-md-0">
        Mostrando {{ $impresoras->count() }} de {{ $impresoras->total() }} registros
    </div>
    <div>
        {{ $impresoras->links('pagination::bootstrap-5') }}
    </div>
</div>
