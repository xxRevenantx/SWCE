<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" type="image/png" href="{{ public_path('imagenes_publicas/logo-letra.png') }}" />
    <title>Horario del profesor</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        .sheet {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
        }

        .top-line {
            height: 3px;
            background: #0ea5e9;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            padding: 6px;
        }

        .title {
            text-align: center;

            letter-spacing: .7px;
            font-size: 14px;
        }

        .subtitle {
            text-align: center;
            margin-top: 2px;
            font-weight: 700;
            font-size: 10px;
            color: #334155;
        }

        .pills {
            text-align: center;
            margin: 8px 0 10px 0;
        }

        .pill {
            display: inline-block;
            background: #eef2ff;
            border: 1px solid #dbeafe;
            color: #1e3a8a;
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 9px;
            margin: 0 4px 4px 0;
        }

        table.horario {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            overflow: hidden;
        }

        table.horario th,
        table.horario td {
            border: 1px solid #e5e7eb;
            padding: 8px 8px;
            vertical-align: top;
        }

        table.horario thead th {
            background: #cbd5e1;
            color: #0f172a;

            text-transform: uppercase;
            font-size: 10px;
            text-align: center;
        }

        .col-hora {
            width: 15%;
            text-align: center;

            background: #f8fafc;
            white-space: nowrap;
            vertical-align: middle !important;
        }

        .materia-card {
            border: 1px solid #dbeafe;
            background: #f8fafc;
            border-radius: 10px;
            padding: 6px;
            margin-bottom: 6px;
        }

        .materia-card:last-child {
            margin-bottom: 0;
        }

        .materia {
            text-align: center;

            font-size: 10px;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .detalle {
            text-align: center;
            font-size: 9px;
            color: #334155;
            line-height: 1.2;
            margin-top: 2px;
        }

        .dash {
            width: 18px;
            height: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            display: inline-block;
            text-align: center;
            line-height: 18px;

            color: #475569;
            background: #f8fafc;
            margin: 0 auto;
        }

        .box {
            margin-top: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: #f8fafc;
        }

        .box-title {
            padding: 10px 12px;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 10px;
            color: #0f172a;
        }

        table.resumen {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin: 0 0 10px 0;
        }

        table.resumen th,
        table.resumen td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 10px;
        }

        table.resumen thead th {
            background: #cbd5e1;
            text-transform: uppercase;
            font-weight: 900;
            text-align: center;
        }

        .total {
            padding: 0 12px 12px 12px;
            font-weight: 900;
            font-size: 10px;
        }

        footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 5px;
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
    </style>
</head>

<body>
    @php
        // Se toman los días
        $diasPdf = $dias ?? collect();

        // Se aseguran las horas ya ordenadas
        $horasPdf = $horasDisponibles ?? [];

        // Se prepara nombre completo del profesor
        $nombreProfesor = trim(
            ($profesor->nombre ?? '') .
                ' ' .
                ($profesor->apellido_paterno ?? '') .
                ' ' .
                ($profesor->apellido_materno ?? ''),
        );

        // Se construye un resumen único por materia + licenciatura + cuatrimestre + generación
        $contador = 1;
        $resumen = [];

        foreach ($horasPdf as $hora) {
            foreach ($diasPdf as $dia) {
                $clases = $matrizHorario[$hora][$dia->id] ?? [];

                foreach ($clases as $clase) {
                    $claveUnica = md5(
                        ($clase['materia'] ?? '') .
                            '|' .
                            ($clase['licenciatura'] ?? '') .
                            '|' .
                            ($clase['cuatrimestre'] ?? '') .
                            '|' .
                            ($clase['generacion'] ?? ''),
                    );

                    if (!isset($resumen[$claveUnica])) {
                        $resumen[$claveUnica] = [
                            '#' => $contador++,
                            'materia' => $clase['materia'] ?? 'Sin materia',
                            'licenciatura' => $clase['licenciatura'] ?? 'Sin licenciatura',
                            'cuatrimestre' => $clase['cuatrimestre'] ?? 'Sin cuatrimestre',
                            'generacion' => $clase['generacion'] ?? 'Sin generación',
                        ];
                    }
                }
            }
        }

        // Total de bloques en el horario
        $totalBloques = 0;

        foreach ($horasPdf as $hora) {
            foreach ($diasPdf as $dia) {
                $clases = $matrizHorario[$hora][$dia->id] ?? [];
                $totalBloques += count($clases);
            }
        }

        date_default_timezone_set('America/Mexico_City');
        $fecha = date('d/m/Y H:i:s');
    @endphp

    <div class="sheet">
        <div class="top-line"></div>

        <table class="header">
            <tr>
                <td style="width:70px;">
                    <img class="logo" src="{{ public_path('imagenes_publicas/logo-letra.png') }}" alt="Logo">
                </td>

                <td>
                    <div class="title">CENTRO UNIVERSITARIO MOCTEZUMA</div>
                    <div class="subtitle">
                        HORARIO DEL PROFESOR
                    </div>
                    <div class="subtitle" style="margin-top: 4px;">
                        {{ mb_strtoupper($nombreProfesor ?: '—', 'UTF-8') }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="pills">
            <span class="pill">Licenciatura: {{ $licenciaturaId ?: 'Todas' }}</span>
            <span class="pill">Cuatrimestre: {{ $cuatrimestreId ?: 'Todos' }}</span>
            <span class="pill">Generación: {{ $generacionId ?: 'Todas' }}</span>
            <span class="pill">Bloques asignados: {{ $totalBloques }}</span>
        </div>

        <table class="horario">
            <thead>
                <tr>
                    <th class="col-hora">HORA</th>
                    @foreach ($diasPdf as $dia)
                        <th>{{ mb_strtoupper($dia->dia, 'UTF-8') }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @forelse($horasPdf as $hora)
                    <tr>
                        <td class="col-hora">{{ $hora }}</td>

                        @foreach ($diasPdf as $dia)
                            @php
                                $clases = $matrizHorario[$hora][$dia->id] ?? [];
                            @endphp

                            <td>
                                @if (count($clases) > 0)
                                    @foreach ($clases as $clase)
                                        <div class="materia-card">
                                            <div class="materia">{{ $clase['materia'] }}</div>
                                            <div class="detalle">Licenciatura: {{ $clase['licenciatura'] }}</div>
                                            <div class="detalle">Cuatrimestre: {{ $clase['cuatrimestre'] }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div style="text-align:center;">
                                        <span class="dash">–</span>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 1 + max(1, $diasPdf->count()) }}" style="text-align:center; padding:12px;">
                            No hay horario registrado para este profesor.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="box">
            <div class="box-title">RESUMEN DE CARGAS HORARIAS</div>

            <table class="resumen">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>MATERIA</th>
                        <th style="width:170px;">LICENCIATURA</th>
                        <th style="width:120px;">CUATRIMESTRE</th>
                        <th style="width:120px;">GENERACIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resumen as $r)
                        <tr>
                            <td style="text-align:center;">{{ $r['#'] }}</td>
                            <td>{{ $r['materia'] }}</td>
                            <td>{{ $r['licenciatura'] }}</td>
                            <td>{{ $r['cuatrimestre'] }}</td>
                            <td>{{ $r['generacion'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:10px; color:#64748b;">
                                Sin materias asignadas en el horario.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="total">
                Total de bloques asignados: {{ $totalBloques }}
            </div>
        </div>
    </div>

    <footer>
        <strong>Centro Universitario Moctezuma A.C.</strong> — C.C.T. 12PSU0173I<br>
        C. Francisco I. Madero Ote. No. 800, Col. Esquipula, C.P. 40665, Altamirano, Guerrero · Tel. 7676880774<br>
        <strong>Fecha de expedición:</strong> {{ $fecha }}
    </footer>
</body>

</html>
