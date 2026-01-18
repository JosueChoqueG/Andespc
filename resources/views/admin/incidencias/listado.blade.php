@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Incidencias Registradas</h2>
        <div>
            <a href="{{ route('admin.incidencias.formulario') }}" class="btn btn-primary">Nueva Incidencia</a>
            <!-- <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Salir</button>
            </form> -->
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Módulo</th>
                    <th>Problema</th>
                    <th>Descripción</th>
                    <th>Solución</th>
                    <th>Usuario</th>
                    <th>Agencia</th>
                    <th>Oficina</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Atendió</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($incidencias as $i => $inc)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ ucfirst($inc->tipo) }}</td>
                        <td>{{ ucfirst($inc->modulo) }}</td>
                        <td>{{ Str::limit($inc->problema, 25) }}</td>
                        <td>{{ Str::limit($inc->descripcion, 50) }}</td>
                        <td>{{ Str::limit($inc->solucion, 50) }}</td>
                        <td>{{ $inc->usuario_afectado }}</td>
                        <td>{{ $inc->agencia }}</td>
                        <td>{{ $inc->sub_agencia }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $inc->prioridad === 'Alta' ? 'danger' : 
                                ($inc->prioridad === 'Media' ? 'warning' : 'info') 
                            }} text-dark">
                                {{ $inc->prioridad }}
                            </span>
                        </td>
                        <td>
                            @php
                                $badge = match(strtolower($inc->estado)) {
                                    'pendiente' => 'danger',
                                    'derivado' => 'warning',
                                    'atendido' => 'success',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">
                                {{ $inc->estado }}
                            </span>
                        </td>
                        <td>{{ $inc->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $inc->atendidoPor?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">No hay incidencias registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection