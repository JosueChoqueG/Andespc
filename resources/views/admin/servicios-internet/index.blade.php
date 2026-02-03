@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Servicios de Internet</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.servicios-internet.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Servicio
            </a>

            <a href="{{ route('admin.servicios-internet.excel') }}"
               class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET"
                  action="{{ route('admin.servicios-internet.index') }}"
                  class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Filtrar por Oficina</label>
                    <select name="oficina_id"
                            class="form-select select2"
                            data-placeholder="Todas las oficinas">
                        <option value="">Todas las oficinas</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}"
                                {{ request('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre_oficina }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Filtrar por Proveedor</label>
                    <input type="text"
                           name="nombre_proveedor"
                           class="form-control"
                           value="{{ request('nombre_proveedor') }}"
                           placeholder="Ej: Redycom">
                </div>

                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.servicios-internet.index') }}"
                       class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- TOTAL --}}
    <div class="mb-3">
        <strong>Total:</strong> {{ $servicios->count() }} servicio(s)
        @if(request('oficina_id') || request('nombre_proveedor'))
            <span class="badge bg-info">Filtrado</span>
        @endif
    </div>

    {{-- TABLA --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Oficina</th>
                    <th>Proveedor</th>
                    <th>Teléfono</th>
                    <th>Megas</th>
                    <th>Tipo</th>
                    <th>IP</th>
                    <th>WiFi</th>
                    <th width="90">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @forelse ($servicios as $s)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $s->oficina->nombre_oficina }}</td>
                        <td>{{ $s->nombre_proveedor }}</td>
                        <td>{{ $s->telefono_proveedor ?? '—' }}</td>
                        <td>{{ $s->megas_contratado }}</td>
                        <td>
                            <span class="badge bg-{{
                                $s->tipo_instalacion === 'Fibra' ? 'success' : 'warning'
                            }} text-dark">
                                {{ $s->tipo_instalacion }}
                            </span>
                        </td>
                        <td>{{ $s->direccion_ip ?? '—' }}</td>
                        <td>{{ $s->nombre_wifi ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.servicios-internet.edit', $s) }}"
                               class="btn btn-warning btn-sm rounded-circle">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('admin.servicios-internet.destroy', $s) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este servicio?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm rounded-circle">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">No hay servicios de internet registrados.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection