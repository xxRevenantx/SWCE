{{-- prettier-ignore-start --}}
{{-- @formatter:off --}}
@php
$promedio = $calificaciones->count() ? number_format($calificaciones->avg('calificacion'), 1) : 'N/A';

$colorPrimario = '#006492'; // Azul institucional
$colorAcento   = '#88AC2E'; // Verde institucional

$alumno = $inscripcion->alumno;

$nombreCompleto = trim(implode(' ', array_filter([
$alumno->nombre ?? null,
$alumno->apellido_paterno ?? null,
$alumno->apellido_materno ?? null,
])));

$noCuatri = $cuatrimestre->no_cuatrimestre ?? ($cuatrimestre->cuatrimestre ?? $cuatrimestre->id);

$esHonorifica = is_numeric($promedio) && $promedio >= 9.9;
@endphp

<x-mail::message>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 16px;">
<tr>
<td style="background:#F8FAFC; border:1px solid #E5E7EB; border-radius:14px; padding:18px 18px;">
<div style="font-size:12px; letter-spacing:.4px; color:#64748B; margin-bottom:6px;">
Centro Universitario Moctezuma · Área de Control Escolar
</div>
<div style="font-weight:800; font-size:20px; color:{{ $colorPrimario }}; text-transform:uppercase; line-height:1.2;">
Calificaciones del {{ $noCuatri }}° Cuatrimestre
</div>
<div style="margin-top:10px; height:3px; width:90px; background:{{ $colorAcento }}; border-radius:999px;"></div>
</td>
</tr>
</table>

{{-- SALUDO --}}
@if (($alumno->sexo ?? null) === 'F')
<p style="margin:0 0 10px;">Estimada Alumna: <strong>{{ $nombreCompleto }}</strong>,</p>
@else
<p style="margin:0 0 10px;">Estimado Alumno: <strong>{{ $nombreCompleto }}</strong>,</p>
@endif

<p style="margin:0 0 14px; color:#334155; line-height:1.6;">
Le informamos que se encuentran disponibles sus resultados del
<strong>{{ $noCuatri }}° cuatrimestre</strong> de la
<strong>Licenciatura en {{ $licenciatura->nombre }}</strong>.
</p>

{{-- RESUMEN --}}
<x-mail::panel>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="padding:4px 0; width:50%;">
<div style="font-size:12px; color:#64748B; margin-bottom:2px;">Licenciatura</div>
<div style="font-weight:800; color:#0F172A;">{{ $licenciatura->nombre }}</div>
</td>
<td style="padding:4px 0; width:50%;">
<div style="font-size:12px; color:#64748B; margin-bottom:2px;">Generación</div>
<div style="font-weight:800; color:#0F172A;">
{{ $generacion->generacion ?? ($generacion->nombre ?? '—') }}
</div>
</td>
</tr>

<tr>
<td colspan="2" style="padding:10px 0 0;">
<div style="font-size:12px; color:#64748B; margin-bottom:6px;">Promedio cuatrimestral</div>

<span style="
display:inline-block;
background:{{ $colorAcento }};
color:#fff;
padding:6px 12px;
border-radius:999px;
font-weight:900;
letter-spacing:.2px;">
{{ $promedio }}
</span>

@if($esHonorifica)
<span style="
display:inline-block;
margin-left:8px;
background:#0F172A;
color:#fff;
padding:6px 10px;
border-radius:999px;
font-weight:800;
letter-spacing:.2px;">
Mención Honorífica
</span>
@endif
</td>
</tr>
</table>
</x-mail::panel>

{{-- TABLA DE CALIFICACIONES --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="
width:100%;
border-collapse:separate;
border-spacing:0;
border:1px solid #E5E7EB;
border-radius:14px;
overflow:hidden;
margin: 8px 0 16px;">
<thead>
<tr>
<th align="left" style="
padding:12px 14px;
font-size:12px;
text-transform:uppercase;
letter-spacing:.4px;
background:{{ $colorPrimario }};
color:#fff;">
Asignatura
</th>
<th align="center" style="
padding:12px 14px;
font-size:12px;
text-transform:uppercase;
letter-spacing:.4px;
background:{{ $colorPrimario }};
color:#fff;
width:140px;">
Calificación
</th>
</tr>
</thead>

<tbody>
@forelse ($calificaciones as $i => $calificacion)
@php $isZebra = $i % 2 === 1; @endphp
<tr style="background:{{ $isZebra ? '#F8FAFC' : '#FFFFFF' }};">
<td style="padding:12px 14px; border-top:1px solid #E5E7EB; color:#0F172A;">
{{ $calificacion->asignacionMateria->materia->nombre }}
</td>
<td align="center" style="padding:12px 14px; border-top:1px solid #E5E7EB; font-weight:900; color:#0F172A;">
{{ $calificacion->calificacion }}
</td>
</tr>
@empty
<tr>
<td colspan="2" style="padding:14px 14px; text-align:center; color:#64748B; border-top:1px solid #E5E7EB;">
No hay calificaciones registradas en este periodo.
</td>
</tr>
@endforelse
</tbody>
</table>

{{-- NOTA --}}
<p style="margin:0 0 10px; color:#334155; line-height:1.6;">
<strong>Nota:</strong> Este mensaje incluye su boleta en formato PDF como anexo.
Para cualquier aclaración comuníquese con <strong>Control Escolar</strong>.
</p>

<p style="margin:0 0 14px; color:#334155;">Sin otro particular, reciba un cordial saludo.</p>

{{-- FIRMA --}}
<table role="presentation" cellpadding="0" cellspacing="0" style="margin-top:8px;">
<tr>
<td style="border-left:4px solid {{ $colorAcento }}; padding-left:12px;">
<div style="font-weight:900; color:#0F172A;">Ing. Edgar García Basilio</div>
<div style="color:#334155;">Encargado del Área de Control Escolar</div>
<div style="color:#334155;">Centro Universitario Moctezuma A.C.</div>
</td>
</tr>
</table>

<x-mail::subcopy>
Este mensaje y sus anexos pueden contener información confidencial y de uso exclusivo del destinatario.
Si lo recibió por error, notifíquelo y elimínelo.
</x-mail::subcopy>
</x-mail::message>
{{-- @formatter:on --}}
{{-- prettier-ignore-end --}}
