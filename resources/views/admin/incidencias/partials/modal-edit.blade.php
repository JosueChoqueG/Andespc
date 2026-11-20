{{-- Modal Editar Incidencia (Bitácora TI) --}}
<div class="modal-header py-2.5 px-3 text-dark" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
            <i class="bi bi-pencil-square text-dark fs-6"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-dark fs-6 d-block lh-sm">
                Editar Ticket #{{ str_pad($incidencia->id, 5, '0', STR_PAD_LEFT) }}
            </span>
            <span class="text-dark text-opacity-75" style="font-size: 0.85rem;">
                Actualizar información, solución técnica o estado del ticket
            </span>
        </div>
    </div>
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('admin.incidencias.update', $incidencia->id) }}" method="POST" id="form-editar-incidencia-modal">
    @csrf
    @method('PUT')

    <div class="modal-body p-3" style="max-height: 82vh; overflow-y: auto; font-size: 0.95rem;">
        <div id="modal-incidencia-edit-errors" class="alert alert-danger py-2 px-3 small d-none mb-2.5"></div>

        {{-- Bloque 1: Clasificación --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-primary fw-semibold mb-2" style="font-size: 0.9rem;">
                <i class="bi bi-sliders me-1"></i> 1. CLASIFICACIÓN DE LA INCIDENCIA
            </div>
            <div class="row g-2.5">
                <div class="col-md-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Tipo de Asistencia <span class="text-danger">*</span></label>
                    <select name="tipo" class="form-select form-select-sm" required id="modal-edit-tipo">
                        <option value="software" {{ old('tipo', $incidencia->tipo) == 'software' ? 'selected' : '' }}>Software</option>
                        <option value="hardware" {{ old('tipo', $incidencia->tipo) == 'hardware' ? 'selected' : '' }}>Hardware</option>
                        <option value="conectividad" {{ old('tipo', $incidencia->tipo) == 'conectividad' ? 'selected' : '' }}>Conectividad</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Módulo <span class="text-danger">*</span></label>
                    <select name="modulo" class="form-select form-select-sm" required>
                        @foreach(['caja', 'creditos', 'administracion', 'soporte administrativo', 'OTROS', 'plataforma'] as $mod)
                            <option value="{{ $mod }}" {{ old('modulo', $incidencia->modulo) == $mod ? 'selected' : '' }}>
                                {{ ucfirst($mod) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Prioridad <span class="text-danger">*</span></label>
                    <select name="prioridad" class="form-select form-select-sm" required>
                        <option value="Alta" {{ old('prioridad', $incidencia->prioridad) == 'Alta' ? 'selected' : '' }}>Alta</option>
                        <option value="Media" {{ old('prioridad', $incidencia->prioridad) == 'Media' ? 'selected' : '' }}>Media</option>
                        <option value="Baja" {{ old('prioridad', $incidencia->prioridad) == 'Baja' ? 'selected' : '' }}>Baja</option>
                    </select>
                </div>
                <div class="col-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Tipo de Problema <span class="text-danger">*</span></label>
                    <input type="text" name="problema" class="form-control form-control-sm" required 
                           value="{{ old('problema', $incidencia->problema) }}">
                </div>
            </div>
        </div>

        {{-- Bloque 2: Solicitante y Ubicación --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-primary fw-semibold mb-2" style="font-size: 0.9rem;">
                <i class="bi bi-person-badge me-1"></i> 2. USUARIO AFECTADO Y UBICACIÓN
            </div>
            <div class="row g-2.5">
                <div class="col-md-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Usuario Afectado <span class="text-danger">*</span></label>
                    <input type="text" name="usuario_afectado" class="form-control form-control-sm" required 
                           value="{{ old('usuario_afectado', $incidencia->usuario_afectado) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Agencia <span class="text-danger">*</span></label>
                    <input type="text" name="agencia" class="form-control form-control-sm" required 
                           value="{{ old('agencia', $incidencia->agencia) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Sub-Agencia / Área</label>
                    <input type="text" name="sub_agencia" class="form-control form-control-sm" 
                           value="{{ old('sub_agencia', $incidencia->sub_agencia) }}">
                </div>
            </div>
        </div>

        {{-- Bloque 3: Descripción, Solución y Estado --}}
        <div class="border rounded-2 p-2.5 mb-2 bg-white">
            <div class="text-primary fw-semibold mb-2" style="font-size: 0.9rem;">
                <i class="bi bi-clipboard2-check me-1"></i> 3. DETALLE, SOLUCIÓN Y ESTADO
            </div>
            <div class="row g-2.5">
                <div class="col-12">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Descripción de la Falla <span class="text-danger">*</span></label>
                    <textarea name="descripcion" class="form-control form-control-sm" rows="3" required>{{ old('descripcion', $incidencia->descripcion) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Procedimiento o Solución Técnica Aplicada <span class="text-danger">*</span></label>
                    <textarea name="solucion" class="form-control form-control-sm" rows="3" required>{{ old('solucion', $incidencia->solucion) }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Estado del Ticket <span class="text-danger">*</span></label>
                    <select name="estado" class="form-select form-select-sm" required>
                        <option value="Atendido" {{ old('estado', $incidencia->estado) == 'Atendido' ? 'selected' : '' }}>Atendido</option>
                        <option value="Pendiente" {{ old('estado', $incidencia->estado) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Derivado" {{ old('estado', $incidencia->estado) == 'Derivado' ? 'selected' : '' }}>Derivado</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-warning btn-sm px-4 fw-medium text-dark">
            <i class="bi bi-check2-circle me-1"></i> Guardar Cambios
        </button>
    </div>
</form>
