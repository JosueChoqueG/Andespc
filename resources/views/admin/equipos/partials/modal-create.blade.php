{{-- Modal Create: Nuevo Equipo (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-white" style="background: linear-gradient(135deg, #198754 0%, #157347 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-pc-display-horizontal text-white small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-white fs-6 d-block lh-sm">Nuevo Equipo</span>
            <span class="text-white-50" style="font-size: 0.95rem;">Registrar una nueva computadora en el sistema</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('equipos.store') }}" method="POST" id="form-crear-equipo-modal">
    @csrf

    <div class="modal-body p-3" style="max-height: 82vh; overflow-y: auto; font-size: 1rem;">
        <div id="modal-equipo-errors" class="alert alert-danger py-1.5 px-3 small d-none mb-2"></div>

        {{-- Sección 1: Identificación y Asignación --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-success fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-tag me-1"></i> 1. IDENTIFICACIÓN Y ASIGNACIÓN
            </div>
            <div class="row g-2.5">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Nombre Dispositivo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_dispositivo" class="form-control form-control-sm py-1"
                           value="{{ old('nombre_dispositivo') }}" placeholder="PC-CONTABILIDAD-01" required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Número de Serie</label>
                    <input type="text" name="numero_serie" class="form-control form-control-sm py-1"
                           value="{{ old('numero_serie') }}" placeholder="SN-XXXXX">
                </div>
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
                        <option value="">— Sin asignar —</option>
                        @foreach($responsables as $resp)
                            <option value="{{ $resp->id }}" {{ old('responsable_id') == $resp->id ? 'selected' : '' }}>
                                {{ $resp->nombre_responsable }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo de Equipo <span class="text-danger">*</span></label>
                    <select name="tipoequipo_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">Seleccione Tipo</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipoequipo_id') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre_tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Estado <span class="text-danger">*</span></label>
                    <select name="estado_equipo" class="form-select form-select-sm py-1" required>
                        @foreach(['Operativo', 'Operativo con Observacion', 'En mantenimiento', 'Fuera de servicio', 'De baja'] as $est)
                            <option value="{{ $est }}" {{ old('estado_equipo', 'Operativo') === $est ? 'selected' : '' }}>{{ $est }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Sección 2: Hardware y Sistema Operativo --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-primary fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-cpu me-1"></i> 2. HARDWARE Y SISTEMA OPERATIVO
            </div>
            <div class="row g-2.5">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Modelo / Marca <span class="text-danger">*</span></label>
                    <select name="modelo_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">Seleccione Modelo</option>
                        @foreach($modelos as $mod)
                            <option value="{{ $mod->id }}" {{ old('modelo_id') == $mod->id ? 'selected' : '' }}>
                                {{ $mod->marca->nombre_marca ?? 'N/A' }} - {{ $mod->nombre_modelo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Hardware Base <span class="text-danger">*</span></label>
                    <select name="hardware_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">Seleccione Hardware</option>
                        @foreach($hardwares as $hw)
                            <option value="{{ $hw->id }}" {{ old('hardware_id') == $hw->id ? 'selected' : '' }}>
                                {{ $hw->procesador }} | {{ $hw->ram_gb }}GB RAM | {{ $hw->almacenamiento_gb }}GB {{ $hw->tipo_almacenamiento }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Sistema Operativo <span class="text-danger">*</span></label>
                    <select name="sistemaoperativo_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">Seleccione S.O.</option>
                        @foreach($sistemas as $so)
                            <option value="{{ $so->id }}" {{ old('sistemaoperativo_id') == $so->id ? 'selected' : '' }}>
                                {{ $so->nombre_so }} {{ $so->edicion }} ({{ $so->version }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Antivirus</label>
                    <input type="text" name="antivirus" class="form-control form-control-sm py-1"
                           value="{{ old('antivirus', 'ACTIVO') }}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Fecha Adquisición</label>
                    <input type="date" name="fecha_adquisicion" class="form-control form-control-sm py-1"
                           value="{{ old('fecha_adquisicion') }}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Último Mantenimiento</label>
                    <input type="date" name="fecha_mantenimiento" class="form-control form-control-sm py-1"
                           value="{{ old('fecha_mantenimiento') }}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Depreciación Anual (%)</label>
                    <input type="number" step="0.01" name="depreciacion_anual" class="form-control form-control-sm py-1"
                           value="{{ old('depreciacion_anual', '20') }}" placeholder="25.00">
                </div>
            </div>
        </div>

        {{-- Sección 3: Red y Conectividad --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-purple fw-semibold mb-1" style="font-size: 0.95rem; color: #6f42c1;">
                <i class="bi bi-diagram-3 me-1"></i> 3. RED Y CONECTIVIDAD
            </div>
            <div class="row g-2.5">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Dirección IP</label>
                    <input type="text" name="direccion_ip" class="form-control form-control-sm py-1"
                           value="{{ old('direccion_ip') }}" placeholder="192.168.1.X">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Dirección MAC</label>
                    <input type="text" name="direccion_mac" class="form-control form-control-sm py-1"
                           value="{{ old('direccion_mac') }}" placeholder="XX:XX:XX:XX:XX:XX">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">VPN Cusco</label>
                    <select name="vpn_cusco" class="form-select form-select-sm py-1">
                        <option value="No" {{ old('vpn_cusco', 'No') === 'No' ? 'selected' : '' }}>No</option>
                        <option value="Sí" {{ old('vpn_cusco') === 'Sí' ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">VPN Abancay</label>
                    <select name="vpn_abancay" class="form-select form-select-sm py-1">
                        <option value="No" {{ old('vpn_abancay', 'No') === 'No' ? 'selected' : '' }}>No</option>
                        <option value="Sí" {{ old('vpn_abancay') === 'Sí' ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Sección 4: Software, Licencias y Observaciones --}}
        <div class="border rounded-2 p-2 bg-white">
            <div class="text-secondary fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-file-earmark-code me-1"></i> 4. SOFTWARE, LICENCIAS Y OBSERVACIONES
            </div>
            <div class="row g-2.5">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Licencias</label>
                    <input type="text" name="licencias" class="form-control form-control-sm py-1"
                           value="{{ old('licencias','Windows y ESET Endpoint Security') }}" placeholder="Office 2021, Windows Pro...">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Copias de Seguridad</label>
                    <input type="text" name="copias_seguridad" class="form-control form-control-sm py-1"
                           value="{{ old('copias_seguridad','La información se almacena en la partición disco local D') }}" placeholder="Backup semanal/mensual...">
                </div>
                <div class="col-md-4 col-sm-12">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Programas Instalados</label>
                    <textarea name="programas_instalados" class="form-control form-control-sm py-1" rows="2"
                              placeholder="Lista de programas...">{{ old('programas_instalados', 'ESET Endpoint Security, Oficce Profesional Plus 2021, Anydesk, 7zip, Zoom, pdf24, navegadores, LibreOffice, accesos unificados, financial 2.5') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Observaciones</label>
                    <textarea name="observacion" class="form-control form-control-sm py-1" rows="2"
                              placeholder="Observaciones adicionales...">{{ old('observacion') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-sm py-1 px-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-success btn-sm py-1 px-3 fw-medium">
            <i class="bi bi-save2 me-1"></i> Guardar Equipo
        </button>
    </div>
</form>
