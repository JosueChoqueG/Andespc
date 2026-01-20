<div class="row">
    <div class="col-md-6 mb-3">
        <label>Oficina</label>
        <select name="oficina_id" class="form-select" required>
            <option value="">Seleccione</option>
            @foreach($oficinas as $oficina)
                <option value="{{ $oficina->id }}"
                    {{ old('oficina_id', $impresora->oficina_id ?? '') == $oficina->id ? 'selected' : '' }}>
                    {{ $oficina->agencia->nombre_agencia }} - {{ $oficina->nombre_oficina }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Marca</label>
        <input type="text" name="marca" class="form-control"
               value="{{ old('marca', $impresora->marca ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>Modelo</label>
        <input type="text" name="modelo" class="form-control"
               value="{{ old('modelo', $impresora->modelo ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>Serie</label>
        <input type="text" name="serie" class="form-control"
               value="{{ old('serie', $impresora->serie ?? '') }}" required>
    </div>

    <div class="col-md-4 mb-3">
        <label>Fecha Compra</label>
        <input type="date" name="fecha_compra" class="form-control"
               value="{{ old('fecha_compra', $impresora->fecha_compra ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>Nombre de Host</label>
        <input type="text" name="nombre_host" class="form-control"
            value="{{ old('nombre_host', $impresora->nombre_host ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>IP</label>
        <input type="text" name="direccion_ip" class="form-control"
               value="{{ old('direccion_ip', $impresora->direccion_ip ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Estado</label>
        <select name="estado_equipo" class="form-select">
            @foreach(['OPERATIVO','INACTIVO','DE BAJA'] as $estado)
                <option value="{{ $estado }}"
                    {{ old('estado_equipo', $impresora->estado_equipo ?? '') == $estado ? 'selected' : '' }}>
                    {{ $estado }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12 mb-3">
        <label>Ubicación Actual</label>
        <input type="text" name="ubicacion_actual" class="form-control"
               value="{{ old('ubicacion_actual', $impresora->ubicacion_actual ?? '') }}">
    </div>
</div>
