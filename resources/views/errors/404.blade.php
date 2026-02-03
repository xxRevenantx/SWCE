{{-- resources/views/errors/404.blade.php --}}
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 | Página no encontrada</title>

    {{-- Si usas Vite/Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-neutral-950 text-white">
    <main class="min-h-screen grid place-items-center px-6">
        <div class="max-w-xl w-full text-center">
            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm">
                <span class="inline-block h-2 w-2 rounded-full bg-rose-400"></span>
                Error 404
            </div>

            <h1 class="mt-6 text-3xl sm:text-5xl font-black tracking-tight">
                Página no encontrada
            </h1>

            <p class="mt-4 text-white/70">
                La URL que intentas abrir no existe o fue movida.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-white text-neutral-900 px-5 py-3 font-semibold hover:bg-white/90 transition">
                    Ir al inicio
                </a>

                <button onclick="history.back()"
                    class="inline-flex items-center justify-center rounded-xl bg-white/10 px-5 py-3 font-semibold hover:bg-white/15 transition">
                    Volver
                </button>
            </div>

            <p class="mt-8 text-xs text-white/40">
                Si crees que esto es un error, contacta al administrador.
            </p>
        </div>
    </main>
</body>

</html>
