{{-- Modal Create: Nuevo Mantenimiento Contabillete (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-white" style="background: linear-gradient(135deg, #198754 0%, #157347 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-tools text-white small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-white fs-6 d-block lh-sm">Nuevo Mantenimiento Contadora</span>
            <span class="text-white-50" style="font-size: 0.95rem;">{{ $contabillete->marca_contabilletes }} {{ $contabillete->modelo_contabilletes }} &bull; S/N: <strong>{{ $contabillete->serie_contabilletes ?: 'N/A' }}</strong> &bull; Oficina: {{ $contabillete->oficina->nombre_oficina ?? 'S/O' }}</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('admin.mantenimientos-contabillete.store', $contabillete->id) }}" method="POST" id="form-mantenimiento-contabillete-modal">
    @csrf

    <div class="modal-body p-3" style="max-height: 82vh; overflow-y: auto; font-size: 1rem;">
        {{-- Resumen de la contadora en 1 línea --}}
        <div class="bg-light border rounded-2 px-2.5 py-1.5 mb-2 d-flex justify-content-between align-items-center flex-wrap gap-2 text-muted" style="font-size: 0.95rem;">
            <span><strong class="text-dark">Oficina:</strong> {{ $contabillete->oficina->nombre_oficina ?? 'N/A' }}</span>
            <span><strong class="text-dark">Tipo:</strong> {{ $contabillete->tipo_contabilletes }}</span>
            <span><strong class="text-dark">Estado:</strong> <span class="badge bg-secondary py-0.5 px-2">{{ $contabillete->estado_contabilletes }}</span></span>
        </div>

        {{-- Formulario Compacto --}}
        <div class="border rounded-2 p-2 bg-white">
            <div class="row g-2.5">
                <div class="col-md-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">
                        Tipo de Mantenimiento <span class="text-danger">*</span>
                    </label>
                    <select name="tipo_mantenimiento" class="form-select form-select-sm py-1" required>
                        <option value="Preventivo">Preventivo</option>
                        <option value="Correctivo">Correctivo</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">
                        Fecha <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="fecha_mantenimiento" class="form-control form-control-sm py-1"
                           value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">
                        Trabajos Realizados <span class="text-danger">*</span>
                    </label>
                    <textarea name="descripcion_mantenimiento" class="form-control form-control-sm py-1" rows="2" required
                              placeholder="Detalle de los trabajos realizados (presione Enter para líneas múltiples)..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Fallas Detectadas</label>
                    <textarea name="fallas_detectadas" class="form-control form-control-sm py-1" rows="2"
                              placeholder="Describa fallas encontradas (opcional)..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Solución Aplicada</label>
                    <textarea name="fallas_solucion" class="form-control form-control-sm py-1" rows="2"
                              placeholder="Describa soluciones aplicadas (opcional)..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Observaciones y Recomendaciones</label>
                    <textarea name="observacion_general" class="form-control form-control-sm py-1" rows="1"
                              placeholder="Observaciones o recomendaciones...">{{ $ultimoMantenimiento->observacion_general ?? '' }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" name="generar_hoja_vida" class="form-check-input" id="generarHojaVidaModalCont" checked>
                        <label class="form-check-label text-dark" for="generarHojaVidaModalCont" style="font-size: 0.95rem;">
                            <i class="bi bi-file-pdf text-danger me-1"></i>Generar Hoja de Vida automáticamente al guardar
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light py-1.5 px-3">
        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5 ms-auto" data-bs-dismiss="modal" style="font-size: 0.95rem;">
            <i class="bi bi-x-circle me-1"></i>Cancelar
        </button>
        <button type="submit" class="btn btn-sm btn-success py-1 px-3 shadow-sm" style="font-size: 0.95rem;">
            <i class="bi bi-save me-1"></i>Guardar Mantenimiento
        </button>
    </div>
</form>
