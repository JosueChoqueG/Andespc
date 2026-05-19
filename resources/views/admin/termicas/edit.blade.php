@extends('layouts.app')

@section('title', 'Editar Impresora Térmica')
@section('header', 'Modificar Impresora Térmica')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-pencil-square"></i> Datos de la Impresora Térmica: <strong>{{ $termica->serie_termica }}</strong>
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.termicas.index') }}" class="btn btn-default btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.termicas.update', $termica->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Oficina *</label>
                                    <select name="oficina_id" class="form-control select2 @error('oficina_id') is-invalid @enderror" required>
                                        <option value="">Seleccione una oficina</option>
                                        @foreach($oficinas as $oficina)
                                            <option value="{{ $oficina->id }}" {{ old('oficina_id', $termica->oficina_id) == $oficina->id ? 'selected' : '' }}>
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
                                    <select name="responsable_id" class="form-control select2 @error('responsable_id') is-invalid @enderror">
                                        <option value="">Seleccionar responsable</option>
                                        @foreach($responsables as $responsable)
                                            <option value="{{ $responsable->id }}" {{ old('responsable_id', $termica->responsable_id) == $responsable->id ? 'selected' : '' }}>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de Impresora *</label>
                                    <input type="text" name="tipo_termica" class="form-control @error('tipo_termica') is-invalid @enderror" 
                                        value="{{ old('tipo_termica', $termica->tipo_termica) }}" placeholder="ej: Térmica POS" required>
                                    @error('tipo_termica')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Marca *</label>
                                    <input type="text" name="marca_termica" class="form-control @error('marca_termica') is-invalid @enderror" 
                                        value="{{ old('marca_termica', $termica->marca_termica) }}" placeholder="ej: Epson" required>
                                    @error('marca_termica')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Modelo *</label>
                                    <input type="text" name="modelo_termica" class="form-control @error('modelo_termica') is-invalid @enderror" 
                                        value="{{ old('modelo_termica', $termica->modelo_termica) }}" placeholder="ej: TM-T20II" required>
                                    @error('modelo_termica')
                                        <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número de Serie *</label>
                                    <input type="text" name="serie_termica" class="form-control @error('serie_termica') is-invalid @enderror" 
                                           value="{{ old('serie_termica', $termica->serie_termica) }}" required>
                                    @error('serie_termica')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de Conexión</label>
                                    <select name="tipo_conexion" class="form-control @error('tipo_conexion') is-invalid @enderror">
                                        <option value="USB" {{ old('tipo_conexion', $termica->tipo_conexion) == 'USB' ? 'selected' : '' }}>USB</option>
                                        <option value="ETHERNET" {{ old('tipo_conexion', $termica->tipo_conexion) == 'ETHERNET' ? 'selected' : '' }}>Ethernet</option>
                                        <option value="SERIAL" {{ old('tipo_conexion', $termica->tipo_conexion) == 'SERIAL' ? 'selected' : '' }}>Serial</option>
                                        <option value="WI-FI" {{ old('tipo_conexion', $termica->tipo_conexion) == 'WI-FI' ? 'selected' : '' }}>Wi-Fi</option>
                                        <option value="BLUETOOTH" {{ old('tipo_conexion', $termica->tipo_conexion) == 'BLUETOOTH' ? 'selected' : '' }}>Bluetooth</option>
                                    </select>
                                    @error('tipo_conexion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre Host</label>
                                    <input type="text" name="nombre_host" class="form-control @error('nombre_host') is-invalid @enderror" 
                                           value="{{ old('nombre_host', $termica->nombre_host) }}" placeholder="ej: TERMICA-POS-01">
                                    @error('nombre_host')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dirección IP</label>
                                    <input type="text" name="direccion_ip" class="form-control @error('direccion_ip') is-invalid @enderror" 
                                           value="{{ old('direccion_ip', $termica->direccion_ip) }}" placeholder="192.168.1.150">
                                    @error('direccion_ip')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Adquisición</label>
                                    <input type="date" name="fecha_adquisicion" class="form-control @error('fecha_adquisicion') is-invalid @enderror" 
                                           value="{{ old('fecha_adquisicion', $termica->fecha_adquisicion ? $termica->fecha_adquisicion->format('Y-m-d') : '') }}">
                                    @error('fecha_adquisicion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="estado_termica" class="form-control @error('estado_termica') is-invalid @enderror">
                                        <option value="OPTIMO" {{ old('estado_termica', $termica->estado_termica) == 'OPTIMO' ? 'selected' : '' }}>Óptimo</option>
                                        <option value="BUENO" {{ old('estado_termica', $termica->estado_termica) == 'BUENO' ? 'selected' : '' }}>Bueno</option>
                                        <option value="REGULAR" {{ old('estado_termica', $termica->estado_termica) == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                                        <option value="DEFICIENTE" {{ old('estado_termica', $termica->estado_termica) == 'DEFICIENTE' ? 'selected' : '' }}>Deficiente</option>
                                        <option value="DE BAJA" {{ old('estado_termica', $termica->estado_termica) == 'DE BAJA' ? 'selected' : '' }}>De Baja</option>
                                    </select>
                                    @error('estado_termica')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Velocidad de Impresión</label>
                                    <input type="text" name="velocidad_impresion" class="form-control @error('velocidad_impresion') is-invalid @enderror" 
                                           value="{{ old('velocidad_impresion', $termica->velocidad_impresion) }}" placeholder="ej: 250 mm/s">
                                    @error('velocidad_impresion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Modelo Consumible</label>
                                    <input type="text" name="modelo_consumible" class="form-control @error('modelo_consumible') is-invalid @enderror" 
                                           value="{{ old('modelo_consumible', $termica->modelo_consumible) }}" placeholder="ej: Rollo Térmico 80mm">
                                    @error('modelo_consumible')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Consumible</label>
                                    <input type="text" name="tipo_consumible" class="form-control @error('tipo_consumible') is-invalid @enderror" 
                                           value="{{ old('tipo_consumible', $termica->tipo_consumible) }}" placeholder="ej: Papel Térmico">
                                    @error('tipo_consumible')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Capacidad (Km de vida útil teórica)</label>
                                    <input type="number" name="capacidad_impresion" class="form-control @error('capacidad_impresion') is-invalid @enderror" 
                                           value="{{ old('capacidad_impresion', $termica->capacidad_impresion) }}" placeholder="ej: 150">
                                    @error('capacidad_impresion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cantidad Impresa Actual (Metros o cortes acumulados)</label>
                                    <input type="number" name="cantidad_impresion" class="form-control @error('cantidad_impresion') is-invalid @enderror" 
                                           value="{{ old('cantidad_impresion', $termica->cantidad_impresion) }}">
                                    @error('cantidad_impresion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.termicas.index') }}" class="btn btn-default">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
