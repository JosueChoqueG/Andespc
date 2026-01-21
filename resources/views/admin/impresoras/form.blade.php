<div class="row">
    <div class="col-md-6">
        <label for="oficina_id" class="form-label">Oficina *</label>

        <select name="oficina_id"
                id="oficina_id"
                class="form-select select2 @error('oficina_id') is-invalid @enderror"
                required>
            <option value="">Seleccionar oficina</option>

            @foreach($oficinas as $oficina)
                <option value="{{ $oficina->id }}"
                    {{ old('oficina_id', $impresora->oficina_id ?? '') == $oficina->id ? 'selected' : '' }}>
                    {{ $oficina->nombre_oficina }} ({{ $oficina->agencia->nombre_agencia }})
                </option>
            @endforeach
        </select>

        @error('oficina_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Marca</label>
        <select name="marca" class="form-select" required>
            @php
                $marcaActual = old('marca', $impresora->marca ?? 'KYOCERA ECOSYS');
            @endphp

            <option value="KYOCERA ECOSYS" {{ $marcaActual == 'KYOCERA ECOSYS' ? 'selected' : '' }}>KYOCERA ECOSYS</option>
            <option value="HP" {{ $marcaActual == 'HP' ? 'selected' : '' }}>HP</option>
            <option value="BROTHER" {{ $marcaActual == 'BROTHER' ? 'selected' : '' }}>BROTHER</option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Modelo</label>
        <select name="modelo" class="form-select" required>
            @php
                $modeloActual = old('modelo', $impresora->modelo ?? 'M2640');
            @endphp
            <option value="M2640idw" {{ $modeloActual == 'M2640' ? 'selected' : '' }}>M2640</option>
            <option value="M3655idn" {{ $modeloActual == 'M3655' ? 'selected' : '' }}>M3655</option>
            <option value="MA4500IFX" {{ $modeloActual == 'MA4500IFX' ? 'selected' : '' }}>MA4500IFX</option>
            <option value="MA5500IFX" {{ $modeloActual == 'M5500IFX' ? 'selected' : '' }}>M5500IFX</option>
        </select>
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
