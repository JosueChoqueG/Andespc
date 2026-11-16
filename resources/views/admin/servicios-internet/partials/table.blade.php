<div class="table-responsive shadow-sm rounded-3 border bg-white">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-nowrap">
            <tr>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Proveedor / Contacto</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Oficina / Agencia</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Tipo / Velocidad</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold">Red / Conexión</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold text-center">Credenciales</th>
                <th class="px-4 py-3 border-bottom-0 text-secondary fw-semibold text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            @forelse($servicios as $s)
            <tr>
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center text-primary me-3" style="width: 42px; height: 42px;">
                            <i class="bi bi-wifi fs-5"></i>
                        </div>
                        <div>
                            <span class="fw-bold text-dark d-block mb-1">{{ $s->nombre_proveedor }}</span>
                            @if($s->telefono_proveedor)
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-telephone me-1 text-primary"></i> {{ $s->telefono_proveedor }}
                                </span>
                            @else
                                <span class="text-muted small">Sin teléfono</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center text-muted small mb-1">
                        <i class="bi bi-building me-2 text-primary"></i>
                        <span class="fw-medium text-dark">{{ $s->oficina?->nombre_oficina ?? 'N/A' }}</span>
                    </div>
                    <div class="text-muted small ps-4">
                        <i class="bi bi-geo-alt me-1"></i> {{ $s->oficina?->agencia?->nombre_agencia ?? 'Sin agencia' }}
                    </div>
                </td>
                <td class="px-4 py-3">
                    @php
                        $badgeMap = [
                            'Fibra óptica' => 'success',
                            'Radioenlace'  => 'info',
                            'Starlink'     => 'warning',
                            'RPC'          => 'secondary',
                        ];
                        $color = $badgeMap[$s->tipo_instalacion] ?? 'dark';
                    @endphp
                    <span class="badge bg-{{ $color }} rounded-pill px-3 py-1 fw-medium mb-1 d-inline-block">
                        {{ $s->tipo_instalacion }}
                    </span>
                    <div class="fw-semibold text-dark small">
                        <i class="bi bi-speedometer2 me-1 text-muted"></i> {{ $s->megas_contratado }} Mbps
                    </div>
                </td>
                <td class="px-4 py-3 text-muted">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-hdd-network me-2 text-primary"></i>
                            <span class="font-monospace fw-medium text-dark">{{ $s->direccion_ip ?? 'Sin IP' }}</span>
                        </div>
                        <div class="small text-muted">
                            <i class="bi bi-broadcast me-1 text-success"></i> {{ $s->nombre_wifi ?? 'Sin SSID' }}
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="d-inline-flex flex-column gap-1 text-start">
                        @if($s->contrasena_router)
                            <span class="badge bg-light text-dark border text-truncate" style="max-width: 140px;" title="Contraseña Router: {{ $s->contrasena_router }}">
                                <i class="bi bi-router text-secondary me-1"></i> {{ $s->contrasena_router }}
                            </span>
                        @endif
                        @if($s->contrasena_wifi)
                            <span class="badge bg-light text-dark border text-truncate" style="max-width: 140px;" title="Contraseña WiFi: {{ $s->contrasena_wifi }}">
                                <i class="bi bi-lock text-secondary me-1"></i> {{ $s->contrasena_wifi }}
                            </span>
                        @endif
                        @if(!$s->contrasena_router && !$s->contrasena_wifi)
                            <span class="text-muted small">Sin claves</span>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="btn-group shadow-sm" role="group">
                        <a href="{{ route('admin.servicios-internet.edit', $s) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.servicios-internet.destroy', $s) }}"
                              method="POST" class="d-inline form-eliminar"
                              onsubmit="return confirm('¿Está seguro de eliminar este servicio de internet?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <div class="d-flex flex-column align-items-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-wifi text-secondary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="fw-medium text-dark">No hay servicios de internet registrados</h5>
                        <p class="mb-0 text-muted small">No se encontraron registros que coincidan con la búsqueda.</p>
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
        Mostrando {{ $servicios->count() }} de {{ $servicios->total() }} registros
    </div>
    <div>
        {{ $servicios->links('pagination::bootstrap-5') }}
    </div>
</div>

