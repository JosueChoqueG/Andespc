{{-- Modal Create: Nueva Impresora Térmica (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-dark" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-receipt text-dark small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-dark fs-6 d-block lh-sm">Nueva Impresora Térmica</span>
            <span class="text-dark text-opacity-75" style="font-size: 0.75rem;">Registrar una nueva impresora de tickets</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('admin.termicas.store') }}" method="POST" id="form-crear-termica-modal">
    @csrf

    <div class="modal-body p-2" style="max-height: 75vh; overflow-y: auto; font-size: 0.82rem;">
        {{-- Sección 1: Identificación y Asignación --}}
        <div class="border rounded-2 p-2 mb-2 bg-white">
            <div class="text-warning fw-semibold mb-1" style="font-size: 0.76rem; color: #b45309 !important;">
                <i class="bi bi-tag me-1"></i> 1. IDENTIFICACIÓN Y ASIGNACIÓN
            </div>
            <div class="row g-2">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Oficina <span class="text-danger">*</span></label>
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
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Responsable</label>
                    <select name="responsable_id" class="form-select form-select-sm py-1 modal-select2">
                        <option value="">Seleccione Responsable</option>
                        @foreach($responsables as $resp)
                            <option value="{{ $resp->id }}" {{ old('responsable_id') == $resp->id ? 'selected' : '' }}>
                                {{ $resp->nombre_responsable }} ({{ $resp->cargo_responsable }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Tipo de Térmica <span class="text-danger">*</span></label>
                    <input type="text" name="tipo_termica" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_termica', 'Impresora Térmica') }}" placeholder="Tickets, Etiquetas..." required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Marca <span class="text-danger">*</span></label>
                    <input type="text" name="marca_termica" class="form-control form-control-sm py-1"
                           value="{{ old('marca_termica') }}" placeholder="Epson, Bixolon, Xprinter..." required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Modelo <span class="text-danger">*</span></label>
                    <input type="text" name="modelo_termica" class="form-control form-control-sm py-1"
                           value="{{ old('modelo_termica') }}" placeholder="TM-T20III..." required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Número de Serie <span class="text-danger">*</span></label>
                    <input type="text" name="serie_termica" class="form-control form-control-sm py-1"
                           value="{{ old('serie_termica') }}" placeholder="TERM-XXXXX" required>
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Estado</label>
                    <select name="estado_termica" class="form-select form-select-sm py-1">
                        @foreach(['OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE', 'DE BAJA'] as $est)
                            <option value="{{ $est }}" {{ old('estado_termica', 'BUENO') === $est ? 'selected' : '' }}>{{ $est }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Fecha Adquisición</label>
                    <input type="date" name="fecha_adquisicion" class="form-control form-control-sm py-1"
                           value="{{ old('fecha_adquisicion') }}">
                </div>
            </div>
        </div>

        {{-- Sección 2: Conectividad y Red --}}
        <div class="border rounded-2 p-2 mb-2 bg-white">
            <div class="text-purple fw-semibold mb-1" style="font-size: 0.76rem; color: #6f42c1;">
                <i class="bi bi-wifi me-1"></i> 2. CONECTIVIDAD Y RED
            </div>
            <div class="row g-2">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Tipo Conexión</label>
                    <select name="tipo_conexion" class="form-select form-select-sm py-1">
                        <option value="">Seleccione Conexión</option>
                        @foreach(['USB', 'ETHERNET', 'SERIAL', 'WI-FI', 'BLUETOOTH'] as $con)
                            <option value="{{ $con }}" {{ old('tipo_conexion') === $con ? 'selected' : '' }}>{{ $con }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Nombre Host</label>
                    <input type="text" name="nombre_host" class="form-control form-control-sm py-1"
                           value="{{ old('nombre_host') }}" placeholder="TERM-VENTANILLA-01">
                </div>
                <div class="col-md-4 col-sm-12">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Dirección IP</label>
                    <input type="text" name="direccion_ip" class="form-control form-control-sm py-1"
                           value="{{ old('direccion_ip') }}" placeholder="192.168.1.60">
                </div>
            </div>
        </div>

        {{-- Sección 3: Especificaciones y Consumibles --}}
        <div class="border rounded-2 p-2 bg-white">
            <div class="text-secondary fw-semibold mb-1" style="font-size: 0.76rem;">
                <i class="bi bi-gear me-1"></i> 3. ESPECIFICACIONES Y CONSUMIBLES
            </div>
            <div class="row g-2">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Velocidad Impresión</label>
                    <input type="text" name="velocidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('velocidad_impresion') }}" placeholder="250 mm/s">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Modelo Consumible</label>
                    <input type="text" name="modelo_consumible" class="form-control form-control-sm py-1"
                           value="{{ old('modelo_consumible') }}" placeholder="Papel térmico 80mm">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Tipo Consumible</label>
                    <input type="text" name="tipo_consumible" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_consumible', 'Rollo Térmico') }}" placeholder="Rollo Térmico">
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Capacidad Impresión</label>
                    <input type="text" name="capacidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('capacidad_impresion') }}" placeholder="80mm x 80m">
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Contador Impresiones (Tickets)</label>
                    <input type="number" name="cantidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('cantidad_impresion', 0) }}" min="0">
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-sm py-1 px-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-warning btn-sm py-1 px-3 fw-medium text-dark">
            <i class="bi bi-save2 me-1"></i> Guardar Térmica
        </button>
    </div>
</form>
