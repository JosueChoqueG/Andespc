{{-- Modal Edit: Editar Impresora (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-dark" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-pencil-square text-dark small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-dark fs-6 d-block lh-sm">Editar Impresora: {{ $impresora->marca_impresora }} {{ $impresora->modelo_impresora }}</span>
            <span class="text-dark text-opacity-75" style="font-size: 0.75rem;">S/N: <strong>{{ $impresora->serie_impresora }}</strong> &bull; {{ $impresora->oficina->nombre_oficina ?? 'Sin oficina' }}</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('admin.impresoras.update', $impresora->id) }}" method="POST" id="form-editar-impresora-modal">
    @csrf
    @method('PUT')

    <div class="modal-body p-2" style="max-height: 75vh; overflow-y: auto; font-size: 0.82rem;">
        {{-- Bloque 1: Identificación y Ubicación --}}
        <div class="border rounded-2 p-2 mb-2 bg-white">
            <div class="text-primary fw-semibold mb-1" style="font-size: 0.76rem;">
                <i class="bi bi-tag me-1"></i> 1. IDENTIFICACIÓN Y ASIGNACIÓN
            </div>
            <div class="row g-2">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Oficina <span class="text-danger">*</span></label>
                    <select name="oficina_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ old('oficina_id', $impresora->oficina_id) == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre_oficina }} ({{ $oficina->agencia->nombre_agencia ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Responsable</label>
                    <select name="responsable_id" class="form-select form-select-sm py-1 modal-select2">
                        <option value="">— Sin asignar —</option>
                        @foreach($responsables as $responsable)
                            <option value="{{ $responsable->id }}" {{ old('responsable_id', $impresora->responsable_id) == $responsable->id ? 'selected' : '' }}>
                                {{ $responsable->nombre_responsable }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Nº Serie <span class="text-danger">*</span></label>
                    <input type="text" name="serie_impresora" class="form-control form-control-sm py-1 font-monospace"
                           value="{{ old('serie_impresora', $impresora->serie_impresora) }}" required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Tipo <span class="text-danger">*</span></label>
                    <select name="tipo_impresora" class="form-select form-select-sm py-1" required>
                        @foreach(['Laser', 'Inyección', 'Matricial', 'Multifuncional', 'Plotter'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_impresora', $impresora->tipo_impresora) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Marca <span class="text-danger">*</span></label>
                    <input type="text" name="marca_impresora" class="form-control form-control-sm py-1"
                           value="{{ old('marca_impresora', $impresora->marca_impresora) }}" required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Modelo <span class="text-danger">*</span></label>
                    <input type="text" name="modelo_impresora" class="form-control form-control-sm py-1"
                           value="{{ old('modelo_impresora', $impresora->modelo_impresora) }}" required>
                </div>
            </div>
        </div>

        {{-- Bloque 2: Red y Conectividad --}}
        <div class="border rounded-2 p-2 mb-2 bg-white">
            <div class="text-info fw-semibold mb-1" style="font-size: 0.76rem;">
                <i class="bi bi-hdd-network me-1"></i> 2. CONECTIVIDAD Y ESTADO
            </div>
            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Conexión</label>
                    <select name="tipo_conexion" class="form-select form-select-sm py-1">
                        @foreach(['USB', 'WIFI', 'ETHERNET', 'WIFI-DIRECT'] as $con)
                            <option value="{{ $con }}" {{ old('tipo_conexion', $impresora->tipo_conexion) == $con ? 'selected' : '' }}>{{ $con }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Nombre Host</label>
                    <input type="text" name="nombre_host" class="form-control form-control-sm py-1"
                           value="{{ old('nombre_host', $impresora->nombre_host) }}" placeholder="HOST-PRINTER">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Dirección IP</label>
                    <input type="text" name="direccion_ip" class="form-control form-control-sm font-monospace py-1"
                           value="{{ old('direccion_ip', $impresora->direccion_ip) }}" placeholder="192.168.1.50">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Estado</label>
                    <select name="estado_impresora" class="form-select form-select-sm py-1">
                        @foreach(['OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE', 'DE BAJA'] as $est)
                            <option value="{{ $est }}" {{ old('estado_impresora', $impresora->estado_impresora) == $est ? 'selected' : '' }}>{{ $est }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Bloque 3: Especificaciones y Consumibles --}}
        <div class="border rounded-2 p-2 mb-2 bg-white">
            <div class="text-success fw-semibold mb-1" style="font-size: 0.76rem;">
                <i class="bi bi-gear me-1"></i> 3. ESPECIFICACIONES Y CONSUMIBLES
            </div>
            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Fecha Adquisición</label>
                    <input type="date" name="fecha_adquisicion" class="form-control form-control-sm py-1"
                           value="{{ old('fecha_adquisicion', $impresora->fecha_adquisicion) }}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Velocidad Impresión</label>
                    <input type="text" name="velocidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('velocidad_impresion', $impresora->velocidad_impresion) }}" placeholder="Ej: 30 ppm">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Modelo Consumible</label>
                    <input type="text" name="modelo_consumible" class="form-control form-control-sm py-1"
                           value="{{ old('modelo_consumible', $impresora->modelo_consumible) }}" placeholder="Ej: TN-1060">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Tipo Consumible</label>
                    <input type="text" name="tipo_consumible" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_consumible', $impresora->tipo_consumible) }}" placeholder="Tóner / Tinta">
                </div>
            </div>
        </div>

        {{-- Bloque 4: Contadores de Uso --}}
        <div class="border rounded-2 p-2 bg-white">
            <div class="text-secondary fw-semibold mb-1" style="font-size: 0.76rem;">
                <i class="bi bi-graph-up me-1"></i> 4. CONTADORES DE PÁGINAS Y ESCANEOS
            </div>
            <div class="row g-2">
                <div class="col-md-4 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Capacidad Total</label>
                    <input type="number" name="capacidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('capacidad_impresion', $impresora->capacidad_impresion ?? 0) }}">
                </div>
                <div class="col-md-4 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Cantidad Impresa</label>
                    <input type="number" name="cantidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('cantidad_impresion', $impresora->cantidad_impresion ?? 0) }}">
                </div>
                <div class="col-md-4 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Cantidad Escaneos</label>
                    <input type="number" name="cantidad_escaneo" class="form-control form-control-sm py-1"
                           value="{{ old('cantidad_escaneo', $impresora->cantidad_escaneo ?? 0) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light py-1.5 px-3">
        <span class="text-muted me-auto" style="font-size: 0.72rem;"><i class="bi bi-info-circle me-1"></i>Campos con <strong class="text-danger">*</strong> obligatorios</span>
        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5" data-bs-dismiss="modal" style="font-size: 0.78rem;">
            <i class="bi bi-x-circle me-1"></i>Cancelar
        </button>
        <button type="submit" class="btn btn-sm btn-primary py-1 px-3 shadow-sm" style="font-size: 0.78rem;">
            <i class="bi bi-floppy me-1"></i>Guardar Cambios
        </button>
    </div>
</form>
