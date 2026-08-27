<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>INFORME N° {{ str_pad($informe->id, 2, '0', STR_PAD_LEFT) }} - {{ $informe->impresora->oficina->nombre_oficina ?? 'IMPRESORA' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #fff;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            padding: 0;
        }

        /* DomPDF maneja el tamaño de página A4 con @page */
        @page {
            margin: 1.27cm 2.09cm 0.49cm 2.40cm; /* arriba, derecha, abajo, izquierda */
        }

        .pagina {
            width: 100%;
            position: relative;
        }

        /* ENCABEZADO */
        .encabezado-cooperativa {
            display: block;
            text-align: center;
            margin-bottom: 10px;
        }

        .logo-container img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }

        .anio-gobierno {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin: 8px 0;
        }

        .fecha-lugar {
            text-align: right;
            font-size: 12pt;
            margin-bottom: 18px;
        }

        .titulo-informe {
            font-size: 12pt;
            font-weight: bold;
            margin: 10px 0 18px 0;
            text-decoration: underline;
            text-align: center;
        }

        /* TABLA DE DESTINATARIOS */
        .tabla-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 12pt;
        }

        .tabla-meta td {
            border: none;
            padding: 3px 8px 3px 0;
            vertical-align: top;
        }

        .tabla-meta .etiqueta {
            width: 70px;
            font-weight: bold;
            white-space: nowrap;
            padding-right: 15px;
        }

        .tabla-meta .contenido .cargo {
            font-size: 12pt;
            color: #222;
            display: block;
        }

        .cuerpo {
            font-size: 12pt;
            text-align: justify;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .cuerpo.primer-parrafo {
            border-top: 1px solid #000;
            padding-top: 12px;
            margin-top: 15px;
        }

        .seccion {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 14px;
            margin-bottom: 8px;
        }

        .datos-equipo {
            font-size: 12pt;
            line-height: 1.8;
            margin-bottom: 12px;
        }

        .lista-proc {
            font-size: 12pt;
            margin-left: 25px;
            margin-bottom: 14px;
            line-height: 1.7;
        }

        .lista-proc li {
            margin-bottom: 3px;
        }

        .caja-conclusion {
            padding: 8px 0;
            margin-bottom: 14px;
            font-size: 12pt;
            line-height: 1.5;
            text-align: justify;
        }

        .caja-conclusion p {
            margin-bottom: 6px;
        }

        .recomendaciones, .caja-texto {
            padding: 8px 0;
            margin-bottom: 14px;
            font-size: 12pt;
            line-height: 1.5;
            text-align: justify;
        }

        .evidencia-titulo {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 16px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .img-evidence {
            width: 100%;
            max-width: 480px;
            max-height: 600px;
            display: block;
            margin: 0 auto;
            border: 1px solid #bbb;
        }

        .caption {
            font-size: 12pt;
            font-style: italic;
            margin-top: 10px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .salto-pagina {
            page-break-after: always;
        }

        /* HEADER FIJO PARA DOMPDF (se repite en cada página) */
        header {
            position: fixed;
            top: -1cm;
            left: 0px;
            right: 0px;
            height: 80px;
            text-align: center;
        }
    </style>
</head>
<body>

    <header>
        <div class="encabezado-cooperativa">
            <div class="logo-container">
                @php
                    $logoData = null;
                    if(isset($logoPath) && is_file($logoPath)) {
                        $logoData = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));
                    }
                @endphp
                @if($logoData)
                    <img src="{{ $logoData }}" alt="Logo Los Andes">
                @else
                    <strong style="font-size:16pt">[LOGO LOS ANDES]</strong>
                @endif
            </div>
        </div>
    </header>

    <!-- Espacio para el header fijo -->
    <div style="margin-top: 60px;"></div>

    @php
        $meses = ['January' => 'enero', 'February' => 'febrero', 'March' => 'marzo',
                  'April' => 'abril', 'May' => 'mayo', 'June' => 'junio',
                  'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre',
                  'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'];
        $dia  = $informe->fecha_informe->format('d');
        $mes  = $meses[$informe->fecha_informe->format('F')];
        $anio = $informe->fecha_informe->format('Y');
        $oficinaNombre = $informe->impresora->oficina->nombre_oficina ?? 'Abancay';

        // Procedimientos como array
        $procs = $informe->procedimientos ?? [];
    @endphp

    <!-- PÁGINA 1 -->
    <div class="pagina">
        <div class="anio-gobierno">"Año de la Esperanza y el Fortalecimiento de la Democracia"</div>
        <div class="fecha-lugar">{{ $oficinaNombre }}, {{ $dia }} de {{ $mes }} de {{ $anio }}</div>

        <div class="titulo-informe">
            INFORME N° {{ str_pad($informe->id, 2, '0', STR_PAD_LEFT) }}-{{ $anio }}-JCG/UII/TI/COOPAC LOS ANDES
        </div>

        <!-- TABLA DE DESTINATARIOS -->
        <table class="tabla-meta">
            <tr>
                <td class="etiqueta">A</td>
                <td class="contenido">
                    Zenon Serrano Quispe
                    <span class="cargo">Jefe de Logística</span>
                </td>
            </tr>
            <tr>
                <td class="etiqueta">CC</td>
                <td class="contenido">
                    Helard Niño de Guzman Bedoya
                    <span class="cargo">Responsable de la Unidad de Patrimonio y Almacén</span>
                </td>
            </tr>
            <tr>
                <td class="etiqueta">De</td>
                <td class="contenido">
                    {{ $informe->tecnico_nombre }}
                    <span class="cargo">Asistente de Infraestructura Informática</span>
                </td>
            </tr>
            <tr>
                <td class="etiqueta">Asunto</td>
                <td class="contenido">Incidencia {{ strtolower($informe->tipo_informe) }} en la oficina {{ $oficinaNombre }}</td>
            </tr>
            <tr>
                <td class="etiqueta">Fecha</td>
                <td class="contenido">{{ $informe->fecha_informe->format('d/m/Y') }}</td>
            </tr>
        </table>

        @if($informe->descripcion_problema)
        <div class="cuerpo primer-parrafo">
            {!! nl2br(e($informe->descripcion_problema)) !!}
        </div>
        @else
        <div class="cuerpo primer-parrafo">
            Es grato dirigirme a usted para saludarlo cordialmente e informarle sobre la impresora multifuncional {{ $informe->impresora->marca_impresora }} {{ $informe->impresora->modelo_impresora }}, con número de serie {{ $informe->impresora->serie_impresora }}. Dicho equipo fue asignado a la oficina {{ $oficinaNombre }}.
        </div>
        @endif

        <div class="datos-equipo">
            <strong>Modelo:</strong> {{ $informe->impresora->modelo_impresora }}<br>
            <strong>Número de Serie:</strong> {{ $informe->impresora->serie_impresora }}<br>
            <strong>Lugar:</strong> {{ $oficinaNombre }}
        </div>

        @if($informe->incidencias)
        <div class="seccion">[Incidencias]</div>
        <div class="datos-equipo">{{ $informe->incidencias }}</div>
        @endif

        @if(count($procs) > 0)
        <div class="seccion">[Procedimientos]</div>
        <ul class="lista-proc">
            @foreach($procs as $paso)
                <li>{{ $paso }}</li>
            @endforeach
        </ul>
        @endif
    </div>

    <div class="salto-pagina"></div>

    <!-- PÁGINA 2 -->
    <div class="pagina">
        <div class="seccion">[Conclusión]</div>
        <div class="caja-conclusion">
            @if($informe->observaciones)
                @foreach(explode("\n", trim($informe->observaciones)) as $linea)
                    @if(trim($linea))
                    <p>{{ $linea }}</p>
                    @endif
                @endforeach
            @endif
            @if($informe->contador_copias)
            <p>
                Como antecedentes sobre las impresoras {{ $informe->impresora->marca_impresora }} al pasar las 25000 copias/impresiones empiezan a presentar fallos y en este caso el equipo presentó fallas a las {{ number_format($informe->contador_copias) }} copias/impresiones.
            </p>
            @endif
        </div>

        <div class="seccion">[Recomendaciones]</div>
        <div class="recomendaciones">
            @if($informe->recomendaciones)
                {!! nl2br(e($informe->recomendaciones)) !!}
            @else
                Sin recomendaciones registradas.
            @endif
        </div>
    </div>

    <!-- PÁGINAS DE EVIDENCIAS -->
    @if($informe->imagen_01_path)
    <div class="salto-pagina"></div>
    <div class="pagina">
        <div class="evidencia-titulo">[EVIDENCIA ATASCO DE PAPEL]</div>
        @php
            $img1Path = storage_path('app/public/' . $informe->imagen_01_path);
            $img1Data = null;
            if (!empty($informe->imagen_01_path) && is_file($img1Path)) {
                $img1Data = 'data:' . mime_content_type($img1Path) . ';base64,' . base64_encode(file_get_contents($img1Path));
            }
        @endphp
        @if($img1Data)
            <img src="{{ $img1Data }}" class="img-evidence" alt="Evidencia 01">
        @else
            <div class="caja-texto" style="text-align:center;color:#999;border:1px solid #ccc;padding:20px;">[Imagen no disponible]</div>
        @endif
        @if($informe->imagen_01_caption)
            <div class="caption">{{ $informe->imagen_01_caption }}</div>
        @endif
    </div>
    @endif

    @if($informe->imagen_02_path)
    <div class="salto-pagina"></div>
    <div class="pagina">
        <div class="evidencia-titulo">[Página de estado de la impresora]</div>
        @php
            $img2Path = storage_path('app/public/' . $informe->imagen_02_path);
            $img2Data = null;
            if (!empty($informe->imagen_02_path) && is_file($img2Path)) {
                $img2Data = 'data:' . mime_content_type($img2Path) . ';base64,' . base64_encode(file_get_contents($img2Path));
            }
        @endphp
        @if($img2Data)
            <img src="{{ $img2Data }}" class="img-evidence" alt="Evidencia 02">
        @else
            <div class="caja-texto" style="text-align:center;color:#999;border:1px solid #ccc;padding:20px;">[Imagen no disponible]</div>
        @endif
        @if($informe->imagen_02_caption)
            <div class="caption">{{ $informe->imagen_02_caption }}</div>
        @endif
    </div>
    @endif

</body>
</html>
