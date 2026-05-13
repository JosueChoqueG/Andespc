@extends('layouts.app')

@section('title', 'Nueva Impresora')
@section('header', 'Registrar Impresora')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-plus-circle"></i> Datos de la Impresora
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.impresoras.index') }}" class="btn btn-default btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.impresoras.store') }}" method="POST">
                    @csrf
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
                                            <option value="{{ $oficina->id }}" {{ old('oficina_id') == $oficina->id ? 'selected' : '' }}>
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
                                        <option value="">Seleccionar</option>
                                        @foreach($responsables as $responsable)
                                            <option value="{{ $responsable->id }}" {{ old('responsable_id') == $responsable->id ? 'selected' : '' }}>
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
                                    <select name="tipo_impresora" class="form-control @error('tipo_impresora') is-invalid @enderror" required>
                                        <option value="">Seleccione</option>
                                        <option value="Inyección" {{ old('tipo_impresora') == 'Inyección' ? 'selected' : '' }}>Inyección</option>
                                        <option value="Multifuncional" {{ old('tipo_impresora') == 'Multifuncional' || is_null(old('tipo_impresora')) ? 'selected' : '' }}>Multifuncional</option>
                                    </select>
                                    @error('tipo_impresora')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Marca *</label>
                                    <input type="text" name="marca_impresora" class="form-control @error('marca_impresora') is-invalid @enderror" 
                                        value="{{ old('marca_impresora', 'KYOCERA') }}" required>
                                    @error('marca_impresora')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Modelo *</label>
                                    <select name="modelo_impresora" class="form-control @error('modelo_impresora') is-invalid @enderror" required>
                                        <option value="">Seleccione un modelo</option>
                                        <option value="M2640" {{ old('modelo_impresora') == 'M2640' ? 'selected' : '' }}>M2640</option>
                                        <option value="MA4500ix" {{ old('modelo_impresora') == 'MA4500ix' ? 'selected' : '' }}>MA4500ix</option>
                                        <option value="MA5500ifx" {{ old('modelo_impresora') == 'MA5500ifx' ? 'selected' : '' }}>MA5500ifx</option>
                                        <option value="HP LaserJet Tank MFP M634" {{ old('modelo_impresora') == 'HP LaserJet Tank MFP M634' ? 'selected' : '' }}>HP LaserJet Tank MFP M634</option>
                                        <option value="HP LaserJet PRO MFP M225dw" {{ old('modelo_impresora') == 'HP LaserJet PRO MFP M225dw' ? 'selected' : '' }}>HP LaserJet PRO MFP M225dw</option>
                                    </select>
                                    @error('modelo_impresora')
                                        <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número de Serie *</label>
                                    <input type="text" name="serie_impresora" class="form-control @error('serie_impresora') is-invalid @enderror" 
                                           value="{{ old('serie_impresora') }}" required>
                                    @error('serie_impresora')
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
                                        <option value="ETHERNET" {{ old('tipo_conexion') == 'ETHERNET' ? 'selected' : '' }}>Ethernet</option>
                                        <option value="USB" {{ old('tipo_conexion') == 'USB' ? 'selected' : '' }}>USB</option>
                                        <option value="WIFI" {{ old('tipo_conexion') == 'WIFI' ? 'selected' : '' }}>WiFi</option>
                                        <option value="WIFI-DIRECT" {{ old('tipo_conexion') == 'WIFI-DIRECT' ? 'selected' : '' }}>WiFi Direct</option>
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
                                           value="{{ old('nombre_host') }}" placeholder="ej: IMPRESORA-01">
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
                                           value="{{ old('direccion_ip') }}" placeholder="192.168.1.100">
                                    @error('direccion_ip')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Adquisición</label>
                                    <input type="date" name="fecha_adquisicion" class="form-control @error('fecha_adquisicion') is-invalid @enderror" 
                                           value="{{ old('fecha_adquisicion') }}">
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
                                    <select name="estado_impresora" class="form-control @error('estado_impresora') is-invalid @enderror">
                                        <option value="OPTIMO" {{ old('estado_impresora') == 'OPTIMO' ? 'selected' : '' }}>Óptimo</option>
                                        <option value="BUENO" {{ old('estado_impresora') == 'BUENO' ? 'selected' : '' }}>Bueno</option>
                                        <option value="REGULAR" {{ old('estado_impresora') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                                        <option value="DEFICIENTE" {{ old('estado_impresora') == 'DEFICIENTE' ? 'selected' : '' }}>Deficiente</option>
                                        <option value="DE BAJA" {{ old('estado_impresora') == 'DE BAJA' ? 'selected' : '' }}>De Baja</option>
                                    </select>
                                    @error('estado_impresora')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Velocidad de Impresión</label>
                                    <input type="text" name="velocidad_impresion" class="form-control @error('velocidad_impresion') is-invalid @enderror" 
                                           value="{{ old('velocidad_impresion') }}" placeholder="ej: 20 ppm">
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
                                    <select name="modelo_consumible" class="form-control">
                                        <option value="" disabled {{ old('modelo_consumible') == '' ? 'selected' : '' }}>Seleccione</option>
                                        <option value="TK-1175" {{ old('modelo_consumible') == 'TK-1175' ? 'selected' : '' }}>TK-1175</option>
                                        <option value="TK-3204" {{ old('modelo_consumible') == 'TK-3204' ? 'selected' : '' }}>TK-3204</option>
                                        <option value="TK-3432" {{ old('modelo_consumible') == 'TK-3432' ? 'selected' : '' }}>TK-3432</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Consumible</label>
                                    <input type="text" name="tipo_consumible" class="form-control" 
                                           value="{{ old('tipo_consumible', 'Tóner') }}" placeholder="ej: Tóner">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Capacidad (páginas)</label>
                                    <input type="number" name="capacidad_impresion" class="form-control" 
                                           value="{{ old('capacidad_impresion') }}" placeholder="ej: 15000">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cantidad Impresa Actual</label>
                                    <input type="number" name="cantidad_impresion" class="form-control" 
                                           value="{{ old('cantidad_impresion', 0) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cantidad Escaneos Actual</label>
                                    <input type="number" name="cantidad_escaneo" class="form-control" 
                                           value="{{ old('cantidad_escaneo', 0) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-default">Limpiar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Impresora
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection