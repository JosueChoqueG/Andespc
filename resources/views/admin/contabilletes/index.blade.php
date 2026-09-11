@extends('layouts.app')

@section('title', 'Gestión de Contadoras de Billetes')

@push('styles')
<style>
    #autocomplete-dropdown .dropdown-item {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    #autocomplete-dropdown .dropdown-item:last-child {
        border-bottom: none;
    }
    #autocomplete-dropdown .dropdown-item:hover,
    #autocomplete-dropdown .dropdown-item.active,
    #autocomplete-dropdown .dropdown-item:focus {
        background-color: #fef9c3;
        color: #854d0e;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Contadoras de Billetes Registradas</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.contabilletes.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle"></i>
            </a>
            <a href="{{ route('admin.contabilletes.exportar') }}" id="btnExportar" class="btn btn-success shadow-sm">
                <i class="bi bi-filetype-xlsx"></i>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <strong><i class="bi bi-list-check"></i> Listado de Contadoras</strong>
                    
                    <!-- Buscador y Filtros Inline -->
                    <form id="filter-form" class="row g-2 align-items-center flex-grow-1 justify-content-end">
                        <div class="col-md-3 col-sm-4 col-12 position-relative">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Buscar por serie, marca, modelo, oficina..." value="{{ request('search') }}" autocomplete="off">
                                <span class="input-group-text bg-white border-start-0 py-0 px-2 d-none" id="searchSpinner" style="cursor: default;">
                                    <span class="spinner-border spinner-border-sm text-primary" role="status" style="width: 0.85rem; height: 0.85rem;"></span>
                                </span>
                            </div>
                            <!-- Dropdown de autocompletado flotante -->
                            <ul id="autocomplete-dropdown" class="dropdown-menu shadow-lg w-100 mt-1 py-1 border border-light" style="display: none; position: absolute; top: 100%; left: 0; z-index: 1055; max-height: 300px; overflow-y: auto; border-radius: 8px;"></ul>
                        </div>
                        <div class="col-md-2 col-sm-4 col-12">
                            <select name="agencia_id" id="filtroAgencia" class="form-select form-select-sm select2">
                                <option value="">Agencias</option>
                                @foreach($agencias ?? [] as $agencia)
                                    <option value="{{ $agencia?->id }}" {{ request('agencia_id') == $agencia?->id ? 'selected' : '' }}>
                                        {{ $agencia?->nombre_agencia }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-4 col-12">
                            <select name="oficina_id" id="filtroOficina" class="form-select form-select-sm select2">
                                <option value="">Oficinas</option>
                                @foreach($oficinas ?? [] as $oficina)
                                    <option value="{{ $oficina?->id }}" 
                                        data-agencia="{{ $oficina?->agencia_id }}"
                                        {{ request('oficina_id') == $oficina?->id ? 'selected' : '' }}>
                                        {{ $oficina?->nombre_oficina }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-4 col-12">
                            <select name="estado_contabilletes" id="filtroEstado" class="form-select form-select-sm">
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
                    <div id="contabilletes-table-container">
                        @include('admin.contabilletes.partials.table')
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
        if (window.jQuery && $(agenciaSelect).data('select2')) {
            $(agenciaSelect).on('change', filterOficinas);
        } else {
            agenciaSelect.addEventListener('change', filterOficinas);
        }
        
        if (agenciaSelect.value) {
            filterOficinas();
            const savedOficinaId = "{{ request('oficina_id') }}";
            if (savedOficinaId) {
                oficinaSelect.value = savedOficinaId;
                if (window.jQuery && $(oficinaSelect).data('select2')) {
                    $(oficinaSelect).trigger('change.select2');
                }
            }
        }
    }

    // Lógica para Live Search y Autocomplete
    const searchInput = document.getElementById('searchInput');
    const searchSpinner = document.getElementById('searchSpinner');
    const tableContainer = document.getElementById('contabilletes-table-container');
    const form = document.getElementById('filter-form');
    const btnExportar = document.getElementById('btnExportar');
    const autocompleteDropdown = document.getElementById('autocomplete-dropdown');
    const filterEstado = document.getElementById('filtroEstado');

    let debounceTimer;
    let autocompleteTimer;
    let currentAbortController = null;

    function escapeRegex(string) {
        return string.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    }

    function escapeHtml(string) {
        return String(string || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function getFormData() {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        return params.toString();
    }

    function updateExportUrl() {
        if(btnExportar) {
            const baseUrl = "{{ route('admin.contabilletes.exportar') }}";
            btnExportar.href = baseUrl + '?' + getFormData();
        }
    }

    function fetchTableData() {
        searchSpinner.classList.remove('d-none');
        
        if (currentAbortController) {
            currentAbortController.abort();
        }
        currentAbortController = new AbortController();

        const queryString = getFormData();
        const url = "{{ route('admin.contabilletes.index') }}?" + queryString;

        updateExportUrl();

        window.history.pushState({}, '', url);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: currentAbortController.signal
        })
        .then(response => response.text())
        .then(html => {
            tableContainer.innerHTML = html;
            searchSpinner.classList.add('d-none');
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('Error:', error);
                searchSpinner.classList.add('d-none');
            }
        });
    }

    function fetchAutocomplete(query) {
        if (!query) {
            autocompleteDropdown.style.display = 'none';
            return;
        }

        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        params.set('q', query);
        params.delete('search');

        fetch("{{ route('admin.contabilletes.sugerencias') }}?" + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            autocompleteDropdown.innerHTML = '';
            if (data && data.length > 0) {
                let html = '';
                const regex = new RegExp('(' + escapeRegex(query) + ')', 'gi');
                
                data.forEach((item, index) => {
                    const highlightSerie = item.serie ? item.serie.replace(regex, '<mark class="bg-warning px-1 rounded">$1</mark>') : 'S/N';
                    const highlightNombre = item.nombre ? item.nombre.replace(regex, '<mark class="bg-warning px-1 rounded">$1</mark>') : '';
                    const highlightResp = item.responsable ? item.responsable.replace(regex, '<mark class="bg-warning px-1 rounded">$1</mark>') : '';

                    const badgeColor = {
                        'OPTIMO': 'success',
                        'BUENO': 'info',
                        'REGULAR': 'warning',
                        'DEFICIENTE': 'danger',
                        'DE BAJA': 'secondary'
                    }[item.estado] || 'dark';

                    html += `
                        <li class="dropdown-item py-2 px-3 suggestion-item" data-value="${escapeHtml(item.value)}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold text-dark">
                                        <i class="bi bi-cash-coin text-warning me-1"></i> ${highlightNombre} 
                                        <span class="badge bg-light text-dark border ms-1">${highlightSerie}</span>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <i class="bi bi-person me-1"></i>${highlightResp} • 
                                        <i class="bi bi-geo-alt me-1"></i>${item.oficina}
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-${badgeColor} opacity-75">${item.estado || ''}</span>
                                </div>
                            </div>
                        </li>
                    `;
                });
                
                autocompleteDropdown.innerHTML = html;
                autocompleteDropdown.style.display = 'block';
                
                autocompleteDropdown.querySelectorAll('.suggestion-item').forEach(el => {
                    el.addEventListener('click', function(e) {
                        e.preventDefault();
                        searchInput.value = this.getAttribute('data-value');
                        autocompleteDropdown.style.display = 'none';
                        fetchTableData();
                    });
                });
            } else {
                autocompleteDropdown.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', function() {
        const val = this.value.trim();
        
        clearTimeout(autocompleteTimer);
        autocompleteTimer = setTimeout(() => {
            fetchAutocomplete(val);
        }, 150);

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchTableData();
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!autocompleteDropdown.contains(e.target) && e.target !== searchInput) {
            autocompleteDropdown.style.display = 'none';
        }
    });

    // Handle form submit (prevent default and fetch via AJAX)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        autocompleteDropdown.style.display = 'none';
        fetchTableData();
    });

    // Handle changes in filters
    [filterEstado].forEach(el => {
        if(el) {
            el.addEventListener('change', () => {
                autocompleteDropdown.style.display = 'none';
                fetchTableData();
            });
        }
    });

    if (window.jQuery) {
        $(agenciaSelect).on('change', function() {
            fetchTableData();
        });
        $(oficinaSelect).on('change', function() {
            fetchTableData();
        });
    } else {
        agenciaSelect.addEventListener('change', fetchTableData);
        oficinaSelect.addEventListener('change', fetchTableData);
    }

    // Pagination AJAX
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('.pagination a').href;
            
            searchSpinner.classList.remove('d-none');
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                tableContainer.innerHTML = html;
                searchSpinner.classList.add('d-none');
                
                // Actualizar URL y Exportar URL
                window.history.pushState({}, '', url);
                updateExportUrl();
            })
            .catch(error => {
                console.error('Error paginating:', error);
                searchSpinner.classList.add('d-none');
            });
        }
    });

    // Initialize export URL
    updateExportUrl();
});
</script>
@endpush
@endsection

