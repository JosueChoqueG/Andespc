@csrf

{{-- ───────── Fila 1: Oficina · Proveedor · Teléfono ───────── --}}
<div class="row g-2 mb-2">
    <div class="col-md-5">
        <label class="form-label form-label-sm mb-1 fw-medium">Oficina <span class="text-danger">*</span></label>
        <select name="oficina_id" class="form-select form-select-sm select2" required>
            <option value="">— Seleccione —</option>
            @foreach($oficinas as $oficina)
                <option value="{{ $oficina->id }}"
                    {{ old('oficina_id', $servicio->oficina_id ?? '') == $oficina->id ? 'selected' : '' }}>
                    {{ $oficina->nombre_oficina }}
                </option>
            @endforeach
        </select>
        @error('oficina_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-sm mb-1 fw-medium">Proveedor <span class="text-danger">*</span></label>
        <input name="nombre_proveedor" class="form-control form-control-sm" required
               placeholder="Ej: Redycom, Claro…"
               value="{{ old('nombre_proveedor', $servicio->nombre_proveedor ?? '') }}">
        @error('nombre_proveedor')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label form-label-sm mb-1 fw-medium">Teléfono proveedor</label>
        <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input name="telefono_proveedor" class="form-control"
                   placeholder="999 999 999"
                   value="{{ old('telefono_proveedor', $servicio->telefono_proveedor ?? '') }}">
        </div>
        @error('telefono_proveedor')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

{{-- ───────── Fila 2: Tipo · Megas · IP ───────── --}}
<div class="row g-2 mb-2">
    <div class="col-md-4">
        <label class="form-label form-label-sm mb-1 fw-medium">Tipo de servicio <span class="text-danger">*</span></label>
        <select name="tipo_instalacion" class="form-select form-select-sm" required>
            <option value="">— Seleccione —</option>
            @foreach(['Fibra óptica', 'Radioenlace', 'Starlink', 'RPC'] as $tipo)
                <option value="{{ $tipo }}"
                    {{ old('tipo_instalacion', $servicio->tipo_instalacion ?? '') === $tipo ? 'selected' : '' }}>
                    {{ $tipo }}
                </option>
            @endforeach
        </select>
        @error('tipo_instalacion')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-sm mb-1 fw-medium">Megas contratados <span class="text-danger">*</span></label>
        <div class="input-group input-group-sm">
            <input name="megas_contratado" class="form-control" required
                   placeholder="Ej: 100"
                   value="{{ old('megas_contratado', $servicio->megas_contratado ?? '') }}">
            <span class="input-group-text text-muted">Mbps</span>
        </div>
        @error('megas_contratado')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-sm mb-1 fw-medium"><i class="bi bi-hdd-network text-muted me-1"></i>Dirección IP</label>
        <input name="direccion_ip" class="form-control form-control-sm"
               placeholder="192.168.1.1"
               value="{{ old('direccion_ip', $servicio->direccion_ip ?? '') }}">
        @error('direccion_ip')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

{{-- ───────── Fila 3: Credenciales de red ───────── --}}
<div class="row g-2 mb-3">
    <div class="col-12">
        <p class="form-label form-label-sm mb-1 fw-medium text-muted">
            <i class="bi bi-shield-lock me-1"></i>Credenciales de red
        </p>
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-sm mb-1">Contraseña router</label>
        <div class="input-group input-group-sm">
            <input type="password" name="contrasena_router"
                   class="form-control font-monospace" autocomplete="off"
                   placeholder="••••••••"
                   value="{{ old('contrasena_router', $servicio->contrasena_router ?? '') }}">
            <button type="button" class="btn btn-outline-secondary btn-toggle-pass" title="Ver / Ocultar">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-sm mb-1">Nombre WiFi (SSID)</label>
        <input name="nombre_wifi" class="form-control form-control-sm"
               placeholder="Ej: Oficina_5G"
               value="{{ old('nombre_wifi', $servicio->nombre_wifi ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-sm mb-1">Contraseña WiFi</label>
        <div class="input-group input-group-sm">
            <input type="password" name="contrasena_wifi"
                   class="form-control font-monospace" autocomplete="off"
                   placeholder="••••••••"
                   value="{{ old('contrasena_wifi', $servicio->contrasena_wifi ?? '') }}">
            <button type="button" class="btn btn-outline-secondary btn-toggle-pass" title="Ver / Ocultar">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>
</div>

@if(!isset($hideButtons) || !$hideButtons)
{{-- ───────── Botones ───────── --}}
<div class="d-flex gap-2 mt-3">
    <button type="submit" class="btn btn-primary btn-sm px-4">
        <i class="bi bi-floppy me-1"></i>Guardar
    </button>
    <a href="{{ $cancelUrl ?? route('admin.servicios-internet.index') }}" class="btn btn-outline-secondary btn-sm px-4">
        <i class="bi bi-x me-1"></i>Cancelar
    </a>
</div>
@endif

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    $(document).on('click', '.btn-toggle-pass', function(e) {
        e.preventDefault();
        const input = $(this).closest('.input-group').find('input')[0];
        if (input) {
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            $(this).find('i').attr('class', isPass ? 'bi bi-eye-slash' : 'bi bi-eye');
        }
    });
});
</script>
@endpush
@endonce
