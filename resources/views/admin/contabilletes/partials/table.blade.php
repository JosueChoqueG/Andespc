<div class="table-responsive shadow-sm rounded-3 border bg-white">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-nowrap">
            <tr>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Dispositivo / SN</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Clasificación</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Asignación</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Detalles Técnicos</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold text-center">Estado</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            @forelse($contabilletes as $contabillete)
            <tr>
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center text-primary me-3" style="width: 42px; height: 42px;">
                            <i class="bi bi-cash-coin fs-5"></i>
                        </div>
                        <div>
                            <span class="fw-bold text-dark d-block mb-1">{{ $contabillete->marca_contabilletes }}</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-upc-scan me-1"></i> {{ $contabillete->serie_contabilletes ?? 'N/A' }}</span>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="d-block fw-medium text-dark mb-1">Contadora de Billetes</span>
                    <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-tags me-1"></i> 
                        {{ $contabillete->modelo_contabilletes ?? 'N/A' }}
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center text-muted small mb-1">
                        <i class="bi bi-geo-alt me-2"></i>
                        <span class="fw-medium text-dark">{{ $contabillete->oficina?->nombre_oficina ?? 'N/A' }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-muted">
                    <div class="d-flex flex-column">
                        <div class="small text-muted mb-1">
                            <i class="bi bi-speedometer2 me-1"></i> Vel: {{ $contabillete->velocidad_contabilletes ?? 'N/A' }}
                        </div>
                        <div class="small text-muted">
                            <i class="bi bi-shield-check me-1"></i> Det: {{ $contabillete->tipo_deteccion ?? 'N/A' }}
                        </div>
                    </div>
                </td>
                
                @php
                    $estado = strtoupper(trim($contabillete->estado_contabilletes));
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
                        {{ $contabillete->estado_contabilletes }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="btn-group shadow-sm" role="group">
                        <a href="{{ route('admin.contabilletes.show', $contabillete->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.contabilletes.edit', $contabillete->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('admin.mantenimientos-contabillete.create', $contabillete->id) }}" target="_blank" 
                           class="btn btn-sm btn-outline-primary" title="Registrar Mantenimiento">
                            <i class="bi bi-tools"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-cash-coin text-secondary" style="font-size: 2.5rem;"></i>
                            </div>
                            <h5 class="fw-medium text-dark">No hay contadoras de billetes registradas</h5>
                            <p class="mb-0">No se encontraron registros que coincidan con la búsqueda.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación Bootstrap -->
<div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
    <div class="text-muted small mb-2 mb-md-0">
        Mostrando {{ $contabilletes->count() }} de {{ $contabilletes->total() }} registros
    </div>
    <div>
        {{ $contabilletes->links('pagination::bootstrap-5') }}
    </div>
</div>
