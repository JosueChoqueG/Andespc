@extends('layouts.app')

@section('title', 'Agencias')

@section('content')

<div>
    <h2>Agencias</h2>
    <a href="{{ route('agencias.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Nueva Agencia
    </a>
</div>
<div class="card shadow-sm d-inline-block">
    <div class="card-body ">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle w-auto">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th class="text-center text-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agencias as $agencia)
                    <tr>
                        <td>{{ $agencia->codigo_agencia }}</td>
                        <td>{{ $agencia->nombre_agencia }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('agencias.edit', $agencia) }}" 
                            class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form method="POST" 
                                action="{{ route('agencias.destroy', $agencia) }}" 
                                class="d-inline-block">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection