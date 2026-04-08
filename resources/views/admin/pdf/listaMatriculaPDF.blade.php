<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Lista de alumnos</title>

    <style>
        @page {
            margin: 24px 24px 42px 24px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        /* =========================
           CONTENEDOR GENERAL
           ========================= */
        .sheet {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px;
            background: #ffffff;
        }

        .top-line {
            height: 4px;
            background: #0ea5e9;
            border-radius: 999px;
            margin-bottom: 12px;
        }

        /* =========================
           HEADER
           ========================= */
        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo {
            width: 76px;
            height: 76px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            padding: 5px;
            background: #ffffff;
        }

        .title {
            text-align: center;
            letter-spacing: .6px;
            font-size: 18px;
            text-transform: uppercase;
        }

        .subtitle {
            text-align: center;
            margin-top: 3px;

            font-size: 10px;
            color: #475569;
        }

        /* =========================
           PILLS
           ========================= */
        .pills {
            text-align: center;
            margin: 10px 0 12px 0;
        }

        .pill {
            display: inline-block;
            background: #f8fafc;
            border: 1px solid #dbeafe;
            color: #1e3a8a;
            border-radius: 999px;
            padding: 5px 9px;
            font-size: 9px;
            margin: 0 4px 4px 0;
        }

        .pill-dark {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
        }

        .pill-green {
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #047857;
        }

        .pill-amber {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #b45309;
        }

        /* =========================
           TABLA PRINCIPAL
           ========================= */
        table.lista {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            overflow: hidden;
        }

        table.lista th,
        table.lista td {
            border: 1px solid #e5e7eb;
            padding: 5px 5px;
            vertical-align: middle;
        }

        table.lista thead th {
            background: #cbd5e1;
            color: #0f172a;

            text-transform: uppercase;
            font-size: 9px;
            text-align: center;
        }

        table.lista tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        .col-num {
            width: 5%;
            text-align: center;

        }

        .col-matricula {
            width: 12%;
            text-align: center;
            white-space: nowrap;

            color: #1e293b;
        }

        .col-nombre {
            width: 26%;
        }

        .col-apellido {
            width: 14%;
        }



        .col-gen {
            width: 12%;
            text-align: center;
        }

        .col-cuatri {
            width: 13%;
            text-align: center;
        }

        .col-status {
            width: 10%;
            text-align: center;
        }

        .alumno {

            font-size: 10px;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .alumno-sub {
            font-size: 9px;
            color: #64748b;
        }

        .badge {
            display: inline-block;
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 9px;

            border: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .badge-blue {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        .badge-green {
            background: #ecfdf5;
            color: #047857;
            border-color: #bbf7d0;
        }

        .badge-amber {
            background: #fffbeb;
            color: #b45309;
            border-color: #fde68a;
        }

        .badge-slate {
            background: #f8fafc;
            color: #334155;
            border-color: #cbd5e1;
        }

        .badge-ok {
            background: #dcfce7;
            color: #166534;
            border-color: #86efac;
        }

        .badge-off {
            background: #fee2e2;
            color: #b91c1c;
            border-color: #fca5a5;
        }

        /* =========================
           RESUMEN
           ========================= */
        .box {
            margin-top: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: #f8fafc;
        }

        .box-title {
            padding: 10px 12px;

            text-transform: uppercase;
            font-size: 10px;
            color: #0f172a;
        }

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
        }

        .summary-grid td {
            border: 1px solid #e5e7eb;
            padding: 9px 10px;
            font-size: 10px;
        }

        .summary-label {

            color: #334155;
            width: 22%;
            background: #f8fafc;
        }

        .empty {
            text-align: center;
            color: #64748b;
            padding: 14px;
        }

        /* =========================
           FOOTER
           ========================= */
        footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 6px;
            text-align: center;
            font-size: 9px;
            color: #475569;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
        }

        footer p {
            margin: 0;
            line-height: 1.35;
        }
    </style>
</head>

<body>
    @php
        date_default_timezone_set('America/Mexico_City');

        $totalAlumnos = $inscripciones->count();

        $activos = $inscripciones
            ->filter(function ($item) {
                return (int) ($item->status ?? 0) === 1;
            })
            ->count();

        $bajas = $totalAlumnos - $activos;

        $fecha = now()->format('d/m/Y H:i:s');
    @endphp

    <div class="sheet">
        <div class="top-line"></div>

        <table class="header">
            <tr>
                <td style="width: 74px;">
                    <img class="logo" src="{{ public_path('imagenes_publicas/logo-letra.png') }}" alt="Logo">
                </td>

                <td>
                    <div class="title">Centro Universitario Moctezuma A.C.</div>
                    <div class="subtitle">
                        LISTA DE ALUMNOS
                    </div>
                </td>

                <td style="width: 74px; text-align:right;">
                    @if (!empty($licenciatura->logo) && file_exists(public_path('storage/licenciaturas/' . $licenciatura->logo)))
                        <img class="logo" src="{{ public_path('storage/licenciaturas/' . $licenciatura->logo) }}"
                            alt="Logo Licenciatura">
                    @endif
                </td>
            </tr>
        </table>

        <div class="pills">

            <span class="pill pill-green">Activos: {{ $activos }}</span>
            <span class="pill pill-dark">Total: {{ $totalAlumnos }}</span>
        </div>

        <table class="lista">
            <thead>
                <tr>
                    <th class="col-num">#</th>
                    <th class="col-matricula">Matrícula</th>
                    <th class="col-nombre">Nombre</th>
                    <th class="col-apellido">Apellido paterno</th>
                    <th class="col-apellido">Apellido materno</th>
                    <th class="col-lic">Licenciatura</th>
                    <th class="col-gen">Generación</th>
                    <th class="col-cuatri">Cuatrimestre</th>
                    <th class="col-status">Estado</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($inscripciones as $index => $inscripcion)
                    @php
                        $alumno = $inscripcion->alumno;
                        $nombreCompleto = trim(
                            ($alumno->nombre ?? '') .
                                ' ' .
                                ($alumno->apellido_paterno ?? '') .
                                ' ' .
                                ($alumno->apellido_materno ?? ''),
                        );

                        $matricula = $alumno->datosEscolares->matricula ?? ($inscripcion->matricula ?? '—');

                        $statusActivo = (int) ($inscripcion->status ?? 0) === 1;
                    @endphp

                    <tr>
                        <td class="col-num">{{ $index + 1 }}</td>

                        <td class="col-matricula">
                            {{ $matricula }}
                        </td>

                        <td class="col-nombre">
                            <div class="alumno">{{ $alumno->nombre ?? '—' }}</div>
                        </td>

                        <td class="col-apellido">
                            {{ $alumno->apellido_paterno ?? '—' }}
                        </td>

                        <td class="col-apellido">
                            {{ $alumno->apellido_materno ?? '—' }}
                        </td>

                        <td class="col-lic">
                            <span class="badge badge-blue">
                                {{ $inscripcion->licenciatura->nombre ?? '—' }}
                            </span>
                        </td>

                        <td class="col-gen">
                            <span class="badge badge-green">
                                {{ $inscripcion->generacion->generacion ?? '—' }}
                            </span>
                        </td>

                        <td class="col-cuatri">
                            <span class="badge badge-amber">
                                {{ $inscripcion->cuatrimestre->nombre_cuatrimestre ?? '—' }}
                            </span>
                        </td>

                        <td class="col-status">
                            @if ($statusActivo)
                                <span class="badge badge-ok">Activo</span>
                            @else
                                <span class="badge badge-off">Baja</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty">
                            No hay alumnos registrados para los filtros seleccionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>


    </div>

    <footer>
        <p><strong>Centro Universitario Moctezuma A.C.</strong> — C.C.T. 12PSU0173I</p>
        <p>C. Francisco I. Madero Ote. No. 800, Col. Esquipula, C.P. 40665, Altamirano, Guerrero · Tel. 7676880774</p>
        <p><strong>Fecha de expedición:</strong> {{ $fecha }}</p>
    </footer>
</body>

</html>
