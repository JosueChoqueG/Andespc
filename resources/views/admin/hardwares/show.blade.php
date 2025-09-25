@extends('layouts.app')

@section('title', 'Detalles de Hardware')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5><i class="bi bi-cpu"></i> Detalles del Hardware #{{ $hardware->id_hardware }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Procesador</th>
                        <td>{{ $hardware->procesador ?? 'No especificado' }}</td>
                    </tr>
                    <tr>
                        <th>RAM</th>
                        <td>{{ $hardware->ram_gb }} GB</td>
                    </tr>
                    <tr>
                        <th>Almacenamiento</th>
                        <td>{{ $hardware->almacenamiento_gb }} GB</td>
                    </tr>
                    <tr>
                        <th>Tipo de Almacenamiento</th>
                        <td>
                            <span class="badge bg-{{ $hardware->tipo_almacenamiento == 'SSD' ? 'success' : ($hardware->tipo_almacenamiento == 'NVMe' ? 'primary' : 'secondary') }}">
                                {{ $hardware->tipo_almacenamiento }}
                            </span>
                        </td>
                    </tr>
                </table>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('hardwares.edit', $hardware) }}" class="btn btn-warning me-2">Editar</a>
                    <a href="{{ route('hardwares.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection