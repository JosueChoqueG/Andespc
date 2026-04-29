@extends('layouts.app')

@section('title', 'Editar Impresora')
@section('header', 'Editar Impresora')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Editar: {{ $impresora->marca_impresora }} {{ $impresora->modelo_impresora }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.impresoras.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.impresoras.update', $impresora->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Serie: <strong>{{ $impresora->serie_impresora }}</strong>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Oficina *</label>
                                    <select name="oficina_id" class="form-control @error('oficina_id') is-invalid @enderror" required>
                                        <option value="">Seleccione una oficina</option>
                                        @foreach($oficinas as $oficina)
                                            <option value="{{ $oficina->id }}" 
                                                {{ old('oficina_id', $impresora->oficina_id) == $oficina->id ? 'selected' : '' }}>
                                                {{ $oficina->nombre_oficina }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('oficina_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Responsable</label>
                                    <select name="responsable_id" class="form-control @error('responsable_id') is-invalid @enderror">
                                        <option value="">Seleccione un responsable</option>
                                        @foreach($responsables as $responsable)
                                            <option value="{{ $responsable->id }}" 
                                                {{ old('responsable_id', $impresora->responsable_id) == $responsable->id ? 'selected' : '' }}>
                                                {{ $responsable->nombre_responsable }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('responsable_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo *</label>
                                    <select name="tipo_impresora" class="form-control @error('tipo_impresora') is-invalid @enderror" required>
                                        <option value="Laser" {{ old('tipo_impresora', $impresora->tipo_impresora) == 'Laser' ? 'selected' : '' }}>Laser</option>
                                        <option value="Inyección" {{ old('tipo_impresora', $impresora->tipo_impresora) == 'Inyección' ? 'selected' : '' }}>Inyección</option>
                                        <option value="Matricial" {{ old('tipo_impresora', $impresora->tipo_impresora) == 'Matricial' ? 'selected' : '' }}>Matricial</option>
                                        <option value="Multifuncional" {{ old('tipo_impresora', $impresora->tipo_impresora) == 'Multifuncional' ? 'selected' : '' }}>Multifuncional</option>
                                        <option value="Plotter" {{ old('tipo_impresora', $impresora->tipo_impresora) == 'Plotter' ? 'selected' : '' }}>Plotter</option>
                                    </select>
                                    @error('tipo_impresora')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Marca *</label>
                                    <input type="text" name="marca_impresora" class="form-control @error('marca_impresora') is-invalid @enderror" 
                                           value="{{ old('marca_impresora', $impresora->marca_impresora) }}" required>
                                    @error('marca_impresora')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Modelo *</label>
                                    <input type="text" name="modelo_impresora" class="form-control @error('modelo_impresora') is-invalid @enderror" 
                                           value="{{ old('modelo_impresora', $impresora->modelo_impresora) }}" required>
                                    @error('modelo_impresora')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Serie *</label>
                                    <input type="text" name="serie_impresora" class="form-control @error('serie_impresora') is-invalid @enderror" 
                                           value="{{ old('serie_impresora', $impresora->serie_impresora) }}" required>
                                    @error('serie_impresora')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Adquisición</label>
                                    <input type="date" name="fecha_adquisicion" class="form-control @error('fecha_adquisicion') is-invalid @enderror" 
                                           value="{{ old('fecha_adquisicion', $impresora->fecha_adquisicion) }}">
                                    @error('fecha_adquisicion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Conexión</label>
                                    <select name="tipo_conexion" class="form-control">
                                        <option value="USB" {{ old('tipo_conexion', $impresora->tipo_conexion) == 'USB' ? 'selected' : '' }}>USB</option>
                                        <option value="WIFI" {{ old('tipo_conexion', $impresora->tipo_conexion) == 'WIFI' ? 'selected' : '' }}>WiFi</option>
                                        <option value="ETHERNET" {{ old('tipo_conexion', $impresora->tipo_conexion) == 'ETHERNET' ? 'selected' : '' }}>Ethernet</option>
                                        <option value="WIFI-DIRECT" {{ old('tipo_conexion', $impresora->tipo_conexion) == 'WIFI-DIRECT' ? 'selected' : '' }}>WiFi Direct</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Host</label>
                                    <input type="text" name="nombre_host" class="form-control" 
                                           value="{{ old('nombre_host', $impresora->nombre_host) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>IP</label>
                                    <input type="text" name="direccion_ip" class="form-control" 
                                           value="{{ old('direccion_ip', $impresora->direccion_ip) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="estado_impresora" class="form-control">
                                        <option value="OPTIMO" {{ old('estado_impresora', $impresora->estado_impresora) == 'OPTIMO' ? 'selected' : '' }}>Óptimo</option>
                                        <option value="BUENO" {{ old('estado_impresora', $impresora->estado_impresora) == 'BUENO' ? 'selected' : '' }}>Bueno</option>
                                        <option value="REGULAR" {{ old('estado_impresora', $impresora->estado_impresora) == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                                        <option value="DEFICIENTE" {{ old('estado_impresora', $impresora->estado_impresora) == 'DEFICIENTE' ? 'selected' : '' }}>Deficiente</option>
                                        <option value="DE BAJA" {{ old('estado_impresora', $impresora->estado_impresora) == 'DE BAJA' ? 'selected' : '' }}>De Baja</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Velocidad</label>
                                    <input type="text" name="velocidad_impresion" class="form-control" 
                                           value="{{ old('velocidad_impresion', $impresora->velocidad_impresion) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Modelo Consumible</label>
                                    <input type="text" name="modelo_consumible" class="form-control" 
                                           value="{{ old('modelo_consumible', $impresora->modelo_consumible) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo Consumible</label>
                                    <input type="text" name="tipo_consumible" class="form-control" 
                                           value="{{ old('tipo_consumible', $impresora->tipo_consumible) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cantidad Impresa</label>
                                    <input type="number" name="cantidad_impresion" class="form-control" 
                                           value="{{ old('cantidad_impresion', $impresora->cantidad_impresion ?? 0) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Capacidad Total</label>
                                    <input type="number" name="capacidad_impresion" class="form-control" 
                                           value="{{ old('capacidad_impresion', $impresora->capacidad_impresion ?? 0) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">Eliminar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" action="{{ route('admin.impresoras.destroy', $impresora->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function confirmDelete() {
    Swal.fire({
        title: '¿Eliminar impresora?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}
</script>
@endpush
@endsection