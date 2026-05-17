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
                        <i class="bi bi-print"></i> Listado de Impresoras
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.impresoras.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i>
                        </a>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalFiltros">
                            <i class="bi bi-filter"></i> Filtros
                            @if(request()->anyFilled(['search', 'oficina_id', 'agencia_id', 'serie', 'estado_impresora']))
                                <span class="badge bg-white text-info ms-1">!</span>
                            @endif
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
                                            $estado = strtoupper(trim($impresora->estado_impresora));
                                            $badgeClass = [
                                                'OPTIMO' => 'success',
                                                'BUENO' => 'info',
                                                'REGULAR' => 'warning',
                                                'DEFICIENTE' => 'danger',
                                                'DE BAJA' => 'secondary'
                                            ][$estado] ?? 'dark';
                                        @endphp
                                        
                                        <span class="badge bg-{{ $badgeClass }}">
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
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.impresoras.edit', $impresora->id) }}" 
                                               class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.mantenimientos-impresora.create', $impresora->id) }}" 
                                               class="btn btn-primary" title="Registrar Mantenimiento">
                                                <i class="bi bi-tools"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    title="Eliminar"
                                                    onclick="confirmDelete({{ $impresora->id }})">
                                                <i class="bi bi-trash"></i>
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
                                            <i class="bi bi-info-circle"></i> No hay impresoras registradas
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
<div class="modal fade" id="modalFiltros">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-filter"></i></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.impresoras.index') }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Búsqueda General</label>
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Marca, modelo, host...">
                                <small class="text-muted">Búsqueda en múltiples campos</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Serie</label>
                                <input type="text" name="serie" class="form-control" 
                                       value="{{ request('serie') }}" placeholder="Ej: SN123456">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Agencia</label>
                                <select name="agencia_id" class="form-select select2" id="filter_agencia_id">
                                    <option value="">Todas las Agencias</option>
                                    @foreach($agencias ?? [] as $agencia)
                                        <option value="{{ $agencia->id }}" 
                                            {{ request('agencia_id') == $agencia->id ? 'selected' : '' }}>
                                            {{ $agencia->nombre_agencia }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Oficina</label>
                                <select name="oficina_id" class="form-select select2" id="filter_oficina_id">
                                    <option value="">Todas las Oficinas</option>
                                    @foreach($oficinas ?? [] as $oficina)
                                        <option value="{{ $oficina->id }}" 
                                            data-agencia="{{ $oficina->agencia_id }}"
                                            {{ request('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                            {{ $oficina->nombre_oficina }} 
                                            @if($oficina->agencia) ({{ $oficina->agencia->nombre_agencia }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estado</label>
                                <select name="estado_impresora" class="form-select">
                                    <option value="">Todos los Estados</option>
                                    <option value="OPTIMO" {{ request('estado_impresora') == 'OPTIMO' ? 'selected' : '' }}>Óptimo</option>
                                    <option value="BUENO" {{ request('estado_impresora') == 'BUENO' ? 'selected' : '' }}>Bueno</option>
                                    <option value="REGULAR" {{ request('estado_impresora') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                                    <option value="DEFICIENTE" {{ request('estado_impresora') == 'DEFICIENTE' ? 'selected' : '' }}>Deficiente</option>
                                    <option value="DE BAJA" {{ request('estado_impresora') == 'DE BAJA' ? 'selected' : '' }}>De Baja</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="{{ route('admin.impresoras.index') }}" class="btn btn-outline-danger">Limpiar Filtros</a>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="bi bi-search me-1"></i> Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2 al abrir el modal para asegurar el foco y el parent
    $('#modalFiltros').on('shown.bs.modal', function () {
        if (window.jQuery && $.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modalFiltros'),
                width: '100%',
                allowClear: true
            });
        }
    });

    const agenciaSelect = document.getElementById('filter_agencia_id');
    const oficinaSelect = document.getElementById('filter_oficina_id');
    const allOficinaOptions = Array.from(oficinaSelect.options);

    function filterOficinas() {
        const selectedAgencia = agenciaSelect.value;
        
        // Limpiar opciones actuales
        oficinaSelect.innerHTML = '';
        
        // Filtrar y añadir opciones
        allOficinaOptions.forEach(option => {
            if (!selectedAgencia || option.value === '' || option.getAttribute('data-agencia') === selectedAgencia) {
                oficinaSelect.appendChild(option.cloneNode(true));
            }
        });

        // Trigger Select2 update if it exists
        if (window.jQuery && $(oficinaSelect).data('select2')) {
            $(oficinaSelect).trigger('change.select2');
        }
    }

    if (agenciaSelect && oficinaSelect) {
        agenciaSelect.addEventListener('change', filterOficinas);
        // Ejecutar al cargar si hay una agencia seleccionada
        if (agenciaSelect.value) {
            filterOficinas();
            // Mantener la oficina seleccionada si es posible
            const savedOficinaId = "{{ request('oficina_id') }}";
            if (savedOficinaId) {
                oficinaSelect.value = savedOficinaId;
            }
        }
    }
});

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