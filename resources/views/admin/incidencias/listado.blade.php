@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Incidencias Registradas</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.incidencias.formulario') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Incidencia
            </a>
            <a href="{{ route('admin.incidencias.exportar', ['estado' => request('estado')]) }}" 
               class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </div>

    <!-- Filtros -->
<!--     <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.incidencias.listado') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Filtrar por Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Derivado" {{ request('estado') == 'Derivado' ? 'selected' : '' }}>Derivado</option>
                        <option value="Atendido" {{ request('estado') == 'Atendido' ? 'selected' : '' }}>Atendido</option>
                    </select>
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-2 align-self-end">
                    <a href="{{ route('admin.incidencias.listado') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div> -->

    <!-- Total de incidencias -->
    <div class="mb-3">
        <strong>Total:</strong> {{ $incidencias->count() }} incidencia(s)
        @if(request('estado'))
            <span class="badge bg-info">Filtrado: {{ request('estado') }}</span>
        @endif
    </div>

    <div class="table-responsive" style="max-height:600px; overflow:auto;">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="position-sticky top-0 bg-dark text-white">#</th>
                    <th class="position-sticky top-0 bg-dark text-white">Tipo</th>
                    <th class="position-sticky top-0 bg-dark text-white">Módulo</th>
                    <th class="position-sticky top-0 bg-dark text-white">Problema</th>
                    <th class="position-sticky top-0 bg-dark text-white">Descripción</th>
                    <th class="position-sticky top-0 bg-dark text-white">Solución</th>
                    <th class="position-sticky top-0 bg-dark text-white">Usuario</th>
                    <th class="position-sticky top-0 bg-dark text-white">Oficina</th>
                    <th class="position-sticky top-0 bg-dark text-white">Prioridad</th>
                    <th class="position-sticky top-0 bg-dark text-white">Estado</th>
                    <th class="position-sticky top-0 bg-dark text-white">Fecha</th>
                    <th class="position-sticky top-0 bg-dark text-white">Atendió</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($incidencias as $i => $inc)
                <tr>
                    <td><strong>{{ $inc->id }}</strong></td>
                    <td>{{ ucfirst($inc->tipo) }}</td>
                    <td>{{ ucfirst($inc->modulo) }}</td>
                    <td>{{ Str::limit($inc->problema, 25) }}</td>
                    <td>{{ Str::limit($inc->descripcion, 50) }}</td>
                    <td>{{ Str::limit($inc->solucion, 50) }}</td>
                    <td>{{ $inc->usuario_afectado }}</td>
                    <td>{{ $inc->sub_agencia }}</td>

                    <td>
                        <span class="badge bg-{{
                            $inc->prioridad === 'Alta' ? 'danger' :
                            ($inc->prioridad === 'Media' ? 'warning' : 'info')
                        }}">
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
                    <td colspan="12" class="text-center text-muted py-4">
                        No hay incidencias registradas
                    </td>
                </tr>

                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection