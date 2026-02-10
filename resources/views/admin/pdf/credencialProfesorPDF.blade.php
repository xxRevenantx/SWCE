<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <title>EXPEDIENTE DEL ALUMNO | {{ $alumno->alumno->nombre }} {{ $alumno->alumno->apellido_paterno }}
        {{ $alumno->alumno->apellido_materno }}
    </title>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Credencial del Profesor</h1>
                <p>Nombre: {{ $profesor->nombre }} {{ $profesor->apellido_paterno }} {{ $profesor->apellido_materno }}
                </p>
                <p>Correo: {{ $profesor->correo }}</p>
                <!-- Agrega más información del profesor según sea necesario -->
            </div>
        </div>
    </div>
</body>

</html>
