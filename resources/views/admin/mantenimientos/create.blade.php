@extends('layouts.app')

@section('content')
<h4>Nuevo Mantenimiento</h4>

<div class="card mb-3">
    <div class="card-body">
        <strong>Impresora:</strong> {{ $impresora->marca }} {{ $impresora->modelo }} <br>
        <strong>Serie:</strong> {{ $impresora->serie }}
    </div>
</div>

<form action="{{ route('admin.mantenimientos.store') }}" method="POST">
    @csrf
    <input type="hidden" name="impresora_id" value="{{ $impresora->id }}">

    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Incidencia</label>
            <input type="text" name="tipo_error" class="form-control" required>
        </div>

        <div class="col-md-3 mb-3">
            <label>Fecha Incidencia</label>
            <input type="date" name="fecha_incidencia" class="form-control">
        </div>

        <div class="col-md-3 mb-3">
            <label>Estado Garantía</label>
            <select name="estado_garantia" class="form-select">
                @php
                    $estado = old('estado_garantia', $mantenimiento->estado_garantia ?? 'Vigente');
                @endphp

                <option value="Vigente" {{ $estado == 'Vigente' ? 'selected' : '' }}>
                    Vigente
                </option>
                <option value="Finalizado" {{ $estado == 'Finalizado' ? 'selected' : '' }}>
                    Finalizado
                </option>
                <option value="No tiene" {{ $estado == 'No tiene' ? 'selected' : '' }}>
                    No tiene
                </option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label>Fecha Envío</label>
            <input type="date" name="fecha_envio_mantenimiento" class="form-control">
        </div>

        <div class="col-md-4 mb-3">
            <label>Fecha Retorno</label>
            <input type="date" name="fecha_retorno_mantenimiento" class="form-control">
        </div>

        <div class="col-md-2 mb-3">
            <label>Contador</label>
            <input type="number" name="contador" class="form-control">
        </div>

        <div class="col-12 mb-3">
            <label>Observación</label>
            <textarea name="observacion" class="form-control" rows="3"></textarea>
        </div>
    </div>

    <button class="btn btn-success">Guardar</button>
    <a href="{{ route('admin.impresoras.show',$impresora) }}" class="btn btn-secondary">Volver</a>
</form>
@endsection
