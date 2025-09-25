@extends('layouts.app')

@section('title', 'Crear Equipo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5><i class="bi bi-pc-display"></i> Registrar Nuevo Equipo</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('equipos.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre_dispositivo" class="form-label">Nombre del Dispositivo *</label>
                            <input type="text" name="nombre_dispositivo" id="nombre_dispositivo" class="form-control @error('nombre_dispositivo') is-invalid @enderror" value="{{ old('nombre_dispositivo') }}" required>
                            @error('nombre_dispositivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="numero_serie" class="form-label">Número de Serie</label>
                            <input type="text" name="numero_serie" id="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror" value="{{ old('numero_serie') }}">
                            @error('numero_serie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="direccion_ip" class="form-label">Dirección IP</label>
                            <input type="text" name="direccion_ip" id="direccion_ip" class="form-control @error('direccion_ip') is-invalid @enderror" value="{{ old('direccion_ip') }}" placeholder="192.168.1.100">
                            @error('direccion_ip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_adquisicion" class="form-label">Fecha de Adquisición</label>
                            <input type="date" name="fecha_adquisicion" id="fecha_adquisicion" class="form-control @error('fecha_adquisicion') is-invalid @enderror" value="{{ old('fecha_adquisicion') }}">
                            @error('fecha_adquisicion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_oficina" class="form-label">Oficina *</label>
                            <select name="id_oficina" id="id_oficina" class="form-select @error('id_oficina') is-invalid @enderror" required>
                                <option value="">Seleccionar oficina</option>
                                @foreach($oficinas as $oficina)
                                    <option value="{{ $oficina->id_oficina }}" {{ old('id_oficina') == $oficina->id_oficina ? 'selected' : '' }}>
                                        {{ $oficina->nombre_oficina }} ({{ $oficina->agencia->nombre_agencia }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_oficina')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="id_tipo" class="form-label">Tipo de Equipo *</label>
                            <select name="id_tipo" id="id_tipo" class="form-select @error('id_tipo') is-invalid @enderror" required>
                                <option value="">Seleccionar tipo</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id_tipo }}" {{ old('id_tipo') == $tipo->id_tipo ? 'selected' : '' }}>
                                        {{ $tipo->nombre_tipo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_modelo" class="form-label">Modelo *</label>
                            <select name="id_modelo" id="id_modelo" class="form-select @error('id_modelo') is-invalid @enderror" required>
                                <option value="">Seleccionar modelo</option>
                                @foreach($modelos as $modelo)
                                    <option value="{{ $modelo->id_modelo }}" {{ old('id_modelo') == $modelo->id_modelo ? 'selected' : '' }}>
                                        {{ $modelo->nombre_modelo }} ({{ $modelo->marca->nombre_marca }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_modelo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="id_hardware" class="form-label">Hardware *</label>
                            <select name="id_hardware" id="id_hardware" class="form-select @error('id_hardware') is-invalid @enderror" required>
                                <option value="">Seleccionar hardware</option>
                                @foreach($hardwares as $hardware)
                                    <option value="{{ $hardware->id_hardware }}" {{ old('id_hardware') == $hardware->id_hardware ? 'selected' : '' }}>
                                        {{ $hardware->procesador }} | {{ $hardware->ram_gb }}GB RAM | {{ $hardware->almacenamiento_gb }}GB {{ $hardware->tipo_almacenamiento }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_hardware')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_so" class="form-label">Sistema Operativo *</label>
                            <select name="id_so" id="id_so" class="form-select @error('id_so') is-invalid @enderror" required>
                                <option value="">Seleccionar SO</option>
                                @foreach($sistemas as $so)
                                    <option value="{{ $so->id_so }}" {{ old('id_so') == $so->id_so ? 'selected' : '' }}>
                                        {{ $so->nombre_so }} {{ $so->edicion }} ({{ $so->version }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_so')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="id_responsable" class="form-label">Responsable *</label>
                            <select name="id_responsable" id="id_responsable" class="form-select @error('id_responsable') is-invalid @enderror" required>
                                <option value="">Seleccionar responsable</option>
                                @foreach($responsables as $responsable)
                                    <option value="{{ $responsable->id_responsable }}" {{ old('id_responsable') == $responsable->id_responsable ? 'selected' : '' }}>
                                        {{ $responsable->nombre_responsable }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_responsable')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="estado_equipo" class="form-label">Estado del Equipo *</label>
                            <select name="estado_equipo" class="form-select" required>
                                <option value="Activo" {{ old('estado_equipo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado_equipo') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="Baja" {{ old('estado_equipo') == 'Baja' ? 'selected' : '' }}>Baja</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_mantenimiento" class="form-label">Último Mantenimiento</label>
                            <input type="date" name="fecha_mantenimiento" id="fecha_mantenimiento" class="form-control" value="{{ old('fecha_mantenimiento') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="vpn_cusco" class="form-label">VPN Cusco</label>
                            <select name="vpn_cusco" class="form-select">
                                <option value="Sí" {{ old('vpn_cusco') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('vpn_cusco') == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="vpn_abancay" class="form-label">VPN Abancay</label>
                            <select name="vpn_abancay" class="form-select">
                                <option value="Sí" {{ old('vpn_abancay') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('vpn_abancay') == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="antivirus" class="form-label">Antivirus</label>
                            <input type="text" name="antivirus" class="form-control" value="{{ old('antivirus') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="depreciacion_anual" class="form-label">Depreciación Anual (%)</label>
                        <input type="number" step="0.01" name="depreciacion_anual" class="form-control" value="{{ old('depreciacion_anual') }}" placeholder="5.00">
                    </div>

                    <div class="mb-3">
                        <label for="programas_instalados" class="form-label">Programas Instalados</label>
                        <textarea name="programas_instalados" class="form-control" rows="3">{{ old('programas_instalados') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="licencias" class="form-label">Licencias</label>
                        <textarea name="licencias" class="form-control" rows="2">{{ old('licencias') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="copias_seguridad" class="form-label">Copias de Seguridad</label>
                        <textarea name="copias_seguridad" class="form-control" rows="2">{{ old('copias_seguridad') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observaciones</label>
                        <textarea name="observacion" class="form-control" rows="3">{{ old('observacion') }}</textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('equipos.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar Equipo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection