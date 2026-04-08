@php

    $plantelNombre = 'Centro Universitario Moctezuma';
    $plantelCCT = '12PSU0173I';

    $plantelCalle = 'Francisco I. Madero Oriente';
    $plantelNo = '800';
    $plantelColonia = 'Esquipula';
    $plantelCP = '40662';
    $plantelCiudad = 'Ciudad Altamirano';
    $plantelMunicipio = 'Pungarabato';
    $plantelEstado = 'Guerrero';
@endphp

<style>
    footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 10px;
        padding: 0 34px;
    }

    .footer-box {
        border-top: 1px solid #cbd5e1;
        padding-top: 6px;
        text-align: center;
        color: #475569;
        font-size: 9.5px;
        line-height: 1.25;
    }

    .footer-box p {
        margin: 0;
    }

    .footer-box .foot-strong {
        font-weight: 700;
        color: #334155;
    }
</style>

<footer>
    <div class="footer-box">
        <p class="foot-strong uppercase">{{ $plantelNombre }} · C.C.T. {{ $plantelCCT }}</p>
        <p>
            C. {{ $plantelCalle }} No. {{ $plantelNo }}, Col. {{ $plantelColonia }}, C.P.
            {{ $plantelCP }},
            Cd. {{ $plantelCiudad }}, {{ $plantelEstado }}.
        </p>
        <p>Fecha de expedición: {{ now()->translatedFormat('d \\d\\e F \\d\\e\\l Y \\a \\l\\a\\s H:i') }}</p>
    </div>
</footer>
