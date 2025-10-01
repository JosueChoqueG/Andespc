@extends('layouts.app')

@section('title', 'Editar Equipo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h5><i class="bi bi-pencil-square"></i> Editar Equipo: {{ $equipo->nombre_dispositivo }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('equipos.update', $equipo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Copia todo el formulario de create.blade.php, pero con los valores de $equipo -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre_dispositivo" class="form-label">Nombre del Dispositivo *</label>
                            <input type="text" name="nombre_dispositivo" id="nombre_dispositivo" class="form-control @error('nombre_dispositivo') is-invalid @enderror" value="{{ old('nombre_dispositivo', $equipo->nombre_dispositivo) }}" required>
                            @error('nombre_dispositivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="numero_serie" class="form-label">Número de Serie</label>
                            <input type="text" name="numero_serie" id="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror" value="{{ old('numero_serie', $equipo->numero_serie) }}">
                            @error('numero_serie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="direccion_ip" class="form-label">Dirección IP</label>
                            <input type="text" name="direccion_ip" id="direccion_ip" class="form-control @error('direccion_ip') is-invalid @enderror" value="{{ old('direccion_ip', $equipo->direccion_ip) }}">
                            @error('direccion_ip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_adquisicion" class="form-label">Fecha de Adquisición</label>
                            <input type="date" name="fecha_adquisicion" id="fecha_adquisicion" class="form-control" value="{{ old('fecha_adquisicion', $equipo->fecha_adquisicion?->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="oficina_id" class="form-label">Oficina *</label>
                            <select name="oficina_id" class="form-select" required>
                                <option value="">Seleccionar oficina</option>
                                @foreach($oficinas as $oficina)
                                    <option value="{{ $oficina->oficina_id }}" {{ old('oficina_id', $equipo->oficina_id) == $oficina->oficina_id ? 'selected' : '' }}>
                                        {{ $oficina->nombre_oficina }} ({{ $oficina->agencia->nombre_agencia }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tipo_id" class="form-label">Tipo de Equipo *</label>
                            <select name="tipo_id" class="form-select" required>
                                <option value="">Seleccionar tipo</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->tipo_id }}" {{ old('tipo_id', $equipo->tipo_id) == $tipo->tipo_id ? 'selected' : '' }}>
                                        {{ $tipo->nombre_tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modelo_id" class="form-label">Modelo *</label>
                            <select name="modelo_id" class="form-select" required>
                                <option value="">Seleccionar modelo</option>
                                @foreach($modelos as $modelo)
                                    <option value="{{ $modelo->modelo_id }}" {{ old('modelo_id', $equipo->modelo_id) == $modelo->modelo_id ? 'selected' : '' }}>
                                        {{ $modelo->nombre_modelo }} ({{ $modelo->marca->nombre_marca }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="hardware_id" class="form-label">Hardware *</label>
                            <select name="hardware_id" class="form-select" required>
                                <option value="">Seleccionar hardware</option>
                                @foreach($hardwares as $hardware)
                                    <option value="{{ $hardware->hardware_id }}" {{ old('hardware_id', $equipo->hardware_id) == $hardware->hardware_id ? 'selected' : '' }}>
                                        {{ $hardware->procesador }} | {{ $hardware->ram_gb }}GB RAM | {{ $hardware->almacenamiento_gb }}GB {{ $hardware->tipo_almacenamiento }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="so_id" class="form-label">Sistema Operativo *</label>
                            <select name="so_id" class="form-select" required>
                                <option value="">Seleccionar SO</option>
                                @foreach($sistemas as $so)
                                    <option value="{{ $so->so_id }}" {{ old('so_id', $equipo->so_id) == $so->so_id ? 'selected' : '' }}>
                                        {{ $so->nombre_so }} {{ $so->edicion }} ({{ $so->version }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="responsable_id" class="form-label">Responsable *</label>
                            <select name="responsable_id" class="form-select" required>
                                <option value="">Seleccionar responsable</option>
                                @foreach($responsables as $responsable)
                                    <option value="{{ $responsable->responsable_id }}" {{ old('responsable_id', $equipo->responsable_id) == $responsable->responsable_id ? 'selected' : '' }}>
                                        {{ $responsable->nombre_responsable }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="estado_equipo" class="form-label">Estado del Equipo *</label>
                            <select name="estado_equipo" class="form-select" required>
                                <option value="Activo" {{ old('estado_equipo', $equipo->estado_equipo) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado_equipo', $equipo->estado_equipo) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="Baja" {{ old('estado_equipo', $equipo->estado_equipo) == 'Baja' ? 'selected' : '' }}>Baja</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_mantenimiento" class="form-label">Último Mantenimiento</label>
                            <input type="date" name="fecha_mantenimiento" class="form-control" value="{{ old('fecha_mantenimiento', $equipo->fecha_mantenimiento?->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="vpn_cusco" class="form-label">VPN Cusco</label>
                            <select name="vpn_cusco" class="form-select">
                                <option value="Sí" {{ old('vpn_cusco', $equipo->vpn_cusco) == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('vpn_cusco', $equipo->vpn_cusco) == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="vpn_abancay" class="form-label">VPN Abancay</label>
                            <select name="vpn_abancay" class="form-select">
                                <option value="Sí" {{ old('vpn_abancay', $equipo->vpn_abancay) == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('vpn_abancay', $equipo->vpn_abancay) == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="antivirus" class="form-label">Antivirus</label>
                            <input type="text" name="antivirus" class="form-control" value="{{ old('antivirus', $equipo->antivirus) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="depreciacion_anual" class="form-label">Depreciación Anual (%)</label>
                        <input type="number" step="0.01" name="depreciacion_anual" class="form-control" value="{{ old('depreciacion_anual', $equipo->depreciacion_anual) }}">
                    </div>

                    <div class="mb-3">
                        <label for="programas_instalados" class="form-label">Programas Instalados</label>
                        <textarea name="programas_instalados" class="form-control" rows="3">{{ old('programas_instalados', $equipo->programas_instalados) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="licencias" class="form-label">Licencias</label>
                        <textarea name="licencias" class="form-control" rows="2">{{ old('licencias', $equipo->licencias) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="copias_seguridad" class="form-label">Copias de Seguridad</label>
                        <textarea name="copias_seguridad" class="form-control" rows="2">{{ old('copias_seguridad', $equipo->copias_seguridad) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observaciones</label>
                        <textarea name="observacion" class="form-control" rows="3">{{ old('observacion', $equipo->observacion) }}</textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('equipos.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection