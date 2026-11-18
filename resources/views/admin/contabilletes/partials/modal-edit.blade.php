{{-- Modal Edit: Editar Contadora de Billetes (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-dark" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-pencil-square text-dark small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-dark fs-6 d-block lh-sm">Editar Contadora: {{ $contabillete->marca_contabilletes }} {{ $contabillete->modelo_contabilletes }}</span>
            <span class="text-dark text-opacity-75" style="font-size: 0.95rem;">S/N: <strong>{{ $contabillete->serie_contabilletes }}</strong> &bull; {{ $contabillete->oficina->nombre_oficina ?? 'Sin oficina' }}</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('admin.contabilletes.update', $contabillete->id) }}" method="POST" id="form-editar-contabillete-modal">
    @csrf
    @method('PUT')

    <div class="modal-body p-3" style="max-height: 82vh; overflow-y: auto; font-size: 1rem;">
        {{-- Bloque 1: Identificación y Ubicación --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-primary fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-tag me-1"></i> 1. IDENTIFICACIÓN Y ASIGNACIÓN
            </div>
            <div class="row g-2.5">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Oficina <span class="text-danger">*</span></label>
                    <select name="oficina_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ old('oficina_id', $contabillete->oficina_id) == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre_oficina }} ({{ $oficina->agencia->nombre_agencia ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Responsable</label>
                    <select name="responsable_id" class="form-select form-select-sm py-1 modal-select2">
                        <option value="">— Sin asignar —</option>
                        @foreach($responsables as $responsable)
                            <option value="{{ $responsable->id }}" {{ old('responsable_id', $contabillete->responsable_id) == $responsable->id ? 'selected' : '' }}>
                                {{ $responsable->nombre_responsable }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Nº Serie <span class="text-danger">*</span></label>
                    <input type="text" name="serie_contabilletes" class="form-control form-control-sm py-1 font-monospace"
                           value="{{ old('serie_contabilletes', $contabillete->serie_contabilletes) }}" required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo <span class="text-danger">*</span></label>
                    <input type="text" name="tipo_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_contabilletes', $contabillete->tipo_contabilletes) }}" placeholder="Ej: Contadora / Clasificadora" required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Marca <span class="text-danger">*</span></label>
                    <input type="text" name="marca_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('marca_contabilletes', $contabillete->marca_contabilletes) }}" required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Modelo <span class="text-danger">*</span></label>
                    <input type="text" name="modelo_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('modelo_contabilletes', $contabillete->modelo_contabilletes) }}" required>
                </div>
            </div>
        </div>

        {{-- Bloque 2: Especificaciones y Capacidades --}}
        <div class="border rounded-2 p-2 bg-white">
            <div class="text-info fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-sliders me-1"></i> 2. ESPECIFICACIONES TÉCNICAS
            </div>
            <div class="row g-2.5">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Velocidad Conteo</label>
                    <input type="text" name="velocidad_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('velocidad_contabilletes', $contabillete->velocidad_contabilletes) }}" placeholder="Ej: 1000 billetes/min">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Detección Falsos</label>
                    <input type="text" name="tipo_deteccion" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_deteccion', $contabillete->tipo_deteccion) }}" placeholder="UV / MG / IR / CIS">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Pantalla</label>
                    <input type="text" name="pantalla_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('pantalla_contabilletes', $contabillete->pantalla_contabilletes) }}" placeholder="LCD / LED">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Estado</label>
                    <select name="estado_contabilletes" class="form-select form-select-sm py-1">
                        @foreach(['OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE', 'DE BAJA'] as $est)
                            <option value="{{ $est }}" {{ old('estado_contabilletes', $contabillete->estado_contabilletes) == $est ? 'selected' : '' }}>{{ $est }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">F. Adquisición</label>
                    <input type="date" name="fecha_adquisicion" class="form-control form-control-sm py-1"
                           value="{{ old('fecha_adquisicion', $contabillete->fecha_adquisicion ? $contabillete->fecha_adquisicion->format('Y-m-d') : '') }}">
                </div>
                <div class="col-md-4 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Capacidad Tolva</label>
                    <input type="number" name="capacidad_tolva" class="form-control form-control-sm py-1"
                           value="{{ old('capacidad_tolva', $contabillete->capacidad_tolva) }}" placeholder="Ej: 300">
                </div>
                <div class="col-md-4 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Capacidad Bandeja</label>
                    <input type="number" name="capacidad_bandeja" class="form-control form-control-sm py-1"
                           value="{{ old('capacidad_bandeja', $contabillete->capacidad_bandeja) }}" placeholder="Ej: 200">
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light py-1.5 px-3">
        <span class="text-muted me-auto" style="font-size: 0.72rem;"><i class="bi bi-info-circle me-1"></i>Campos con <strong class="text-danger">*</strong> obligatorios</span>
        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5" data-bs-dismiss="modal" style="font-size: 0.95rem;">
            <i class="bi bi-x-circle me-1"></i>Cancelar
        </button>
        <button type="submit" class="btn btn-sm btn-primary py-1 px-3 shadow-sm" style="font-size: 0.95rem;">
            <i class="bi bi-floppy me-1"></i>Guardar Cambios
        </button>
    </div>
</form>
