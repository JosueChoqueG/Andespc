<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hoja de Vida - {{ $impresora->serie_impresora }}</title>
        <style>
            @page { size: A4; margin: 8mm; }
            body {
                font-family: Arial, sans-serif;
                font-size: 11px;
                margin: 0;
                padding: 0;
                background-color: #272525ff;
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
            .signature-line {
                border-top: 1px solid #000;
                width: 80%;
                margin: 40px auto 5px auto;
                text-align: center;
            }
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
        <a href="{{ isset($mantenimiento) ? route('admin.impresoras.hoja-vida-mantenimiento.pdf', $mantenimiento) : route('admin.impresoras.hoja-vida.pdf', $impresora) }}" 
           class="btn-pdf">📥 Descargar PDF
        </a>

        <div class="page">
            <table>
                <tr>
                    <td style="width: 20%; padding: 0;">
                        <img src="{{ asset('logo.jpeg') }}" alt="LOS ANDES" style="width: 100%; height: 100%; object-fit: cover;">
                    </td>
                    <td style="width: 55%; text-align: center; font-size: 13px; font-weight: bold;">
                        HOJA DE VIDA DE EQUIPOS INFORMÁTICOS (IMPRESORAS)
                        <br><span style="font-size: 15px; font-weight: normal;">
                        Oficina: {{ $impresora->oficina->nombre_oficina ?? 'Abancay' }}
                        </span>
                    </td>
                    <td style="width: 7%;"><strong>Código</strong></td>
                    <td style="width: 18%;"><strong>{{ $impresora->serie_impresora ?? $impresora->id }}</strong></td>
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
                    <th style="width: 15%;">Tipo de impresora</th>
                    <td style="width: 12%;" class="text-center">{{ $impresora->tipo_impresora }}</td>
                    <th style="width: 10%;">Marca</th>
                    <td style="width: 20%;" class="text-center">{{ $impresora->marca_impresora }}</td>
                    <th style="width: 10%;">Modelo</th>
                    <td class="text-center" >{{ $impresora->modelo_impresora }}</td>
                </tr>
                <tr>
                    <th style="width: 15%;">Fecha Adquisición</th>
                    <td style="width: 12%;" class="text-center">{{ $impresora->fecha_adquisicion ? $impresora->fecha_adquisicion->format('d/m/Y') : 'N/A' }}</td>
                    <th style="width: 10%;">Proveedor</th>
                    <td style="width: 20%;" class="text-center">J&C CORP E.I.R.L</td>
                    <th style="width: 10%;">Garantía</th>
                    <td class="text-center">
                        @php
                            $anio = $impresora->fecha_adquisicion ? $impresora->fecha_adquisicion->format('Y') : null;
                        @endphp
                        {{ ($anio && $anio < date('Y')) ? 'Sin Garantía' : 'Con Garantía' }}
                    </td>
                </tr>
                <tr>
                    <th style="width: 15%;">Número Serie</th>
                    <td style="width: 12%;" class="text-center">{{ $impresora->serie_impresora }}</td>
                    <th style="width: 10%;">Ubicación</th>
                    <td style="width: 20%;" class="text-center">{{ $impresora->oficina->nombre_oficina ?? 'Abancay' }}</td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width: 15%;">Responsable / Área</td>
                    <td style="width: 40%;" class="text-center">
                        {{ $impresora->responsable->nombre_responsable ?? 'N/A' }}
                    </td>
                </tr>
            </table>
            <div class="section-title">2. CARACTERÍSTICAS TECNICAS</div>
            <table>
                <tr>
                    <th style="width: 15%;">Nombre de Host</th>
                    <td style="width: 20%;" class="text-center">{{ $impresora->nombre_host ?? 'N/A' }}</td>
                    <th style="width: 15%;">Tipo conexion</th>
                    <td style="width: 20%;" class="text-center">{{ $impresora->tipo_conexion }}</td>
                    <th style="width: 10%;">Direcion IP</th>
                    <td class="text-center">{{ $impresora->direccion_ip ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th width="15%">Velocidad impresion</th>
                    <td width="20%" class="text-center">{{ $impresora->velocidad_impresion ?? 'N/A' }}</td>
                    <th width="15%">Cantidad impresion</th>
                    <td width="20%" class="text-center">{{ number_format($impresora->cantidad_impresion ?? 0) }}</td>
                    <th width="10%">Tipo consumible</th>
                    <td class="text-center">{{ $impresora->tipo_consumible ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th width="15%">Modelo consumible</th>
                    <td width="20%" class="text-center">{{ $impresora->modelo_consumible ?? 'N/A' }}</td>
                    <th width="15%">Capacidad impresion</th>
                    <td width="20%" class="text-center">{{ number_format($impresora->capacidad_impresion ?? 0) }}</td>
                    <th width="10%">Cantidad escaneo</th>
                    <td width="20%" class="text-center">{{ number_format($impresora->cantidad_escaneo ?? 0) }}</td>
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
            <div class="section-title">5. ESTADO ACTUAL DE LA IMPRESORA</div>
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
                        <!-- Descripción -->
                        <td>
                            @foreach($estados as $key => $label)
                                <label style="display:block; margin-bottom: 8px;">
                                {{ $label }}
                                </label>
                            @endforeach
                        </td>
                        <!-- Radio buttons -->
                        <td class="text-center">
                            @foreach($estados as $key => $label)
                                <label style="display:block; ">
                                <input type="radio" name="estado_impresora" value="{{ $key }}" 
                                    {{ $impresora->estado_impresora == $key ? 'checked' : '' }}>
                                </label>
                            @endforeach
                        </td>
                        <!-- Observaciones -->
                        <td style="font-size: 11.5px;">
                            {{ $mantenimiento->observacion_general ?? ($impresora->mantenimientos->first()->observacion_general ?? 'Sin observaciones generales') }}
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
                        <td>{{ $impresora->responsable->nombre_responsable ?? 'N/A' }}</td>
                        <td style="width: 40%; text-align: center;">
                            Usuario de Oficina {{ $impresora->oficina->nombre_oficina ?? '' }}
                        </td>
                        <td> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>