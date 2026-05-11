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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('equipos.update', $equipo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nombre y serie -->
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

                    <!-- IP y fecha -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="direccion_ip" class="form-label">Dirección IP</label>
                            <input type="text" name="direccion_ip" id="direccion_ip" class="form-control @error('direccion_ip') is-invalid @enderror" value="{{ old('direccion_ip', $equipo->direccion_ip) }}">
                            @error('direccion_ip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="direccion_mac" class="form-label">Dirección MAC</label>
                            <input type="text" name="direccion_mac" id="direccion_mac" class="form-control @error('direccion_mac') is-invalid @enderror" value="{{ old('direccion_mac', $equipo->direccion_mac) }}">
                            @error('direccion_mac')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="fecha_adquisicion" class="form-label">Fecha de Adquisición</label>
                            <input type="date" name="fecha_adquisicion" id="fecha_adquisicion" class="form-control @error('fecha_adquisicion') is-invalid @enderror" value="{{ old('fecha_adquisicion', optional($equipo->fecha_adquisicion)->format('Y-m-d')) }}">
                            @error('fecha_adquisicion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Oficina y tipo -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="oficina_id" class="form-label">Oficina *</label>
                            <select name="oficina_id" id="oficina_id" class="form-select select2 @error('oficina_id') is-invalid @enderror" required>
                                <option value="">Seleccionar oficina</option>
                                @foreach($oficinas as $oficina)
                                    <option value="{{ $oficina->id }}" {{ old('oficina_id', $equipo->oficina_id) == $oficina->id ? 'selected' : '' }}>
                                        {{ $oficina->nombre_oficina }} ({{ $oficina->agencia->nombre_agencia }})
                                    </option>
                                @endforeach
                            </select>
                            @error('oficina_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tipoequipo_id" class="form-label">Tipo de Equipo *</label>
                            <select name="tipoequipo_id" id="tipoequipo_id" class="form-select @error('tipoequipo_id') is-invalid @enderror" required>
                                <option value="">Seleccionar tipo</option>
                                @foreach($tipoequipos as $tipo)
                                    <option value="{{ $tipo->id }}" {{ old('tipoequipo_id', $equipo->tipoequipo_id) == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nombre_tipo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipoequipo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Modelo y hardware -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modelo_id" class="form-label">Modelo *</label>
                            <select name="modelo_id" id="modelo_id" class="form-select @error('modelo_id') is-invalid @enderror" required>
                                <option value="">Seleccionar modelo</option>
                                @foreach($modelos as $modelo)
                                    <option value="{{ $modelo->id }}" {{ old('modelo_id', $equipo->modelo_id) == $modelo->id ? 'selected' : '' }}>
                                        {{ $modelo->nombre_modelo }} ({{ $modelo->marca->nombre_marca }})
                                    </option>
                                @endforeach
                            </select>
                            @error('modelo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="hardware_id" class="form-label">Hardware *</label>
                            <select name="hardware_id" id="hardware_id" class="form-select @error('hardware_id') is-invalid @enderror" required>
                                <option value="">Seleccionar hardware</option>
                                @foreach($hardwares as $hardware)
                                    <option value="{{ $hardware->id }}" {{ old('hardware_id', $equipo->hardware_id) == $hardware->id ? 'selected' : '' }}>
                                        {{ $hardware->procesador }} | {{ $hardware->ram_gb }}GB RAM | {{ $hardware->almacenamiento_gb }}GB {{ $hardware->tipo_almacenamiento }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hardware_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Sistema operativo y responsable -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sistemaoperativo_id" class="form-label">Sistema Operativo *</label>
                            <select name="sistemaoperativo_id" id="sistemaoperativo_id" class="form-select @error('sistemaoperativo_id') is-invalid @enderror" required>
                                <option value="">Seleccionar SO</option>
                                @foreach($sistemas as $so)
                                    <option value="{{ $so->id }}" {{ old('sistemaoperativo_id', $equipo->sistemaoperativo_id) == $so->id ? 'selected' : '' }}>
                                        {{ $so->nombre_so }} {{ $so->edicion }} ({{ $so->version }})
                                    </option>
                                @endforeach
                            </select>
                            @error('sistemaoperativo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="responsable_id" class="form-label">Responsable *</label>
                            <select name="responsable_id" id="responsable_id" class="form-select select2 @error('responsable_id') is-invalid @enderror">
                                <option value="">Seleccionar responsable</option>
                                @foreach($responsables as $responsable)
                                    <option value="{{ $responsable->id }}" {{ old('responsable_id', $equipo->responsable_id) == $responsable->id ? 'selected' : '' }}>
                                        {{ $responsable->nombre_responsable }}
                                    </option>
                                @endforeach
                            </select>
                            @error('responsable_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Estado y mantenimiento -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="estado_equipo" class="form-label">Estado del Equipo *</label>
                            <select name="estado_equipo" id="estado_equipo" class="form-select @error('estado_equipo') is-invalid @enderror" required>
                                <option value="Operativo" {{ old('estado_equipo', $equipo->estado_equipo) == 'Operativo' ? 'selected' : '' }}>Operativo</option>
                                <option value="Operativo con Observacion" {{ old('estado_equipo', $equipo->estado_equipo) == 'Operativo con Observacion' ? 'selected' : '' }}>Operativo con Observacion</option>
                                <option value="En mantenimiento" {{ old('estado_equipo', $equipo->estado_equipo) == 'En mantenimiento' ? 'selected' : '' }}>En mantenimiento</option>
                                <option value="Fuera de servicio" {{ old('estado_equipo', $equipo->estado_equipo) == 'Fuera de servicio' ? 'selected' : '' }}>Fuera de servicio</option>
                                <option value="De baja" {{ old('estado_equipo', $equipo->estado_equipo) == 'De baja' ? 'selected' : '' }}>De baja</option>
                            </select>
                            @error('estado_equipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_mantenimiento" class="form-label">Último Mantenimiento</label>
                            <input type="date" name="fecha_mantenimiento" id="fecha_mantenimiento" class="form-control @error('fecha_mantenimiento') is-invalid @enderror" value="{{ old('fecha_mantenimiento', optional($equipo->fecha_mantenimiento)->format('Y-m-d')) }}">
                            @error('fecha_mantenimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- VPNs y antivirus -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="vpn_cusco" class="form-label">VPN Cusco</label>
                            <select name="vpn_cusco" id="vpn_cusco" class="form-select">
                                <option value="Sí" {{ old('vpn_cusco', $equipo->vpn_cusco) == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('vpn_cusco', $equipo->vpn_cusco) == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="vpn_abancay" class="form-label">VPN Abancay</label>
                            <select name="vpn_abancay" id="vpn_abancay" class="form-select">
                                <option value="Sí" {{ old('vpn_abancay', $equipo->vpn_abancay) == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('vpn_abancay', $equipo->vpn_abancay) == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="antivirus" class="form-label">Antivirus</label>
                            <input type="text" name="antivirus" id="antivirus" class="form-control" value="{{ old('antivirus', $equipo->antivirus) }}">
                        </div>
                    </div>

                    <!-- Campos de texto largos -->
                    <div class="mb-3">
                        <label for="depreciacion_anual" class="form-label">Depreciación Anual (%)</label>
                        <input type="number" step="0.01" name="depreciacion_anual" id="depreciacion_anual" class="form-control @error('depreciacion_anual') is-invalid @enderror" value="{{ old('depreciacion_anual', $equipo->depreciacion_anual) }}">
                        @error('depreciacion_anual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="programas_instalados" class="form-label">Programas Instalados</label>
                        <textarea name="programas_instalados" id="programas_instalados" class="form-control @error('programas_instalados') is-invalid @enderror" rows="3">{{ old('programas_instalados', $equipo->programas_instalados) }}</textarea>
                        @error('programas_instalados')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="licencias" class="form-label">Licencias</label>
                        <textarea name="licencias" id="licencias" class="form-control @error('licencias') is-invalid @enderror" rows="2">{{ old('licencias', $equipo->licencias) }}</textarea>
                        @error('licencias')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="copias_seguridad" class="form-label">Copias de Seguridad</label>
                        <textarea name="copias_seguridad" id="copias_seguridad" class="form-control @error('copias_seguridad') is-invalid @enderror" rows="2">{{ old('copias_seguridad', $equipo->copias_seguridad) }}</textarea>
                        @error('copias_seguridad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observaciones</label>
                        <textarea name="observacion" id="observacion" class="form-control @error('observacion') is-invalid @enderror" rows="3">{{ old('observacion', $equipo->observacion) }}</textarea>
                        @error('observacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones -->
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
