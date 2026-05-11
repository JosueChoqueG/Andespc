<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Hoja de Vida - {{ $impresora->serie_impresora }}</title>
        <style>
            @page { size: A4; margin: 8mm; }
            body {
                font-family: Arial, sans-serif;
                font-size: 10px;
                margin: 0;
                padding: 0;
                background-color: white;
            }
            .page {
                width: 100%;
                background: white;
                padding: 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 8px;
                table-layout: fixed;
            }
            th, td {
                border: 1px solid #000;
                padding: 4px;
                vertical-align: middle;
                word-wrap: break-word;
            }
            th {
                font-weight: bold;
                text-align: center;
                background-color: #f2f2f2;
            }
            .section-title {
                background-color: #d9d9d9;
                font-weight: bold;
                padding: 4px;
                border: 1px solid #000;
                margin-top: 8px;
                margin-bottom: -1px;
                text-transform: uppercase;
                font-size: 9px;
            }
            .text-center { text-align: center; }
            ul { margin: 0; padding-left: 12px; }
            .no-break { page-break-inside: avoid; }
        </style>
    </head>
    <body>
        <div class="page">
            <table>
                <tr>
                    <td style="width: 20%; padding: 0; text-align: center;">
                        @if(isset($logoPath))
                            <img src="{{ $logoPath }}" alt="LOGO" style="width: 80px;">
                        @else
                            <strong>LOS ANDES</strong>
                        @endif
                    </td>
                    <td style="width: 55%; text-align: center; font-size: 12px; font-weight: bold;">
                        HOJA DE VIDA DE EQUIPOS INFORMÁTICOS (IMPRESORAS)
                        <br><span style="font-size: 14px; font-weight: normal;">
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
                    <td colspan="2"></td>
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
                        <th style="width: 12%;">Fecha</th>
                        <th style="width: 12%;">Tipo</th>
                        <th>Actividad realizada</th>
                        <th style="width: 25%;">Observaciones</th>
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
                <tr>
                    <th width="25%">Descripción</th>
                    <th width="10%" class="text-center">Estado</th>
                    <th width="65%">Observaciones Generales</th>
                </tr>
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
                            {{ $label }}<br>
                        @endforeach
                    </td>
                    <td class="text-center">
                        @foreach($estados as $key => $label)
                            [ {{ $impresora->estado_impresora == $key ? 'X' : ' ' }} ]<br>
                        @endforeach
                    </td>
                    <td>
                        {{ $mantenimiento->observacion_general ?? ($impresora->mantenimientos->first()->observacion_general ?? 'Sin observaciones generales') }}
                    </td>
                </tr>
            </table>

            <div class="no-break">
                <div class="section-title">6. RESPONSABLES</div>
                <table>
                    <thead>
                        <tr>
                            <th width="25%"></th>
                            <th width="35%">NOMBRE Y APELLIDO</th>
                            <th>CARGO</th>
                            <th width="15%">FIRMA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="height: 40px;">6.1 Ejecutivo TI</td>
                            <td>{{ $tecnico }}</td>
                            <td>Asistente de Infraestructura Informática</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="height: 40px;">6.2 Usuario Asignado</td>
                            <td>{{ $impresora->responsable->nombre_responsable ?? 'N/A' }}</td>
                            <td>Usuario de Oficina {{ $impresora->oficina->nombre_oficina ?? '' }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
