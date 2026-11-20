<div class="table-responsive" style="min-height: 400px;">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark" style="background-color: #1d2d44; font-size: 0.88rem;">
            <tr>
                <th class="text-center py-2.5" style="width: 85px;">Ticket</th>
                <th class="py-2.5" style="width: 140px;">Fecha / Hora</th>
                <th class="py-2.5" style="width: 120px;">Clasificación</th>
                <th class="py-2.5">Problema & Detalle</th>
                <th class="py-2.5" style="width: 180px;">Usuario & Sede</th>
                <th class="text-center py-2.5" style="width: 90px;">Prioridad</th>
                <th class="text-center py-2.5" style="width: 120px;">Estado</th>
                <th class="py-2.5" style="width: 130px;">Atendido por</th>
                <th class="text-center py-2.5" style="width: 100px;">Acciones</th>
            </tr>
        </thead>
        <tbody style="font-size: 0.92rem;">
            @forelse ($incidencias as $inc)
            <tr>
                {{-- Ticket ID --}}
                <td class="text-center">
                    <span class="badge bg-light text-primary border fw-bold px-2 py-1">
                        #{{ str_pad($inc->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </td>

                {{-- Fecha --}}
                <td>
                    <span class="fw-semibold text-dark d-block">
                        {{ $inc->created_at ? $inc->created_at->format('d/m/Y') : '-' }}
                    </span>
                    <span class="text-muted small">
                        <i class="bi bi-clock me-1"></i>{{ $inc->created_at ? $inc->created_at->format('H:i') : '' }}
                    </span>
                </td>

                {{-- Tipo y Módulo --}}
                <td>
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-medium px-2 py-0.5 d-inline-block mb-1">
                        {{ ucfirst($inc->tipo) }}
                    </span>
                    <span class="text-muted small d-block">{{ ucfirst($inc->modulo) }}</span>
                </td>

                {{-- Problema y Diagnóstico --}}
                <td>
                    <a href="javascript:void(0)" class="fw-bold text-dark text-decoration-none btn-modal-incidencia d-block" 
                       data-url="{{ route('admin.incidencias.show', $inc->id) }}"
                       title="Ver Ficha Técnica Completa">
                        {{ Str::limit($inc->problema, 45) }}
                    </a>
                    <span class="text-muted small d-block text-truncate" style="max-width: 380px;">
                        {{ Str::limit($inc->descripcion, 75) }}
                    </span>
                </td>

                {{-- Usuario y Sede --}}
                <td>
                    <span class="fw-semibold text-dark d-block text-truncate" title="{{ $inc->usuario_afectado }}">
                        <i class="bi bi-person text-secondary me-1"></i>{{ $inc->usuario_afectado }}
                    </span>
                    <span class="text-muted small d-block text-truncate" title="{{ $inc->agencia }} - {{ $inc->sub_agencia }}">
                        <i class="bi bi-geo-alt text-danger me-1"></i>{{ $inc->sub_agencia ?: $inc->agencia }}
                    </span>
                </td>

                {{-- Prioridad --}}
                <td class="text-center">
                    @php
                        $prioClass = match($inc->prioridad) {
                            'Alta' => 'danger',
                            'Media' => 'warning text-dark',
                            'Baja' => 'info text-dark',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $prioClass }} px-2 py-1" style="font-size: 0.8rem;">
                        {{ $inc->prioridad }}
                    </span>
                </td>

                {{-- Estado con Selector Rápido --}}
                <td class="text-center">
                    <div class="dropdown">
                        @php
                            $estadoBadge = match(strtolower($inc->estado)) {
                                'atendido' => 'success',
                                'pendiente' => 'danger',
                                'derivado' => 'warning text-dark',
                                default => 'secondary'
                            };
                        @endphp
                        <button class="btn btn-sm badge bg-{{ $estadoBadge }} rounded-pill px-2.5 py-1.5 dropdown-toggle border-0 shadow-none" 
                                type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Clic para cambiar estado rápidamente">
                            {{ $inc->estado }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="font-size: 0.85rem;">
                            <li><h6 class="dropdown-header text-muted py-1" style="font-size: 0.75rem;">CAMBIAR ESTADO</h6></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 btn-cambiar-estado-rapido py-1.5 {{ $inc->estado === 'Atendido' ? 'active' : '' }}" 
                                   href="javascript:void(0)" 
                                   data-id="{{ $inc->id }}" 
                                   data-estado="Atendido"
                                   data-url="{{ route('admin.incidencias.cambiar-estado', $inc->id) }}">
                                    <span class="badge bg-success rounded-circle p-1"></span> Atendido (Solucionado)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 btn-cambiar-estado-rapido py-1.5 {{ $inc->estado === 'Pendiente' ? 'active' : '' }}" 
                                   href="javascript:void(0)" 
                                   data-id="{{ $inc->id }}" 
                                   data-estado="Pendiente"
                                   data-url="{{ route('admin.incidencias.cambiar-estado', $inc->id) }}">
                                    <span class="badge bg-danger rounded-circle p-1"></span> Pendiente
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 btn-cambiar-estado-rapido py-1.5 {{ $inc->estado === 'Derivado' ? 'active' : '' }}" 
                                   href="javascript:void(0)" 
                                   data-id="{{ $inc->id }}" 
                                   data-estado="Derivado"
                                   data-url="{{ route('admin.incidencias.cambiar-estado', $inc->id) }}">
                                    <span class="badge bg-warning rounded-circle p-1"></span> Derivado
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>

                {{-- Especialista --}}
                <td>
                    <span class="small text-dark fw-medium d-block text-truncate" title="{{ $inc->atendidoPor?->name ?? 'No asignado' }}">
                        {{ $inc->atendidoPor?->name ?? '—' }}
                    </span>
                </td>

                {{-- Acciones --}}
                <td class="text-center">
                    <div class="d-inline-flex gap-1">
                        <button type="button" class="btn btn-sm btn-outline-info p-1 px-2 btn-modal-incidencia" 
                                data-url="{{ route('admin.incidencias.show', $inc->id) }}" 
                                title="Ver Ficha Técnica">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning p-1 px-2 btn-modal-incidencia" 
                                data-url="{{ route('admin.incidencias.edit', $inc->id) }}" 
                                title="Editar Ficha / Solución">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5 text-muted">
                    <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <h6 class="fw-bold">No se encontraron tickets en la bitácora</h6>
                    <p class="small mb-0">Prueba ajustando los filtros de búsqueda o registra una nueva incidencia.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginación Profesional --}}
@if($incidencias->hasPages())
<div class="card-footer bg-light border-top py-2.5 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <span class="text-muted small">
        Página {{ $incidencias->currentPage() }} de {{ $incidencias->lastPage() }} (Total: {{ $incidencias->total() }} tickets)
    </span>
    <div class="pagination-container">
        {{ $incidencias->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif
