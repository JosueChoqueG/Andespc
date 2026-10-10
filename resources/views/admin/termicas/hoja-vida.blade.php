<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hoja de Vida - {{ $termica->serie_termica }}</title>
        <style>
            /* ── PRINT ─────────────────────────────────── */
            @page { size: A4; margin: 8mm; }
            @media print {
                .btn-bar { display: none !important; }
                body { background: white; margin: 0; padding: 0; }
                .page {
                    width: 210mm !important;
                    max-width: 210mm !important;
                    min-height: 297mm;
                    box-shadow: none !important;
                    padding: 10mm !important;
                    margin: 0 !important;
                    border-radius: 0 !important;
                }
                /* Restaurar tablas normales en impresión */
                .mobile-card thead { display: table-header-group !important; }
                .mobile-card, .mobile-card tbody { display: table !important; width: 100% !important; }
                .mobile-card tr { display: table-row !important; background: transparent !important;
                    border: none !important; margin: 0 !important; padding: 0 !important; border-radius: 0 !important; }
                .mobile-card td, .mobile-card th { display: table-cell !important;
                    border: 1px solid #000 !important; padding: 5px !important;
                    font-size: 10px !important; text-align: left !important; width: auto !important; }
                .mobile-card td::before { display: none !important; }
                .header-table { display: table !important; width: 100% !important; }
                .header-table tbody { display: table-row-group !important; }
                .header-table tr { display: table-row !important; flex-direction: unset !important; }
                .header-table td { display: table-cell !important; border: 1px solid #000 !important;
                    text-align: left !important; width: auto !important; }
                .table-responsive { overflow: visible !important; }
                table { table-layout: fixed; font-size: 10px; }
            }

            /* ── BASE ──────────────────────────────────── */
            *, *::before, *::after { box-sizing: border-box; }

            body {
                font-family: Arial, sans-serif;
                font-size: 11px;
                margin: 0;
                padding: 0;
                background-color: #272525;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Barra de botones fija */
            .btn-bar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                display: flex;
                justify-content: flex-end;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                background: rgba(39,37,37,0.92);
                backdrop-filter: blur(4px);
                flex-wrap: wrap;
            }

            .btn-print, .btn-pdf {
                padding: 8px 16px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 13px;
                font-weight: bold;
                text-decoration: none;
                white-space: nowrap;
                display: inline-block;
            }
            .btn-print { background: #007bff; color: white; }
            .btn-pdf   { background: #dc3545; color: white; }

            /* Contenedor de la página A4 */
            .page {
                width: 100%;
                max-width: 210mm;
                min-height: 297mm;
                background: white;
                margin: 60px auto 20px; /* espacio para la barra de botones */
                padding: 10mm 15mm;
                box-shadow: 0 0 15px rgba(0,0,0,0.4);
            }

            /* Tablas */
            .table-responsive {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 8px;
                table-layout: fixed;
            }
            th, td {
                border: 1px solid #000;
                padding: 5px;
                vertical-align: middle;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
            th {
                font-weight: bold;
                text-align: center;
            }

            /* Títulos de sección */
            .section-title {
                background-color: #d9d9d9;
                font-weight: bold;
                padding: 5px;
                border: 1px solid #000;
                margin-top: 10px;
                margin-bottom: -1px;
                text-transform: uppercase;
                font-size: 10px;
            }

            .text-center { text-align: center; }
            ul { margin: 0; padding-left: 15px; }

            /* ── TABLET  (≤ 768 px) ───────────────────── */
            @media screen and (max-width: 768px) {
                body { font-size: 10px; }
                .btn-bar { justify-content: center; }
                .btn-print, .btn-pdf { font-size: 12px; padding: 7px 12px; }
                .page { margin-top: 70px; padding: 8px 10px; }
                table { font-size: 9.5px; }
                th, td { padding: 4px 3px; }
                .section-title { font-size: 9px; }
            }

            /* ── MÓVIL  (≤ 600 px) — tarjetas verticales ── */
            @media screen and (max-width: 600px) {

                body { font-size: 13px; background: #1a1a1a; }

                /* Botones full-width en móvil */
                .btn-bar { gap: 6px; padding: 8px; justify-content: center; }
                .btn-print, .btn-pdf {
                    flex: 1;
                    text-align: center;
                    font-size: 13px;
                    padding: 10px 6px;
                    border-radius: 8px;
                }

                /* Página sin margen lateral */
                .page {
                    margin: 68px 6px 24px;
                    padding: 12px 8px;
                    border-radius: 10px;
                    min-height: unset;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.6);
                }

                /* ── Encabezado del documento ── */
                .header-table,
                .header-table tbody { display: block; width: 100%; }
                .header-table tr {
                    display: flex;
                    flex-direction: column;
                    border: none;
                    padding: 0;
                    margin-bottom: 0;
                    background: transparent;
                }
                .header-table td {
                    display: block;
                    width: 100% !important;
                    border: 1px solid #000;
                    text-align: center !important;
                    padding: 6px;
                    font-size: 12px;
                    margin-bottom: -1px;
                }

                /* ── Convertir tablas de datos en tarjetas ── */
                .mobile-card thead { display: none; }
                .mobile-card,
                .mobile-card tbody { display: block; width: 100%; }
                .mobile-card tr {
                    display: block;
                    background: #f8f8f8;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    margin-bottom: 10px;
                    padding: 6px 8px;
                    overflow: hidden;
                }
                .mobile-card td {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: 6px;
                    border: none;
                    border-bottom: 1px solid #e0e0e0;
                    padding: 7px 4px;
                    font-size: 12px;
                    text-align: right;
                    width: 100% !important;
                }
                .mobile-card tr td:last-child { border-bottom: none; }

                /* Etiqueta generada por data-label */
                .mobile-card td::before {
                    content: attr(data-label);
                    font-weight: bold;
                    color: #333;
                    flex: 0 0 45%;
                    text-align: left;
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: 0.3px;
                    line-height: 1.4;
                }

                /* Celdas sin data-label (ej. colspan) centradas */
                .mobile-card td:not([data-label]) {
                    justify-content: center;
                    text-align: center;
                }
                .mobile-card td:not([data-label])::before { display: none; }

                .section-title {
                    font-size: 11px;
                    padding: 6px 8px;
                    margin-top: 14px;
                    border-radius: 4px 4px 0 0;
                }

                ul { padding-left: 16px; }
                ul li { margin-bottom: 4px; }
            }

            /* ── PANTALLAS GRANDES (≥ 1200 px) ─────────── */
            @media screen and (min-width: 1200px) {
                .page { padding: 12mm 18mm; }
            }
        </style>
    </head>
    <body>
        <div class="btn-bar no-print">
            <a href="{{ isset($mantenimiento) ? route('admin.termicas.hoja-vida-mantenimiento.pdf', $mantenimiento) : route('admin.termicas.hoja-vida.pdf', $termica) }}"
               class="btn-pdf">📥 Descargar PDF</a>
            <button class="btn-print" onclick="window.print()">🖨️ Imprimir</button>
        </div>

        <div class="page">
            {{-- Encabezado del documento --}}
            <div class="table-responsive">
                <table class="header-table">
                    <tbody>
                        <tr>
                            <td style="width: 20%; padding: 0;">
                                <img src="{{ asset('logo.jpeg') }}" alt="LOS ANDES" style="width: 100%; height: auto; object-fit: cover; display:block;">
                            </td>
                            <td style="width: 55%; text-align: center; font-size: 13px; font-weight: bold;">
                                HOJA DE VIDA DE EQUIPOS INFORMÁTICOS (IMPRESORAS TÉRMICAS)
                                <br><span style="font-size: 15px; font-weight: normal;">
                                Oficina: {{ $termica->oficina->nombre_oficina ?? 'Abancay' }}
                                </span>
                            </td>
                            <td style="width: 7%;"><strong>Código</strong></td>
                            <td style="width: 18%;"><strong>{{ $termica->serie_termica ?? $termica->id }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="header-table">
                    <tbody>
                        <tr>
                            <th style="width: 15%;">Realizado por</th>
                            <td style="width: 20%;" class="text-center">{{ $tecnico }}</td>
                            <th style="width: 15%;">Departamento</th>
                            <td style="width: 15%;" class="text-center">TI</td>
                            <td style="width: 35%;" class="text-center">Versión: 1.0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="header-table">
                    <tbody>
                        <tr>
                            <td style="width: 40%;" class="text-center">Uso: Interno - Confidencial</td>
                            <td style="width: 60%;" class="text-center">UNIDAD DE INFRAESTRUCTURA COMUNICACIÓN Y SOPORTE</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="section-title">1. DATOS GENERALES DEL EQUIPO</div>
            <div class="table-responsive">
                <table class="mobile-card">
                    <tbody>
                        <tr>
                            <td data-label="Tipo Impresora">{{ $termica->tipo_termica }}</td>
                            <td data-label="Marca">{{ $termica->marca_termica }}</td>
                            <td data-label="Modelo">{{ $termica->modelo_termica }}</td>
                        </tr>
                        <tr>
                            <td data-label="Fecha Adquisición">{{ $termica->fecha_adquisicion ? $termica->fecha_adquisicion->format('d/m/Y') : 'N/A' }}</td>
                            <td data-label="Proveedor">JHT</td>
                            <td data-label="Garantía">
                                @php
                                    $anio_compra = $termica->fecha_adquisicion ? $termica->fecha_adquisicion->format('Y') : null;
                                    $anio_actual = date('Y');
                                @endphp
                                {{ ($anio_compra && ($anio_actual - $anio_compra) <= 2) ? 'Con Garantía' : 'Sin Garantía' }}
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Número Serie">{{ $termica->serie_termica }}</td>
                            <td data-label="Ubicación">{{ $termica->oficina->nombre_oficina ?? 'Abancay' }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td data-label="Responsable / Área" colspan="3">{{ $termica->responsable->nombre_responsable ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="section-title">2. CARACTERÍSTICAS TECNICAS</div>
            <div class="table-responsive">
                <table class="mobile-card">
                    <tbody>
                        <tr>
                            <td data-label="Nombre de Host">{{ $termica->nombre_host ?? 'N/A' }}</td>
                            <td data-label="Tipo Conexión">{{ $termica->tipo_conexion }}</td>
                            <td data-label="Dirección IP">{{ $termica->direccion_ip ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td data-label="Velocidad Impresión">{{ $termica->velocidad_impresion ?? 'N/A' }}</td>
                            <td data-label="Cantidad Impresión">{{ number_format($termica->cantidad_impresion ?? 0) }} m/cortes</td>
                            <td data-label="Tipo Consumible">{{ $termica->tipo_consumible ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td data-label="Modelo Consumible">{{ $termica->modelo_consumible ?? 'N/A' }}</td>
                            <td data-label="Capacidad Útil">{{ $termica->capacidad_impresion ? number_format($termica->capacidad_impresion) . ' Km' : 'N/A' }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="section-title">3. HISTORIAL DE MANTENIMIENTO</div>
            <div class="table-responsive">
                <table class="mobile-card">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Fecha</th>
                            <th style="width: 15%;">Tipo</th>
                            <th>Actividad realizada</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historialMantenimientos as $item)
                        <tr>
                            <td data-label="Fecha">{{ $item->fecha_mantenimiento->format('d/m/Y') }}</td>
                            <td data-label="Tipo">{{ $item->tipo_mantenimiento }}</td>
                            <td data-label="Actividad">
                                <ul>
                                    @foreach($item->descripcion_array as $trabajo)
                                        <li>{{ $trabajo }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td data-label="Observaciones">{{ $item->observacion_mantenimiento ?? 'Sin observaciones' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">No hay mantenimientos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="section-title">4. REGISTRO DE FALLAS E INCIDENCIAS</div>
            <div class="table-responsive">
                <table class="mobile-card">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Fecha</th>
                            <th style="width: 35%;">Falla detectada</th>
                            <th>Solución Aplicada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fallasHistorial as $falla)
                        <tr>
                            <td data-label="Fecha">{{ $falla->fecha_mantenimiento->format('d/m/Y') }}</td>
                            <td data-label="Falla detectada">{{ $falla->fallas_detectadas }}</td>
                            <td data-label="Solución">{{ $falla->fallas_solucion ?? 'No especificada' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">No hay fallas registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="section-title">5. ESTADO ACTUAL DE LA IMPRESORA TÉRMICA</div>
            <div class="table-responsive">
                <table class="mobile-card">
                    <thead>
                        <tr>
                            <th width="25%">Descripción</th>
                            <th width="10%" class="text-center">Estado</th>
                            <th width="65%">Observaciones Generales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $estados = [
                                'OPTIMO' => 'Óptimo',
                                'BUENO' => 'Bueno',
                                'REGULAR' => 'Regular',
                                'DEFICIENTE' => 'Deficiente',
                                'DE BAJA' => 'De Baja'
                            ];
                        @endphp
                        <tr>
                            <td data-label="Descripción">
                                @foreach($estados as $key => $label)
                                    <label style="display:block; margin-bottom: 8px;">{{ $label }}</label>
                                @endforeach
                            </td>
                            <td data-label="Estado" class="text-center">
                                @foreach($estados as $key => $label)
                                    <label style="display:block;">
                                        <input type="radio" name="estado_termica" value="{{ $key }}"
                                            {{ $termica->estado_termica == $key ? 'checked' : '' }}>
                                    </label>
                                @endforeach
                            </td>
                            <td data-label="Observaciones" style="font-size: 11.5px;">
                                {{ $mantenimiento->observacion_general ?? ($termica->mantenimientos->first()->observacion_general ?? 'Sin observaciones generales') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="section-title">6. RESPONSABLES</div>
            <div class="table-responsive">
                <table class="mobile-card">
                    <thead>
                        <tr>
                            <th width="20%"></th>
                            <th width="30%">NOMBRE Y APELLIDO</th>
                            <th>CARGO</th>
                            <th>FIRMA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Rol">6.1 Ejecutivo TI</td>
                            <td data-label="Nombre">{{ $tecnico }}</td>
                            <td data-label="Cargo">Asistente de Infraestructura Informática</td>
                            <td data-label="Firma"></td>
                        </tr>
                        <tr>
                            <td data-label="Rol">6.2 Usuario Asignado</td>
                            <td data-label="Nombre">{{ $termica->responsable->nombre_responsable ?? 'N/A' }}</td>
                            <td data-label="Cargo">Usuario de Oficina {{ $termica->oficina->nombre_oficina ?? '' }}</td>
                            <td data-label="Firma"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
