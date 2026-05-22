<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hoja de Vida - {{ $contabillete->serie_contabilletes }}</title>
        <style>
            @page { size: A4; margin: 8mm; }
            body {
                font-family: Arial, sans-serif;
                font-size: 11px;
                margin: 0;
                padding: 0;
                background-color: #272525;
                -webkit-print-color-adjust: exact;
            }
            .page {
                width: 210mm;
                min-height: 297mm;
                background: white;
                margin: 0 auto;
                padding: 10mm 15mm;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                box-sizing: border-box;
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
            }
            th {
                font-weight: bold;
                text-align: center;
            }
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
            .btn-print {
                position: fixed;
                top: 10px;
                right: 10px;
                padding: 10px 20px;
                background: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                z-index: 1000;
            }
            .btn-pdf {
                position: fixed;
                top: 10px;
                right: 150px;
                padding: 10px 20px;
                background: #dc3545;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                z-index: 1000;
                text-decoration: none;
            }
            @media print {
                .btn-print, .btn-pdf { display: none; }
                body { background: white; }
                .page { box-shadow: none; padding: 10mm; }
            }
        </style>
    </head>
    <body>
        <button class="btn-print no-print" onclick="window.print()">🖨️ Imprimir</button>
        <a href="{{ isset($mantenimiento) ? route('admin.contabilletes.hoja-vida-mantenimiento.pdf', $mantenimiento) : route('admin.contabilletes.hoja-vida.pdf', $contabillete) }}" 
           class="btn-pdf">📥 Descargar PDF
        </a>

        <div class="page">
            <table>
                <tr>
                    <td style="width: 20%; padding: 0;">
                        <img src="{{ asset('logo.jpeg') }}" alt="LOS ANDES" style="width: 100%; height: 100%; object-fit: cover;">
                    </td>
                    <td style="width: 55%; text-align: center; font-size: 13px; font-weight: bold;">
                        HOJA DE VIDA DE EQUIPOS INFORMÁTICOS (CONTADORA DE BILLETES)
                        <br><span style="font-size: 15px; font-weight: normal;">
                        Oficina: {{ $contabillete->oficina->nombre_oficina ?? 'Abancay' }}
                        </span>
                    </td>
                    <td style="width: 7%;"><strong>Código</strong></td>
                    <td style="width: 18%;"><strong>{{ $contabillete->serie_contabilletes ?? $contabillete->id }}</strong></td>
                </tr>
            </table>
            <table>
                <tr>
                    <th style="width: 15%;">Realizado por</th>
                    <td style="width: 20%;" class="text-center">{{ $tecnico }}</td>
                    <th style="width: 15%;">Departamento</th>
                    <td style="width: 15%;" class="text-center">TI</td>
                    <td style="width: 35%;" class="text-center">Versión: 1.0</td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width: 40%;" class="text-center">Uso: Interno - Confidencial</td>
                    <td style="width: 60%;" class="text-center">UNIDAD DE INFRAESTRUCTURA COMUNICACIÓN Y SOPORTE</td>
                </tr>
            </table>
            <div class="section-title">1. DATOS GENERALES DEL EQUIPO</div>
            <table>
                <tr>
                    <th style="width: 15%;">Tipo de contadora</th>
                    <td style="width: 12%;" class="text-center">{{ $contabillete->tipo_contabilletes }}</td>
                    <th style="width: 10%;">Marca</th>
                    <td style="width: 20%;" class="text-center">{{ $contabillete->marca_contabilletes }}</td>
                    <th style="width: 10%;">Modelo</th>
                    <td class="text-center" >{{ $contabillete->modelo_contabilletes }}</td>
                </tr>
                <tr>
                    <th style="width: 15%;">Fecha Adquisición</th>
                    <td style="width: 12%;" class="text-center">{{ $contabillete->fecha_adquisicion ? $contabillete->fecha_adquisicion->format('d/m/Y') : 'N/A' }}</td>
                    <th style="width: 10%;">Proveedor</th>
                    <td style="width: 20%;" class="text-center">JHT</td>
                    <th style="width: 10%;">Garantía</th>
                    <td class="text-center">
                        @php
                            $anio_compra = $contabillete->fecha_adquisicion ? $contabillete->fecha_adquisicion->format('Y') : null;
                            $anio_actual = date('Y');
                        @endphp
                        
                        {{ ($anio_compra && ($anio_actual - $anio_compra) <= 2) ? 'Con Garantía' : 'Sin Garantía' }}
                    </td>
                </tr>
                <tr>
                    <th style="width: 15%;">Número Serie</th>
                    <td style="width: 12%;" class="text-center">{{ $contabillete->serie_contabilletes }}</td>
                    <th style="width: 10%;">Ubicación</th>
                    <td style="width: 20%;" class="text-center">{{ $contabillete->oficina->nombre_oficina ?? 'Abancay' }}</td>
                    <td colspan="2"></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width: 15%;">Responsable / Área</td>
                    <td style="width: 40%;" class="text-center">
                        {{ $contabillete->responsable->nombre_responsable ?? 'N/A' }}
                    </td>
                </tr>
            </table>
            <div class="section-title">2. CARACTERÍSTICAS TECNICAS</div>
            <table>
                <tr>
                    <th style="width: 15%;">Velocidad Conteo</th>
                    <td style="width: 20%;" class="text-center">{{ $contabillete->velocidad_contabilletes ?? 'N/A' }}</td>
                    <th style="width: 15%;">Capacidad Tolva</th>
                    <td style="width: 20%;" class="text-center">{{ $contabillete->capacidad_tolva ? $contabillete->capacidad_tolva . ' billetes' : 'N/A' }}</td>
                    <th style="width: 10%;">Capacidad Bandeja</th>
                    <td class="text-center">{{ $contabillete->capacidad_bandeja ? $contabillete->capacidad_bandeja . ' billetes' : 'N/A' }}</td>
                </tr>
                <tr>
                    <th width="15%">Detección de Falsos</th>
                    <td width="20%" class="text-center">{{ $contabillete->tipo_deteccion ?? 'N/A' }}</td>
                    <th width="15%">Tipo de Pantalla</th>
                    <td width="20%" class="text-center">{{ $contabillete->pantalla_contabilletes ?? 'N/A' }}</td>
                    <th width="10%">-</th>
                    <td class="text-center">-</td>
                </tr>
            </table>
            <div class="section-title">3. HISTORIAL DE MANTENIMIENTO</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Fecha</th>
                        <th style="width: 15%;">Tipo</th>
                        <th>Actividad realizada</th>
                        <th>Observaciones mantenimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historialMantenimientos as $item)
                    <tr>
                        <td class="text-center">{{ $item->fecha_mantenimiento->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $item->tipo_mantenimiento }}</td>
                        <td>
                            <ul>
                                @foreach($item->descripcion_array as $trabajo)
                                    <li>{{ $trabajo }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $item->observacion_mantenimiento ?? 'Sin observaciones' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="4">No hay mantenimientos registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="section-title">4. REGISTRO DE FALLAS E INCIDENCIAS</div>
            <table>
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
                        <td class="text-center">{{ $falla->fecha_mantenimiento->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $falla->fallas_detectadas }}</td>
                        <td>{{ $falla->fallas_solucion ?? 'No especificada' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="3">No hay fallas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="section-title">5. ESTADO ACTUAL DE LA CONTADORA DE BILLETES</div>
            <table>
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
                        <td>
                            @foreach($estados as $key => $label)
                                <label style="display:block; margin-bottom: 8px;">
                                {{ $label }}
                                </label>
                            @endforeach
                        </td>
                        <td class="text-center">
                            @foreach($estados as $key => $label)
                                <label style="display:block; ">
                                <input type="radio" name="estado_contabilletes" value="{{ $key }}" 
                                    {{ $contabillete->estado_contabilletes == $key ? 'checked' : '' }}>
                                </label>
                            @endforeach
                        </td>
                        <td style="font-size: 11.5px;">
                            {{ $mantenimiento->observacion_general ?? ($contabillete->mantenimientos->first()->observacion_general ?? 'Sin observaciones generales') }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="section-title">6. RESPONSABLES</div>
            <table>
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
                        <td style="height: 30px;">
                            6.1 Ejecutivo TI
                        </td>
                        <td> {{ $tecnico }}</td>
                        <td> Asistente de Infraestructura Informática</td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td style="height: 30px;">
                            6.2 Usuario Asignado
                        </td>
                        <td>{{ $contabillete->responsable->nombre_responsable ?? 'N/A' }}</td>
                        <td style="width: 40%; text-align: center;">
                            Usuario de Oficina {{ $contabillete->oficina->nombre_oficina ?? '' }}
                        </td>
                        <td> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
