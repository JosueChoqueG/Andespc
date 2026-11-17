@extends('layouts.app')

@section('title', 'Editar Equipo - ' . $equipo->nombre_dispositivo)

@section('content')
<div class="container-fluid py-2">
    {{-- Cabecera con Navegación y Acciones --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle shadow-sm" title="Volver al listado">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="bg-warning bg-opacity-10 text-warning rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                <i class="bi bi-pencil-square fs-4"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold text-dark">Editar Equipo: {{ $equipo->nombre_dispositivo }}</h5>
                <div class="text-muted small mt-1">
                    <span class="me-3"><i class="bi bi-upc-scan me-1 text-primary"></i>S/N: <strong>{{ $equipo->numero_serie ?: 'S/N' }}</strong></span>
                    <span class="me-3"><i class="bi bi-building me-1 text-primary"></i>{{ $equipo->oficina->nombre_oficina ?? 'Sin oficina' }}</span>
                    <span><i class="bi bi-shield-check me-1 text-primary"></i>Estado: <span class="badge bg-light text-dark border">{{ $equipo->estado_equipo }}</span></span>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-outline-info btn-sm shadow-sm" title="Ver Detalles">
                <i class="bi bi-eye me-1"></i>Ver Detalle
            </a>
            <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                <i class="bi bi-x-circle me-1"></i>Cancelar
            </a>
        </div>
    </div>

    {{-- Errores de Validación --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3 py-2 small" role="alert">
            <div class="d-flex align-items-center mb-1">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <strong>Por favor revise los siguientes errores:</strong>
            </div>
            <ul class="mb-0 ps-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('equipos.update', $equipo) }}" method="POST" id="form-editar-equipo">
        @csrf
        @method('PUT')

        <div class="row g-3">
            {{-- Sección 1: Identificación y Ubicación --}}
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 py-2 d-flex align-items-center">
                        <i class="bi bi-tag text-primary me-2"></i>
                        <span class="fw-semibold small text-uppercase">1. Identificación y Asignación</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label for="nombre_dispositivo" class="form-label form-label-sm mb-1 fw-medium">
                                    Nombre del Dispositivo <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre_dispositivo" id="nombre_dispositivo"
                                       class="form-control form-control-sm @error('nombre_dispositivo') is-invalid @enderror"
                                       value="{{ old('nombre_dispositivo', $equipo->nombre_dispositivo) }}"
                                       placeholder="Ej: PC-CONTABILIDAD-01" required>
                                @error('nombre_dispositivo')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="numero_serie" class="form-label form-label-sm mb-1 fw-medium">Número de Serie</label>
                                <input type="text" name="numero_serie" id="numero_serie"
                                       class="form-control form-control-sm @error('numero_serie') is-invalid @enderror"
                                       value="{{ old('numero_serie', $equipo->numero_serie) }}"
                                       placeholder="Ej: CNU1234567">
                                @error('numero_serie')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="tipoequipo_id" class="form-label form-label-sm mb-1 fw-medium">
                                    Tipo de Equipo <span class="text-danger">*</span>
                                </label>
                                <select name="tipoequipo_id" id="tipoequipo_id"
                                        class="form-select form-select-sm @error('tipoequipo_id') is-invalid @enderror" required>
                                    <option value="">— Seleccionar tipo —</option>
                                    @foreach($tipoequipos as $tipo)
                                        <option value="{{ $tipo->id }}" {{ old('tipoequipo_id', $equipo->tipoequipo_id) == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->nombre_tipo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipoequipo_id')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="oficina_id" class="form-label form-label-sm mb-1 fw-medium">
                                    Oficina <span class="text-danger">*</span>
                                </label>
                                <select name="oficina_id" id="oficina_id"
                                        class="form-select form-select-sm select2 @error('oficina_id') is-invalid @enderror" required>
                                    <option value="">— Seleccionar oficina —</option>
                                    @foreach($oficinas as $oficina)
                                        <option value="{{ $oficina->id }}" {{ old('oficina_id', $equipo->oficina_id) == $oficina->id ? 'selected' : '' }}>
                                            {{ $oficina->nombre_oficina }} ({{ $oficina->agencia->nombre_agencia ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('oficina_id')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="responsable_id" class="form-label form-label-sm mb-1 fw-medium">Responsable Asignado</label>
                                <select name="responsable_id" id="responsable_id"
                                        class="form-select form-select-sm select2 @error('responsable_id') is-invalid @enderror">
                                    <option value="">— Sin asignar —</option>
                                    @foreach($responsables as $responsable)
                                        <option value="{{ $responsable->id }}" {{ old('responsable_id', $equipo->responsable_id) == $responsable->id ? 'selected' : '' }}>
                                            {{ $responsable->nombre_responsable }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsable_id')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="estado_equipo" class="form-label form-label-sm mb-1 fw-medium">
                                    Estado del Equipo <span class="text-danger">*</span>
                                </label>
                                <select name="estado_equipo" id="estado_equipo"
                                        class="form-select form-select-sm @error('estado_equipo') is-invalid @enderror" required>
                                    @foreach(['Operativo', 'Operativo con Observacion', 'En mantenimiento', 'Fuera de servicio', 'De baja'] as $st)
                                        <option value="{{ $st }}" {{ old('estado_equipo', $equipo->estado_equipo) == $st ? 'selected' : '' }}>
                                            {{ $st }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado_equipo')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sección 2: Hardware, Modelo y Sistema Operativo --}}
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 py-2 d-flex align-items-center">
                        <i class="bi bi-cpu text-info me-2"></i>
                        <span class="fw-semibold small text-uppercase">2. Hardware, Modelo y Sistema Operativo</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label for="modelo_id" class="form-label form-label-sm mb-1 fw-medium">
                                    Modelo / Marca <span class="text-danger">*</span>
                                </label>
                                <select name="modelo_id" id="modelo_id"
                                        class="form-select form-select-sm select2 @error('modelo_id') is-invalid @enderror" required>
                                    <option value="">— Seleccionar modelo —</option>
                                    @foreach($modelos as $modelo)
                                        <option value="{{ $modelo->id }}" {{ old('modelo_id', $equipo->modelo_id) == $modelo->id ? 'selected' : '' }}>
                                            {{ $modelo->nombre_modelo }} ({{ $modelo->marca->nombre_marca ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('modelo_id')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hardware_id" class="form-label form-label-sm mb-1 fw-medium">
                                    Hardware <span class="text-danger">*</span>
                                </label>
                                <select name="hardware_id" id="hardware_id"
                                        class="form-select form-select-sm select2 @error('hardware_id') is-invalid @enderror" required>
                                    <option value="">— Seleccionar hardware —</option>
                                    @foreach($hardwares as $hardware)
                                        <option value="{{ $hardware->id }}" {{ old('hardware_id', $equipo->hardware_id) == $hardware->id ? 'selected' : '' }}>
                                            {{ $hardware->procesador }} | {{ $hardware->ram_gb }}GB RAM | {{ $hardware->almacenamiento_gb }}GB {{ $hardware->tipo_almacenamiento }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hardware_id')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="sistemaoperativo_id" class="form-label form-label-sm mb-1 fw-medium">
                                    Sistema Operativo <span class="text-danger">*</span>
                                </label>
                                <select name="sistemaoperativo_id" id="sistemaoperativo_id"
                                        class="form-select form-select-sm select2 @error('sistemaoperativo_id') is-invalid @enderror" required>
                                    <option value="">— Seleccionar SO —</option>
                                    @foreach($sistemas as $so)
                                        <option value="{{ $so->id }}" {{ old('sistemaoperativo_id', $equipo->sistemaoperativo_id) == $so->id ? 'selected' : '' }}>
                                            {{ $so->nombre_so }} {{ $so->edicion }} ({{ $so->version }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('sistemaoperativo_id')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="fecha_adquisicion" class="form-label form-label-sm mb-1 fw-medium">Fecha de Adquisición</label>
                                <input type="date" name="fecha_adquisicion" id="fecha_adquisicion"
                                       class="form-control form-control-sm @error('fecha_adquisicion') is-invalid @enderror"
                                       value="{{ old('fecha_adquisicion', optional($equipo->fecha_adquisicion)->format('Y-m-d')) }}">
                                @error('fecha_adquisicion')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="fecha_mantenimiento" class="form-label form-label-sm mb-1 fw-medium">Último Mantenimiento</label>
                                <input type="date" name="fecha_mantenimiento" id="fecha_mantenimiento"
                                       class="form-control form-control-sm @error('fecha_mantenimiento') is-invalid @enderror"
                                       value="{{ old('fecha_mantenimiento', optional($equipo->fecha_mantenimiento)->format('Y-m-d')) }}">
                                @error('fecha_mantenimiento')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="depreciacion_anual" class="form-label form-label-sm mb-1 fw-medium">Depreciación Anual (%)</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" min="0" max="100" name="depreciacion_anual" id="depreciacion_anual"
                                           class="form-control form-control-sm @error('depreciacion_anual') is-invalid @enderror"
                                           value="{{ old('depreciacion_anual', $equipo->depreciacion_anual) }}"
                                           placeholder="Ej: 25.00">
                                    <span class="input-group-text text-muted">%</span>
                                </div>
                                @error('depreciacion_anual')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sección 3: Conectividad y Red --}}
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 py-2 d-flex align-items-center">
                        <i class="bi bi-hdd-network text-success me-2"></i>
                        <span class="fw-semibold small text-uppercase">3. Conectividad y Seguridad de Red</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label for="direccion_ip" class="form-label form-label-sm mb-1 fw-medium">Dirección IP</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                                    <input type="text" name="direccion_ip" id="direccion_ip"
                                           class="form-control font-monospace @error('direccion_ip') is-invalid @enderror"
                                           value="{{ old('direccion_ip', $equipo->direccion_ip) }}"
                                           placeholder="Ej: 192.168.1.100">
                                </div>
                                @error('direccion_ip')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label for="direccion_mac" class="form-label form-label-sm mb-1 fw-medium">Dirección MAC</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-ethernet"></i></span>
                                    <input type="text" name="direccion_mac" id="direccion_mac"
                                           class="form-control font-monospace @error('direccion_mac') is-invalid @enderror"
                                           value="{{ old('direccion_mac', $equipo->direccion_mac) }}"
                                           placeholder="Ej: AA:BB:CC:DD:EE:FF">
                                </div>
                                @error('direccion_mac')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-2">
                                <label for="antivirus" class="form-label form-label-sm mb-1 fw-medium">Antivirus</label>
                                <input type="text" name="antivirus" id="antivirus"
                                       class="form-control form-control-sm @error('antivirus') is-invalid @enderror"
                                       value="{{ old('antivirus', $equipo->antivirus) }}"
                                       placeholder="Ej: ESET / Defender">
                                @error('antivirus')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-2">
                                <label for="vpn_cusco" class="form-label form-label-sm mb-1 fw-medium">VPN Cusco</label>
                                <select name="vpn_cusco" id="vpn_cusco" class="form-select form-select-sm">
                                    <option value="No" {{ old('vpn_cusco', $equipo->vpn_cusco) == 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Sí" {{ old('vpn_cusco', $equipo->vpn_cusco) == 'Sí' ? 'selected' : '' }}>Sí</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="vpn_abancay" class="form-label form-label-sm mb-1 fw-medium">VPN Abancay</label>
                                <select name="vpn_abancay" id="vpn_abancay" class="form-select form-select-sm">
                                    <option value="No" {{ old('vpn_abancay', $equipo->vpn_abancay) == 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Sí" {{ old('vpn_abancay', $equipo->vpn_abancay) == 'Sí' ? 'selected' : '' }}>Sí</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sección 4: Software, Licencias y Observaciones --}}
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 py-2 d-flex align-items-center">
                        <i class="bi bi-journal-text text-warning me-2"></i>
                        <span class="fw-semibold small text-uppercase">4. Software, Licencias y Observaciones</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="programas_instalados" class="form-label form-label-sm mb-1 fw-medium">
                                    <i class="bi bi-app text-primary me-1"></i>Programas Instalados
                                </label>
                                <textarea name="programas_instalados" id="programas_instalados"
                                          class="form-control form-control-sm @error('programas_instalados') is-invalid @enderror"
                                          rows="2" placeholder="Office, Adobe Reader, Chrome...">{{ old('programas_instalados', $equipo->programas_instalados) }}</textarea>
                                @error('programas_instalados')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="licencias" class="form-label form-label-sm mb-1 fw-medium">
                                    <i class="bi bi-key text-warning me-1"></i>Licencias
                                </label>
                                <textarea name="licencias" id="licencias"
                                          class="form-control form-control-sm @error('licencias') is-invalid @enderror"
                                          rows="2" placeholder="Detalle de claves o licencias">{{ old('licencias', $equipo->licencias) }}</textarea>
                                @error('licencias')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="copias_seguridad" class="form-label form-label-sm mb-1 fw-medium">
                                    <i class="bi bi-shield-check text-success me-1"></i>Copias de Seguridad (Backup)
                                </label>
                                <textarea name="copias_seguridad" id="copias_seguridad"
                                          class="form-control form-control-sm @error('copias_seguridad') is-invalid @enderror"
                                          rows="2" placeholder="Frecuencia o destino de respaldos">{{ old('copias_seguridad', $equipo->copias_seguridad) }}</textarea>
                                @error('copias_seguridad')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="observacion" class="form-label form-label-sm mb-1 fw-medium">
                                    <i class="bi bi-chat-left-text text-info me-1"></i>Observaciones Adicionales
                                </label>
                                <textarea name="observacion" id="observacion"
                                          class="form-control form-control-sm @error('observacion') is-invalid @enderror"
                                          rows="2" placeholder="Notas técnicas, estado físico o requerimientos">{{ old('observacion', $equipo->observacion) }}</textarea>
                                @error('observacion')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Barra Inferior de Guardar --}}
            <div class="col-12">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                        <span class="text-muted small ps-2">
                            <i class="bi bi-info-circle me-1"></i>Los campos marcados con <strong class="text-danger">*</strong> son obligatorios.
                        </span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="bi bi-x-circle me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                                <i class="bi bi-floppy me-1"></i>Actualizar Equipo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    }
});
</script>
@endpush
@endsection
