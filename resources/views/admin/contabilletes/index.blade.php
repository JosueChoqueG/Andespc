@extends('layouts.app')

@section('title', 'Gestión de Contadoras de Billetes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Contadoras de Billetes Registradas</h5>
        <a href="{{ route('admin.contabilletes.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle"></i>
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <strong><i class="bi bi-list-check"></i> Listado de Contadoras</strong>
                    
                    <!-- Buscador y Filtros Inline -->
                    <form method="GET" action="{{ route('admin.contabilletes.index') }}" class="row g-2 align-items-center flex-grow-1 justify-content-end">
                        <div class="col-md-2 col-sm-4 col-12">
                            <input type="text" name="serie" class="form-control form-control-sm" placeholder="Por serie..." value="{{ request('serie') }}">
                        </div>
                        <div class="col-md-2 col-sm-4 col-12">
                            <select name="agencia_id" id="filtroAgencia" class="form-select form-select-sm select2">
                                <option value="">Agencias</option>
                                @foreach($agencias ?? [] as $agencia)
                                    <option value="{{ $agencia->id }}" {{ request('agencia_id') == $agencia->id ? 'selected' : '' }}>
                                        {{ $agencia->nombre_agencia }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-4 col-12">
                            <select name="oficina_id" id="filtroOficina" class="form-select form-select-sm select2">
                                <option value="">Oficinas</option>
                                @foreach($oficinas ?? [] as $oficina)
                                    <option value="{{ $oficina->id }}" 
                                        data-agencia="{{ $oficina->agencia_id }}"
                                        {{ request('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                        {{ $oficina->nombre_oficina }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-4 col-12">
                            <select name="estado_contabilletes" class="form-select form-select-sm">
                                <option value="">Estados</option>
                                <option value="OPTIMO" {{ request('estado_contabilletes') == 'OPTIMO' ? 'selected' : '' }}>Óptimo</option>
                                <option value="BUENO" {{ request('estado_contabilletes') == 'BUENO' ? 'selected' : '' }}>Bueno</option>
                                <option value="REGULAR" {{ request('estado_contabilletes') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                                <option value="DEFICIENTE" {{ request('estado_contabilletes') == 'DEFICIENTE' ? 'selected' : '' }}>Deficiente</option>
                                <option value="DE BAJA" {{ request('estado_contabilletes') == 'DE BAJA' ? 'selected' : '' }}>De Baja</option>
                            </select>
                        </div>
                        <div class="col-auto d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center shadow-sm" title="Filtrar">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('admin.contabilletes.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center shadow-sm" title="Limpiar Filtros">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
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
                                    <th>Velocidad</th>
                                    <th>Detección</th>
                                    <th>Pantalla</th>
                                    <th>Estado</th>
                                    <th>Últ. Mant.</th>
                                    <th width="150">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contabilletes as $contabillete)
                                <tr>
                                    <td>{{ $contabillete->id }}</td>
                                    <td>{{ $contabillete->serie_contabilletes }}</td>
                                    <td>
                                        <strong>{{ $contabillete->marca_contabilletes }}</strong><br>
                                        <small>{{ $contabillete->modelo_contabilletes }}</small>
                                    </td>
                                    <td>{{ $contabillete->oficina->nombre_oficina ?? 'N/A' }}</td>
                                    <td>{{ $contabillete->velocidad_contabilletes ?? 'N/A' }}</td>
                                    <td>{{ $contabillete->tipo_deteccion ?? 'N/A' }}</td>
                                    <td>{{ $contabillete->pantalla_contabilletes ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $estado = strtoupper(trim($contabillete->estado_contabilletes));
                                            $badgeClass = [
                                                'OPTIMO' => 'success',
                                                'BUENO' => 'info',
                                                'REGULAR' => 'warning',
                                                'DEFICIENTE' => 'danger',
                                                'DE BAJA' => 'secondary'
                                            ][$estado] ?? 'dark';
                                        @endphp
                                        
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ $contabillete->estado_contabilletes }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($contabillete->ultimoMantenimiento)
                                            {{ date('d/m/Y', strtotime($contabillete->ultimoMantenimiento->fecha_mantenimiento)) }}
                                        @else
                                            <span class="text-muted">Sin registro</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.contabilletes.show', $contabillete->id) }}" 
                                               class="btn btn-info" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.contabilletes.edit', $contabillete->id) }}" 
                                               class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.mantenimientos-contabillete.create', $contabillete->id) }}" 
                                               class="btn btn-primary" title="Registrar Mantenimiento">
                                                <i class="bi bi-tools"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    title="Eliminar"
                                                    onclick="confirmDelete({{ $contabillete->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <form id="delete-form-{{ $contabillete->id }}" 
                                              action="{{ route('admin.contabilletes.destroy', $contabillete->id) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle"></i> No hay contadoras de billetes registradas
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $contabilletes->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2
    if (window.jQuery && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true
        });
    }

    const agenciaSelect = document.getElementById('filtroAgencia');
    const oficinaSelect = document.getElementById('filtroOficina');
    const allOficinaOptions = Array.from(oficinaSelect.options);

    function filterOficinas() {
        const selectedAgencia = agenciaSelect.value;
        
        // Guardar selección actual de oficina para ver si se puede conservar
        const currentOficinaVal = oficinaSelect.value;
        
        // Limpiar opciones actuales
        oficinaSelect.innerHTML = '';
        
        // Filtrar y añadir opciones
        allOficinaOptions.forEach(option => {
            if (!selectedAgencia || option.value === '' || option.getAttribute('data-agencia') === selectedAgencia) {
                oficinaSelect.appendChild(option.cloneNode(true));
            }
        });

        // Restaurar selección previa si aún existe entre las opciones filtradas
        if (Array.from(oficinaSelect.options).some(opt => opt.value === currentOficinaVal)) {
            oficinaSelect.value = currentOficinaVal;
        } else {
            oficinaSelect.value = '';
        }

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
        title: '¿Eliminar contadora de billetes?',
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
