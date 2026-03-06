<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ public_path('imagenes_publicas/logo-letra.png') }}" type="image/png">
    <title>CALIFICACIONES GENERALES DE {{ $nombreLicenciatura }} {{ $nombreGeneracion }} {{ $nombreCuatrimestre }}
    </title>

    @php

        $plantelNombre = 'Centro Universitario Moctezuma';
        $plantelCCT = '12PSU0173I';

        $plantelCalle = 'Francisco I. Madero Oriente';
        $plantelNo = '800';
        $plantelColonia = 'Esquipula';
        $plantelCP = '40662';
        $plantelCiudad = 'Ciudad Altamirano';
        $plantelMunicipio = 'Pungarabato';
        $plantelEstado = 'Guerrero';
    @endphp

    <style>
        /* ========= Página y tipografías ========= */
        @page {
            margin: 14px 48px 0px 48px;
        }

        @font-face {
            font-family: 'calibri';
            font-style: normal;
            src: url('{{ storage_path('fonts/calibri/calibri.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'calibri';
            font-weight: 700;
            src: url('{{ storage_path('fonts/calibri/calibri-bold.ttf') }}') format('truetype');
        }

        html,
        body {
            font-family: sans-serif;
            color: #1f2937;
            font-size: 11px;
            line-height: 25px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .fw-700 {
            font-weight: 700;
        }

        .muted {
            color: #6b7280;
        }

        .mb-2 {
            margin-bottom: 6px;
        }

        .mb-4 {
            margin-bottom: 10px;
        }

        .mb-6 {
            margin-bottom: 14px;
        }

        .mb-8 {
            margin-bottom: 18px;
        }

        .mt-2 {
            margin-top: 6px;
        }

        .small {
            font-size: 10px;
        }

        .xs {
            font-size: 9px;
        }

        .lg {
            font-size: 13px;
        }

        .xl {
            font-size: 16px;
        }

        .banner {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 8px 12px;
            margin-top: 4px;
            margin-bottom: 8px;
            background: linear-gradient(180deg, #f8fafc 0%, #f3f4f6 100%);
        }

        .banner-table {
            width: 100%;
            border-collapse: collapse;
        }

        .banner-table td {
            vertical-align: middle;
        }

        .logo {
            width: 78px;
            height: 78px;
            object-fit: contain;
        }

        .titular {
            text-align: center;
        }

        .titular h1 {
            margin: 0;
            font-size: 18px;
            letter-spacing: .3px;
            color: #334155;
            font-weight: 700;
        }

        .titular h2 {
            margin: 2px 0 0;
            font-size: 13px;
            color: #475569;
            font-weight: 700;
        }

        .titular .chip {
            display: inline-block;
            margin-top: 6px;
            padding: 2px 8px;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            background: #ffffff;
            font-weight: 700;
        }

        .watermark {
            position: fixed;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            opacity: 0.06;
            z-index: -1;
            text-align: center;
        }

        .watermark img {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px 8px;
        }

        .meta {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .meta thead th {
            background: #0ea5e9;
            color: #fff;
            font-weight: 700;
            letter-spacing: .4px;
            padding: 6px 8px;
            font-size: 13px;
            text-align: center;
        }

        .meta tbody td {
            text-align: center;
            border-top: 1px solid #eef2f7;
            font-size: 11px;
        }

        .meta .row-alt td {
            background: #f8fafc;
        }

        .grades {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 16px;
        }

        .grades thead th {
            background: #0284c7;
            color: #fff;
            font-weight: 700;
            letter-spacing: .4px;
            font-size: 10px;
            padding: 7px 8px;
        }

        .grades tbody td {
            border-top: 1px solid #eef2f7;
            font-size: 11px;
            vertical-align: middle;
        }

        .grades tbody tr:nth-child(even) {
            background: #fbfdff;
        }

        .col-asig {
            text-align: left;
        }

        .col-cal {
            width: 110px;
            text-align: center;
        }

        .score {
            display: inline-block;
            min-width: 42px;
            padding: 0px 8px;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            background: #fff;
            font-weight: 700;
        }

        .ok {
            background: #ecfdf5;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .bien {
            background: #f2f2f2;
            border-color: #dadada;
            color: #3d3d3d;
        }

        .rep {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .np {
            background: #fff1f2;
            border-color: #fecdd3;
            color: #9f1239;
        }

        .enproceso {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #334155;
        }

        .resumen {
            margin-top: 20px;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 8px 10px;
            background: #f8fafc;
        }

        .resumen .lbl {
            font-weight: 700;
            margin-top: 10px;
        }

        .resumen .valor {
            display: inline-block;
            min-width: 58px;
            text-align: center;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            font-size: 13px;
        }

        footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 12px;
            text-align: center;
            font-size: 10px;
            color: #475569;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
        }

        footer p {
            margin: 0;
            line-height: 1.25;
        }

        tr,
        td,
        th {
            page-break-inside: avoid;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        <style>

        /* ========= Tabla Boleta (Pro) ========= */
        .boleta {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 10px;
            table-layout: fixed;
            /* ayuda a que no se rompa feo en PDF */
        }

        .boleta thead th {
            background: #0f172a;
            /* encabezado oscuro elegante */
            color: #ffffff;
            padding: 8px 8px;
            font-size: 9.5px;
            line-height: 1.15;
            letter-spacing: .2px;
            text-align: center;
            border-right: 1px solid rgba(255, 255, 255, .12);
            vertical-align: middle;
        }

        .boleta thead th:last-child {
            border-right: 0;
        }

        .boleta thead th.th-estudiante {
            text-align: left;
            width: 220px;
            /* ajusta si necesitas */
        }

        .boleta tbody td {
            padding: 7px 8px;
            font-size: 10.5px;
            line-height: 1.15;
            border-top: 1px solid #eef2f7;
            border-right: 1px solid #eef2f7;
            vertical-align: middle;
            background: #ffffff;
        }

        .boleta tbody tr:nth-child(even) td {
            background: #fbfdff;
        }

        .boleta tbody td:last-child {
            border-right: 0;
        }

        .boleta .alumno-nombre {
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.15;
        }

        .boleta .alumno-matricula {
            font-size: 9px;
            color: #64748b;
            margin-top: 3px;
            line-height: 1.15;
        }

        .boleta .materia-clave {
            font-weight: 700;
            font-size: 9px;
            line-height: 1.1;
            margin: 0;
        }

        .boleta .materia-nombre {
            font-size: 8.6px;
            line-height: 1.1;
            margin-top: 2px;
            opacity: .9;
        }

        /* Chip de calificación */
        .boleta .celda-cal {
            text-align: center;
            white-space: nowrap;
        }

        .badge {
            display: inline-block;
            min-width: 34px;
            padding: 2px 8px;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            font-weight: 700;
            font-size: 10px;
            line-height: 1.2;
            color: #0f172a;
        }

        .badge-ok {
            background: #ecfdf5;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .badge-rep {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .badge-np {
            background: #fff1f2;
            border-color: #fecdd3;
            color: #9f1239;
        }

        .badge-na {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #334155;
            font-weight: 700;
            min-width: 34px;
        }
    </style>


    <head>


    <body>

        <div class="banner">
            <table class="banner-table">
                <tr>
                    <td style="width:92px;">
                        <img class="logo" src="{{ public_path('imagenes_publicas/logo-letra.png') }}"
                            alt="Logo Izquierdo">
                    </td>
                    <td class="titular">
                        <h1 class="uppercase" style="font-size: 20px">{{ $plantelNombre }}</h1>
                        <div class="chip uppercase" style="font-size: 18px">
                            Boleta de Calificaciones
                        </div>
                    </td>
                    <td style="width:92px; text-align:right;">
                        @if (!empty($licenciatura->logo) && file_exists(public_path('storage/licenciaturas/' . $licenciatura->logo)))
                            <img class="logo" src="{{ public_path('storage/licenciaturas/' . $licenciatura->logo) }}"
                                alt="Logo Licenciatura">
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <table class="boleta">
            <thead>
                <tr>
                    <th class="th-estudiante">Estudiante</th>

                    @foreach ($materias as $m)
                        <th>
                            @if (!empty($m->clave))
                                <div class="materia-clave">{{ $m->clave }}</div>
                            @endif
                            <div class="materia-nombre">{{ $m->nombre }}</div>
                        </th>
                    @endforeach

                    <th class="th-promedio">Promedio</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($alumnos as $a)
                    <tr>
                        <td>
                            <div class="alumno-nombre">{{ $a->nombre_completo }}</div>
                            @if (!empty($a->matricula))
                                <div class="alumno-matricula">{{ $a->matricula }}</div>
                            @endif
                        </td>

                        @foreach ($materias as $m)
                            @php
                                $valor = $matriz[$a->inscripcion_id][$m->asignacion_materia_id] ?? null;

                                $clase = 'badge';
                                if ($valor === null || !is_numeric($valor)) {
                                    $clase = 'badge badge-na';
                                } elseif ((float) $valor === 0.0) {
                                    $clase = 'badge badge-np';
                                } elseif ((float) $valor < 6.0) {
                                    $clase = 'badge badge-rep';
                                } else {
                                    $clase = 'badge badge-ok';
                                }
                            @endphp

                            <td class="celda-cal">
                                <span class="{{ $clase }}">
                                    {{ $valor !== null ? $valor : '—' }}
                                </span>
                            </td>
                        @endforeach

                        @php
                            $prom = $promedios[$a->inscripcion_id] ?? null;

                            $claseProm = 'badge badge-prom';
                            if ($prom === null) {
                                $claseProm = 'badge badge-na';
                            } elseif (is_numeric($prom) && (float) $prom < 6.0) {
                                $claseProm = 'badge badge-rep';
                            }
                        @endphp

                        <td class="celda-cal">
                            <span class="{{ $claseProm }}">
                                {{ $prom !== null ? number_format((float) $prom, 1) : '—' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 2 + $materias->count() }}" style="text-align:center; padding:14px;">
                            No hay registros con esos filtros.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>


        <footer>
            <p class="uppercase fw-700">{{ $plantelNombre }} · C.C.T. {{ $plantelCCT }}</p>
            <p>
                C. {{ $plantelCalle }} No. {{ $plantelNo }}, Col. {{ $plantelColonia }},
                C.P. {{ $plantelCP }}, Cd. {{ $plantelCiudad }}, {{ $plantelEstado }}.
            </p>
            <p>Fecha de expedición: {{ now()->translatedFormat('d \\d\\e F \\d\\e\\l Y \\a \\l\\a\\s H:i') }}</p>
        </footer>


    </body>

</html>
