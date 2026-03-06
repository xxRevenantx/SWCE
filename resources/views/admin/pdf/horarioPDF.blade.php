<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" type="image/png" href="{{ public_path('imagenes_publicas/logo-letra.png') }}" />
    <title>Horario de clases</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        /* Contenedor general tipo tarjeta */
        .sheet {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
        }

        /* Header */
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
            font-weight: 800;
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

        /* Píldoras */
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

        /* Tabla horario */
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
            vertical-align: middle;
        }

        table.horario thead th {
            background: #cbd5e1;
            color: #0f172a;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 10px;
            text-align: center;
        }

        .col-hora {
            width: 15%;
            text-align: center;
            font-weight: 800;
            background: #f8fafc;
            white-space: nowrap;
        }

        .materia {
            text-align: center;
            font-weight: 700;
            font-size: 10px;
            line-height: 1.25;
        }

        .dash {
            width: 18px;
            height: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            display: inline-block;
            text-align: center;
            line-height: 18px;
            font-weight: 800;
            color: #475569;
            background: #f8fafc;
            margin: 0 auto;
        }

        /* Receso a lo ancho */
        .receso-row td {
            background: #ffffff;
        }

        .receso-cell {
            text-align: center;
            letter-spacing: 6px;
            font-weight: 900;
            color: #0f172a;
            padding: 10px 0;
        }

        /* Resumen */
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

        /* Footer */
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
        // ===== CONFIG =====
        $horaReceso = '10:00am-10:30am';
        $turno = $turno ?? 'Matutino';

        // ===== DÍAS (Lunes a Viernes por id) =====
        $dias = $horario
            ->map(fn($h) => ['id' => $h->dia_id, 'nombre' => mb_strtoupper($h->dia->dia ?? '')])
            ->unique('id')
            ->sortBy('id')
            ->values();

        // Si por alguna razón no llegan días (colección vacía), fallback a 5 columnas
        $diasCount = max(1, $dias->count());

        // ===== HORAS (aseguro receso) =====
        $horas = $horario->pluck('hora')->unique()->values()->all();
        if (!in_array($horaReceso, $horas, true)) {
            $horas[] = $horaReceso;
        }

        // Orden por hora inicial
        $aMin = function ($rango) {
            $rango = trim((string) $rango);
            $ini = explode('-', $rango)[0] ?? $rango;
            $ini = strtolower(trim($ini));
            $ini = str_replace(['a.m.', 'p.m.'], ['am', 'pm'], $ini);
            $ini = preg_replace('/\s+/', '', $ini);

            if (!preg_match('/^(\d{1,2})(?::(\d{2}))?(am|pm)$/', $ini, $m)) {
                return 99999;
            }

            $h = (int) $m[1];
            $min = isset($m[2]) ? (int) $m[2] : 0;
            $ampm = $m[3];

            if ($ampm === 'pm' && $h !== 12) {
                $h += 12;
            }
            if ($ampm === 'am' && $h === 12) {
                $h = 0;
            }

            return $h * 60 + $min;
        };
        usort($horas, fn($x, $y) => $aMin($x) <=> $aMin($y));

        // Duración de un rango "8:00am-9:00am" en minutos
        $duracionMin = function ($rango) use ($aMin) {
            $rango = trim((string) $rango);
            $partes = explode('-', $rango);
            $ini = trim($partes[0] ?? $rango);
            $fin = trim($partes[1] ?? $rango);

            $mIni = $aMin($ini);
            $mFin = $aMin($fin);

            if ($mIni === 99999 || $mFin === 99999) {
                return 0;
            }
            $dif = $mFin - $mIni;
            return $dif > 0 ? $dif : 0;
        };

        // ===== MATRIZ: [hora][dia_id] => texto materia (1 por celda) =====
        $matriz = [];
        $usadasAsignacion = collect(); // para resumen
        $totalMinutosSemana = 0;

        foreach ($horario as $h) {
            if (($h->hora ?? '') === $horaReceso) {
                continue;
            }

            $hora = $h->hora;
            $diaId = $h->dia_id;

            $materia = $h->asignacionMateria->materia->nombre ?? null;

            // Si no hay asignación, no cuenta
            if (!$materia) {
                continue;
            }

            $matriz[$hora][$diaId] = $materia;

            // Resumen (por asignación)
            if (!empty($h->asignacion_materia_id)) {
                $usadasAsignacion->push($h->asignacion_materia_id);
            }

            // Total semanal: suma duración por cada registro (día+hora con materia)
            $totalMinutosSemana += $duracionMin($hora);
        }

        // ===== RESUMEN DE CARGAS =====
        $idsAsignacion = $usadasAsignacion->unique()->values();

        $resumen = $horario
            ->filter(fn($h) => !empty($h->asignacion_materia_id))
            ->unique('asignacion_materia_id')
            ->values()
            ->map(function ($h, $i) {
                $clave = $h->asignacionMateria->materia->clave ?? '—';
                $materia = $h->asignacionMateria->materia->nombre ?? '—';

                $prof = $h->asignacionMateria->profesor ?? null;
                $profesor = $prof
                    ? trim(
                        ($prof->nombre ?? '') .
                            ' ' .
                            ($prof->apellido_paterno ?? '') .
                            ' ' .
                            ($prof->apellido_materno ?? ''),
                    )
                    : '—';

                return [
                    '#' => $i + 1,
                    'clave' => $clave,
                    'materia' => $materia,
                    'profesor' => $profesor,
                ];
            });

        // Formato HH:MM
        $formatHM = function ($min) {
            $h = intdiv($min, 60);
            $m = $min % 60;
            return str_pad((string) $h, 2, '0', STR_PAD_LEFT) . ':' . str_pad((string) $m, 2, '0', STR_PAD_LEFT);
        };

        // Fecha
        date_default_timezone_set('America/Mexico_City');
        setlocale(LC_TIME, 'spanish');
        $fecha = strftime('%d/%m/%Y %H:%M:%S');
    @endphp

    <div class="sheet">
        <div class="top-line"></div>

        <table class="header">
            <tr>
                <td style="width:70px;">
                    {{-- Logo izquierdo --}}
                    <img class="logo" src="{{ public_path('imagenes_publicas/logo-letra.png') }}" alt="Logo">
                </td>

                <td>
                    <div class="title">CENTRO UNIVERSITARIO MOCTEZUMA</div>
                    <div class="subtitle">
                        HORARIO DE CLASES – {{ mb_strtoupper($licenciatura->nombre ?? '—') }}
                    </div>
                </td>

                <td style="width:70px; text-align:right;">
                    {{-- Logo derecho --}}
                    @if (!empty($licenciatura->logo) && file_exists(public_path('storage/licenciaturas/' . $licenciatura->logo)))
                        <img class="logo" src="{{ public_path('storage/licenciaturas/' . $licenciatura->logo) }}"
                            alt="Logo Licenciatura">
                    @endif
                </td>
            </tr>
        </table>

        <div class="pills">
            <span class="pill">Cuat.: {{ $cuatrimestre->no_cuatrimestre ?? '—' }}°</span>
            <span class="pill">Turno: {{ $turno }}</span>
            <span class="pill">Generación: {{ $generacion->generacion ?? '—' }}</span>
            <span class="pill">Total semanal grupo: {{ $formatHM($totalMinutosSemana) }} h</span>
        </div>

        <table class="horario">
            <thead>
                <tr>
                    <th class="col-hora">HORA</th>
                    @foreach ($dias as $d)
                        <th>{{ $d['nombre'] }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @forelse($horas as $hora)
                    @php $esReceso = $hora === $horaReceso; @endphp

                    @if ($esReceso)
                        {{-- ✅ RECESO: una sola vez a todo lo ancho --}}
                        <tr class="receso-row">
                            <td class="col-hora">{{ $hora }}</td>
                            <td colspan="{{ $diasCount }}">
                                <div class="receso-cell">RECESO</div>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="col-hora">{{ $hora }}</td>

                            @foreach ($dias as $d)
                                @php
                                    $diaId = $d['id'];
                                    $texto = $matriz[$hora][$diaId] ?? null;
                                @endphp

                                <td>
                                    @if ($texto)
                                        <div class="materia">{{ $texto }}</div>
                                    @else
                                        <div style="text-align:center;">
                                            <span class="dash">–</span>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="{{ 1 + $diasCount }}" style="text-align:center; padding:12px;">
                            No hay horario registrado para los filtros seleccionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Resumen de cargas --}}
        <div class="box">
            <div class="box-title">RESUMEN DE CARGAS HORARIAS</div>

            <table class="resumen">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th style="width:90px;">CLAVE</th>
                        <th>MATERIA</th>
                        <th style="width:190px;">PROFESOR</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resumen as $r)
                        <tr>
                            <td style="text-align:center;">{{ $r['#'] }}</td>
                            <td style="text-align:center;">{{ $r['clave'] }}</td>
                            <td>{{ $r['materia'] }}</td>
                            <td>{{ $r['profesor'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:10px; color:#64748b;">
                                Sin materias asignadas en el horario.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="total">
                Total semanal del grupo: {{ $formatHM($totalMinutosSemana) }} h
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
