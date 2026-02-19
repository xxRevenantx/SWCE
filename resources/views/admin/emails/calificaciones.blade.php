@php
    $promedio = $calificaciones->count() ? number_format($calificaciones->avg('calificacion'), 1) : 'N/A';

    $colorPrimario = '#006492'; // Azul institucional
    $colorAcento = '#88AC2E'; // Verde institucional

    $alumno = $inscripcion->alumno;

    $nombreCompleto = trim(
        implode(
            ' ',
            array_filter([
                $alumno->nombre ?? null,
                $alumno->apellido_paterno ?? null,
                $alumno->apellido_materno ?? null,
            ]),
        ),
    );

    $noCuatri = $cuatrimestre->no_cuatrimestre ?? ($cuatrimestre->cuatrimestre ?? $cuatrimestre->id);

    $mencionHonorifica = is_numeric($promedio) && $promedio >= 9.9 ? ' • Mención Honorífica' : '';
@endphp

<x-mail::message>

    {{-- ENCABEZADO --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px;">
        <tr>
            <td align="center">
                <div style="font-size:12px; letter-spacing:.5px; color:#666; margin-bottom:4px;">
                    Centro Universitario Moctezuma · Área de Control Escolar
                </div>
                <div style="font-weight:700; font-size:20px; color:{{ $colorPrimario }}; text-transform:uppercase;">
                    Calificaciones del {{ $noCuatri }}° Cuatrimestre
                </div>
            </td>
        </tr>
    </table>

    {{-- SALUDO --}}
    @if ($alumno->sexo === 'F')
        <p>Estimada Alumna: <strong>{{ $nombreCompleto }}</strong>,</p>
    @else
        <p>Estimado Alumno: <strong>{{ $nombreCompleto }}</strong>,</p>
    @endif

    <p>
        Le informamos que se encuentran disponibles sus resultados del
        <strong>{{ $noCuatri }}° cuatrimestre</strong> de la
        <strong>Licenciatura en {{ $licenciatura->nombre }}</strong>.
    </p>

    <x-mail::panel>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="padding:4px 0;">
                    <strong>Licenciatura:</strong> {{ $licenciatura->nombre }}
                </td>
                <td style="padding:4px 0;">
                    <strong>Generación:</strong>
                    {{ $generacion->generacion ?? ($generacion->nombre ?? '—') }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:4px 0;">
                    <strong>Promedio cuatrimestral:</strong>
                    <span
                        style="background:{{ $colorAcento }};
                               color:#fff;
                               padding:2px 8px;
                               border-radius:999px;
                               font-weight:700;">
                        {{ $promedio }}{!! $mencionHonorifica !!}
                    </span>
                </td>
            </tr>
        </table>
    </x-mail::panel>

    {{-- TABLA DE CALIFICACIONES --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="border-collapse:collapse; width:100%; margin: 6px 0 14px; border:1px solid #E5E7EB;">
        <thead>
            <tr>
                <th align="left"
                    style="padding:10px 12px; font-size:12px; text-transform:uppercase;
                           letter-spacing:.4px; background:{{ $colorPrimario }};
                           color:#fff; border-bottom:1px solid #D1D5DB;">
                    Asignatura
                </th>
                <th align="center"
                    style="padding:10px 12px; font-size:12px; text-transform:uppercase;
                           letter-spacing:.4px; background:{{ $colorPrimario }};
                           color:#fff; border-bottom:1px solid #D1D5DB; width:140px;">
                    Calificación
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($calificaciones as $i => $calificacion)
                @php $isZebra = $i % 2 === 1; @endphp
                <tr style="background:{{ $isZebra ? '#F9FAFB' : '#FFFFFF' }};">
                    <td style="padding:10px 12px; border-bottom:1px solid #E5E7EB;">
                        {{ $calificacion->asignacionMateria->materia->nombre }}
                    </td>
                    <td align="center" style="padding:10px 12px; border-bottom:1px solid #E5E7EB; font-weight:700;">
                        {{ $calificacion->calificacion }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="padding:14px 12px; text-align:center; color:#6B7280;">
                        No hay calificaciones registradas en este periodo.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- CIERRE --}}
    <p>
        <em>Nota:</em> Este mensaje incluye su boleta en formato PDF como anexo.
        Para cualquier aclaración comuníquese con <strong>Control Escolar</strong>.
    </p>

    <p>Sin otro particular, reciba un cordial saludo.</p>

    {{-- FIRMA --}}
    <table role="presentation" cellpadding="0" cellspacing="0" style="margin-top:6px;">
        <tr>
            <td style="border-left:4px solid {{ $colorAcento }}; padding-left:12px;">
                <div style="font-weight:700;">Ing. Edgar García Basilio</div>
                <div>Encargado del Área de Control Escolar</div>
                <div>Centro Universitario Moctezuma</div>
            </td>
        </tr>
    </table>

    <x-mail::subcopy>
        Este mensaje y sus anexos pueden contener información confidencial y de uso exclusivo
        del destinatario. Si lo recibió por error, notifíquelo y elimínelo.
    </x-mail::subcopy>

</x-mail::message>
