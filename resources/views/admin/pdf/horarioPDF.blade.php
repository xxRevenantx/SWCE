<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Horario de clases</title>


    <style>
        body {
            font-family: "sans-serif";
            font-size: 11px;
            color: #0f172a;
        }

        .header {
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
            background: #fff;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .logo {
            width: 58px;
            height: 58px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 6px;
        }

        .titulo {
            font-size: 18px;
            font-weight: 800;
            margin: 0;
            color: #0b3b8a;
        }

        .subtitulo {
            margin: 3px 0 0 0;
            color: #475569;
            font-size: 10px;
        }

        .pill {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            background: #eef2ff;
            color: #1e3a8a;
            font-weight: 700;
            font-size: 10px;
        }

        .meta {
            text-align: right;
            font-size: 10px;
            color: #334155;
            line-height: 1.4;
        }

        .linea {
            height: 1px;
            background: #e2e8f0;
            margin: 10px 0 0 0;
        }

        table.horario {
            width: 100%;
            font-family: 'sans-serif';
            border-collapse: collapse;
            border: 1px solid #0f172a;
        }

        table.horario th,
        table.horario td {
            border: 1px solid #0f172a;
            padding: 7px;
            vertical-align: top;
        }

        table.horario thead th {
            background: #0b3b8a;
            color: #fff;
            font-weight: 800;
            text-align: center;
            font-size: 11px;
        }

        .col-hora {
            width: 16%;
            background: #f8fafc;
            font-weight: 800;
            text-align: center;
        }

        tbody tr:nth-child(even) td {
            background: #fbfdff;
        }

        tbody tr:nth-child(even) td.col-hora {
            background: #f1f5f9;
        }

        .card {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 7px;
            margin-bottom: 6px;
            background: #fff;
        }

        .materia {
            font-weight: 800;
            font-size: 11px;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 999px;
            background: #ecfeff;
            color: #155e75;
            font-weight: 800;
            font-size: 9px;
            border: 1px solid #a5f3fc;
            margin-top: 3px;
        }

        .vacio {
            color: #94a3b8;
            text-align: center;
            padding-top: 10px;
        }

        .footer {
            margin-top: 10px;
            font-size: 9px;
            color: #64748b;
            text-align: right;
        }
    </style>
</head>

<body>
    @php
        // DÍAS: uso dia_id como llave (más limpio)
        // Ordeno por dia_id (asumiendo que en tu seeder Lunes=1, Martes=2, etc.)
        $dias = $horario
            ->map(function ($h) {
                return [
                    'id' => $h->dia_id,
                    'nombre' => mb_strtoupper($h->dia->dia ?? ''),
                ];
            })
            ->unique('id')
            ->sortBy('id')
            ->values();

        // HORAS únicas
        $horas = $horario->pluck('hora')->unique()->values()->all();

        // Orden por hora inicial (si tu formato es 8:00am-9:00am)
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

        // MATRIZ: [hora][dia_id] => items
        $matriz = [];

        foreach ($horario as $h) {
            $hora = $h->hora;
            $diaId = $h->dia_id;

            $materia = $h->asignacionMateria->materia->nombre ?? 'Sin materia';
            $clave = $h->asignacionMateria->materia->clave ?? '';

            $matriz[$hora][$diaId][] = [
                'materia' => $materia,
                'clave' => $clave,
            ];
        }

        date_default_timezone_set('America/Mexico_City');
        setlocale(LC_TIME, 'spanish');
        $fecha = strftime('%d de %B de %Y %H:%M');
    @endphp

    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width:70px;">
                    <img class="logo" src="{{ public_path('imagenes_publicas/logo-letra.png') }}" alt="Logo">
                </td>

                <td>
                    <p class="titulo">Horario de clases</p>
                    <p class="subtitulo">
                        <span class="pill">{{ $licenciatura->nombre ?? '-' }}</span>
                        &nbsp; · &nbsp;
                        <span class="pill">Gen. {{ $generacion->generacion ?? '-' }}</span>
                        &nbsp; · &nbsp;
                        <span class="pill">{{ $cuatrimestre->no_cuatrimestre ?? '-' }}° Cuatrimestre</span>
                    </p>
                </td>

                <td class="meta" style="width:220px;">
                    <div><strong>Generado:</strong> {{ $fecha }}</div>
                </td>
            </tr>
        </table>
        <div class="linea"></div>
    </div>

    <table class="horario">
        <thead>
            <tr>
                <th class="col-hora">Hora</th>
                @foreach ($dias as $d)
                    <th>{{ $d['nombre'] }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @forelse($horas as $hora)
                <tr>
                    <td class="col-hora">{{ $hora }}</td>

                    @foreach ($dias as $d)
                        @php
                            $diaId = $d['id'];
                            $items = $matriz[$hora][$diaId] ?? [];
                        @endphp

                        <td>
                            @if (count($items))
                                @foreach ($items as $it)
                                    <div class="card">
                                        <div class="materia">{{ $it['materia'] }}</div>
                                        @if (!empty($it['clave']))
                                            <div class="badge">{{ $it['clave'] }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="vacio">—</div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 1 + max(1, $dias->count()) }}" style="text-align:center; padding:12px;">
                        No hay horario registrado para los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Centro Universitario Moctezuma A.C. · Control Escolar
    </div>
</body>

</html>
