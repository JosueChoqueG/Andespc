{{-- Modal Create: Nueva Contadora de Billetes (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-white" style="background: linear-gradient(135deg, #6f42c1 0%, #59359a 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-cash-stack text-white small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-white fs-6 d-block lh-sm">Nueva Contadora de Billetes</span>
            <span class="text-white-50" style="font-size: 0.95rem;">Registrar una nueva contadora / detectora en el sistema</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('admin.contabilletes.store') }}" method="POST" id="form-crear-contabillete-modal">
    @csrf

    <div class="modal-body p-3" style="max-height: 82vh; overflow-y: auto; font-size: 1rem;">
        <div id="modal-contabillete-errors" class="alert alert-danger py-1.5 px-3 small d-none mb-2"></div>

        {{-- Sección 1: Identificación y Asignación --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-purple fw-semibold mb-1" style="font-size: 0.95rem; color: #6f42c1;">
                <i class="bi bi-tag me-1"></i> 1. IDENTIFICACIÓN Y ASIGNACIÓN
            </div>
            <div class="row g-2.5">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Oficina <span class="text-danger">*</span></label>
                    <select name="oficina_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">Seleccione Oficina</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ old('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre_oficina }} ({{ $oficina->agencia->nombre_agencia ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Responsable</label>
                    <select name="responsable_id" class="form-select form-select-sm py-1 modal-select2">
                        <option value="">Seleccione Responsable</option>
                        @foreach($responsables as $resp)
                            <option value="{{ $resp->id }}" {{ old('responsable_id') == $resp->id ? 'selected' : '' }}>
                                {{ $resp->nombre_responsable }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo de Contadora <span class="text-danger">*</span></label>
                    <input type="text" name="tipo_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_contabilletes', 'Contadora de Billetes') }}" placeholder="Mixta, Clasificadora..." required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Marca <span class="text-danger">*</span></label>
                    <input type="text" name="marca_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('marca_contabilletes') }}" placeholder="Glory, Kisan, Magner..." required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Modelo <span class="text-danger">*</span></label>
                    <input type="text" name="modelo_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('modelo_contabilletes') }}" placeholder="GFB-800, K2..." required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Número de Serie <span class="text-danger">*</span></label>
                    <input type="text" name="serie_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('serie_contabilletes') }}" placeholder="CB-XXXXX" required>
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Estado</label>
                    <select name="estado_contabilletes" class="form-select form-select-sm py-1">
                        @foreach(['OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE', 'DE BAJA'] as $est)
                            <option value="{{ $est }}" {{ old('estado_contabilletes', 'BUENO') === $est ? 'selected' : '' }}>{{ $est }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Fecha Adquisición</label>
                    <input type="date" name="fecha_adquisicion" class="form-control form-control-sm py-1"
                           value="{{ old('fecha_adquisicion') }}">
                </div>
            </div>
        </div>

        {{-- Sección 2: Especificaciones Técnicas --}}
        <div class="border rounded-2 p-2 bg-white">
            <div class="text-secondary fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-gear me-1"></i> 2. ESPECIFICACIONES TÉCNICAS
            </div>
            <div class="row g-2.5">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Velocidad (billetes/min)</label>
                    <input type="number" name="velocidad_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('velocidad_contabilletes') }}" placeholder="1000">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Capacidad Tolva (billetes)</label>
                    <input type="number" name="capacidad_tolva" class="form-control form-control-sm py-1"
                           value="{{ old('capacidad_tolva') }}" placeholder="300">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Capacidad Bandeja (billetes)</label>
                    <input type="number" name="capacidad_bandeja" class="form-control form-control-sm py-1"
                           value="{{ old('capacidad_bandeja') }}" placeholder="200">
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo Detección Falsos</label>
                    <input type="text" name="tipo_deteccion" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_deteccion') }}" placeholder="UV, MG, IR, CIS...">
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo Pantalla</label>
                    <input type="text" name="pantalla_contabilletes" class="form-control form-control-sm py-1"
                           value="{{ old('pantalla_contabilletes') }}" placeholder="LCD Color, LED...">
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-sm py-1 px-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-purple btn-sm py-1 px-3 fw-medium text-white" style="background-color: #6f42c1;">
            <i class="bi bi-save2 me-1"></i> Guardar Contadora
        </button>
    </div>
</form>
