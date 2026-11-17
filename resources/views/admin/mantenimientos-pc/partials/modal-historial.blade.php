{{-- Modal Historial: Historial de Mantenimientos (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-white" style="background: linear-gradient(135deg, #495057 0%, #343a40 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-clock-history text-white small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-white fs-6 d-block lh-sm">Historial de Mantenimientos</span>
            <span class="text-white-50" style="font-size: 0.75rem;">{{ $equipo->nombre_dispositivo }} &bull; S/N: <strong>{{ $equipo->numero_serie ?: 'N/A' }}</strong> &bull; Total: {{ $mantenimientos->total() ?? $mantenimientos->count() }} registros</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-2" style="max-height: 75vh; overflow-y: auto; font-size: 0.82rem;">
    {{-- Resumen del equipo en 1 línea --}}
    <div class="bg-light border rounded-2 px-2.5 py-1.5 mb-2 d-flex justify-content-between align-items-center flex-wrap gap-2 text-muted" style="font-size: 0.76rem;">
        <span><strong class="text-dark">Oficina:</strong> {{ $equipo->oficina->nombre_oficina ?? 'N/A' }}</span>
        <span><strong class="text-dark">Responsable:</strong> {{ $equipo->responsable->nombre_responsable ?? 'Sin asignar' }}</span>
        @php
            $badgeConfig = [
                'Operativo' => 'success',
                'Operativo con Observacion' => 'info text-dark',
                'En mantenimiento' => 'warning text-dark',
                'Fuera de servicio' => 'danger',
                'De baja' => 'secondary',
            ][trim($equipo->estado_equipo)] ?? 'dark';
        @endphp
        <span><strong class="text-dark">Estado:</strong> <span class="badge bg-{{ $badgeConfig }} py-0.5 px-2" style="font-size: 0.7rem;">{{ $equipo->estado_equipo }}</span></span>
    </div>

    {{-- Tabla de historial --}}
    @if($mantenimientos->count() > 0)
    <div class="table-responsive border rounded-2 bg-white">
        <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.78rem;">
            <thead class="table-light">
                <tr>
                    <th class="py-1.5 ps-2.5" style="width: 85px;">Fecha</th>
                    <th class="py-1.5 px-2" style="width: 95px;">Tipo</th>
                    <th class="py-1.5 px-2">Trabajos Realizados</th>
                    <th class="py-1.5 px-2" style="width: 130px;">Técnico</th>
                    <th class="py-1.5 text-center pe-2.5" style="width: 75px;">PDF</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mantenimientos as $mantenimiento)
                <tr>
                    <td class="py-1.5 ps-2.5 fw-medium">{{ $mantenimiento->fecha_mantenimiento ? $mantenimiento->fecha_mantenimiento->format('d/m/Y') : 'N/A' }}</td>
                    <td class="py-1.5 px-2">
                        <span class="badge bg-{{ $mantenimiento->tipo_mantenimiento == 'Preventivo' ? 'success' : 'warning text-dark' }} py-0.5 px-1.5" style="font-size: 0.68rem;">
                            {{ $mantenimiento->tipo_mantenimiento }}
                        </span>
                    </td>
                    <td class="py-1.5 px-2">
                        <div style="max-height: 50px; overflow-y: auto; line-height: 1.3;">
                            @foreach($mantenimiento->getDescripcionArrayAttribute() as $item)
                                <div class="text-truncate" title="{{ $item }}">&bull; {{ $item }}</div>
                            @endforeach
                        </div>
                    </td>
                    <td class="py-1.5 px-2 text-muted text-truncate">{{ $mantenimiento->tecnico_nombre ?? 'Técnico' }}</td>
                    <td class="py-1.5 text-center pe-2.5">
                        <a href="{{ route('admin.equipos.hoja-vida-mantenimiento', $mantenimiento) }}"
                           class="btn btn-outline-primary btn-sm py-0.5 px-1.5" title="Hoja de Vida" target="_blank" style="font-size: 0.72rem;">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($mantenimientos->hasPages())
    <div class="d-flex justify-content-center mt-2">
        {{ $mantenimientos->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-4 text-muted bg-white border rounded-2">
        <i class="bi bi-inbox fs-2 d-block mb-1 text-secondary opacity-50"></i>
        <p class="mb-0 small">No hay mantenimientos registrados para este equipo.</p>
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
