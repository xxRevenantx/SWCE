<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <title>LISTA DE ALUMNOS
    </title>
</head>

<style>
    /* =========================
           DOMPDF: CONFIG BÁSICA
           ========================= */
    @page {
        margin: 28px 28px 36px 28px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        color: #111827;
    }

    /* =========================
           HEADER
           ========================= */
    .header {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 14px 14px;
        margin-bottom: 14px;
        background: #f8fafc;
    }

    .header-grid {
        width: 100%;
    }

    .title {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 4px 0;
    }

    .subtitle {
        margin: 0;
        color: #4b5563;
        font-size: 11px;
        line-height: 1.4;
    }

    .meta {
        text-align: right;
        vertical-align: top;
        white-space: nowrap;
    }

    .chip {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        font-size: 10px;
        color: #374151;
    }

    /* =========================
           TABLE
           ========================= */
    table {
        width: 100%;
        border-collapse: collapse;
        /* Dompdf friendly */
    }

    thead th {
        background: #0f172a;
        /* azul oscuro */
        color: #ffffff;
        font-weight: 700;
        font-size: 10px;
        letter-spacing: .2px;
        text-transform: uppercase;
        padding: 10px 8px;
        border: 1px solid #0f172a;
    }

    tbody td {
        padding: 9px 8px;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    /* Zebra */
    tbody tr:nth-child(even) td {
        background: #f9fafb;
    }

    /* =========================
           “Avatar” e info de alumno
           ========================= */
    .alumno-wrap {
        width: 100%;
    }

    .avatar {
        display: inline-block;
        width: 26px;
        height: 26px;
        line-height: 26px;
        text-align: center;
        border-radius: 8px;
        background: #e0f2fe;
        /* sky-100 */
        color: #075985;
        /* sky-800 */
        font-weight: 700;
        font-size: 10px;
        margin-right: 8px;
    }

    .alumno-name {
        font-weight: 700;
        font-size: 11px;
        margin: 0;
    }

    .alumno-sub {
        margin: 1px 0 0 0;
        font-size: 10px;
        color: #6b7280;
    }

    /* =========================
           Badges (Lic, Gen, Cuatr)
           ========================= */
    .badge {
        display: inline-block;
        padding: 5px 8px;
        border-radius: 999px;
        font-size: 10px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #111827;
        white-space: nowrap;
    }

    .badge-blue {
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #1e40af;
    }

    .badge-green {
        border-color: #bbf7d0;
        background: #ecfdf5;
        color: #065f46;
    }

    .badge-amber {
        border-color: #fde68a;
        background: #fffbeb;
        color: #92400e;
    }

    /* =========================
           FOOTER
           ========================= */
    footer {
        position: absolute;
        bottom: 0;
        left: 5%;
        text-align: center;
        font-size: 12px;
        line-height: 12px;
        width: 90%;
        margin: auto;
        border-top: 1px solid #4a5568;
        border-bottom: 1px solid #4a5568;
    }

    footer p {
        margin: 0;
        padding: 0;
    }
</style>
</head>

<body>

    {{-- Encabezado --}}
    <div class="header">
        <table class="header-grid">
            <tr>
                <td>
                    <p class="title">Lista de alumnos</p>
                </td>
                <td class="meta">
                    <span class="chip">Total: <strong>{{ $inscripciones->count() }}</strong></span><br>
                    <span class="chip">Fecha: <strong>{{ now()->format('d/m/Y H:i') }}</strong></span>
                </td>
            </tr>
        </table>
    </div>

    {{-- Tabla --}}
    <table>
        <thead>
            <tr>
                <th style="width: 26%;">Alumno</th>
                <th style="width: 14%;">Apellido paterno</th>
                <th style="width: 14%;">Apellido materno</th>
                <th style="width: 20%;">Licenciatura</th>
                <th style="width: 13%;">Generación</th>
                <th style="width: 13%;">Cuatrimestre</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($inscripciones as $matricula)
                @php
                    $nombre = $matricula->alumno->nombre ?? '';
                    $ap = $matricula->alumno->apellido_paterno ?? '';
                    $iniciales = mb_strtoupper(mb_substr($nombre, 0, 1) . mb_substr($ap, 0, 1));
                @endphp

                <tr>
                    <td>
                        <div class="alumno-wrap">
                            <span style="display:inline-block; vertical-align: top;">
                                <p class="alumno-name">{{ $matricula->alumno->nombre }}</p>
                                </p>
                            </span>
                        </div>
                    </td>

                    <td>{{ $matricula->alumno->apellido_paterno }}</td>
                    <td>{{ $matricula->alumno->apellido_materno }}</td>

                    <td>
                        <span class="badge badge-blue">{{ $matricula->licenciatura->nombre }}</span>
                    </td>

                    <td>
                        <span class="badge badge-green">{{ $matricula->generacion->generacion }}</span>
                    </td>

                    <td>
                        <span class="badge badge-amber">{{ $matricula->cuatrimestre->nombre_cuatrimestre }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 14px; text-align:center; color:#6b7280;">
                        No hay inscripciones para mostrar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <footer style="line-height: 20px">
        <p>Sistema Web de Control Escolar | {{ config('app.name') }} | Fecha de expedición:
            {{ \Carbon\Carbon::now()->locale('es')->isoFormat('DD/MM/YYYY') }}</p>
    </footer>

</body>

</html>
