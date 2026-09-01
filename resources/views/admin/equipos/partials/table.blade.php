<div class="table-responsive shadow-sm rounded-3 border bg-white">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-nowrap">
            <tr>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Dispositivo / SN</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Clasificación</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Asignación</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">F. Compra</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold text-center">Estado</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            @forelse ($equipos as $equipo)
            <tr>
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center text-primary me-3" style="width: 42px; height: 42px;">
                            <i class="bi bi-pc-display fs-5"></i>
                        </div>
                        <div>
                            <span class="fw-bold text-dark d-block mb-1">{{ $equipo->nombre_dispositivo }}</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-upc-scan me-1"></i> {{ $equipo->numero_serie ?? 'N/A' }}</span>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="d-block fw-medium text-dark mb-1">{{ $equipo->tipoequipo?->nombre_tipo ?? 'N/A' }}</span>
                    <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-tags me-1"></i> 
                        {{ $equipo->modelo?->marca?->nombre_marca ?? 'N/A' }} 
                        <span class="mx-1">•</span> 
                        {{ $equipo->modelo?->nombre_modelo ?? 'N/A' }}
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-person-badge text-muted me-2"></i>
                        <span class="fw-medium text-dark">{{ $equipo->responsable?->nombre_responsable ?? 'Sin asignar' }}</span>
                    </div>
                    <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-geo-alt me-2"></i>
                        {{ $equipo->oficina?->nombre_oficina ?? 'N/A' }}
                    </div>
                </td>
                <td class="px-4 py-3 text-muted">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar3 me-2"></i> 
                        {{ $equipo->fecha_adquisicion ? \Carbon\Carbon::parse($equipo->fecha_adquisicion)->format('d/m/Y') : 'N/A' }}
                    </div>
                </td>
                
                @php
                    $colores = [
                        'Operativo' => 'success',
                        'Operativo con observaciones' => 'primary',
                        'En mantenimiento' => 'warning',
                        'Fuera de servicio' => 'danger',
                        'De baja' => 'dark',
                    ];
                    $color = $colores[$equipo->estado_equipo] ?? 'secondary';
                @endphp

                <td class="px-4 py-3 text-center">
                    <span class="badge bg-{{ $color }} rounded-pill px-3 py-2 fw-medium shadow-sm">
                        {{ $equipo->estado_equipo }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="btn-group shadow-sm" role="group">
                        <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-sm btn-outline-info" title="Ver detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('admin.mantenimientos-pc.create', $equipo) }}" target="_blank" 
                        class="btn btn-sm btn-outline-success" title="Nuevo Mantenimiento">
                            <i class="bi bi-tools"></i>
                        </a>
                        <a href="{{ route('admin.mantenimientos-pc.historial', $equipo) }} " target="_blank"
                        class="btn btn-sm btn-outline-secondary" title="Historial">
                            <i class="bi bi-clock-history"></i>
                        </a>
                        <a href="{{ route('admin.equipos.hoja-vida', $equipo) }}" 
                        class="btn btn-sm btn-outline-primary" title="Hoja de Vida" target="_blank">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-pc-display text-secondary" style="font-size: 2.5rem;"></i>
                            </div>
                            <h5 class="fw-medium text-dark">No hay equipos registrados</h5>
                            <p class="mb-0">No se encontraron registros de equipos en el sistema.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación Bootstrap -->
<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted small">
        Mostrando resultados para equipos registrados
    </div>
    <div>
        {{ $equipos->links('pagination::bootstrap-5') }}
    </div>
</div>