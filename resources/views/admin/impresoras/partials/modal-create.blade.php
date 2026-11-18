{{-- Modal Create: Nueva Impresora (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-printer-fill text-white small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-white fs-6 d-block lh-sm">Nueva Impresora</span>
            <span class="text-white-50" style="font-size: 0.95rem;">Registrar un nuevo dispositivo de impresión</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('admin.impresoras.store') }}" method="POST" id="form-crear-impresora-modal">
    @csrf

    <div class="modal-body p-3" style="max-height: 82vh; overflow-y: auto; font-size: 1rem;">
        <div id="modal-impresora-errors" class="alert alert-danger py-1.5 px-3 small d-none mb-2"></div>

        {{-- Sección 1: Identificación y Asignación --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-primary fw-semibold mb-1" style="font-size: 0.95rem;">
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
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo de Impresora <span class="text-danger">*</span></label>
                    <select name="tipo_impresora" class="form-select form-select-sm py-1" required>
                        <option value="">Seleccione Tipo</option>
                        @foreach(['Laser', 'Inyección', 'Matricial', 'Multifuncional', 'Plotter'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_impresora') === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Marca <span class="text-danger">*</span></label>
                    <input type="text" name="marca_impresora" class="form-control form-control-sm py-1"
                           value="{{ old('marca_impresora') }}" placeholder="HP, Epson, Canon..." required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Modelo <span class="text-danger">*</span></label>
                    <input type="text" name="modelo_impresora" class="form-control form-control-sm py-1"
                           value="{{ old('modelo_impresora') }}" placeholder="LaserJet Pro MFP..." required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Número de Serie <span class="text-danger">*</span></label>
                    <input type="text" name="serie_impresora" class="form-control form-control-sm py-1"
                           value="{{ old('serie_impresora') }}" placeholder="VND3K0..." required>
                </div>
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Estado</label>
                    <select name="estado_impresora" class="form-select form-select-sm py-1">
                        @foreach(['OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE', 'DE BAJA'] as $est)
                            <option value="{{ $est }}" {{ old('estado_impresora', 'BUENO') === $est ? 'selected' : '' }}>{{ $est }}</option>
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

        {{-- Sección 2: Conectividad y Red --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-purple fw-semibold mb-1" style="font-size: 0.95rem; color: #6f42c1;">
                <i class="bi bi-wifi me-1"></i> 2. CONECTIVIDAD Y RED
            </div>
            <div class="row g-2.5">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo Conexión</label>
                    <select name="tipo_conexion" class="form-select form-select-sm py-1">
                        <option value="">Seleccione Conexión</option>
                        @foreach(['USB', 'WIFI', 'ETHERNET', 'WIFI-DIRECT'] as $con)
                            <option value="{{ $con }}" {{ old('tipo_conexion') === $con ? 'selected' : '' }}>{{ $con }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Nombre Host</label>
                    <input type="text" name="nombre_host" class="form-control form-control-sm py-1"
                           value="{{ old('nombre_host') }}" placeholder="PRN-OFICINA-01">
                </div>
                <div class="col-md-4 col-sm-12">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Dirección IP</label>
                    <input type="text" name="direccion_ip" class="form-control form-control-sm py-1"
                           value="{{ old('direccion_ip') }}" placeholder="192.168.1.50">
                </div>
            </div>
        </div>

        {{-- Sección 3: Especificaciones y Consumibles --}}
        <div class="border rounded-2 p-2 bg-white">
            <div class="text-secondary fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-gear me-1"></i> 3. ESPECIFICACIONES Y CONSUMIBLES
            </div>
            <div class="row g-2.5">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Velocidad Impresión (ppm)</label>
                    <input type="text" name="velocidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('velocidad_impresion') }}" placeholder="30 ppm">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Modelo Consumible</label>
                    <input type="text" name="modelo_consumible" class="form-control form-control-sm py-1"
                           value="{{ old('modelo_consumible') }}" placeholder="Toner HP 85A">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo Consumible</label>
                    <input type="text" name="tipo_consumible" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_consumible') }}" placeholder="Tóner / Tinta">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Capacidad Impresión (hojas)</label>
                    <input type="text" name="capacidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('capacidad_impresion') }}" placeholder="1500 hojas">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Contador Impresiones</label>
                    <input type="number" name="cantidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('cantidad_impresion', 0) }}" min="0">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Contador Escaneos</label>
                    <input type="number" name="cantidad_escaneo" class="form-control form-control-sm py-1"
                           value="{{ old('cantidad_escaneo', 0) }}" min="0">
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-sm py-1 px-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary btn-sm py-1 px-3 fw-medium">
            <i class="bi bi-save2 me-1"></i> Guardar Impresora
        </button>
    </div>
</form>
