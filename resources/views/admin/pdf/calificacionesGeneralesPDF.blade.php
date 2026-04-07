<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ public_path('imagenes_publicas/logo-letra.png') }}" type="image/png">
    <title>
        CALIFICACIONES GENERALES - {{ $nombreLicenciatura }} - CUATRIMESTRE {{ $nombreCuatrimestre }} - GENERACIÓN
        {{ $nombreGeneracion }}
    </title>

    @php
        $plantelNombre = 'Centro Universitario Moctezuma';
        $plantelCCT = '12PSU0173I';

        $plantelCalle = 'Francisco I. Madero Oriente';
        $plantelNo = '800';
        $plantelColonia = 'Esquipula';
        $plantelCP = '40662';
        $plantelCiudad = 'Ciudad Altamirano';
        $plantelEstado = 'Guerrero';
    @endphp

    <style>
        @page {
            margin: 18px 34px 55px 34px;
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
            font-family: 'calibri', sans-serif;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.25;
        }

        * {
            box-sizing: border-box;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .fw-700 {
            font-weight: 700;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-8 {
            margin-bottom: 8px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-12 {
            margin-bottom: 12px;
        }

        .mb-16 {
            margin-bottom: 16px;
        }

        .small {
            font-size: 10px;
        }

        .xs {
            font-size: 9px;
        }

        .page-bg {
            position: fixed;
            inset: 0;
            z-index: -20;
        }

        .bg-top-left {
            position: absolute;
            top: -40px;
            left: -40px;
            width: 220px;
            height: 220px;
            background: #e0f2fe;
            border-radius: 999px;
            opacity: .45;
        }

        .bg-bottom-right {
            position: absolute;
            right: -60px;
            bottom: 40px;
            width: 240px;
            height: 240px;
            background: #ede9fe;
            border-radius: 999px;
            opacity: .35;
        }

        .watermark {
            position: fixed;
            top: 53%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 58%;
            z-index: -10;
            opacity: 0.045;
            text-align: center;
        }

        .watermark img {
            width: 100%;
        }

        .top-accent {
            height: 8px;
            width: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #0ea5e9 0%, #2563eb 50%, #7c3aed 100%);
            margin-bottom: 12px;
        }

        .hero {
            border: 1px solid #dbeafe;
            border-radius: 22px;
            padding: 14px 16px 12px 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .hero-table {
            width: 100%;
            border-collapse: collapse;
        }

        .hero-table td {
            vertical-align: middle;
        }

        .logo-plantel {
            width: 82px;
            height: 82px;
            object-fit: contain;
        }

        .logo-licenciatura {
            width: 78px;
            height: 78px;
            object-fit: contain;
        }

        .hero-title {
            text-align: center;
            padding: 0 12px;
        }

        .hero-title .plantel {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: .3px;
        }

        .hero-title .subtitle {
            margin: 6px 0 0 0;
        }

        .pill-main {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid #bfdbfe;
            background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%);
            color: #1d4ed8;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: .2px;
        }

        .hero-title .desc {
            margin-top: 7px;
            font-size: 10.5px;
            color: #475569;
        }

        .chips-wrap {
            margin-top: 12px;
        }

        .chip {
            display: inline-block;
            padding: 5px 10px;
            margin: 3px 4px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
        }

        .chip-info {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        .chip-violet {
            background: #f5f3ff;
            border-color: #ddd6fe;
            color: #6d28d9;
        }

        .chip-slate {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #334155;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 22px;
            overflow: hidden;
            background: #ffffff;
            margin-top: 14px;
        }

        .card-head {
            padding: 10px 14px;
            background: linear-gradient(90deg, #0f172a 0%, #1e3a8a 55%, #1d4ed8 100%);
            color: #ffffff;
        }

        .card-head-title {
            font-size: 12.5px;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .card-head-subtitle {
            margin-top: 2px;
            font-size: 9.5px;
            opacity: .92;
        }

        .table-wrap {
            padding: 0;
        }

        .tabla-premium {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
        }

        .tabla-premium thead th {
            background: #f8fafc;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #eef2f7;
            padding: 8px 6px;
            font-size: 9px;
            text-align: center;
            vertical-align: middle;
        }

        .tabla-premium thead th:last-child {
            border-right: 0;
        }

        .tabla-premium thead th.col-estudiante {
            width: 220px;
            text-align: left;
            padding-left: 12px;
        }

        .tabla-premium thead th.col-promedio {
            width: 84px;
        }

        .tabla-premium tbody td {
            border-top: 1px solid #eef2f7;
            border-right: 1px solid #f1f5f9;
            padding: 7px 6px;
            font-size: 10px;
            vertical-align: middle;
            background: #ffffff;
        }

        .tabla-premium tbody tr:nth-child(even) td {
            background: #fcfdff;
        }

        .tabla-premium tbody td:last-child {
            border-right: 0;
        }

        .celda-estudiante {
            padding-left: 12px !important;
        }

        .nombre-estudiante {
            font-size: 10.5px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.15;
        }

        .matricula-estudiante {
            margin-top: 3px;
            font-size: 8.8px;
            color: #64748b;
            line-height: 1.1;
        }

        .materia-clave {
            font-size: 8.5px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.05;
            margin-bottom: 2px;
        }

        .materia-nombre {
            font-size: 8px;
            color: #475569;
            line-height: 1.05;
        }

        .celda-cal {
            text-align: center;
            white-space: nowrap;
        }

        .badge {
            display: inline-block;
            min-width: 38px;
            padding: 3px 8px;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            font-weight: 700;
            font-size: 9.5px;
            line-height: 1.1;
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
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #475569;
        }

        .empty-box {
            padding: 18px;
            text-align: center;
            color: #64748b;
            font-size: 11px;
        }

        footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 10px;
            padding: 0 34px;
        }

        .footer-box {
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            text-align: center;
            color: #475569;
            font-size: 9.5px;
            line-height: 1.25;
        }

        .footer-box p {
            margin: 0;
        }

        .footer-box .foot-strong {
            font-weight: 700;
            color: #334155;
        }

        tr,
        td,
        th {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="page-bg">
        <div class="bg-top-left"></div>
        <div class="bg-bottom-right"></div>
    </div>

    <div class="watermark">
        <img src="{{ public_path('imagenes_publicas/logo-letra.png') }}" alt="Marca de agua">
    </div>

    <div class="top-accent"></div>

    <div class="hero">
        <table class="hero-table">
            <tr>
                <td style="width: 90px;">
                    <img class="logo-plantel" src="{{ public_path('imagenes_publicas/logo-letra.png') }}"
                        alt="Logo plantel">
                </td>

                <td class="hero-title">
                    <p class="plantel uppercase">{{ $plantelNombre }}</p>

                    <div class="subtitle">
                        <span class="pill-main uppercase">Calificaciones Generales</span>
                    </div>

                    <div class="desc">
                        Reporte general de calificaciones por grupo académico
                    </div>

                    <div class="chips-wrap">
                        <span class="chip chip-info uppercase">{{ $nombreLicenciatura }}</span>
                        <span class="chip chip-violet">Cuatrimestre {{ $nombreCuatrimestre }}</span>
                        <span class="chip chip-slate">Generación {{ $nombreGeneracion }}</span>
                    </div>
                </td>

                <td style="width: 90px;" class="text-right">
                    @if (!empty($licenciatura?->logo) && file_exists(public_path('storage/licenciaturas/' . $licenciatura->logo)))
                        <img class="logo-licenciatura"
                            src="{{ public_path('storage/licenciaturas/' . $licenciatura->logo) }}"
                            alt="Logo licenciatura">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="card">
        <div class="card-head">
            <div class="card-head-title uppercase">Listado General de Calificaciones</div>
            <div class="card-head-subtitle">
                Se muestran las materias registradas y el promedio final truncado a un decimal.
            </div>
        </div>

        <div class="table-wrap">
            <table class="tabla-premium">
                <thead>
                    <tr>
                        <th class="col-estudiante">Estudiante</th>

                        @foreach ($materias as $m)
                            <th>
                                @if (!empty($m->clave))
                                    <div class="materia-clave">{{ $m->clave }}</div>
                                @endif
                                <div class="materia-nombre">{{ $m->nombre }}</div>
                            </th>
                        @endforeach

                        <th class="col-promedio">Promedio</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($alumnos as $a)
                        <tr>
                            <td class="celda-estudiante">
                                <div class="nombre-estudiante">{{ $a->nombre_completo }}</div>
                                @if (!empty($a->matricula))
                                    <div class="matricula-estudiante">Matrícula: {{ $a->matricula }}</div>
                                @endif
                            </td>

                            @foreach ($materias as $m)
                                @php
                                    $valor = $matriz[$a->inscripcion_id][$m->asignacion_materia_id] ?? null;

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

                                if ($prom === null) {
                                    $claseProm = 'badge badge-na';
                                } elseif ((float) $prom < 6.0) {
                                    $claseProm = 'badge badge-rep';
                                } else {
                                    $claseProm = 'badge badge-ok';
                                }
                            @endphp

                            <td class="celda-cal">
                                <span class="{{ $claseProm }}">
                                    {{ $prom !== null ? number_format((float) $prom, 1, '.', '') : '—' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + $materias->count() }}" class="empty-box">
                                No hay registros con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <div class="footer-box">
            <p class="foot-strong uppercase">{{ $plantelNombre }} · C.C.T. {{ $plantelCCT }}</p>
            <p>
                C. {{ $plantelCalle }} No. {{ $plantelNo }}, Col. {{ $plantelColonia }}, C.P.
                {{ $plantelCP }},
                Cd. {{ $plantelCiudad }}, {{ $plantelEstado }}.
            </p>
            <p>Fecha de expedición: {{ now()->translatedFormat('d \\d\\e F \\d\\e\\l Y \\a \\l\\a\\s H:i') }}</p>
        </div>
    </footer>

</body>

</html>
