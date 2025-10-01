@extends('layouts.app')

@section('title', 'Detalles de Oficina')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5><i class="bi bi-building"></i> Detalles de la Oficina</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Nombre</th><td>{{ $oficina->nombre_oficina }}</td></tr>
                    <tr><th>Agencia</th><td>{{ $oficina->agencia->nombre_agencia ?? 'N/A' }}</td></tr>
                </table>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('oficinas.edit', $oficina) }}" class="btn btn-warning me-2">Editar</a>
                    <a href="{{ route('oficinas.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection