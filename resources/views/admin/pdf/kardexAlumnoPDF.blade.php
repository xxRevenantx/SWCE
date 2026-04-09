<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KARDEX | {{ $alumno->datosEscolares?->matricula ?? $alumno->id }}</title>

    <style>
        @page {
            margin: 5px 45px 0px 45px;
        }

        body {
            font-family: sans-serif;
            margin: auto;
            color: #000;
        }

        .page-break {
            page-break-after: always;
        }

        .encabezado {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
        }

        .img_encabezado {
            width: 70%;
            margin-left: -100px;
        }

        .titulo {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: -10px;
            margin-bottom: 5px;
        }

        table.datos {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            text-transform: uppercase;
        }

        table.datos td {
            padding: 5px;
            font-size: 12px;
            line-height: 10px;
        }

        table.cuatrimestres {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        table.cuatrimestres td {
            font-weight: bold;
            font-size: 12px;
        }

        table.calificaciones {
            width: 100%;
            border-collapse: collapse;
        }

        table.calificaciones td {
            font-size: 12px;
            border: 1px solid #000;
        }

        .tabla-contenedor {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 70px;
        }

        .celda {
            width: 50%;
            height: 110px;
            border-collapse: collapse;
            vertical-align: top;
        }

        .firma-rector {
            text-align: center;
            padding-top: 150px;
        }

        .firma-rector .linea {
            border-top: 1px solid black;
            width: 75%;
            margin: 0 auto;
            padding-top: 0px;
            text-transform: uppercase;
            font-size: 12px;
            line-height: 14px;
        }

        .promedio {
            padding: 65px 0 0 20px;
        }

        .prom-label {
            line-height: 1.5;
            display: inline-block;
            margin-top: 90px;
            font-size: 12px;
        }

        .prom-box {
            display: inline-block;
            width: 70px;
            height: 20px;
            border: 1px solid black;
            vertical-align: middle;
            margin-left: 10px;
            margin-top: 90px;
            text-align: center;
            padding-bottom: 20px;
            font-size: 20px;
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="encabezado">
        <img class="img_encabezado" src="{{ public_path('imagenes_publicas/imagen_kardex.png') }}" alt="Encabezado">
    </div>

    <h1 class="titulo">KARDEX DEL ALUMNO</h1>

    <table class="datos">
        <tr>
            <td style="text-decoration: underline; font-weight:bold; text-align:center; font-size:12px">
                {{ $escuela->nombre }}
            </td>
            <td style="text-decoration: underline; font-weight:bold; text-align:center">
                {{ $licenciatura->RVOE ?? '-------' }}
            </td>
            <td style="text-decoration: underline; font-weight:bold; text-align:center">
                {{ $escuela->CCT }}
            </td>
        </tr>
        <tr>
            <td style="font-size:12px; text-align:center">NOMBRE DE LA ESCUELA</td>
            <td style="font-size:12px; text-align:center">No. DE ACUERDO DE INCORPORACIÓN</td>
            <td style="font-size:12px; text-align:center">CLAVE DEL CENTRO DE TRABAJO</td>
        </tr>

        <tr>
            <td style="text-decoration: underline; font-weight:bold; text-align:center; padding-top:8px">
                {{ $alumno->nombre }}
            </td>
            <td style="text-decoration: underline; font-weight:bold; text-align:center; padding-top:8px">
                {{ $alumno->apellido_paterno }}
            </td>
            <td style="text-decoration: underline; font-weight:bold; text-align:center; padding-top:8px">
                {{ $alumno->apellido_materno }}
            </td>
        </tr>

        <tr>
            <td style="font-size:12px; text-align:center">NOMBRE(S)</td>
            <td style="font-size:12px; text-align:center">PRIMER APELLIDO</td>
            <td style="font-size:12px; text-align:center">SEGUNDO APELLIDO</td>
        </tr>

        <tr>
            <td colspan="2" style="text-decoration: underline; font-weight:bold; text-align:center; padding-top:8px">
                {{ $licenciatura->nombre ?? '---' }}
            </td>
            <td style="text-decoration: underline; font-weight:bold; text-align:center; padding-top:8px">
                ESCOLARIZADA
            </td>
        </tr>

        <tr>
            <td colspan="2" style="font-size:13px; text-align:center">LICENCIATURA</td>
            <td style="font-size:13px; text-align:center">MODALIDAD</td>
        </tr>
    </table>

    @foreach ($kardex as $index => $bloque)
        @if ($index === 5)
            <div class="page-break"></div>
        @endif

        @php
            $nombreCuatrimestre = match ((int) $bloque['numero']) {
                1 => 'PRIMER CUATRIMESTRE',
                2 => 'SEGUNDO CUATRIMESTRE',
                3 => 'TERCER CUATRIMESTRE',
                4 => 'CUARTO CUATRIMESTRE',
                5 => 'QUINTO CUATRIMESTRE',
                6 => 'SEXTO CUATRIMESTRE',
                7 => 'SÉPTIMO CUATRIMESTRE',
                8 => 'OCTAVO CUATRIMESTRE',
                9 => 'NOVENO CUATRIMESTRE',
                default => mb_strtoupper($bloque['nombre'] ?? 'CUATRIMESTRE'),
            };
        @endphp

        <table class="cuatrimestres">
            <tr>
                <td style="width:300px">{{ $nombreCuatrimestre }}</td>
            </tr>
        </table>

        <table class="calificaciones">
            <tr>
                <td style="text-align:center; line-height:15px; padding:0; font-weight:bold;" rowspan="2">CLAVE</td>
                <td style="width:230px; text-align:center; font-weight:bold; line-height:9px; padding:0;"
                    rowspan="2">
                    ASIGNATURA
                </td>
                <td style="width:40px; text-align:center; font-weight:bold; line-height:12px; padding:0;"
                    rowspan="2">
                    CAL.<br>FINAL
                </td>
                <td style="text-align:center; font-weight:bold; line-height:12px; padding:0;" rowspan="2">
                    %.<br>ASIST.
                </td>
                <td style="text-align:center; font-weight:bold; line-height:12px; padding:0;" colspan="6">
                    PERIODOS DE REGULARIZACIÓN
                </td>

                <td rowspan="{{ max(count($bloque['materias']), 1) + 2 }}"
                    style="width: 20px; border:none; padding:0; margin:0"></td>

                <td rowspan="{{ max(count($bloque['materias']), 1) + 2 }}" style="width:89px; padding:0; margin:0">
                    <p
                        style="text-align:center; font-size:11px; font-weight:bold; line-height:12px; margin:0; padding:0">
                        REVISADO Y CONFRONTADO
                    </p>

                    <p style="width:100%; border-top:1px solid #000; margin:100px 0 0 0; padding:0"></p>
                    <p style="text-align:center; font-size:14px; margin:0; padding:0; line-height:15px;">
                        / &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;/
                        <br>
                    </p>
                </td>
            </tr>

            <tr>
                <td style="text-align:center; font-weight:bold;">FECHA</td>
                <td style="text-align:center; font-weight:bold;">CALIF.</td>
                <td style="text-align:center; font-weight:bold;">FECHA</td>
                <td style="text-align:center; font-weight:bold;">CALIF.</td>
                <td style="text-align:center; font-weight:bold;">FECHA</td>
                <td style="text-align:center; font-weight:bold;">CALIF.</td>
            </tr>

            <tbody>
                @forelse ($bloque['materias'] as $materia)
                    <tr>
                        <td style="text-align:center; padding:0px">
                            {{ $materia['clave'] }}
                        </td>

                        <td
                            style="text-transform:uppercase; font-size:11px; padding-left:5px; padding-top:0; padding-bottom:0; margin:0">
                            {{ $materia['materia'] }}
                        </td>

                        <td style="text-align:center; padding:0px">
                            {{ $materia['calificacion'] }}
                        </td>

                        <td style="text-align:center; padding:0px">
                            {{ is_numeric($materia['calificacion']) ? '100' : '---' }}
                        </td>

                        <td style="padding:0px"></td>
                        <td style="padding:0px"></td>
                        <td style="padding:0px"></td>
                        <td style="padding:0px"></td>
                        <td style="padding:0px"></td>
                        <td style="padding:0px"></td>
                    </tr>
                @empty
                    <tr>
                        <td style="text-align:center; height:5px; padding:0px">---</td>
                        <td
                            style="text-transform:uppercase; height:5px; line-height:8px; font-size:11px; padding-left:5px; padding-top:0; padding-bottom:0; margin:0">
                            SIN MATERIAS REGISTRADAS
                        </td>
                        <td style="text-align:center; padding:0px">---</td>
                        <td style="text-align:center; padding:0px">---</td>
                        <td style="padding:0px"></td>
                        <td style="padding:0px"></td>
                        <td style="padding:0px"></td>
                        <td style="padding:0px"></td>
                        <td style="padding:0px"></td>
                        <td style="padding:0px"></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach

    <table class="tabla-contenedor">
        <tr>
            <td class="celda firma-rector" style="border: 1px solid #000">
                <div class="linea">
                    {{ $rector->nombre }} {{ $rector->apellido_paterno }} {{ $rector->apellido_materno }} RECTOR(A)
                </div>
            </td>
            <td class="celda">
                <div class="promedio">
                    <span class="prom-label">PROMEDIO<br>GENERAL DE<br>APROVECHAMIENTO:</span>
                    <span class="prom-box">{{ $promedioGeneral }}</span>
                </div>
            </td>
        </tr>
    </table>

</body>

</html>
