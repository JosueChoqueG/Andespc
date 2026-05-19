@extends('layouts.app')

@section('title', 'Gestión de Impresoras Térmicas')
@section('header', 'Impresoras Térmicas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-print"></i> Listado de Impresoras Térmicas
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.termicas.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Nueva Térmica
                        </a>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalFiltros">
                            <i class="bi bi-filter"></i> Filtros
                            @if(request()->anyFilled(['search', 'oficina_id', 'agencia_id', 'serie', 'estado_termica']))
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
                                @forelse($termicas as $termica)
                                <tr>
                                    <td>{{ $termica->id }}</td>
                                    <td>{{ $termica->serie_termica }}</td>
                                    <td>
                                        <strong>{{ $termica->marca_termica }}</strong><br>
                                        <small>{{ $termica->modelo_termica }}</small>
                                    </td>
                                    <td>{{ $termica->oficina->nombre_oficina ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $iconos = [
                                                'USB' => 'fab fa-usb',
                                                'WI-FI' => 'fas fa-wifi',
                                                'ETHERNET' => 'fas fa-network-wired',
                                                'SERIAL' => 'fas fa-plug',
                                                'BLUETOOTH' => 'fab fa-bluetooth'
                                            ];
                                        @endphp
                                        <i class="{{ $iconos[$termica->tipo_conexion] ?? 'fas fa-plug' }}"></i>
                                        {{ $termica->tipo_conexion }}
                                    </td>
                                    <td>{{ $termica->direccion_ip ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $estado = strtoupper(trim($termica->estado_termica));
                                            $badgeClass = [
                                                'OPTIMO' => 'success',
                                                'BUENO' => 'info',
                                                'REGULAR' => 'warning',
                                                'DEFICIENTE' => 'danger',
                                                'DE BAJA' => 'secondary'
                                            ][$estado] ?? 'dark';
                                        @endphp
                                        
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ $termica->estado_termica }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($termica->ultimoMantenimiento)
                                            {{ date('d/m/Y', strtotime($termica->ultimoMantenimiento->fecha_mantenimiento)) }}
                                        @else
                                            <span class="text-muted">Sin registro</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.termicas.show', $termica->id) }}" 
                                               class="btn btn-info" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.termicas.edit', $termica->id) }}" 
                                               class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.mantenimientos-termica.create', $termica->id) }}" 
                                               class="btn btn-primary" title="Registrar Mantenimiento">
                                                <i class="bi bi-tools"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    title="Eliminar"
                                                    onclick="confirmDelete({{ $termica->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <form id="delete-form-{{ $termica->id }}" 
                                              action="{{ route('admin.termicas.destroy', $termica->id) }}" 
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
                                            <i class="bi bi-info-circle"></i> No hay impresoras térmicas registradas
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $termicas->appends(request()->query())->links() }}
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
                <h5 class="modal-title"><i class="bi bi-filter"></i> Filtros de Búsqueda</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.termicas.index') }}">
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
                                <select name="estado_termica" class="form-select">
                                    <option value="">Todos los Estados</option>
                                    <option value="OPTIMO" {{ request('estado_termica') == 'OPTIMO' ? 'selected' : '' }}>Óptimo</option>
                                    <option value="BUENO" {{ request('estado_termica') == 'BUENO' ? 'selected' : '' }}>Bueno</option>
                                    <option value="REGULAR" {{ request('estado_termica') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                                    <option value="DEFICIENTE" {{ request('estado_termica') == 'DEFICIENTE' ? 'selected' : '' }}>Deficiente</option>
                                    <option value="DE BAJA" {{ request('estado_termica') == 'DE BAJA' ? 'selected' : '' }}>De Baja</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="{{ route('admin.termicas.index') }}" class="btn btn-outline-danger">Limpiar Filtros</a>
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
        title: '¿Eliminar impresora térmica?',
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
