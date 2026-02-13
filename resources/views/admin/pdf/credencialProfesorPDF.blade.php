<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <title>CREDENCIAL DEL PROFESOR | {{ $profesor->nombre }} {{ $profesor->apellido_paterno }}
        {{ $profesor->apellido_materno }}
    </title>

    <style>
        @page {
            margin: 30px 0px 0px 0px;
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
            font-family: sans-serif;
        }


        .contenedor {
            width: 100%;
            /* background: #000; */
            margin: auto;
            padding: 0 0 0 70px;
        }

        .credencial {
            border: 1px solid #000;
            width: 18cm;
            height: 5.7cm;
            margin: 5px auto;
            /* padding: 10px; */

        }

        .imagen {
            width: 50px;
            position: absolute;
            right: 90px;
            margin: 15px auto 0;

        }

        .titulo {
            font-size: 10px;
            color: #fff;
            margin-top: -165px;
            margin-left: 38px;
        }

        .info {
            font-size: 10px;
            margin-top: -150px;
            line-height: 15px;
            margin-left: 130px;
            width: 200px;
        }

        .info .contenido {
            font-size: 9.5px;
        }
    </style>

</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="contenedor">


        <img class="credencial" src="{{ public_path('imagenes_publicas/credencial-profesor.jpg') }}">

        <div style="border:1px solid #767676; width: 2.4cm; height: 2.8cm; margin:-155px 0 0 10px">

        </div>

        <div class="info">
            <h1 class="titulo">CREDENCIAL DEL PROFESOR</h1>

            <div class="contenido">
                <b>Nombre:</b>{{ $profesor->nombre }} {{ $profesor->apellido_paterno }}
                {{ $profesor->apellido_materno }}<br>
                <b>CURP:</b> {{ $profesor->CURP ?? '-----------' }} <br>
                <b>Teléfono:</b> {{ $profesor->telefono ?? '-----------' }} <br>
                <b>Ciclo escolar:</b> 2025-2026 <br>
                <b>Vigencia:</b> AGOSTO 2026
            </div>




        </div>




    </div>
</body>

</html>
