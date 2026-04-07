<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de asistencia</title>
    <style>
        @page {
            margin: 22px 24px 22px 24px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        .pagina {
            width: 100%;
        }

        .barra-superior {
            height: 8px;
            width: 100%;
            background: #2563eb;
            border-radius: 8px 8px 0 0;
        }

        .contenedor {

            border-top: 0;
            border-radius: 0 0 14px 14px;
            padding: 16px 18px 18px 18px;
            background: #ffffff;
        }

        .encabezado-tabla {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .logo-celda {
            width: 90px;
            vertical-align: top;
        }

        .logo {
            width: 72px;
            height: 72px;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 6px;
            background: #eff6ff;
            text-align: center;
            line-height: 60px;
            font-weight: bold;
            color: #1d4ed8;
            font-size: 11px;
        }

        .titulo-celda {
            vertical-align: top;
            text-align: center;
        }

        .titulo-principal {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .titulo-principal2 {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            margin: 0 0 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .subtitulo-principal {
            font-size: 10px;
            color: #475569;
            margin: 0;
        }

        .bloque-info {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 12px;
            margin-bottom: 14px;
        }

        .bloque-info td {
            vertical-align: top;
        }

        .card-info {
            border: 1px solid #dbeafe;
            background: #f8fbff;
            border-radius: 12px;
            padding: 10px 12px;
        }

        .card-info-secundaria {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 12px;
            padding: 10px 12px;
        }

        .etiqueta {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #2563eb;
            margin-bottom: 3px;
        }

        .valor {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 7px;
        }

        .texto-normal {
            font-size: 10px;
            color: #334155;
            margin-bottom: 6px;
        }

        .resumen {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
            margin-bottom: 14px;
        }

        .resumen td {
            width: 25%;
            padding-right: 8px;
            vertical-align: top;
        }

        .mini-box {
            border: 1px solid #dbeafe;
            background: #eff6ff;
            border-radius: 10px;
            padding: 8px 10px;
            text-align: center;
        }

        .mini-box-gris {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 10px;
            padding: 8px 10px;
            text-align: center;
        }

        .mini-titulo {
            font-size: 8px;
            text-transform: uppercase;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 3px;
        }

        .mini-valor {
            font-size: 11px;
            color: #0f172a;
        }

        .tabla-asistencia {

            border-collapse: collapse;
            margin-top: 4px;
        }

        .tabla-asistencia th {
            border: 1px solid #94a3b8;
            background: #1e40af;
            color: #ffffff;
            font-size: 9px;
            padding: 7px 4px;
            text-align: center;
        }

        .tabla-asistencia td {
            border: 1px solid #cbd5e1;
            padding: 7px 5px;
            text-align: center;
            vertical-align: middle;
            font-size: 9px;
        }

        .tabla-asistencia tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .izquierda {
            text-align: left !important;
        }

        .numero {
            width: 34px;
        }

        .matricula {
            width: 88px;
        }

        .nombre {
            width: 280px;
        }


        .pie {
            margin-top: 16px;
        }

        .pie-tabla {
            width: 100%;
            border-collapse: collapse;
        }

        .pie-tabla td {
            width: 50%;
            vertical-align: top;
        }

        .firma-box {
            padding-top: 20px;
        }

        .linea-firma {
            border-top: 1px solid #64748b;
            width: 85%;
            margin-top: 28px;
            margin-bottom: 4px;
        }

        .firma-label {
            font-size: 10px;
            color: #475569;
            text-align: center;
            margin-top: 70px;
        }

        .nota {
            text-align: right;
            font-size: 9px;
            color: #64748b;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border: 1px solid #bfdbfe;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 999px;
            font-size: 9px;
            font-weight: bold;
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
    <div class="pagina">
        <div class="barra-superior"></div>

        <div class="contenedor">
            <table class="encabezado-tabla">
                <tr>
                    <td class="logo-celda">
                        <img class="logo" src="{{ public_path('imagenes_publicas/logo-letra.png') }}" alt="Logo">
                    </td>
                    <td class="titulo-celda">
                        <p class="titulo-principal">Centro Universitario Moctezuma A.C.</p>
                        <p class="titulo-principal2">Lista de asistencia</p>
                        <p class="subtitulo-principal">
                            Sistema Web de Control Escolar · Reporte de asistencia por materia
                        </p>
                    </td>
                </tr>
            </table>

            <table class="bloque-info">
                <tr>
                    <td style="width: 100%; padding-right: 8px;">
                        <div class="card-info">
                            <div class="etiqueta">Profesor</div>
                            <div class="valor">
                                {{ $profesor->nombre }} {{ $profesor->apellido_paterno }}
                                {{ $profesor->apellido_materno }}
                            </div>

                            <div class="etiqueta">Materia</div>
                            <div class="valor">
                                {{ $materia }}
                            </div>

                            <div class="texto-normal">
                                <strong>Clave:</strong> {{ $clave }}
                            </div>

                            <div class="texto-normal">
                                <strong>Licenciatura:</strong> {{ $licenciatura }}
                            </div>

                            <div class="texto-normal">
                                <strong>Cuatrimestre:</strong> {{ $cuatrimestre }}
                            </div>

                            <div class="texto-normal" style="margin-bottom: 0;">
                                <strong>Generación:</strong> {{ $generacion }}
                            </div>
                        </div>
                    </td>


                </tr>
            </table>



            <table class="tabla-asistencia">
                <thead>
                    <tr>
                        <th class="numero">No.</th>
                        <th class="matricula">Matrícula</th>
                        <th class="nombre">Nombre completo</th>
                        @for ($i = 1; $i <= 28; $i++)
                            <th class="asistencia"></th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alumnos as $index => $alumno)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $alumno->matricula ?? 'Sin matrícula' }}</td>
                            <td class="izquierda">
                                {{ $alumno->nombre }}
                                {{ $alumno->apellido_paterno }}
                                {{ $alumno->apellido_materno }}
                            </td>

                            @for ($i = 1; $i <= 28; $i++)
                                <td></td>
                            @endfor

                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No hay alumnos para mostrar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="firma-label">_______________________________________<br>Firma del profesor</div>
        </div>
    </div>
    <footer>
        <strong>Centro Universitario Moctezuma A.C.</strong> — C.C.T. 12PSU0173I<br>
        C. Francisco I. Madero Ote. No. 800, Col. Esquipula, C.P. 40665, Altamirano, Guerrero · Tel. 7676880774<br>
        <strong>Fecha de expedición:</strong>
        {{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
    </footer>
</body>

</html>
