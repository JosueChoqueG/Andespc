@extends('layouts.app')

@section('title', 'Gestión de Impresoras')
@section('header', 'Impresoras')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-print"></i> Listado de Impresoras
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.impresoras.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nueva Impresora
                        </a>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalFiltros">
                            <i class="fas fa-filter"></i> Filtros
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Serie</th>
                                    <th>Marca / Modelo</th>
                                    <th>Oficina</th>
                                    <th>Conexión</th>
                                    <th>IP</th>
                                    <th>Estado</th>
                                    <th>Últ. Mant.</th>
                                    <th width="150">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($impresoras as $impresora)
                                <tr>
                                    <td>{{ $impresora->id }}</td>
                                    <td>{{ $impresora->serie_impresora }}</td>
                                    <td>
                                        <strong>{{ $impresora->marca_impresora }}</strong><br>
                                        <small>{{ $impresora->modelo_impresora }}</small>
                                    </td>
                                    <td>{{ $impresora->oficina->nombre_oficina ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $iconos = [
                                                'USB' => 'fa-usb',
                                                'WIFI' => 'fa-wifi',
                                                'ETHERNET' => 'fa-network-wired',
                                                'WIFI-DIRECT' => 'fa-wifi'
                                            ];
                                        @endphp
                                        <i class="fas {{ $iconos[$impresora->tipo_conexion] ?? 'fa-plug' }}"></i>
                                        {{ $impresora->tipo_conexion }}
                                    </td>
                                    <td>{{ $impresora->direccion_ip ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'OPTIMO' => 'success',
                                                'BUENO' => 'info',
                                                'REGULAR' => 'warning',
                                                'DEFICIENTE' => 'danger',
                                                'DE BAJA' => 'secondary'
                                            ][$impresora->estado_impresora] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $badgeClass }}">
                                            {{ $impresora->estado_impresora }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($impresora->ultimoMantenimiento)
                                            {{ date('d/m/Y', strtotime($impresora->ultimoMantenimiento->fecha_mantenimiento)) }}
                                        @else
                                            <span class="text-muted">Sin registro</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.impresoras.show', $impresora->id) }}" 
                                               class="btn btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.impresoras.edit', $impresora->id) }}" 
                                               class="btn btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.mantenimientos-impresora.create', $impresora->id) }}" 
                                               class="btn btn-primary" title="Registrar Mantenimiento">
                                                <i class="fas fa-tools"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    title="Eliminar"
                                                    onclick="confirmDelete({{ $impresora->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <form id="delete-form-{{ $impresora->id }}" 
                                              action="{{ route('admin.impresoras.destroy', $impresora->id) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle"></i> No hay impresoras registradas
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $impresoras->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtrar Impresoras</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="GET" action="{{ route('admin.impresoras.index') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Buscar</label>
                        <input type="text" name="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Serie, marca, modelo...">
                    </div>
                    <div class="form-group">
                        <label>Oficina</label>
                        <select name="oficina_id" class="form-control">
                            <option value="">Todas</option>
                            @foreach($oficinas ?? [] as $oficina)
                                <option value="{{ $oficina->id }}" 
                                    {{ request('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                    {{ $oficina->nombre_oficina }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado_impresora" class="form-control">
                            <option value="">Todos</option>
                            <option value="OPTIMO" {{ request('estado_impresora') == 'OPTIMO' ? 'selected' : '' }}>Óptimo</option>
                            <option value="BUENO" {{ request('estado_impresora') == 'BUENO' ? 'selected' : '' }}>Bueno</option>
                            <option value="REGULAR" {{ request('estado_impresora') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                            <option value="DEFICIENTE" {{ request('estado_impresora') == 'DEFICIENTE' ? 'selected' : '' }}>Deficiente</option>
                            <option value="DE BAJA" {{ request('estado_impresora') == 'DE BAJA' ? 'selected' : '' }}>De Baja</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                    <a href="{{ route('admin.impresoras.index') }}" class="btn btn-danger">Limpiar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
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
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush
@endsection