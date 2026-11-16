@extends('layouts.app')

@section('title', 'Servicios de Internet')

@section('content')
<div class="container-fluid">
    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-wifi text-primary me-2"></i>Servicios de Internet</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrear" title="Nuevo Servicio de Internet">
                <i class="bi bi-plus-circle"></i>
            </button>
            <a href="{{ route('admin.servicios-internet.excel') }}" id="btnExportarExcel" class="btn btn-success shadow-sm" title="Exportar a Excel">
                <i class="bi bi-filetype-xlsx"></i>
            </a>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <strong><i class="bi bi-list-check me-1"></i>Listado de Servicios</strong>

                    <!-- Buscador y Filtros Inline -->
                    <form method="GET" action="{{ route('admin.servicios-internet.index') }}" class="row g-2 align-items-center flex-grow-1 justify-content-end" id="filtros-form">
                        <div class="col-md-3 col-sm-4 col-12 position-relative">
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" id="inputBusqueda" class="form-control form-control-sm" placeholder="Por proveedor, IP, WiFi..." value="{{ request('search') ?? request('q') }}" autocomplete="off">
                                <span class="input-group-text bg-white border-start-0 py-0 px-2 d-none" id="search-spinner" style="cursor: default;">
                                    <span class="spinner-border spinner-border-sm text-primary" role="status" style="width: 0.85rem; height: 0.85rem;"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-12">
                            <select name="agencia_id" id="filtroAgencia" class="form-select form-select-sm select2">
                                <option value="">Agencias</option>
                                @foreach($agencias ?? [] as $agencia)
                                    <option value="{{ $agencia->id }}" {{ (request('agencia_id') == $agencia->id || request('agencia') == $agencia->id) ? 'selected' : '' }}>
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
                                        {{ (request('oficina_id') == $oficina->id || request('oficina') == $oficina->id) ? 'selected' : '' }}>
                                        {{ $oficina->nombre_oficina }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-4 col-12">
                            <select name="tipo_instalacion" id="filtroTipo" class="form-select form-select-sm">
                                <option value="">Tipos de servicio</option>
                                <option value="Fibra óptica" {{ request('tipo_instalacion') == 'Fibra óptica' ? 'selected' : '' }}>Fibra óptica</option>
                                <option value="Radioenlace" {{ request('tipo_instalacion') == 'Radioenlace' ? 'selected' : '' }}>Radioenlace</option>
                                <option value="Starlink" {{ request('tipo_instalacion') == 'Starlink' ? 'selected' : '' }}>Starlink</option>
                                <option value="RPC" {{ request('tipo_instalacion') == 'RPC' ? 'selected' : '' }}>RPC</option>
                            </select>
                        </div>
                        <div class="col-auto d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center shadow-sm" title="Filtrar">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('admin.servicios-internet.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center shadow-sm" title="Limpiar Filtros" id="btnLimpiarFiltros">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div id="servicios-table-container">
                        @include('admin.servicios-internet.partials.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL — Nuevo Servicio de Internet
══════════════════════════════════════════ --}}
<div class="modal fade" id="modalCrear" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow border-0">

            <div class="modal-header bg-light border-bottom py-3">
                <h5 class="modal-title fw-bold" id="modalCrearLabel">
                    <i class="bi bi-wifi text-primary me-2"></i>Nuevo Servicio de Internet
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form method="POST" action="{{ route('admin.servicios-internet.store') }}">
                <div class="modal-body p-3">

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show py-2 small mb-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <strong>Corrija los siguientes errores:</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @php $servicio = null; @endphp
                    @include('admin.servicios-internet.form', ['hideButtons' => true])

                </div>
                <div class="modal-footer bg-light border-top py-2 px-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-floppy me-1"></i>Guardar Servicio
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<style>
    #modalCrear {
        z-index: 1060 !important;
    }
    .modal-backdrop {
        z-index: 1050 !important;
    }
    .select2-container--bootstrap-5.select2-container--open,
    .select2-container--open {
        z-index: 1070 !important;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Inicialización de Select2 para filtros
    if (window.jQuery && $.fn.select2) {
        $('#filtroAgencia, #filtroOficina').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true
        });
    }

    const form = document.getElementById('filtros-form');
    const inputBusqueda = document.getElementById('inputBusqueda');
    const agenciaSelect = document.getElementById('filtroAgencia');
    const oficinaSelect = document.getElementById('filtroOficina');
    const tipoSelect = document.getElementById('filtroTipo');
    const spinner = document.getElementById('search-spinner');
    const tableContainer = document.getElementById('servicios-table-container');
    const btnExportar = document.getElementById('btnExportarExcel');
    const exportBaseUrl = "{{ route('admin.servicios-internet.excel') }}";
    const indexUrl = "{{ route('admin.servicios-internet.index') }}";

    const allOficinaOptions = Array.from(oficinaSelect ? oficinaSelect.options : []);
    let debounceTimer = null;
    let activeAbortController = null;

    // 2. Cascada Agencias -> Oficinas
    function filterOficinas() {
        if (!agenciaSelect || !oficinaSelect) return;
        const selectedAgencia = agenciaSelect.value;
        const currentOficinaVal = oficinaSelect.value;
        oficinaSelect.innerHTML = '';
        allOficinaOptions.forEach(option => {
            if (!selectedAgencia || option.value === '' || option.getAttribute('data-agencia') === selectedAgencia) {
                oficinaSelect.appendChild(option.cloneNode(true));
            }
        });
        if (Array.from(oficinaSelect.options).some(opt => opt.value === currentOficinaVal)) {
            oficinaSelect.value = currentOficinaVal;
        } else {
            oficinaSelect.value = '';
        }
        if (window.jQuery && $(oficinaSelect).data('select2')) {
            $(oficinaSelect).trigger('change.select2');
        }
    }

    if (agenciaSelect && oficinaSelect) {
        agenciaSelect.addEventListener('change', function() {
            filterOficinas();
            ejecutarFiltroAjax();
        });
        if (agenciaSelect.value) {
            filterOficinas();
            const savedOficinaId = "{{ request('oficina_id') ?? request('oficina') }}";
            if (savedOficinaId) {
                oficinaSelect.value = savedOficinaId;
            }
        }
    }

    // 3. Sincronizar enlace de Exportar
    function updateExportUrl() {
        if (!btnExportar || !form) return;
        const params = new URLSearchParams(new FormData(form));
        btnExportar.href = exportBaseUrl + '?' + params.toString();
    }

    // 4. Filtrado en vivo vía AJAX
    function ejecutarFiltroAjax(pageUrl = null) {
        if (!form) return;
        if (spinner) spinner.classList.remove('d-none');

        if (activeAbortController) {
            activeAbortController.abort();
        }
        activeAbortController = new AbortController();

        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        let fetchUrl = pageUrl ? pageUrl : (indexUrl + '?' + params.toString());

        if (!pageUrl) {
            window.history.replaceState({}, '', indexUrl + '?' + params.toString());
            updateExportUrl();
        }

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            signal: activeAbortController.signal
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al filtrar');
            return response.text();
        })
        .then(html => {
            tableContainer.innerHTML = html;
            if (spinner) spinner.classList.add('d-none');
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error('Error al filtrar servicios:', err);
                if (spinner) spinner.classList.add('d-none');
            }
        });
    }

    // 5. Eventos de búsqueda
    if (inputBusqueda) {
        inputBusqueda.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                ejecutarFiltroAjax();
            }, 250);
        });
    }

    if (oficinaSelect) {
        oficinaSelect.addEventListener('change', () => ejecutarFiltroAjax());
    }
    if (tipoSelect) {
        tipoSelect.addEventListener('change', () => ejecutarFiltroAjax());
    }

    if (window.jQuery && $.fn.select2) {
        $('#filtroAgencia, #filtroOficina').on('select2:select select2:clear', function() {
            if (this.id === 'filtroAgencia') {
                filterOficinas();
            }
            ejecutarFiltroAjax();
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            ejecutarFiltroAjax();
        });
    }

    // 6. Paginación AJAX
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#servicios-table-container .pagination a');
        if (link && link.href) {
            e.preventDefault();
            ejecutarFiltroAjax(link.href);
            tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    updateExportUrl();

    // 7. Configuración del Modal Crear
    const modalEl = document.getElementById('modalCrear');
    if (modalEl && modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }

    if (window.jQuery && $.fn.select2) {
        $('#modalCrear').on('shown.bs.modal', function () {
            const $select = $(this).find('select[name="oficina_id"]');
            if ($select.length) {
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                $select.select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#modalCrear'),
                    width: '100%',
                    placeholder: '— Seleccione una oficina —'
                });
            }
        });
    }

    @if($errors->any())
        if (modalEl) {
            const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            bsModal.show();
        }
    @endif
});
</script>
@endpush
@endsection
