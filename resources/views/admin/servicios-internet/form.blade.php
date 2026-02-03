@csrf

<div class="mb-3">
    <label>Oficina</label>
    <select name="oficina_id" class="form-select select2" required>
        @foreach($oficinas as $oficina)
            <option value="{{ $oficina->id }}"
                {{ old('oficina_id', $servicio->oficina_id ?? '') == $oficina->id ? 'selected' : '' }}>
                {{ $oficina->nombre_oficina }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Dirección</label>
    <textarea name="direccion" class="form-control">{{ old('direccion', $servicio->direccion ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Coordenadas</label>
    <input name="coordenada" class="form-control"
       placeholder="-14.4172478,-73.2044887"
       value="{{ old('coordenada', $servicio->coordenada ?? '') }}">
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Megas Contratados</label>
        <input name="megas_contratado" class="form-control" required
               value="{{ old('megas_contratado', $servicio->megas_contratado ?? '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Tipo de Instalación</label>
        <select name="tipo_instalacion" class="form-select" required>
            <option value="">-- Seleccione --</option>

            <option value="Fibra óptica"
                {{ old('tipo_instalacion', $servicio->tipo_instalacion ?? '')=='Fibra óptica'?'selected':'' }}>
                Fibra óptica
            </option>

            <option value="Radio enlace"
                {{ old('tipo_instalacion', $servicio->tipo_instalacion ?? '')=='Radio enlace'?'selected':'' }}>
                Radio enlace
            </option>

            <option value="RPC"
                {{ old('tipo_instalacion', $servicio->tipo_instalacion ?? '')=='RPC'?'selected':'' }}>
                RPC
            </option>

            <option value="Starlink"
                {{ old('tipo_instalacion', $servicio->tipo_instalacion ?? '')=='Starlink'?'selected':'' }}>
                Starlink
            </option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Proveedor</label>
        <input name="nombre_proveedor" class="form-control" required
               value="{{ old('nombre_proveedor', $servicio->nombre_proveedor ?? '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Teléfono Proveedor</label>
        <input name="telefono_proveedor" class="form-control"
               value="{{ old('telefono_proveedor', $servicio->telefono_proveedor ?? '') }}">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label>Contraseña Router</label>
        <input name="contrasena_router" class="form-control"
               value="{{ old('contrasena_router', $servicio->contrasena_router ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Nombre WiFi</label>
        <input name="nombre_wifi" class="form-control"
               value="{{ old('nombre_wifi', $servicio->nombre_wifi ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Contraseña WiFi</label>
        <input name="contrasena_wifi" class="form-control"
               value="{{ old('contrasena_wifi', $servicio->contrasena_wifi ?? '') }}">
    </div>
</div>

<div class="mb-3">
    <label>Dirección IP</label>
    <input name="direccion_ip" class="form-control"
        placeholder="192.168.1.1"
        value="{{ old('direccion_ip', $servicio->direccion_ip ?? '') }}">
</div>

<button class="btn btn-primary">Guardar</button>
<a href="{{ route('admin.servicios-internet.index') }}" class="btn btn-secondary">Cancelar</a>
