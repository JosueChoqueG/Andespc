@extends('layouts.app')

@section('title', 'Gestión de Impresoras')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-printer"></i> Impresoras Registradas</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary shadow-sm btn-modal-impresora"
                    data-bs-toggle="modal" data-bs-target="#modalImpresora"
                    data-url="{{ route('admin.impresoras.create') }}" data-size="modal-lg"
                    title="Nueva Impresora">
                <i class="bi bi-plus-circle"></i>
            </button>
            <a href="{{ route('admin.impresoras.exportar') }}" id="btnExportarExcel" class="btn btn-success shadow-sm" title="Exportar a Excel">
                <i class="bi bi-filetype-xlsx"></i>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <strong><i class="bi bi-list-check"></i> Listado de Impresoras</strong>
                    
                    <!-- Buscador y Filtros Inline -->
                    <form method="GET" action="{{ route('admin.impresoras.index') }}" class="row g-2 align-items-center flex-grow-1 justify-content-end" id="filtros-form">
                        <div class="col-md-3 col-sm-4 col-12 position-relative">
                            <div class="input-group input-group-sm">
                                <input type="text" name="serie" id="inputSerieBusqueda" class="form-control form-control-sm" placeholder="Por serie, marca, modelo, ip..." value="{{ request('serie') ?? request('search') }}" autocomplete="off">
                                <span class="input-group-text bg-white border-start-0 py-0 px-2 d-none" id="search-spinner" style="cursor: default;">
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
                            <select name="estado_impresora" id="filtroEstado" class="form-select form-select-sm">
                                <option value="">Estados</option>
                                <option value="OPTIMO" {{ request('estado_impresora') == 'OPTIMO' ? 'selected' : '' }}>Óptimo</option>
                                <option value="BUENO" {{ request('estado_impresora') == 'BUENO' ? 'selected' : '' }}>Bueno</option>
                                <option value="REGULAR" {{ request('estado_impresora') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                                <option value="DEFICIENTE" {{ request('estado_impresora') == 'DEFICIENTE' ? 'selected' : '' }}>Deficiente</option>
                                <option value="DE BAJA" {{ request('estado_impresora') == 'DE BAJA' ? 'selected' : '' }}>De Baja</option>
                            </select>
                        </div>
                        <div class="col-auto d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center shadow-sm" title="Filtrar">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('admin.impresoras.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center shadow-sm" title="Limpiar Filtros" id="btnLimpiarFiltros">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div id="impresoras-table-container">
                        @include('admin.impresoras.partials.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Genérico para Impresoras --}}
<div class="modal fade" id="modalImpresora" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div id="modalImpresoraContent"></div>
        </div>
    </div>
</div>

<style>
    #autocomplete-dropdown {
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18) !important;
        border-radius: 8px;
    }
    #autocomplete-dropdown .dropdown-item {
        padding: 9px 14px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        background-color: #ffffff !important;
        color: #1e293b !important;
        transition: background-color 0.15s ease;
    }
    #autocomplete-dropdown .dropdown-item:last-child {
        border-bottom: none;
    }
    #autocomplete-dropdown .dropdown-item .fw-semibold {
        color: #0f172a !important;
    }
    #autocomplete-dropdown .dropdown-item .text-muted {
        color: #64748b !important;
    }
    #autocomplete-dropdown .dropdown-item:hover,
    #autocomplete-dropdown .dropdown-item.active,
    #autocomplete-dropdown .dropdown-item:focus {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
    }
    #autocomplete-dropdown .dropdown-item:hover .fw-semibold,
    #autocomplete-dropdown .dropdown-item.active .fw-semibold {
        color: #1d4ed8 !important;
    }
    #autocomplete-dropdown .dropdown-item:hover .text-muted,
    #autocomplete-dropdown .dropdown-item.active .text-muted {
        color: #3b82f6 !important;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Inicialización de Select2
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
    const tableContainer = document.getElementById('impresoras-table-container');
    const btnExportar = document.getElementById('btnExportarExcel');
    const exportBaseUrl = "{{ route('admin.impresoras.exportar') }}";
    const indexUrl = "{{ route('admin.impresoras.index') }}";
    const sugerenciasUrl = "{{ route('admin.impresoras.sugerencias') }}";

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
        if (!btnExportar) return;
        const params = new URLSearchParams(new FormData(form));
        btnExportar.href = exportBaseUrl + '?' + params.toString();
    }

    // 4. Filtrado en vivo de la tabla vía AJAX
    function ejecutarFiltroAjax(pageUrl = null) {
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
            if (!response.ok) throw new Error('Error en respuesta');
            return response.text();
        })
        .then(html => {
            tableContainer.innerHTML = html;
            if (spinner) spinner.classList.add('d-none');
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error('Error al filtrar impresoras:', err);
                if (spinner) spinner.classList.add('d-none');
            }
        });
    }

    // 5. Autocompletado en tiempo real
    function buscarSugerencias(query) {
        if (!query || query.trim().length === 0) {
            cerrarAutocomplete();
            return;
        }

        const params = new URLSearchParams({
            q: query.trim(),
            agencia_id: agenciaSelect ? agenciaSelect.value : '',
            oficina_id: oficinaSelect ? oficinaSelect.value : '',
            estado_impresora: estadoSelect ? estadoSelect.value : ''
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
            console.error('Error en sugerencias:', err);
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
            const highlightResp = item.responsable ? item.responsable.replace(regex, '<mark class="bg-warning px-1 rounded">$1</mark>') : '';

            html += `
                <li class="dropdown-item py-2 px-3 suggestion-item" data-index="${index}" data-value="${escapeHtml(item.value)}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-printer text-primary me-1"></i> ${highlightNombre} 
                                <span class="badge bg-light text-dark border ms-1">${highlightSerie}</span>
                            </div>
                            <div class="small text-muted mt-1">
                                ${item.ip ? '<i class="bi bi-hdd-network me-1"></i>' + item.ip + ' • ' : ''}
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

        autocompleteDropdown.querySelectorAll('.suggestion-item').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                inputBusqueda.value = this.getAttribute('data-value');
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

    // 6. Eventos de búsqueda
    inputBusqueda.addEventListener('input', function() {
        const query = this.value;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            ejecutarFiltroAjax();
        }, 250);

        clearTimeout(autocompleteTimer);
        autocompleteTimer = setTimeout(() => {
            buscarSugerencias(query);
        }, 150);
    });

    inputBusqueda.addEventListener('keydown', function(e) {
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
                inputBusqueda.value = items[selectedSuggestionIndex].getAttribute('data-value');
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

    document.addEventListener('click', function(e) {
        if (!inputBusqueda.contains(e.target) && !autocompleteDropdown.contains(e.target)) {
            cerrarAutocomplete();
        }
    });

    // 7. Selects
    if (oficinaSelect) {
        oficinaSelect.addEventListener('change', () => ejecutarFiltroAjax());
    }
    if (estadoSelect) {
        estadoSelect.addEventListener('change', () => ejecutarFiltroAjax());
    }

    if (window.jQuery && $.fn.select2) {
        $('#filtroAgencia, #filtroOficina').on('select2:select select2:clear', function() {
            if (this.id === 'filtroAgencia') {
                filterOficinas();
            }
            ejecutarFiltroAjax();
        });
    }

    // 8. Form submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        cerrarAutocomplete();
        ejecutarFiltroAjax();
    });

    // 9. Paginación AJAX
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#impresoras-table-container .pagination a');
        if (link && link.href) {
            e.preventDefault();
            ejecutarFiltroAjax(link.href);
            tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    updateExportUrl();

    // ========================================
    // MODAL AJAX - Impresoras (show, edit, mantenimiento, historial)
    // ========================================
    const modalEl = document.getElementById('modalImpresora');
    const modalContent = document.getElementById('modalImpresoraContent');

    if (modalEl && modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
    if (modalEl) {
        modalEl.style.zIndex = '1060';
    }

    function getBootstrapModal(el) {
        if (window.bootstrap && typeof window.bootstrap.Modal !== 'undefined') {
            try {
                return window.bootstrap.Modal.getOrCreateInstance(el);
            } catch (err) {
                console.warn('Error getOrCreateInstance bootstrap modal:', err);
            }
        }
        return null;
    }

    function abrirModal(el, size) {
        const modalDialog = el.querySelector('.modal-dialog');
        if (modalDialog) {
            modalDialog.className = 'modal-dialog ' + (size || 'modal-lg') + ' modal-dialog-centered modal-dialog-scrollable';
        }

        const bs = getBootstrapModal(el);
        if (bs) {
            bs.show();
        } else if (window.jQuery && typeof $(el).modal === 'function') {
            $(el).modal('show');
        } else {
            el.classList.add('show');
            el.style.display = 'block';
            document.body.classList.add('modal-open');
        }
    }

    function cerrarModal(el) {
        const bs = getBootstrapModal(el);
        if (bs) {
            bs.hide();
        } else if (window.jQuery && typeof $(el).modal === 'function') {
            $(el).modal('hide');
        } else {
            el.classList.remove('show');
            el.style.display = 'none';
        }

        setTimeout(() => {
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }, 150);
    }

    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            if (modalContent) modalContent.innerHTML = '';
            if (window.jQuery && $.fn.select2) {
                $(modalEl).find('.modal-select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
            }
        });
    }

    function cargarModalImpresora(btn) {
        const url = btn.getAttribute('data-url');
        if (!url) return;
        const size = btn.getAttribute('data-size') || 'modal-lg';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar: ' + response.status);
            return response.text();
        })
        .then(html => {
            if (modalContent) modalContent.innerHTML = html;
            abrirModal(modalEl, size);

            if (window.jQuery && $.fn.select2) {
                $(modalEl).find('.modal-select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $(modalEl)
                });
            }
        })
        .catch(error => {
            if (modalContent) {
                modalContent.innerHTML = `
                    <div class="modal-header bg-danger text-white py-2 px-3">
                        <h6 class="modal-title small mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Error</h6>
                        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-3">
                        <p class="text-muted small mb-0">No se pudo cargar el contenido. Intente nuevamente.</p>
                    </div>
                `;
            }
            abrirModal(modalEl, 'modal-sm');
        });
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-modal-impresora');
        if (!btn) return;

        e.preventDefault();
        cargarModalImpresora(btn);
    });

    // Envío asíncrono para formularios de modal (Crear y Editar Impresoras)
    document.addEventListener('submit', function (e) {
        const formEl = e.target.closest('#form-crear-impresora-modal, #form-editar-impresora-modal');
        if (!formEl) return;

        e.preventDefault();

        const submitBtn = formEl.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando...';
        }

        const errorDiv = formEl.querySelector('#modal-impresora-errors');
        if (errorDiv) {
            errorDiv.classList.add('d-none');
            errorDiv.innerHTML = '';
        }

        const formData = new FormData(formEl);

        fetch(formEl.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                let errorMsg = 'Error al procesar el formulario.';
                if (data.errors) {
                    const list = Object.values(data.errors).flat().map(err => `<li>${err}</li>`).join('');
                    errorMsg = `<ul class="mb-0 ps-3">${list}</ul>`;
                } else if (data.message) {
                    errorMsg = data.message;
                }
                if (errorDiv) {
                    errorDiv.innerHTML = errorMsg;
                    errorDiv.classList.remove('d-none');
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'Error de validación', html: errorMsg });
                }
                return;
            }

            const bs = getBootstrapModal(modalEl);
            if (bs) bs.hide();
            cerrarModal(modalEl);

            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: data.message || 'Impresora guardada correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            ejecutarFiltroAjax();
        })
        .catch(err => {
            console.error('Error al guardar impresora:', err);
            if (errorDiv) {
                errorDiv.innerHTML = 'Ocurrió un error inesperado al guardar. Intente nuevamente.';
                errorDiv.classList.remove('d-none');
            }
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        });
    });
});
</script>
@endpush
@endsection