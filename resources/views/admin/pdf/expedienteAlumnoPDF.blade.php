<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <title>EXPEDIENTE DEL ALUMNO | {{ $alumno->nombre }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}
    </title>
</head>

<style>
    @page {
        margin: 20px 0px 10px 0px;
    }

    .page-break {
        page-break-after: always;
    }

    @font-face {
        font-family: 'calibri';
        font-style: normal;
        src: url('{{ storage_path('fonts/calibri/calibri.ttf') }}') format('truetype');
    }

    @font-face {
        font-family: 'calibri';
        font-style: bold;
        font-weight: 700;
        src: url('{{ storage_path('fonts/calibri/calibri-bold.ttf') }}') format('truetype');
    }

    body {
        /* font-family: 'calibri'; */
        font-family: sans-serif;
        margin: auto;
        font-size: 13px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
    }

    td,
    th {
        border: 1px solid #000;
        padding: 4px;
        vertical-align: top;
        font-size: 13px;
    }

    th {
        border: 1px solid #2d2d2d;
        background: #638acd;
        font-weight: bold;
        text-align: center;
        color: white;
    }

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

    .watermark {
        position: fixed;
        top: 100%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 150%;
        height: 150%;
        z-index: -1;
        opacity: 0.1;
        text-align: center;
    }

    h2 {
        background-color: #b3d38f;
        text-align: center;
        padding: 5px;
        font-size: 14px;
        margin: 8px 0 6px 0;
    }

    .center {
        text-align: center
    }

    .sin-borde {
        border: none;
    }

    .subtitulo {
        background-color: #eaeaea;
        font-weight: bold;
    }

    .email {
        color: #333;
        font-weight: bold;
    }

    .foto {
        width: 80px;
        height: 80px;
        border: 1px solid #000;
        text-align: center;
    }

    .contenedor {
        padding: 0 35px
    }

    .chip {
        display: inline-block;
        padding: 2px 8px;
        border: 1px solid #000;
        font-size: 11px;
        font-weight: bold;
    }

    .chip.ok {
        color: green;
        border-color: green;
    }

    .chip.bad {
        color: red;
        border-color: red;
    }

    .two-col {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0 8px 0;
    }

    .two-col td {
        border: 1px solid #000;
        padding: 6px;
        width: 50%;
        vertical-align: top;
    }
</style>

<body>
    @php
        $dash = '-------------';

        $v = function ($value) use ($dash) {
            return !empty($value) || $value === '0' ? $value : $dash;
        };

        $upper = function ($value) use ($dash) {
            return !empty($value) ? mb_strtoupper($value) : $dash;
        };

        $inscripcion = $inscripcion ?? ($alumno->inscripcion ?? null);
        $statusTxt = 'ACTIVO';
        $statusOk = true;
        if ($inscripcion && isset($inscripcion->status)) {
            $statusOk = (string) $inscripcion->status === '1' || (int) $inscripcion->status === 1;
            $statusTxt = $statusOk ? 'ACTIVO' : 'BAJA / INACTIVO';
        }
    @endphp


    {{ $alumno }}



    <div style="width: 12%; text-align: center; margin-top: 0px;  position: absolute; left: 50px; top: 20px; ">
        <img style="width: 100%;" src="{{ public_path('storage/licenciaturas/' . $alumno->licenciatura->logo) }}"
            alt="{{ $alumno->licenciatura->nombre }}">
    </div>
    <div style="width: 100%; text-align: center; margin-top: 0px;">
        <img style="width: 40%;" src="{{ public_path('imagenes_publicas/logo.png') }}" alt="">
    </div>

    <div style="text-align: center; margin-top: 0px; font-size:25px;  position: absolute; right: 147px; top: 3px; ">
        <p>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('DD') }}</p>
    </div>
    <div style="text-align: center; margin-top: 0px; font-size:25px;  position: absolute; right: 105px; top: 3px; ">
        <p>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('MM') }}</p>
    </div>
    <div style="text-align: center; margin-top: 0px; font-size:25px;  position: absolute; right: 60px; top: 3px; ">
        <p>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('YY') }}</p>
    </div>

    <div class="contenedor">

        {{-- WATERMARK --}}
        <div class="watermark">
            <img src="{{ public_path('imagenes_publicas/logo-letra.png') }}" alt="Watermark">
        </div>

        {{-- ====================== SECCIÓN 1: DATOS DEL ALUMNO ====================== --}}
        <h2>DATOS DEL ALUMNO</h2>

        <table>
            <tr>
                <td colspan="2" class="subtitulo">NOMBRE DEL ALUMNO (A)</td>
                <td style="text-align: center">{{ $v($alumno->alumno->nombre) }}</td>
                <td style="text-align: center">{{ $v($alumno->alumno->apellido_paterno) }}</td>
                <td style="text-align: center">{{ $v($alumno->alumno->apellido_materno) }}</td>

                <td rowspan="3" class="foto">
                    @if (!empty($alumno->foto))
                        <img src="{{ public_path('storage/estudiantes/' . $alumno->foto) }}" width="75"
                            height="90" style="object-fit: cover;">
                    @else
                        <img src="{{ public_path('imagenes_publicas/user.png') }}" width="75" height="90"
                            style="object-fit: cover;">
                    @endif
                </td>
            </tr>

            <tr>
                <td colspan="2"></td>
                <td style="text-align: center; font-size:9px; padding:0px;">NOMBRE(S)</td>
                <td style="text-align: center; font-size:9px; padding:0px;">A. PATERNO</td>
                <td style="text-align: center; font-size:9px; padding:0px;">A. MATERNO</td>
            </tr>

            <tr>
                <td class="subtitulo">FECHA DE NACIMIENTO</td>
                <td class="center">
                    @if (!empty($alumno->fecha_nacimiento))
                        {{ \Carbon\Carbon::parse($alumno->fecha_nacimiento)->format('d/m/Y') }}
                    @else
                        {{ $dash }}
                    @endif
                </td>
                <td class="subtitulo center">CURP</td>
                <td colspan="2" class="center">{{ $v($alumno->CURP ?? ($alumno->curp ?? null)) }}</td>
            </tr>

            <tr>
                <td class="subtitulo">LUGAR DE NACIMIENTO</td>
                <td class="center" colspan="5">
                    @if (!empty($alumno->ciudadNacimiento->nombre))
                        {{ $alumno->ciudadNacimiento->nombre }}, {{ $alumno->estadoNacimiento->nombre }}
                    @else
                        {{ $dash }}
                    @endif
                </td>
            </tr>

            <tr>
                <td class="subtitulo">DOMICILIO</td>
                <td colspan="5" class="center" style="text-transform: uppercase;">
                    @if (!empty($alumno->calle))
                        {{ $alumno->calle }}
                        @if (!empty($alumno->numero_exterior))
                            NO. EXT.{{ $alumno->numero_exterior }}
                        @else
                            S/N
                        @endif
                        @if (!empty($alumno->numero_interior))
                            {{ $alumno->numero_interior }}
                        @endif
                    @else
                        {{ $dash }}
                    @endif
                </td>
            </tr>

            <tr>
                <td class="subtitulo">COLONIA</td>
                <td colspan="2" class="center">{{ $v($alumno->colonia ?? null) }}</td>
                <td class="subtitulo center">CP</td>
                <td colspan="2" class="center">{{ $v($alumno->codigo_postal ?? null) }}</td>
            </tr>

            <tr>
                <td class="subtitulo">MUNICIPIO</td>
                <td colspan="2" class="center">{{ $v($alumno->municipio ?? null) }}</td>
                <td class="subtitulo center">EMAIL</td>
                <td colspan="2" class="email center">{{ $v($alumno->user->email ?? null) }}</td>
            </tr>

            <tr>
                <td class="subtitulo">TELÉFONO</td>
                <td class="center" colspan="2">{{ $v($alumno->telefono ?? null) }}</td>
                <td class="subtitulo center">CELULAR</td>
                <td class="center" colspan="2">{{ $v($alumno->celular ?? null) }}</td>
            </tr>

            <tr>
                <td class="subtitulo">NOMBRE DEL PADRE O TUTOR</td>
                <td style="text-transform: uppercase;" colspan="5" class="center">
                    {{ $upper($alumno->tutor ?? null) }}</td>
            </tr>

            <tr>
                <td class="subtitulo">BACHILLERATO DE PROCEDENCIA</td>
                <td style="text-transform: uppercase;" colspan="5" class="center">
                    {{ $upper($alumno->bachillerato_procedente ?? null) }}</td>
            </tr>
        </table>

        {{-- ====================== SECCIÓN 2: DATOS ESCOLARES ====================== --}}
        <h2>DATOS ESCOLARES</h2>

        <table class="two-col">
            <tr>
                <td>
                    <b>Modalidad:</b> <span
                        style="text-transform: uppercase;">{{ $v($alumno->modalidad->nombre ?? null) }}</span><br>
                    <b>Licenciatura:</b> <span
                        style="text-transform: uppercase;">{{ $v($alumno->licenciatura->nombre ?? null) }}</span><br>
                    <b>Generación:</b> <span
                        style="text-transform: uppercase;">{{ $v($alumno->generacion->generacion ?? null) }}</span><br>
                </td>
                <td>
                    <b>Sexo:</b> <span style="text-transform: uppercase;">{{ $v($alumno->sexo ?? null) }}</span><br>
                    <b>Matrícula:</b> <span
                        style="text-transform: uppercase;">{{ $v($alumno->matricula ?? null) }}</span><br>
                    <b>Fecha de inscripción:</b>
                    @if (!empty($alumno->fecha_inscripcion))
                        {{ \Carbon\Carbon::parse($alumno->fecha_inscripcion)->format('d/m/Y') }}
                    @else
                        {{ $dash }}
                    @endif
                </td>
            </tr>
        </table>

        {{-- ====================== SECCIÓN 3: DATOS DE CONTACTO ====================== --}}
        <h2>DATOS DE CONTACTO</h2>

        <table>
            <tr>
                <td class="subtitulo" style="width: 30%;">EMAIL</td>
                <td class="center">{{ $v($alumno->user->email ?? null) }}</td>
                <td class="subtitulo" style="width: 20%;">TELÉFONO</td>
                <td class="center">{{ $v($alumno->telefono ?? null) }}</td>
            </tr>
            <tr>
                <td class="subtitulo">CELULAR</td>
                <td class="center">{{ $v($alumno->celular ?? null) }}</td>
                <td class="subtitulo">DOMICILIO</td>
                <td class="center" style="text-transform: uppercase;">
                    @if (!empty($alumno->calle))
                        {{ $alumno->calle }}
                        @if (!empty($alumno->numero_exterior))
                            NO. EXT.{{ $alumno->numero_exterior }}
                        @else
                            S/N
                        @endif
                    @else
                        {{ $dash }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="subtitulo">COLONIA</td>
                <td class="center">{{ $v($alumno->colonia ?? null) }}</td>
                <td class="subtitulo">MUNICIPIO</td>
                <td class="center">{{ $v($alumno->municipio ?? null) }}</td>
            </tr>
            <tr>
                <td class="subtitulo">C.P.</td>
                <td class="center">{{ $v($alumno->codigo_postal ?? null) }}</td>
                <td class="subtitulo">TUTOR</td>
                <td class="center" style="text-transform: uppercase;">{{ $upper($alumno->tutor ?? null) }}</td>
            </tr>
        </table>

        {{-- ====================== SECCIÓN 4: INSCRIPCIÓN ====================== --}}
        <h2>INSCRIPCIÓN</h2>

        <table>
            <tr>
                <td class="subtitulo" style="width: 30%;">FOLIO / ID</td>
                <td class="center">
                    @if ($inscripcion && !empty($inscripcion->id))
                        {{ $inscripcion->id }}
                    @else
                        {{ $dash }}
                    @endif
                </td>

                <td class="subtitulo" style="width: 20%;">ESTATUS</td>
                <td class="center">
                    <span class="chip {{ $statusOk ? 'ok' : 'bad' }}">{{ $statusTxt }}</span>
                </td>
            </tr>

            <tr>
                <td class="subtitulo">FECHA DE INSCRIPCIÓN</td>
                <td class="center">
                    @if ($inscripcion && !empty($inscripcion->fecha_inscripcion))
                        {{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}
                    @else
                        @if (!empty($alumno->fecha_inscripcion))
                            {{ \Carbon\Carbon::parse($alumno->fecha_inscripcion)->format('d/m/Y') }}
                        @else
                            {{ $dash }}
                        @endif
                    @endif
                </td>

                <td class="subtitulo">OBSERVACIONES</td>
                <td class="center" style="text-transform: uppercase;">
                    {{ $v($inscripcion->observaciones ?? null) }}
                </td>
            </tr>
        </table>

        {{-- ====================== SECCIÓN 5: DATOS DE LA LICENCIATURA (COMO REFERENCIA) ====================== --}}
        <h2>DATOS DE LA LICENCIATURA</h2>

        <table>
            <tr>
                <td class="subtitulo">LICENCIATURA ASIGNADA</td>
                <td colspan="3" class="center" style="text-transform: uppercase;">
                    {{ $alumno->licenciatura->nombre }}
                </td>
            </tr>
            <tr>
                <td class="subtitulo">GENERACIÓN</td>
                <td class="center" style="text-transform: uppercase;">{{ $alumno->generacion->generacion }}</td>
            </tr>
        </table>




    </div>
</body>

</html>
