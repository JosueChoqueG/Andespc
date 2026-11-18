{{-- Modal Edit: Editar Impresora Térmica (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-dark" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-pencil-square text-dark small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-dark fs-6 d-block lh-sm">Editar Térmica: {{ $termica->marca_termica }} {{ $termica->modelo_termica }}</span>
            <span class="text-dark text-opacity-75" style="font-size: 0.95rem;">S/N: <strong>{{ $termica->serie_termica }}</strong> &bull; {{ $termica->oficina->nombre_oficina ?? 'Sin oficina' }}</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('admin.termicas.update', $termica->id) }}" method="POST" id="form-editar-termica-modal">
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
                            <option value="{{ $oficina->id }}" {{ old('oficina_id', $termica->oficina_id) == $oficina->id ? 'selected' : '' }}>
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
                            <option value="{{ $responsable->id }}" {{ old('responsable_id', $termica->responsable_id) == $responsable->id ? 'selected' : '' }}>
                                {{ $responsable->nombre_responsable }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Nº Serie <span class="text-danger">*</span></label>
                    <input type="text" name="serie_termica" class="form-control form-control-sm py-1 font-monospace"
                           value="{{ old('serie_termica', $termica->serie_termica) }}" required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo <span class="text-danger">*</span></label>
                    <input type="text" name="tipo_termica" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_termica', $termica->tipo_termica) }}" placeholder="Ej: Térmica Directa" required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Marca <span class="text-danger">*</span></label>
                    <input type="text" name="marca_termica" class="form-control form-control-sm py-1"
                           value="{{ old('marca_termica', $termica->marca_termica) }}" required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Modelo <span class="text-danger">*</span></label>
                    <input type="text" name="modelo_termica" class="form-control form-control-sm py-1"
                           value="{{ old('modelo_termica', $termica->modelo_termica) }}" required>
                </div>
            </div>
        </div>

        {{-- Bloque 2: Red y Conectividad --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-info fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-hdd-network me-1"></i> 2. CONECTIVIDAD Y ESTADO
            </div>
            <div class="row g-2.5">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Conexión</label>
                    <select name="tipo_conexion" class="form-select form-select-sm py-1">
                        @foreach(['USB', 'ETHERNET', 'SERIAL', 'WI-FI', 'BLUETOOTH'] as $con)
                            <option value="{{ $con }}" {{ old('tipo_conexion', $termica->tipo_conexion) == $con ? 'selected' : '' }}>{{ $con }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Nombre Host</label>
                    <input type="text" name="nombre_host" class="form-control form-control-sm py-1"
                           value="{{ old('nombre_host', $termica->nombre_host) }}" placeholder="POS-PRINTER">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Dirección IP</label>
                    <input type="text" name="direccion_ip" class="form-control form-control-sm font-monospace py-1"
                           value="{{ old('direccion_ip', $termica->direccion_ip) }}" placeholder="192.168.1.60">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Estado</label>
                    <select name="estado_termica" class="form-select form-select-sm py-1">
                        @foreach(['OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE', 'DE BAJA'] as $est)
                            <option value="{{ $est }}" {{ old('estado_termica', $termica->estado_termica) == $est ? 'selected' : '' }}>{{ $est }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Bloque 3: Especificaciones y Consumibles --}}
        <div class="border rounded-2 p-2 bg-white">
            <div class="text-success fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-gear me-1"></i> 3. ESPECIFICACIONES Y CONTADORES
            </div>
            <div class="row g-2.5">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Fecha Adquisición</label>
                    <input type="date" name="fecha_adquisicion" class="form-control form-control-sm py-1"
                           value="{{ old('fecha_adquisicion', $termica->fecha_adquisicion) }}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Velocidad</label>
                    <input type="text" name="velocidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('velocidad_impresion', $termica->velocidad_impresion) }}" placeholder="Ej: 250 mm/s">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Modelo Consumible</label>
                    <input type="text" name="modelo_consumible" class="form-control form-control-sm py-1"
                           value="{{ old('modelo_consumible', $termica->modelo_consumible) }}" placeholder="Rollo 80mm">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo Consumible</label>
                    <input type="text" name="tipo_consumible" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_consumible', $termica->tipo_consumible) }}" placeholder="Papel Térmico">
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Cantidad Impresa</label>
                    <input type="number" name="cantidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('cantidad_impresion', $termica->cantidad_impresion ?? 0) }}">
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Capacidad Total</label>
                    <input type="number" name="capacidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('capacidad_impresion', $termica->capacidad_impresion ?? 0) }}">
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
