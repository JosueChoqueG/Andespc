{{-- Modal Edit: Editar Equipo (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-dark" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-pencil-square text-dark small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-dark fs-6 d-block lh-sm">Editar: {{ $equipo->nombre_dispositivo }}</span>
            <span class="text-dark text-opacity-75" style="font-size: 0.75rem;">S/N: <strong>{{ $equipo->numero_serie ?: 'N/A' }}</strong> &bull; {{ $equipo->oficina->nombre_oficina ?? 'Sin oficina' }}</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('equipos.update', $equipo) }}" method="POST" id="form-editar-equipo-modal">
    @csrf
    @method('PUT')

    <div class="modal-body p-2" style="max-height: 75vh; overflow-y: auto; font-size: 0.82rem;">
        {{-- Sección 1: Identificación y Asignación --}}
        <div class="border rounded-2 p-2 mb-2 bg-white">
            <div class="text-primary fw-semibold mb-1" style="font-size: 0.76rem;">
                <i class="bi bi-tag me-1"></i> 1. IDENTIFICACIÓN Y ASIGNACIÓN
            </div>
            <div class="row g-2">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Nombre Dispositivo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_dispositivo" class="form-control form-control-sm py-1"
                           value="{{ $equipo->nombre_dispositivo }}" placeholder="PC-CONTABILIDAD-01" required>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Nº Serie</label>
                    <input type="text" name="numero_serie" class="form-control form-control-sm py-1"
                           value="{{ $equipo->numero_serie }}" placeholder="CNU1234567">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Tipo <span class="text-danger">*</span></label>
                    <select name="tipoequipo_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($tipoequipos as $tipo)
                            <option value="{{ $tipo->id }}" {{ $equipo->tipoequipo_id == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre_tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Oficina <span class="text-danger">*</span></label>
                    <select name="oficina_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ $equipo->oficina_id == $oficina->id ? 'selected' : '' }}>{{ $oficina->nombre_oficina }} ({{ $oficina->agencia->nombre_agencia ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Responsable</label>
                    <select name="responsable_id" class="form-select form-select-sm py-1 modal-select2">
                        <option value="">— Sin asignar —</option>
                        @foreach($responsables as $responsable)
                            <option value="{{ $responsable->id }}" {{ $equipo->responsable_id == $responsable->id ? 'selected' : '' }}>{{ $responsable->nombre_responsable }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Estado <span class="text-danger">*</span></label>
                    <select name="estado_equipo" class="form-select form-select-sm py-1" required>
                        @foreach(['Operativo', 'Operativo con Observacion', 'En mantenimiento', 'Fuera de servicio', 'De baja'] as $st)
                            <option value="{{ $st }}" {{ $equipo->estado_equipo == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Sección 2: Hardware, Modelo y S.O. --}}
        <div class="border rounded-2 p-2 mb-2 bg-white">
            <div class="text-info fw-semibold mb-1" style="font-size: 0.76rem;">
                <i class="bi bi-cpu me-1"></i> 2. HARDWARE, MODELO Y S.O.
            </div>
            <div class="row g-2">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Modelo / Marca <span class="text-danger">*</span></label>
                    <select name="modelo_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($modelos as $modelo)
                            <option value="{{ $modelo->id }}" {{ $equipo->modelo_id == $modelo->id ? 'selected' : '' }}>{{ $modelo->nombre_modelo }} ({{ $modelo->marca->nombre_marca ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Hardware <span class="text-danger">*</span></label>
                    <select name="hardware_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($hardwares as $hardware)
                            <option value="{{ $hardware->id }}" {{ $equipo->hardware_id == $hardware->id ? 'selected' : '' }}>{{ $hardware->procesador }} | {{ $hardware->ram_gb }}GB | {{ $hardware->almacenamiento_gb }}GB</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">S.O. <span class="text-danger">*</span></label>
                    <select name="sistemaoperativo_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($sistemas as $so)
                            <option value="{{ $so->id }}" {{ $equipo->sistemaoperativo_id == $so->id ? 'selected' : '' }}>{{ $so->nombre_so }} {{ $so->edicion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">F. Adquisición</label>
                    <input type="date" name="fecha_adquisicion" class="form-control form-control-sm py-1"
                           value="{{ optional($equipo->fecha_adquisicion)->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Últ. Mantenimiento</label>
                    <input type="date" name="fecha_mantenimiento" class="form-control form-control-sm py-1"
                           value="{{ optional($equipo->fecha_mantenimiento)->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Depreciación (%)</label>
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.01" min="0" max="100" name="depreciacion_anual" class="form-control py-1"
                               value="{{ $equipo->depreciacion_anual }}" placeholder="25.00">
                        <span class="input-group-text py-0">%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección 3: Conectividad y Red --}}
        <div class="border rounded-2 p-2 mb-2 bg-white">
            <div class="text-success fw-semibold mb-1" style="font-size: 0.76rem;">
                <i class="bi bi-hdd-network me-1"></i> 3. CONECTIVIDAD Y RED
            </div>
            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">IP</label>
                    <input type="text" name="direccion_ip" class="form-control form-control-sm font-monospace py-1"
                           value="{{ $equipo->direccion_ip }}" placeholder="192.168.1.100">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">MAC</label>
                    <input type="text" name="direccion_mac" class="form-control form-control-sm font-monospace py-1"
                           value="{{ $equipo->direccion_mac }}" placeholder="AA:BB:CC:DD:EE:FF">
                </div>
                <div class="col-md-2 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">Antivirus</label>
                    <input type="text" name="antivirus" class="form-control form-control-sm py-1"
                           value="{{ $equipo->antivirus }}" placeholder="ESET / Defender">
                </div>
                <div class="col-md-2 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">VPN Cusco</label>
                    <select name="vpn_cusco" class="form-select form-select-sm py-1">
                        <option value="No" {{ $equipo->vpn_cusco == 'No' ? 'selected' : '' }}>No</option>
                        <option value="Sí" {{ $equipo->vpn_cusco == 'Sí' ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-4">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;">VPN Abancay</label>
                    <select name="vpn_abancay" class="form-select form-select-sm py-1">
                        <option value="No" {{ $equipo->vpn_abancay == 'No' ? 'selected' : '' }}>No</option>
                        <option value="Sí" {{ $equipo->vpn_abancay == 'Sí' ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Sección 4: Software y Observaciones --}}
        <div class="border rounded-2 p-2 bg-white">
            <div class="text-warning fw-semibold mb-1" style="font-size: 0.76rem;">
                <i class="bi bi-journal-text me-1"></i> 4. SOFTWARE Y OBSERVACIONES
            </div>
            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;"><i class="bi bi-app text-primary me-1"></i>Programas</label>
                    <textarea name="programas_instalados" class="form-control form-control-sm py-1" rows="1" placeholder="Office, Chrome...">{{ $equipo->programas_instalados }}</textarea>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;"><i class="bi bi-key text-warning me-1"></i>Licencias</label>
                    <textarea name="licencias" class="form-control form-control-sm py-1" rows="1" placeholder="Claves/licencias">{{ $equipo->licencias }}</textarea>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;"><i class="bi bi-shield-check text-success me-1"></i>Backups</label>
                    <textarea name="copias_seguridad" class="form-control form-control-sm py-1" rows="1" placeholder="Frecuencia/destino">{{ $equipo->copias_seguridad }}</textarea>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.75rem;"><i class="bi bi-chat-left-text text-info me-1"></i>Observaciones</label>
                    <textarea name="observacion" class="form-control form-control-sm py-1" rows="1" placeholder="Notas técnicas">{{ $equipo->observacion }}</textarea>
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
