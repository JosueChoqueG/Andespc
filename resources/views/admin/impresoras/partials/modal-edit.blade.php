{{-- Modal Edit: Editar Impresora (Ultra Compacto & Estético) --}}
<div class="modal-header py-2 px-3 text-dark" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
            <i class="bi bi-pencil-square text-dark small"></i>
        </div>
        <div>
            <span class="modal-title fw-bold text-dark fs-6 d-block lh-sm">Editar Impresora: {{ $impresora->marca_impresora }} {{ $impresora->modelo_impresora }}</span>
            <span class="text-dark text-opacity-75" style="font-size: 0.95rem;">S/N: <strong>{{ $impresora->serie_impresora }}</strong> &bull; {{ $impresora->oficina->nombre_oficina ?? 'Sin oficina' }}</span>
        </div>
    </div>
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('admin.impresoras.update', $impresora->id) }}" method="POST" id="form-editar-impresora-modal">
    @csrf
    @method('PUT')

    <div class="modal-body p-3" style="max-height: 82vh; overflow-y: auto; font-size: 1rem;">
        <div id="modal-impresora-errors" class="alert alert-danger py-1.5 px-3 small d-none mb-2"></div>

        {{-- Sección 1: Identificación y Asignación --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-primary fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-tag me-1"></i> 1. IDENTIFICACIÓN Y ASIGNACIÓN
            </div>
            <div class="row g-2.5">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Oficina <span class="text-danger">*</span></label>
                    <select name="oficina_id" class="form-select form-select-sm py-1 modal-select2" required>
                        <option value="">Seleccione Oficina</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ old('oficina_id', $impresora->oficina_id) == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre_oficina }} ({{ $oficina->agencia->nombre_agencia ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Responsable</label>
                    <select name="responsable_id" class="form-select form-select-sm py-1 modal-select2">
                        <option value="">Seleccione Responsable</option>
                        @foreach($responsables as $resp)
                            <option value="{{ $resp->id }}" {{ old('responsable_id', $impresora->responsable_id) == $resp->id ? 'selected' : '' }}>
                                {{ $resp->nombre_responsable }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo de Impresora <span class="text-danger">*</span></label>
                    <select name="tipo_impresora" class="form-select form-select-sm py-1" required>
                        <option value="">Seleccione</option>
                        @php
                            $tipos = ['Inyección', 'Multifuncional', 'Escaner'];
                            $tipoActual = old('tipo_impresora', $impresora->tipo_impresora);
                        @endphp
                        @if($tipoActual && !in_array($tipoActual, $tipos))
                            <option value="{{ $tipoActual }}" selected>{{ $tipoActual }}</option>
                        @endif
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo }}" {{ $tipoActual === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Marca <span class="text-danger">*</span></label>        
                    <select name="marca_impresora" class="form-select form-select-sm py-1" required>
                        <option value="">Seleccione</option>
                        @php
                            $marcas = ["KYOCERA", "HP", "BROTHER", "Avision"];
                            $marcaActual = old('marca_impresora', $impresora->marca_impresora);
                        @endphp
                        @if($marcaActual && !in_array($marcaActual, $marcas))
                            <option value="{{ $marcaActual }}" selected>{{ $marcaActual }}</option>
                        @endif
                        @foreach ($marcas as $est)
                            <option value="{{ $est }}" {{ $marcaActual === $est ? 'selected' : '' }}>{{ $est }}</option>        
                        @endforeach
                    </select>        
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Modelo <span class="text-danger">*</span></label>
                    <select name="modelo_impresora" class="form-select form-select-sm py-1" required>
                        <option value="">Seleccione</option>
                        @php
                            $modelos = ['M2640', 'M3655idn', 'MA4500ix', 'MA5500ifx', 'HP LaserJet Tank MFP M634', 'HP LaserJet PRO MFP M225dw', 'HP LaserJet Tank MFP 2602sdw', 'HP LaserJet Pro M15w', 'HP Smart Tank 750', 'FF-1905B'];
                            $modeloActual = old('modelo_impresora', $impresora->modelo_impresora);
                        @endphp
                        @if($modeloActual && !in_array($modeloActual, $modelos))
                            <option value="{{ $modeloActual }}" selected>{{ $modeloActual }}</option>
                        @endif
                        @foreach($modelos as $est)
                            <option value="{{ $est }}" {{ $modeloActual === $est ? 'selected' : '' }}>{{ $est }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Número de Serie <span class="text-danger">*</span></label>
                    <input type="text" name="serie_impresora" class="form-control form-control-sm py-1 font-monospace"
                           value="{{ old('serie_impresora', $impresora->serie_impresora) }}" placeholder="VND3K0..." required>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Estado</label>
                    <select name="estado_impresora" class="form-select form-select-sm py-1">
                        @foreach(['OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE', 'DE BAJA'] as $est)
                            <option value="{{ $est }}" {{ old('estado_impresora', $impresora->estado_impresora) === $est ? 'selected' : '' }}>{{ $est }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Fecha Adquisición</label>
                    <input type="date" name="fecha_adquisicion" class="form-control form-control-sm py-1"
                           value="{{ old('fecha_adquisicion', $impresora->fecha_adquisicion?->format('Y-m-d') ?? ($impresora->fecha_adquisicion ? date('Y-m-d', strtotime($impresora->fecha_adquisicion)) : '')) }}">
                </div>
            </div>
        </div>

        {{-- Sección 2: Conectividad y Red --}}
        <div class="border rounded-2 p-2.5 mb-2.5 bg-white">
            <div class="text-purple fw-semibold mb-1" style="font-size: 0.95rem; color: #6f42c1;">
                <i class="bi bi-wifi me-1"></i> 2. CONECTIVIDAD Y RED
            </div>
            <div class="row g-2.5">
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo Conexión</label>
                    <select name="tipo_conexion" class="form-select form-select-sm py-1">
                        <option value="">Seleccione</option>
                        @foreach(['USB', 'WIFI', 'ETHERNET', 'WIFI-DIRECT'] as $con)
                            <option value="{{ $con }}" {{ old('tipo_conexion', $impresora->tipo_conexion) === $con ? 'selected' : '' }}>{{ $con }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Nombre Host</label>
                    <input type="text" name="nombre_host" class="form-control form-control-sm py-1"
                           value="{{ old('nombre_host', $impresora->nombre_host) }}" placeholder="PRN-OFICINA-01">
                </div>
                <div class="col-md-2 col-sm-12">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Dirección IP</label>
                    <input type="text" name="direccion_ip" class="form-control form-control-sm py-1 font-monospace"
                           value="{{ old('direccion_ip', $impresora->direccion_ip) }}" placeholder="192.168.1.50">
                </div>
            </div>
        </div>

        {{-- Sección 3: Especificaciones y Consumibles --}}
        <div class="border rounded-2 p-2 bg-white">
            <div class="text-secondary fw-semibold mb-1" style="font-size: 0.95rem;">
                <i class="bi bi-gear me-1"></i> 3. ESPECIFICACIONES Y CONSUMIBLES
            </div>
            <div class="row g-2.5">
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Velocidad Impresión</label>
                    <select name="velocidad_impresion" class="form-select form-select-sm py-1">
                        <option value="">Seleccione</option>
                        @php
                            $velocidades = ['20 ppm', '30 ppm', '40 ppm', '45 ppm', '50 ppm', '55 ppm'];
                            $velocidadActual = old('velocidad_impresion', $impresora->velocidad_impresion);
                        @endphp
                        @if($velocidadActual && !in_array($velocidadActual, $velocidades))
                            <option value="{{ $velocidadActual }}" selected>{{ $velocidadActual }}</option>
                        @endif
                        @foreach($velocidades as $con)
                            <option value="{{ $con }}" {{ $velocidadActual === $con ? 'selected' : '' }}>{{ $con }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Modelo Consumible</label>
                    <select name="modelo_consumible" class="form-select form-select-sm py-1">
                        <option value="">Seleccione</option>
                        @php
                            $consumibles = ['TK-1175', 'TK-3182', 'TK-3204', 'TK-3432', 'HP 147A', 'W1105A', 'HP 154A', 'HP 6UU47A', 'HP 44A'];
                            $consumibleActual = old('modelo_consumible', $impresora->modelo_consumible);
                        @endphp
                        @if($consumibleActual && !in_array($consumibleActual, $consumibles))
                            <option value="{{ $consumibleActual }}" selected>{{ $consumibleActual }}</option>
                        @endif
                        @foreach($consumibles as $con)
                            <option value="{{ $con }}" {{ $consumibleActual === $con ? 'selected' : '' }}>{{ $con }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Tipo Consumible</label>
                    <input type="text" name="tipo_consumible" class="form-control form-control-sm py-1"
                           value="{{ old('tipo_consumible', $impresora->tipo_consumible ?? 'Toner') }}" placeholder="Tóner / Tinta">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Capacidad Impresión</label>
                    <input type="text" name="capacidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('capacidad_impresion', $impresora->capacidad_impresion) }}" placeholder="1500 hojas">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Contador Impresiones</label>
                    <input type="number" name="cantidad_impresion" class="form-control form-control-sm py-1"
                           value="{{ old('cantidad_impresion', $impresora->cantidad_impresion ?? 0) }}" min="0">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Contador Escaneos</label>
                    <input type="number" name="cantidad_escaneo" class="form-control form-control-sm py-1"
                           value="{{ old('cantidad_escaneo', $impresora->cantidad_escaneo ?? 0) }}" min="0">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-0 fw-medium text-muted" style="font-size: 0.95rem;">Total de fallos</label>
                    <input type="number" name="cantidad_fallos" class="form-control form-control-sm py-1"
                           value="{{ old('cantidad_fallos', $impresora->cantidad_fallos ?? 0) }}" min="0">
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-sm py-1 px-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary btn-sm py-1 px-3 fw-medium">
            <i class="bi bi-save2 me-1"></i> Guardar Cambios
        </button>
    </div>
</form>
