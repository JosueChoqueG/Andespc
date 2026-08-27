@extends('layouts.app')

@section('title', 'Nuevo Informe — ' . $impresora->modelo_impresora)

@section('content')
<div class="container-fluid">

    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.impresoras.show', $impresora->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
        <nav aria-label="breadcrumb" class="ms-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.impresoras.index') }}">Impresoras</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.impresoras.show', $impresora->id) }}">{{ $impresora->serie_impresora }}</a></li>
                <li class="breadcrumb-item active">Nuevo Informe</li>
            </ol>
        </nav>
    </div>

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="d-flex align-items-center justify-content-center rounded-3 text-white"
             style="width:48px;height:48px;background:linear-gradient(135deg,#9f3f04,#e9c46a)">
            <i class="bi bi-file-earmark-text fs-4"></i>
        </div>
        <div>
            <h4 class="mb-0 fw-bold" style="color:#1d2d44">Nuevo Informe Técnico</h4>
            <small class="text-muted">
                <i class="bi bi-printer me-1"></i>
                <strong>{{ $impresora->marca_impresora }} {{ $impresora->modelo_impresora }}</strong>
                &nbsp;·&nbsp; Serie: <strong>{{ $impresora->serie_impresora }}</strong>
                &nbsp;·&nbsp; Oficina: <strong>{{ $impresora->oficina->nombre_oficina ?? '—' }}</strong>
            </small>
        </div>
    </div>

    <form action="{{ route('admin.informes-impresora.store', $impresora) }}" method="POST" enctype="multipart/form-data" id="formInforme">
        @csrf

        {{-- ── DATOS AUTOCOMPLETOS (ocultos) ─────────────────────── --}}
        {{-- La impresora ya está vinculada por la ruta; el técnico viene del auth --}}
        <input type="hidden" name="tecnico_nombre" value="{{ $tecnico }}">
        <input type="hidden" name="estado_equipo"  value="REGULAR"> {{-- editable en el futuro si se necesita --}}

        {{-- ══════════════════════════════════════════════════════════
             TARJETA INFO AUTOCOMPLETA (solo lectura, orientativa)
        ══════════════════════════════════════════════════════════ --}}
        <div class="alert border-0 mb-4 p-3 d-flex flex-wrap gap-4"
             style="background:rgba(42,157,143,0.08);border-left:4px solid #2a9d8f !important">
            <div>
                <small class="text-muted d-block">Impresora</small>
                <strong>{{ $impresora->marca_impresora }} {{ $impresora->modelo_impresora }}</strong>
            </div>
            <div>
                <small class="text-muted d-block">N° de Serie</small>
                <strong>{{ $impresora->serie_impresora ?? '—' }}</strong>
            </div>
            <div>
                <small class="text-muted d-block">Oficina / Área</small>
                <strong>{{ $impresora->oficina->nombre_oficina ?? '—' }}</strong>
            </div>
            <div>
                <small class="text-muted d-block">Técnico</small>
                <strong>{{ $tecnico }}</strong>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             1. DATOS DEL INFORME
        ══════════════════════════════════════════════════════════ --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header d-flex align-items-center gap-2 py-3"
                 style="background:linear-gradient(135deg,#1d2d44,#0d1b2a);color:#fff;border-radius:12px 12px 0 0">
                <i class="bi bi-calendar3 text-warning"></i>
                <span class="fw-semibold">1. Datos del Informe</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="fecha_informe" class="form-label fw-semibold">
                            Fecha <span class="text-danger">*</span>
                        </label>
                        <input type="date" id="fecha_informe" name="fecha_informe"
                               class="form-control @error('fecha_informe') is-invalid @enderror"
                               value="{{ old('fecha_informe', date('Y-m-d')) }}" required>
                        @error('fecha_informe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="tipo_informe" class="form-label fw-semibold">
                            Tipo de Informe <span class="text-danger">*</span>
                        </label>
                        <select id="tipo_informe" name="tipo_informe"
                                class="form-select @error('tipo_informe') is-invalid @enderror" required>
                            <option value="">— Seleccionar —</option>
                            @foreach(\App\Models\InformeImpresora::TIPOS_INFORME as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_informe') == $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_informe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="contador_copias" class="form-label fw-semibold">Contador de copias</label>
                        <input type="number" id="contador_copias" name="contador_copias" min="0"
                               class="form-control @error('contador_copias') is-invalid @enderror"
                               value="{{ old('contador_copias', $impresora->cantidad_impresion) }}" placeholder="Ej: 32651">
                        @error('contador_copias') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2 d-flex align-items-end pb-1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="en_garantia" name="en_garantia"
                                   value="1" {{ old('en_garantia') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="en_garantia">
                                <i class="bi bi-shield-check text-success me-1"></i> En garantía
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             2. INCIDENCIAS
        ══════════════════════════════════════════════════════════ --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header d-flex align-items-center gap-2 py-3"
                 style="background:linear-gradient(135deg,#c0392b,#e74c3c);color:#fff;border-radius:12px 12px 0 0">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <span class="fw-semibold">2. Incidencias</span>
            </div>
            <div class="card-body p-4">
                <label for="incidencias" class="form-label fw-semibold">
                    ¿Qué falla o problema presenta el equipo? <span class="text-danger">*</span>
                </label>
                <textarea id="incidencias" name="incidencias" rows="2"
                          class="form-control @error('incidencias') is-invalid @enderror"
                          placeholder="Ej: Atasco de papel al imprimir y hacer copias">{{ old('incidencias','Es grato dirigirme a usted para saludarlo cordialmente e informarle sobre la impresora multifuncional Kyocera Ecosys M2640, con número de serie VR84416886. Dicho equipo fue asignado a la oficina informativa de Haquira – módulo créditos. El equipo presenta inconvenientes al realizar impresiones y copias, ya que presenta atasco en el bloque D.
Para hacer las validaciones se hizo limpieza del bloque B, así como residual de tóner, pero persiste el problema. Los síntomas que presenta pueden ser por diferentes factores como; requiere la renovación de la unidad fusor, requiere cambio de la unidad de imagen o limpieza general.') }}</textarea>
                @error('incidencias') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             3. PROCEDIMIENTOS (lista dinámica)
        ══════════════════════════════════════════════════════════ --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header d-flex align-items-center gap-2 py-3"
                 style="background:linear-gradient(135deg,#2a9d8f,#21867a);color:#fff;border-radius:12px 12px 0 0">
                <i class="bi bi-list-check"></i>
                <span class="fw-semibold">3. Procedimientos Realizados</span>
                <small class="ms-auto opacity-75" style="font-size:10pt">Cada ítem = un paso de la lista</small>
            </div>
            <div class="card-body p-4">
                <div id="procedimientos-container">
                    @php $procs = old('procedimientos', ['Verificar cantidad de tóner (Correcto)', 
                                                        'Prueba: Primera impresión con 1 hojas (Atasco de papel en bloque D)', 
                                                        'Prueba: Segunda impresión múltiple (Atasco de papel bloque D)', 
                                                        'Prueba: Copias/Impresiones múltiples 06 hojas (Todas las hojas con manchas)', 
                                                        'Impresión de página de estado (total de copias/impresión 32651) [Página de estado]']);
                    @endphp
                    @foreach($procs as $i => $proc)
                    <div class="d-flex gap-2 mb-2 proc-item">
                        <span class="badge bg-secondary d-flex align-items-center justify-content-center"
                              style="min-width:28px;font-size:10pt">{{ $i + 1 }}</span>
                        <input type="text" name="procedimientos[]"
                               class="form-control"
                               value="{{ $proc }}"
                               placeholder="Ej: Verificar cantidad de tóner (Correcto)">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-proc" title="Eliminar paso">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="btn-add-proc" class="btn btn-outline-success btn-sm mt-1">
                    <i class="bi bi-plus-circle me-1"></i> Agregar paso
                </button>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             4. CONCLUSIÓN Y RECOMENDACIONES
        ══════════════════════════════════════════════════════════ --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header d-flex align-items-center gap-2 py-3"
                 style="background:linear-gradient(135deg,#e9c46a,#f4a261);color:#fff;border-radius:12px 12px 0 0">
                <i class="bi bi-lightbulb-fill"></i>
                <span class="fw-semibold">4. Conclusión y Recomendaciones</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="observaciones" class="form-label fw-semibold">Conclusión</label>
                        <textarea id="observaciones" name="observaciones" rows="5"
                                  class="form-control @error('observaciones') is-invalid @enderror"
                                  placeholder="Ej: Se hizo limpieza de suciedad en el bloque C y residual de tóner.">{{ old('observaciones','Se hizo limpieza de suciedad en el bloque C y residual de tóner.
Se realizó pruebas de impresión, copias y reiniciar el equipo, pero el problema persiste.
Como antecedentes sobre las impresoras Kyocera al pasar las 25000 copias/impresiones empiezan a presentar fallo como atascos o impresiones con manchas y en este caso el equipo en mención presentó fallas a las 32651 copias/impresiones. [Foto] [Pagina de Estado].') }}</textarea>
                        @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Cada línea aparecerá como un párrafo separado en el informe.</small>
                    </div>
                    <div class="col-md-6">
                        <label for="recomendaciones" class="form-label fw-semibold">Recomendaciones</label>
                        <textarea id="recomendaciones" name="recomendaciones" rows="5"
                                  class="form-control @error('recomendaciones') is-invalid @enderror"
                                  placeholder="Ej: Dado que el equipo se encuentra dentro del periodo de garantía, se sugiere remitirlo al proveedor para el mantenimiento correspondiente.">{{ old('recomendaciones','Actualmente, el contador registra un total de 32 651 copias e impresiones. Dado que el equipo se encuentra dentro del periodo de garantía, se sugiere remitirlo al proveedor para el mantenimiento correspondiente.') }}</textarea>
                        @error('recomendaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             5. EVIDENCIAS FOTOGRÁFICAS (opcionales)
        ══════════════════════════════════════════════════════════ --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header d-flex align-items-center gap-2 py-3"
                 style="background:linear-gradient(135deg,#457b9d,#1d3557);color:#fff;border-radius:12px 12px 0 0">
                <i class="bi bi-camera-fill"></i>
                <span class="fw-semibold">5. Evidencias Fotográficas</span>
                <small class="ms-auto opacity-75" style="font-size:10pt">Opcionales</small>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    {{-- Imagen 01 --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-image me-1 text-danger"></i>Imagen 01 — Evidencia del problema
                        </label>
                        <div class="border rounded-3 p-3" style="border-style:dashed !important">
                            <input type="file" id="imagen_01" name="imagen_01"
                                   class="form-control @error('imagen_01') is-invalid @enderror"
                                   accept="image/*" onchange="previewImage(this,'preview01')">
                            @error('imagen_01') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div id="preview01" class="mt-2 text-center" style="display:none">
                                <img src="" alt="Preview" class="img-fluid rounded" style="max-height:180px">
                            </div>
                        </div>
                        <input type="text" name="imagen_01_caption"
                               class="form-control mt-2"
                               placeholder="Descripción breve de la imagen"
                               value="{{ old('imagen_01_caption', 'Se puede observar atasco de papel en el bloque D') }}">
                    </div>

                    {{-- Imagen 02 --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-image me-1 text-primary"></i>Imagen 02 — Página de estado
                        </label>
                        <div class="border rounded-3 p-3" style="border-style:dashed !important">
                            <input type="file" id="imagen_02" name="imagen_02"
                                   class="form-control @error('imagen_02') is-invalid @enderror"
                                   accept="image/*" onchange="previewImage(this,'preview02')">
                            @error('imagen_02') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div id="preview02" class="mt-2 text-center" style="display:none">
                                <img src="" alt="Preview" class="img-fluid rounded" style="max-height:180px">
                            </div>
                        </div>
                        <input type="text" name="imagen_02_caption"
                               class="form-control mt-2"
                               placeholder="Descripción breve de la imagen"
                               value="{{ old('imagen_02_caption', 'La imagen muestra todas las características y configuraciones preestablecidas en la impresora.') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-between align-items-center pb-4">
            <a href="{{ route('admin.impresoras.show', $impresora->id) }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-x-circle me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn px-5 py-2 fw-semibold text-white"
                    style="background:linear-gradient(135deg,#9f3f04,#e9c46a);border:none;border-radius:10px;box-shadow:0 4px 14px rgba(159,63,4,0.3)">
                <i class="bi bi-save me-2"></i> Guardar Informe
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// ── Preview de imágenes ─────────────────────────────────────────────────────
function previewImage(input, containerId) {
    const container = document.getElementById(containerId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            container.querySelector('img').src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Procedimientos dinámicos ────────────────────────────────────────────────
const procContainer = document.getElementById('procedimientos-container');

function renumber() {
    procContainer.querySelectorAll('.proc-item').forEach((item, idx) => {
        item.querySelector('.badge').textContent = idx + 1;
    });
}

function bindRemove(btn) {
    btn.addEventListener('click', () => {
        if (procContainer.querySelectorAll('.proc-item').length > 1) {
            btn.closest('.proc-item').remove();
            renumber();
        }
    });
}

// Inicializar botones existentes (por old())
procContainer.querySelectorAll('.btn-remove-proc').forEach(bindRemove);

document.getElementById('btn-add-proc').addEventListener('click', () => {
    const n = procContainer.querySelectorAll('.proc-item').length;
    const div = document.createElement('div');
    div.className = 'd-flex gap-2 mb-2 proc-item';
    div.innerHTML = `
        <span class="badge bg-secondary d-flex align-items-center justify-content-center"
              style="min-width:28px;font-size:10pt">${n + 1}</span>
        <input type="text" name="procedimientos[]" class="form-control"
               placeholder="Ej: Prueba de impresión múltiple (resultado)">
        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-proc" title="Eliminar paso">
            <i class="bi bi-trash"></i>
        </button>`;
    procContainer.appendChild(div);
    bindRemove(div.querySelector('.btn-remove-proc'));
    div.querySelector('input').focus();
});
</script>
@endpush
