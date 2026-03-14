{{-- resources/views/errors/404.blade.php --}}
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 | SWCE - Página no encontrada</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800 dark:bg-neutral-950 dark:text-white">
    <main class="relative min-h-screen overflow-hidden">
        {{-- Fondo decorativo --}}
        <div class="absolute inset-0">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.14),transparent_30%),radial-gradient(circle_at_top_right,rgba(37,99,235,0.14),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(168,85,247,0.10),transparent_28%)]">
            </div>
            <div class="absolute -top-24 left-0 h-72 w-72 rounded-full bg-sky-400/20 blur-3xl"></div>
            <div class="absolute -bottom-20 right-0 h-72 w-72 rounded-full bg-blue-500/20 blur-3xl"></div>
        </div>

        <div class="relative grid min-h-screen place-items-center px-6 py-10">
            <section
                class="w-full max-w-3xl overflow-hidden rounded-[28px] border border-white/60 bg-white/80 shadow-[0_20px_60px_-20px_rgba(15,23,42,0.30)] backdrop-blur-xl dark:border-white/10 dark:bg-neutral-900/80">

                {{-- Barra superior institucional --}}
                <div class="h-1.5 w-full bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600"></div>

                <div class="p-8 sm:p-10 lg:p-12">
                    <div class="flex flex-col items-center text-center">
                        {{-- Badge --}}
                        <div
                            class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-medium text-sky-700 shadow-sm dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-300">
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-sky-500"></span>
                            Sistema Web de Control Escolar
                        </div>

                        {{-- Código de error --}}
                        <div class="mt-8">
                            <p
                                class="text-7xl font-black tracking-tight text-transparent bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 bg-clip-text sm:text-8xl">
                                404
                            </p>
                        </div>

                        {{-- Título --}}
                        <h1
                            class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                            Página no encontrada
                        </h1>

                        {{-- Descripción --}}
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base dark:text-neutral-300">
                            La dirección que intentas abrir no está disponible dentro del sistema SWCE.
                            Esto puede ocurrir porque la ruta no existe, fue movida o no tienes permisos para acceder a
                            ella.
                        </p>

                        {{-- Tarjeta informativa --}}
                        <div
                            class="mt-8 w-full rounded-2xl border border-slate-200 bg-slate-50/80 p-5 text-left shadow-sm dark:border-neutral-800 dark:bg-neutral-800/60">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-lg">
                                    !
                                </div>

                                <div class="space-y-1">
                                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">
                                        Recomendación
                                    </h2>
                                    <p class="text-sm leading-6 text-slate-600 dark:text-neutral-300">
                                        Verifica que la URL sea correcta o regresa al panel principal para continuar
                                        navegando
                                        dentro del sistema de forma segura.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="mt-8 flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
                            <a href="{{ url('/') }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:scale-[1.01] hover:shadow-xl">
                                Ir al inicio
                            </a>

                            <button onclick="history.back()"
                                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800">
                                Volver a la página anterior
                            </button>
                        </div>

                        {{-- Pie --}}
                        <p class="mt-8 text-xs text-slate-500 dark:text-neutral-500">
                            SWCE · Centro Universitario Moctezuma
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>

</html>
