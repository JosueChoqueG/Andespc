@extends('layouts.app')

@section('title', 'Gestión de Equipos')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-pc-display"></i> Equipos Registrados</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('equipos.create') }}" class="btn btn-primary shadow-sm" title="Nuevo Equipo">
                <i class="bi bi-plus-circle"></i>
            </a>
            <a href="{{ route('admin.equipos.exportar') }}" id="btnExportarExcel" class="btn btn-success shadow-sm" title="Exportar a Excel">
                <i class="bi bi-filetype-xlsx"></i>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <strong><i class="bi bi-list-check"></i> Listado de Equipos</strong>
                    
                    <!-- Buscador y Filtros Inline -->
                    <form method="GET" action="{{ route('equipos.index') }}" class="row g-2 align-items-center flex-grow-1 justify-content-end" id="filtros-form">
                        <div class="col-md-3 col-sm-4 col-12 position-relative">
                            <div class="input-group input-group-sm">
                                <input type="text" name="serie" id="inputSerieBusqueda" class="form-control form-control-sm" placeholder="Por serie, nombre, marca, ip..." value="{{ request('serie') ?? request('search') }}" autocomplete="off">
                                <span class="input-group-text bg-white border-start-0 py-0 px-2 d-none" id="search-spinner" style="cursor: default;">
                                    <span class="spinner-border spinner-border-sm text-primary" role="status" style="width: 0.85rem; height: 0.85rem;"></span>
                                </span>
                            </div>
                            <!-- Dropdown de autocompletado flotante -->
                            <ul id="autocomplete-dropdown" class="dropdown-menu shadow-lg w-100 mt-1 py-1 border border-light" style="display: none; position: absolute; top: 100%; left: 0; z-index: 1055; max-height: 300px; overflow-y: auto; border-radius: 8px;"></ul>
                        </div>
                        <div class="col-md-2 col-sm-4 col-12">
                            <select name="agencia" id="filtroAgencia" class="form-select form-select-sm select2">
                                <option value="">Agencias</option>
                                @foreach($agencias ?? [] as $agencia)
                                    <option value="{{ $agencia->id }}" {{ (request('agencia') == $agencia->id || request('agencia_id') == $agencia->id) ? 'selected' : '' }}>
                                        {{ $agencia->nombre_agencia }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-4 col-12">
                            <select name="oficina" id="filtroOficina" class="form-select form-select-sm select2">
                                <option value="">Oficinas</option>
                                @foreach($oficinas ?? [] as $oficina)
                                    <option value="{{ $oficina->id }}" 
                                        data-agencia="{{ $oficina->agencia_id }}"
                                        {{ (request('oficina') == $oficina->id || request('oficina_id') == $oficina->id) ? 'selected' : '' }}>
                                        {{ $oficina->nombre_oficina }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-4 col-12">
                            <select name="estado_equipo" id="filtroEstado" class="form-select form-select-sm">
                                <option value="">Estados</option>
                                <option value="Operativo" {{ request('estado_equipo') == 'Operativo' ? 'selected' : '' }}>Operativo</option>
                                <option value="Operativo con Observacion" {{ request('estado_equipo') == 'Operativo con Observacion' ? 'selected' : '' }}>Operativo con Observación</option>
                                <option value="En mantenimiento" {{ request('estado_equipo') == 'En mantenimiento' ? 'selected' : '' }}>En mantenimiento</option>
                                <option value="Fuera de servicio" {{ request('estado_equipo') == 'Fuera de servicio' ? 'selected' : '' }}>Fuera de servicio</option>
                                <option value="De baja" {{ request('estado_equipo') == 'De baja' ? 'selected' : '' }}>De baja</option>
                            </select>
                        </div>
                        <div class="col-auto d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center shadow-sm" title="Filtrar">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('equipos.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center shadow-sm" title="Limpiar Filtros" id="btnLimpiarFiltros">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div id="equipos-table-container">
                        @include('admin.equipos.partials.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos para el autocompletado */
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
        background-color: #f0f7ff;
        color: #0d6efd;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Inicialización de Select2 si está disponible
    if (window.jQuery && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true
        });
    }

    const form = document.getElementById('filtros-form');
    const inputBusqueda = document.getElementById('inputSerieBusqueda');
    const agenciaSelect = document.getElementById('filtroAgencia');
    const oficinaSelect = document.getElementById('filtroOficina');
    const estadoSelect = document.getElementById('filtroEstado');
    const spinner = document.getElementById('search-spinner');
    const autocompleteDropdown = document.getElementById('autocomplete-dropdown');
    const tableContainer = document.getElementById('equipos-table-container');
    const btnExportar = document.getElementById('btnExportarExcel');
    const exportBaseUrl = "{{ route('admin.equipos.exportar') }}";
    const indexUrl = "{{ route('equipos.index') }}";
    const sugerenciasUrl = "{{ route('equipos.sugerencias') }}";

    const allOficinaOptions = Array.from(oficinaSelect.options);
    let debounceTimer = null;
    let autocompleteTimer = null;
    let activeAbortController = null;
    let selectedSuggestionIndex = -1;

    // 2. Cascada Agencias -> Oficinas
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
        agenciaSelect.addEventListener('change', function () {
            filterOficinas();
            ejecutarFiltroAjax();
        });
        if (agenciaSelect.value) {
            filterOficinas();
            const savedOficinaId = "{{ request('oficina') ?? request('oficina_id') }}";
            if (savedOficinaId) {
                oficinaSelect.value = savedOficinaId;
            }
        }
    }

    // 3. Actualizar enlace del botón Exportar a Excel con los filtros actuales
    function updateExportUrl() {
        if (!btnExportar) return;
        const params = new URLSearchParams(new FormData(form));
        btnExportar.href = exportBaseUrl + '?' + params.toString();
    }

    // 4. Filtrado en vivo de la tabla vía AJAX
    function ejecutarFiltroAjax(pageUrl = null) {
        if (spinner) spinner.classList.remove('d-none');

        // Cancelar petición previa si estuviera en vuelo
        if (activeAbortController) {
            activeAbortController.abort();
        }
        activeAbortController = new AbortController();

        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        let fetchUrl = pageUrl ? pageUrl : (indexUrl + '?' + params.toString());

        // Actualizar URL del navegador sin recargar la página
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
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            return response.text();
        })
        .then(html => {
            tableContainer.innerHTML = html;
            if (spinner) spinner.classList.add('d-none');
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error('Error al filtrar equipos:', err);
                if (spinner) spinner.classList.add('d-none');
            }
        });
    }

    // 5. Autocompletado mientras se digita
    function buscarSugerencias(query) {
        if (!query || query.trim().length === 0) {
            cerrarAutocomplete();
            return;
        }

        const params = new URLSearchParams({
            q: query.trim(),
            agencia: agenciaSelect ? agenciaSelect.value : '',
            oficina: oficinaSelect ? oficinaSelect.value : '',
            estado_equipo: estadoSelect ? estadoSelect.value : ''
        });

        fetch(sugerenciasUrl + '?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            renderSugerencias(data, query.trim());
        })
        .catch(err => {
            console.error('Error cargando sugerencias:', err);
            cerrarAutocomplete();
        });
    }

    function renderSugerencias(items, query) {
        if (!items || items.length === 0) {
            cerrarAutocomplete();
            return;
        }

        selectedSuggestionIndex = -1;
        let html = '';
        const regex = new RegExp('(' + escapeRegex(query) + ')', 'gi');

        items.forEach((item, index) => {
            const highlightSerie = item.serie ? item.serie.replace(regex, '<mark class="bg-warning px-1 rounded">$1</mark>') : 'S/N';
            const highlightNombre = item.nombre ? item.nombre.replace(regex, '<mark class="bg-warning px-1 rounded">$1</mark>') : '';
            const highlightMarca = item.marcaModelo ? item.marcaModelo.replace(regex, '<mark class="bg-warning px-1 rounded">$1</mark>') : '';
            const highlightResp = item.responsable ? item.responsable.replace(regex, '<mark class="bg-warning px-1 rounded">$1</mark>') : '';

            html += `
                <li class="dropdown-item py-2 px-3 suggestion-item" data-index="${index}" data-value="${escapeHtml(item.value)}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-pc-display text-primary me-1"></i> ${highlightNombre} 
                                <span class="badge bg-light text-dark border ms-1">${highlightSerie}</span>
                            </div>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-tag me-1"></i>${highlightMarca || 'Sin modelo'} • 
                                <i class="bi bi-person me-1"></i>${highlightResp} • 
                                <i class="bi bi-geo-alt me-1"></i>${item.oficina}
                            </div>
                        </div>
                        <div>
                            <span class="badge bg-secondary opacity-75">${item.estado || ''}</span>
                        </div>
                    </div>
                </li>
            `;
        });

        autocompleteDropdown.innerHTML = html;
        autocompleteDropdown.style.display = 'block';

        // Eventos click en cada sugerencia
        autocompleteDropdown.querySelectorAll('.suggestion-item').forEach(el => {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                const val = this.getAttribute('data-value');
                inputBusqueda.value = val;
                cerrarAutocomplete();
                ejecutarFiltroAjax();
            });
        });
    }

    function cerrarAutocomplete() {
        if (autocompleteDropdown) {
            autocompleteDropdown.style.display = 'none';
            autocompleteDropdown.innerHTML = '';
            selectedSuggestionIndex = -1;
        }
    }

    function escapeRegex(string) {
        return string.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    }

    function escapeHtml(string) {
        return String(string || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // 6. Eventos en el input de búsqueda (escritura en tiempo real)
    inputBusqueda.addEventListener('input', function () {
        const query = this.value;

        // Debounce para filtrado de la tabla (250ms)
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            ejecutarFiltroAjax();
        }, 250);

        // Debounce para sugerencias de autocompletado (150ms)
        clearTimeout(autocompleteTimer);
        autocompleteTimer = setTimeout(() => {
            buscarSugerencias(query);
        }, 150);
    });

    // Navegación con teclado en el autocompletado
    inputBusqueda.addEventListener('keydown', function (e) {
        const items = autocompleteDropdown.querySelectorAll('.suggestion-item');
        if (!items || items.length === 0 || autocompleteDropdown.style.display === 'none') return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedSuggestionIndex = (selectedSuggestionIndex + 1) % items.length;
            actualizarSeleccion(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedSuggestionIndex = (selectedSuggestionIndex - 1 + items.length) % items.length;
            actualizarSeleccion(items);
        } else if (e.key === 'Enter') {
            if (selectedSuggestionIndex >= 0 && items[selectedSuggestionIndex]) {
                e.preventDefault();
                const val = items[selectedSuggestionIndex].getAttribute('data-value');
                inputBusqueda.value = val;
                cerrarAutocomplete();
                ejecutarFiltroAjax();
            }
        } else if (e.key === 'Escape') {
            cerrarAutocomplete();
        }
    });

    function actualizarSeleccion(items) {
        items.forEach((item, idx) => {
            if (idx === selectedSuggestionIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    // Cerrar autocompletado al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (!inputBusqueda.contains(e.target) && !autocompleteDropdown.contains(e.target)) {
            cerrarAutocomplete();
        }
    });

    // 7. Eventos en selects de filtros
    if (oficinaSelect) {
        oficinaSelect.addEventListener('change', () => ejecutarFiltroAjax());
    }
    if (estadoSelect) {
        estadoSelect.addEventListener('change', () => ejecutarFiltroAjax());
    }

    // Select2 triggers
    if (window.jQuery && $.fn.select2) {
        $('#filtroAgencia, #filtroOficina').on('select2:select select2:clear', function () {
            if (this.id === 'filtroAgencia') {
                filterOficinas();
            }
            ejecutarFiltroAjax();
        });
    }

    // 8. Enviar formulario (Enter o botón Filtrar)
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        cerrarAutocomplete();
        ejecutarFiltroAjax();
    });

    // 9. Paginación AJAX dinámica
    document.addEventListener('click', function (e) {
        const link = e.target.closest('#equipos-table-container .pagination a');
        if (link && link.href) {
            e.preventDefault();
            ejecutarFiltroAjax(link.href);
            // Scroll suave hacia la tabla
            tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    // Inicializar URL del exportar
    updateExportUrl();
});
</script>
@endpush
@endsection