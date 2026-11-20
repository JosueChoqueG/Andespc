{{-- Modal Create: Registrar Nueva Incidencia (Bitácora TI) --}}
<div class="modal-header py-2.5 px-3 text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
            <i class="bi bi-journal-plus text-white fs-6"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-white fs-6 d-block lh-sm">
                Nueva Entrada en Bitácora de Incidencias
            </span>
            <span class="text-white-50" style="font-size: 0.85rem;">
                Registro técnico de asistencia, eventos de soporte y resolución de fallas
            </span>
        </div>
    </div>
    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('admin.incidencias.guardar') }}" method="POST" id="form-crear-incidencia-modal">
    @csrf

    <div class="modal-body p-3" style="max-height: 82vh; overflow-y: auto; font-size: 0.95rem;">
        <div id="modal-incidencia-create-errors" class="alert alert-danger py-2 px-3 small d-none mb-2.5"></div>

        {{-- Bloque 1: Clasificación --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-primary fw-semibold mb-2" style="font-size: 0.9rem;">
                <i class="bi bi-sliders me-1"></i> 1. CLASIFICACIÓN DE LA ASISTENCIA TÉCNICA
            </div>
            <div class="row g-2.5">
                <div class="col-md-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Tipo de Asistencia <span class="text-danger">*</span></label>
                    <select name="tipo" class="form-select form-select-sm" required id="modal-create-tipo">
                        <option value="">— Seleccionar —</option>
                        <option value="software">Software</option>
                        <option value="hardware">Hardware</option>
                        <option value="conectividad">Conectividad</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Módulo <span class="text-danger">*</span></label>
                    <select name="modulo" class="form-select form-select-sm" required>
                        <option value="">— Seleccionar —</option>
                        <option value="caja">Caja</option>
                        <option value="creditos">Créditos</option>
                        <option value="administracion">Administración</option>
                        <option value="soporte administrativo">Soporte Administrativo</option>
                        <option value="OTROS" selected>OTROS</option>
                        <option value="plataforma">Plataforma</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Prioridad <span class="text-danger">*</span></label>
                    <select name="prioridad" class="form-select form-select-sm" required>
                        <option value="Alta" selected>Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>
                <div class="col-3">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Tipo de Problema <span class="text-danger">*</span></label>
                    <select name="problema" class="form-select form-select-sm" required id="modal-create-problema">
                        <option value="">Seleccione un tipo</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Bloque 2: Solicitante y Ubicación --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-primary fw-semibold mb-2" style="font-size: 0.9rem;">
                <i class="bi bi-person-badge me-1"></i> 2. USUARIO AFECTADO Y SEDE DE ORIGEN
            </div>
            <div class="row g-2.5">
                <div class="col-md-4">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Usuario Afectado <span class="text-danger">*</span></label>
                    <input type="text" name="usuario_afectado" class="form-control form-control-sm" required 
                           placeholder="Ej: jquispe / Juan Quispe" value="{{ old('usuario_afectado') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Agencia Principal <span class="text-danger">*</span></label>
                    <select name="agencia" id="modal-create-agencia" class="form-select form-select-sm" required>
                        <option value="">— Seleccionar —</option>
                        <option value="Empresas de coopac">Empresas de coopac</option>
                        <option value="99 Soporte Administrativo">99 Soporte Administrativo</option>
                        <option value="101 Chalhuanca">101 Chalhuanca</option>
                        <option value="102 Andahuaylas">102 Andahuaylas</option>
                        <option value="103 Uripa">103 Uripa</option>
                        <option value="104 Antabamba">104 Antabamba</option>
                        <option value="105 Tintay">105 Tintay</option>
                        <option value="106 Huaccana">106 Huaccana</option>
                        <option value="107 Lima">107 Lima</option>
                        <option value="108 Huancarama">108 Huancarama</option>
                        <option value="109 Abancay">109 Abancay</option>
                        <option value="110 Grau">110 Grau</option>
                        <option value="111 Cotabambas">111 Cotabambas</option>
                        <option value="112 Curahuasi">112 Curahuasi</option>
                        <option value="113 Secclla">113 Secclla</option>
                        <option value="114 Cusco">114 Cusco</option>
                        <option value="115 Santo Tomás">115 Santo Tomás</option>
                        <option value="116 Pampa Cangallo">116 Pampa Cangallo</option>
                        <option value="117 Huamanga">117 Huamanga</option>
                        <option value="118 Pampas Tayacaja">118 Pampas Tayacaja</option>
                        <option value="119 Huancayo">119 Huancayo</option>
                        <option value="120 Urubamba">120 Urubamba</option>
                        <option value="121 Combapata">121 Combapata</option>
                        <option value="122 La Merced">122 La Merced</option>
                        <option value="123 San Juan de Miraflores">123 S.J. Miraflores</option>
                        <option value="124 San Juan de Lurigancho">124 S.J. Lurigancho</option>
                        <option value="125 Nueva Esperanza">125 Nueva Esperanza</option>
                        <option value="126 Ocampo">126 Ocampo</option>
                        <option value="127 Cañete">127 Cañete</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Sub-Agencia / Oficina</label>
                    <select name="sub_agencia" id="modal-create-subagencia" class="form-select form-select-sm">
                        <option value="">Seleccione una agencia primero</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Bloque 3: Detalle y Solución Técnica --}}
        <div class="border rounded-2 p-2.5 mb-2 bg-white">
            <div class="text-primary fw-semibold mb-2" style="font-size: 0.9rem;">
                <i class="bi bi-file-earmark-medical me-1"></i> 3. DETALLE DE LA FALLA & PROCEDIMIENTO TÉCNICO
            </div>
            <div class="row g-2.5">
                <div class="col-12">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Descripción del Problema / Falla <span class="text-danger">*</span></label>
                    <textarea name="descripcion" class="form-control form-control-sm" rows="3" required
                              placeholder="Describa con precisión los síntomas, mensaje de error o comportamiento observado...">{{ old('descripcion') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Procedimiento o Solución Técnica Aplicada <span class="text-danger">*</span></label>
                    <textarea name="solucion" class="form-control form-control-sm" rows="3" required
                              placeholder="Indique los pasos realizados para solucionar o derivar el incidente...">{{ old('solucion') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label mb-0 text-muted" style="font-size: 0.85rem;">Estado Inicial del Ticket <span class="text-danger">*</span></label>
                    <select name="estado" class="form-select form-select-sm" required>
                        <option value="Atendido" selected>Atendido (Resuelto en la intervención)</option>
                        <option value="Pendiente">Pendiente (Requiere seguimiento o repuestos)</option>
                        <option value="Derivado">Derivado (Escalado a Desarrollo / Tercero)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary btn-sm px-4 fw-medium">
            <i class="bi bi-floppy2-fill me-1"></i> Guardar en Bitácora
        </button>
    </div>
</form>
