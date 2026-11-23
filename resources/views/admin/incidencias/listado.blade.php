@extends('layouts.app')

@section('title', 'Bitácora de Incidencias TI')

@section('content')
<div class="container-fluid">
    {{-- Encabezado Principal --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 text-dark fw-bold">
                <i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Bitácora de Incidencias & Soporte TI
            </h4>
            <span class="text-muted" style="font-size: 0.9rem;">
                Control, seguimiento y registro de tickets de soporte técnico corporativo
            </span>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary shadow-sm px-3 btn-modal-incidencia"
                    data-url="{{ route('admin.incidencias.create') }}"
                    data-size="modal-lg"
                    title="Nueva Incidencia">
                <i class="bi bi-plus-circle me-1"></i> Nueva Incidencia
            </button>
            <a href="{{ route('admin.incidencias.exportar', request()->query()) }}" id="btnExportarExcel" class="btn btn-success shadow-sm px-3" title="Exportar Bitácora a Excel">
                <i class="bi bi-filetype-xlsx me-1"></i> Exportar Bitácora
            </a>
        </div>
    </div>

    {{-- Métricas & KPIs de la Bitácora (Compactos y Clickeables) --}}
    <div class="row g-2.5 mb-3">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-2.5 cursor-pointer kpi-filter-card" 
                 style="border-left: 4px solid #0d6efd !important; cursor: pointer;" 
                 onclick="filtrarPorEstadoKpi('')" title="Click para ver todas las incidencias">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">TOTAL REGISTRADAS</span>
                        <h4 class="mb-0 fw-bold text-dark mt-0.5 font-outfit">{{ number_format($totalGeneral) }}</h4>
                        <span class="text-muted" style="font-size: 0.72rem;">Historial en Bitácora</span>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-journal-text fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-2.5 cursor-pointer kpi-filter-card" 
                 style="border-left: 4px solid #198754 !important; cursor: pointer;" 
                 onclick="filtrarPorEstadoKpi('Atendido')" title="Click para filtrar atendidas">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">ATENDIDAS CON ÉXITO</span>
                        <h4 class="mb-0 fw-bold text-success mt-0.5 font-outfit">{{ number_format($totalAtendidos) }}</h4>
                        <span class="text-success fw-semibold" style="font-size: 0.72rem;">
                            <i class="bi bi-check-circle me-1"></i>{{ $porcentajeResolucion }}% de resolución
                        </span>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-check2-all fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-2.5 cursor-pointer kpi-filter-card" 
                 style="border-left: 4px solid #dc3545 !important; cursor: pointer;" 
                 onclick="filtrarPorEstadoKpi('Pendiente')" title="Click para filtrar pendientes">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">PENDIENTES DE ATENCIÓN</span>
                        <h4 class="mb-0 fw-bold text-danger mt-0.5 font-outfit">{{ number_format($totalPendientes) }}</h4>
                        <span class="text-danger fw-semibold" style="font-size: 0.72rem;">
                            @if($totalPendientes > 0)
                                <i class="bi bi-exclamation-circle-fill"></i> Requieren atención
                            @else
                                <i class="bi bi-check2"></i> Al día
                            @endif
                        </span>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-2.5 cursor-pointer kpi-filter-card" 
                 style="border-left: 4px solid #ffc107 !important; cursor: pointer;" 
                 onclick="filtrarPorEstadoKpi('Derivado')" title="Click para filtrar derivadas">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">DERIVADAS / EN PROCESO</span>
                        <h4 class="mb-0 fw-bold text-warning mt-0.5 font-outfit">{{ number_format($totalDerivados) }}</h4>
                        <span class="text-muted" style="font-size: 0.72rem;">Terceros o Desarrollo</span>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-arrow-right-circle fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros y Búsqueda Automatizada en Vivo --}}
    <div class="card shadow-sm border-0 rounded-3 mb-3">
        <div class="card-header bg-light border-bottom py-2.5 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="fw-bold text-dark" style="font-size: 0.95rem;">
                    <i class="bi bi-funnel me-1 text-primary"></i> Filtros de Bitácora (En tiempo real)
                </span>
                <div class="d-flex align-items-center gap-2">
                    <span class="spinner-border spinner-border-sm text-primary d-none" id="search-spinner" role="status"></span>
                    <span class="text-muted small">
                        Total en Bitácora: <strong>{{ number_format($totalGeneral) }}</strong> tickets
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.incidencias.listado') }}" id="filtros-form">
                <div class="row g-2.5 align-items-end">
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Búsqueda rápida en vivo</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" id="inputBusqueda" class="form-control form-control-sm border-start-0" 
                                   placeholder="Nº ticket, usuario, falla, sede..." 
                                   value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Estado</label>
                        <select name="estado" id="filtroEstado" class="form-select form-select-sm auto-filtro">
                            <option value="">Todos los Estados</option>
                            <option value="Atendido" {{ request('estado') === 'Atendido' ? 'selected' : '' }}>Atendido</option>
                            <option value="Pendiente" {{ request('estado') === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Derivado" {{ request('estado') === 'Derivado' ? 'selected' : '' }}>Derivado</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Prioridad</label>
                        <select name="prioridad" id="filtroPrioridad" class="form-select form-select-sm auto-filtro">
                            <option value="">Todas las Prioridades</option>
                            <option value="Alta" {{ request('prioridad') === 'Alta' ? 'selected' : '' }}>Alta</option>
                            <option value="Media" {{ request('prioridad') === 'Media' ? 'selected' : '' }}>Media</option>
                            <option value="Baja" {{ request('prioridad') === 'Baja' ? 'selected' : '' }}>Baja</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Tipo Asistencia</label>
                        <select name="tipo" id="filtroTipo" class="form-select form-select-sm auto-filtro">
                            <option value="">Todos los Tipos</option>
                            <option value="software" {{ request('tipo') === 'software' ? 'selected' : '' }}>Software</option>
                            <option value="hardware" {{ request('tipo') === 'hardware' ? 'selected' : '' }}>Hardware</option>
                            <option value="conectividad" {{ request('tipo') === 'conectividad' ? 'selected' : '' }}>Conectividad</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Agencia / Sede</label>
                        <select name="agencia" id="filtroAgencia" class="form-select form-select-sm select2">
                            <option value="">Todas las Sedes</option>
                            @foreach($agenciasList as $ag)
                                <option value="{{ $ag }}" {{ request('agencia') === $ag ? 'selected' : '' }}>{{ $ag }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Fila secundaria de fechas y paginación --}}
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Desde fecha</label>
                        <input type="date" name="fecha_desde" id="filtroFechaDesde" class="form-control form-control-sm auto-filtro" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Hasta fecha</label>
                        <input type="date" name="fecha_hasta" id="filtroFechaHasta" class="form-control form-control-sm auto-filtro" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label form-label-sm mb-1 fw-semibold text-muted">Por página</label>
                        <select name="per_page" id="filtroPerPage" class="form-select form-select-sm auto-filtro">
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 tickets</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 tickets</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 tickets</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm flex-grow-1 shadow-sm">
                            <i class="bi bi-search me-1"></i> Filtrar
                        </button>
                        <a href="{{ route('admin.incidencias.listado') }}" class="btn btn-outline-secondary btn-sm px-3 shadow-sm" title="Limpiar Filtros" id="btnLimpiarFiltros">
                            <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Contenedor de la Tabla Bitácora (actualizable por AJAX) --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div id="incidencias-table-container">
            @include('admin.incidencias.partials.table')
        </div>
    </div>
</div>

{{-- Modal Genérico para Incidencias (Create, Show & Edit) --}}
<div class="modal fade" id="modalIncidencia" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 980px;">
        <div class="modal-content border-0 shadow-lg">
            <div id="modalIncidenciaContent"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Diccionario de problemas y sub-agencias
    const problemasData = { 
        software: [
            "USUARIO SFI BLOQUEADO", "USUARIO SE OLVIDO LA CONTRASEÑA FINANCIAL", "DESCONFIGURACION FINANCIAL",
            "ERROR DE INCIO SESION FINANCIAL", "ERROR DE FUNCIONALIDAD FINANCIAL", "PARAMETROS DE SEGURIDAD FINANCIAL",
            "USUARIO FINANCIAL", "CAIDA DEL SERVICIO FINANCIAL", "NO ACTUALIZA FINANCIAL", "USUARIO SIEPA BLOQUEADO",
            "USUARIO SE OLVIDO LA CONTRASEÑA SIEPA", "ERROR DE INICIO SESION SIEPA", "ERROR DE FUNCIONALIDAD SIEPA",
            "CAIDA DEL SERVICIO SIEPA", "REPORTEADOR", "USUARIO BLOQUEADO SENTINEL", "USUARIO SE OLVIDO CONTRASEÑA SENTINEL",
            "ERROR DE INICIO SESION CARPETA COMPARTIDA", "ERROR AL CARGAR BAUCHER DIGITALIZADO", "USUARIO CORREO BLOQUEADO",
            "USUARIO SE OLVIDO CONTRASEÑA CORREO", "FIRMA DE USUARIO SIN CONFIGURAR EN CORREO", "ALMACENAMIENTO DE CORREO",
            "ERROR DE FUNCIONALIDAD CORREO", "ERROR DE INICIO SESION CORREO", "ERROR DE ACTIVCACION ANTIVIRUS ESET",
            "USUARIO CLOUD BLOQUEADO", "USUARIO SE OLVIDO CONTRASEÑA CLOUD", "ERROR DE FUNCIONALIDAD CLOUD",
            "ERROR DE INICIO SESION CLOUD", "NAVEGADOR DE INTERNET", "ANYDESK", "WINDOWS", "ANDROID"
        ],
        hardware: [
            "TECLADO", "IMPRESORA", "MINI IMPRESORA", "ERROR DE ESCANEO IMPRESORA", "ERROR DE COMPONENTE EQUIPO COMPUTO",
            "ERROR DE CONFIGURACION BIOMETRICO", "ERROR DE CONFIGURACION TOTEM", "IMPRESORA TERMICA",
            "ESCANEADOR DE VOUCHERS", "ALMACENAMIENTO LLENO", "PUERTO USB BLOQUEADO", "LECTOR DE HUELLAS", "CELULAR RPC"
        ],
        conectividad: [
            "DESCONFIGURACION DE TOTEM", "CERTIFICADO VPN", "CONECTIVIDAD RED", "CONFIGURACION DE VPN",
            "CONECTIVIDAD GENERAL", "ENERGÍA ELÉCTRICA", "ACCESO A INTERNET", "INTALACION/ERROR DE ANTIVIRUS",
            "CARPETAS COMPARTIDAS", "CLOUD LA NUBE"
        ]
    };

    const subAgenciasData = {
        "Empresas de coopac": ['Ayni Andes', 'Incoop','Servisur Andino' ],
        "99 Soporte Administrativo": ['TI', 'Operaciones', 'Contabilidad', 'Logística', 'Comunicaciones','Asesoría Legal','Riesgos','Procesos','Planificación','Tesorería','Subgerencia', 'Servicios Financieros', 'Talento Humano','Servicios Complementarios','Auditoría Interna','Oficial de Cumplimiento'],
        "101 Chalhuanca": ['Chalhuanca', 'Huancapampa','Cotaruse', 'Chacapuente', 'Socco', 'Pachaconas', 'Tampumayu'],
        "102 Andahuaylas": ['Andahuaylas','Talavera','Champacocha','Pacucha', 'Andarapa','Kaquiabamba', 'Soras','Pampachiri','Jose María Arguedas','Turpo','Huancaray'],
        "103 Uripa": ['Uripa', 'Ahuayro', 'Cocharcas', 'Uranmarca', 'Chumbes' ,'Ranracancha'],
        "104 Antabamba": ['Antabamba', 'Mollebamba', 'Huaquirca'],
        "105 Tintay": ['Tintay', 'Casinchihua'],
        "106 Huaccana": ['Huaccana','Chungui','Rocchacc','Ocobamba'],
        "107 Lima": ['San Isidro'],
        "108 Huancarama": ['Huancarama','Pacobamba','Kishuara','Matapuquio','San Fernando','San Martin','Amaybamba','Pucyura'],
        "109 Abancay": ['Abancay','Huanipaca','Tacmara'],
        "110 Grau": ['Chuquibambilla','Vilcabamba','Totora Oropesa'],
        "111 Cotabambas": ['Challhuahuacho','Haquira','Mara','Tambobamba','Cotabambas','Coyllurqui','Progreso'],
        "112 Curahuasi": ['Curahuasi','Cachora','Limatambo','Mollepata'],
        "113 Secclla": ['Secclla','Congalla','Lircay','Acobamba','Paucará'],
        "114 Cusco": ['Cusco','Ocongate','Pillcopata','Anta'],
        "115 Santo Tomás": ['Santo Tomás','Velille','Pulpera','Colquemarca','Quiñota'],
        "116 Pampa Cangallo": ['Pampa Cangallo','Vilcas Huamán','Chuschi','Huanca Sancos','Huancapi'],
        "117 Huamanga": ['Huamanga','Tambo La Mar','Tambillo','Acos Vinchos','Vinchos','Pampamarca','Ccayarpachi'],
        "118 Pampas Tayacaja": ['Pampas Tayacaja','Paucarbamba','Colcabamba','Inyac'],
        "119 Huancayo": ['Huancayo','Lampa','S.M. de Rocchacc','Pichus'],
        "120 Urubamba": ['Urubamba','Chinchero','Calca'],
        "121 Combapata": ['Combapata','Pomacanchi','Acomayo', 'Accha'],
        "122 La Merced": ['La Merced','Yurinaki'],
        "123 San Juan de Miraflores": ['S.J. Miraflores'],
        "124 San Juan de Lurigancho": ['S.J. de Lurigancho'],
        "125 Nueva Esperanza": ['Nueva Esperanza'],
        "126 Ocampo": ['Ocampo'],
        "127 Cañete": ['Cañete']
    };

    // 1. Inicializar Select2 en filtros
    if (window.jQuery && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true
        });
    }

    // 2. Control de Modal y Prevención de Pantalla Congelada / Backdrops Huérfanos
    const modalEl = document.getElementById('modalIncidencia');
    const modalContent = document.getElementById('modalIncidenciaContent');

    // Mover modal al body para evitar problemas de stacking context
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
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        });
    }

    function vincularCascadasModal() {
        const createTipo = document.getElementById('modal-create-tipo');
        const createProblema = document.getElementById('modal-create-problema');
        if (createTipo && createProblema) {
            createTipo.addEventListener('change', function() {
                const tipo = this.value;
                createProblema.innerHTML = '<option value="">— Seleccione Problema —</option>';
                if (problemasData[tipo]) {
                    problemasData[tipo].forEach(prob => {
                        const opt = document.createElement('option');
                        opt.value = prob;
                        opt.textContent = prob;
                        createProblema.appendChild(opt);
                    });
                }
            });
        }

        const createAgencia = document.getElementById('modal-create-agencia');
        const createSubAgencia = document.getElementById('modal-create-subagencia');
        if (createAgencia && createSubAgencia) {
            createAgencia.addEventListener('change', function() {
                const agencia = this.value;
                createSubAgencia.innerHTML = '<option value="">— Seleccione Sub Agencia —</option>';
                if (subAgenciasData[agencia]) {
                    subAgenciasData[agencia].forEach(sub => {
                        const opt = document.createElement('option');
                        opt.value = sub;
                        opt.textContent = sub;
                        createSubAgencia.appendChild(opt);
                    });
                }
            });
        }
    }

    // 3. Cargar contenido en Modal por AJAX
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-modal-incidencia');
        if (!btn) return;
        e.preventDefault();

        const url = btn.getAttribute('data-url');
        if (!url) return;
        const size = btn.getAttribute('data-size') || 'modal-lg';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar: ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data.html && modalContent) {
                modalContent.innerHTML = data.html;
                abrirModal(modalEl, size);
                vincularCascadasModal();
            }
        })
        .catch(err => {
            console.error(err);
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
    });

    // 4. Filtros Automatizados en Vivo (Similar a Equipos)
    const form = document.getElementById('filtros-form');
    const inputBusqueda = document.getElementById('inputBusqueda');
    const spinner = document.getElementById('search-spinner');
    const tableContainer = document.getElementById('incidencias-table-container');
    const btnExportar = document.getElementById('btnExportarExcel');
    const exportBaseUrl = "{{ route('admin.incidencias.exportar') }}";
    const indexUrl = "{{ route('admin.incidencias.listado') }}";

    let debounceTimer = null;
    let activeAbortController = null;

    function updateExportUrl() {
        if (!btnExportar || !form) return;
        const params = new URLSearchParams(new FormData(form));
        btnExportar.href = exportBaseUrl + '?' + params.toString();
    }

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
            if (!response.ok) throw new Error('Error al filtrar');
            return response.text();
        })
        .then(html => {
            tableContainer.innerHTML = html;
            if (spinner) spinner.classList.add('d-none');
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error(err);
                if (spinner) spinner.classList.add('d-none');
            }
        });
    }

    if (form) {
        form.querySelectorAll('.auto-filtro').forEach(el => {
            el.addEventListener('change', () => ejecutarFiltroAjax());
        });

        $('#filtroAgencia').on('change', function() {
            ejecutarFiltroAjax();
        });

        if (inputBusqueda) {
            inputBusqueda.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    ejecutarFiltroAjax();
                }, 350);
            });
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            ejecutarFiltroAjax();
        });
    }

    // Paginación vía AJAX
    $(document).on('click', '#incidencias-table-container .pagination a', function(e) {
        e.preventDefault();
        const pageUrl = $(this).attr('href');
        if (pageUrl) {
            ejecutarFiltroAjax(pageUrl);
            window.scrollTo({ top: tableContainer.offsetTop - 80, behavior: 'smooth' });
        }
    });

    // 5. Envío Asíncrono de Formularios de Modal (Crear y Editar Incidencias)
    document.addEventListener('submit', function(e) {
        const formEl = e.target.closest('#form-crear-incidencia-modal, #form-editar-incidencia-modal');
        if (!formEl) return;
        e.preventDefault();

        const submitBtn = formEl.querySelector('button[type="submit"]');
        const origBtnHtml = submitBtn ? submitBtn.innerHTML : '';
        const errorAlert = formEl.querySelector('#modal-incidencia-create-errors, #modal-incidencia-edit-errors');

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';
        }
        if (errorAlert) {
            errorAlert.classList.add('d-none');
            errorAlert.innerHTML = '';
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
            const data = await response.json();
            if (!response.ok) {
                let msg = data.message || 'Error al procesar el formulario.';
                if (data.errors) {
                    msg = '<ul class="mb-0 ps-3">' + Object.values(data.errors).map(arr => `<li>${arr[0]}</li>`).join('') + '</ul>';
                }
                throw new Error(msg);
            }
            return data;
        })
        .then(data => {
            cerrarModal(modalEl);

            Swal.fire({
                title: '¡Operación Exitosa!',
                text: data.message || 'Operación realizada correctamente.',
                icon: 'success',
                timer: 1600,
                showConfirmButton: false
            }).then(() => {
                ejecutarFiltroAjax();
            });
        })
        .catch(err => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origBtnHtml;
            }
            if (errorAlert) {
                errorAlert.classList.remove('d-none');
                errorAlert.innerHTML = err.message || 'Ocurrió un error inesperado.';
            } else {
                Swal.fire('Error', err.message, 'error');
            }
        });
    });

    // 6. Cambio Rápido de Estado desde la Tabla (Dropdown)
    $(document).on('click', '.btn-cambiar-estado-rapido', function(e) {
        e.preventDefault();
        const btn = $(this);
        const url = btn.data('url');
        const nuevoEstado = btn.data('estado');
        const ticketId = btn.data('id');

        if (nuevoEstado === 'Atendido') {
            Swal.fire({
                title: 'Marcar como Atendido',
                text: `¿Desea registrar o confirmar la solución aplicada para el Ticket #${ticketId}?`,
                input: 'textarea',
                inputPlaceholder: 'Escriba la solución técnica aplicada (opcional si ya tenía una)...',
                showCancelButton: true,
                confirmButtonText: 'Sí, marcar como Atendido',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#198754'
            }).then((result) => {
                if (result.isConfirmed) {
                    actualizarEstadoAjax(url, nuevoEstado, result.value);
                }
            });
        } else {
            actualizarEstadoAjax(url, nuevoEstado, null);
        }
    });

    function actualizarEstadoAjax(url, nuevoEstado, solucion) {
        $.ajax({
            url: url,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                estado: nuevoEstado,
                solucion: solucion
            },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Estado Actualizado!',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    ejecutarFiltroAjax();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'No se pudo actualizar el estado.'
                });
            }
        });
    }
    // 7. Filtro Dinámico desde las Tarjetas de Métricas (KPIs)
    window.filtrarPorEstadoKpi = function(estado) {
        const selectEstado = document.getElementById('filtroEstado');
        if (selectEstado) {
            selectEstado.value = estado;
            if (window.jQuery && $(selectEstado).data('select2')) {
                $(selectEstado).trigger('change.select2');
            }
            ejecutarFiltroAjax();

            // Feedback visual en la tarjeta activa
            document.querySelectorAll('.kpi-filter-card').forEach(c => {
                c.style.opacity = '0.75';
                c.style.boxShadow = 'none';
            });
            if (window.event && window.event.currentTarget) {
                const target = window.event.currentTarget;
                target.style.opacity = '1';
                target.style.boxShadow = '0 6px 18px rgba(0,0,0,0.12)';
            }
        }
    };
});
</script>
@endpush
@endsection
