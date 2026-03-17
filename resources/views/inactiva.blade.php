<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cuenta dada de baja | SWCE</title>
    <meta name="theme-color" content="#f8fafc" />
    <meta name="color-scheme" content="light" />

    <link rel="icon" href="{{ asset('imagenes_publicas/logo-letra.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance

    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .swce-bg {
            background:
                radial-gradient(circle at top left, rgba(14, 165, 233, 0.10), transparent 25%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.08), transparent 22%),
                radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.08), transparent 22%),
                linear-gradient(180deg, #f8fbff 0%, #f1f5f9 45%, #eaf2fb 100%);
        }

        .swce-grid {
            background-image:
                linear-gradient(rgba(15, 23, 42, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.03) 1px, transparent 1px);
            background-size: 26px 26px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, .9), rgba(0, 0, 0, .45));
        }

        .glass-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.88), rgba(255, 255, 255, 0.75));
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .border-soft {
            border: 1px solid rgba(148, 163, 184, 0.22);
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .floaty {
            animation: floaty 6s ease-in-out infinite;
        }

        @keyframes pulse-soft {

            0%,
            100% {
                opacity: .7;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.03);
            }
        }

        .pulse-soft {
            animation: pulse-soft 4s ease-in-out infinite;
        }
    </style>
</head>

<body class="min-h-screen swce-bg text-slate-800 antialiased selection:bg-sky-200 selection:text-slate-900">
    <div class="fixed inset-0 swce-grid pointer-events-none"></div>

    <section class="relative min-h-screen overflow-hidden py-8 sm:py-12 lg:py-16">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-16 h-72 w-72 rounded-full bg-sky-400/20 blur-3xl"></div>
            <div class="absolute top-1/3 -right-20 h-80 w-80 rounded-full bg-blue-400/15 blur-3xl"></div>
            <div class="absolute -bottom-20 left-1/3 h-72 w-72 rounded-full bg-emerald-400/15 blur-3xl"></div>
        </div>

        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <header
                class="mb-8 flex flex-col gap-4 rounded-[28px] border-soft glass-card px-5 py-4 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.18)] sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-4">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <img src="{{ asset('imagenes_publicas/logo-letra.png') }}" alt="Logo SWCE" class="h-10 w-auto"
                            loading="eager" decoding="async">
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700/80">
                            Sistema Web de Control Escolar
                        </p>
                        <h2 class="mt-1 text-lg font-bold text-slate-900 sm:text-xl">
                            Centro Universitario Moctezuma A.C.
                        </h2>
                    </div>
                </a>

                <div class="flex flex-wrap items-center gap-3">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700">
                        <span class="h-2.5 w-2.5 rounded-full bg-rose-500 pulse-soft"></span>
                        Cuenta dada de baja
                    </span>

                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm text-slate-600">
                        Acceso restringido al sistema
                    </span>
                </div>
            </header>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1.15fr_.85fr] lg:gap-10">
                {{-- Panel izquierdo --}}
                <div
                    class="relative overflow-hidden rounded-[32px] border-soft glass-card p-6 shadow-[0_25px_70px_-35px_rgba(15,23,42,0.20)] sm:p-8 lg:p-10">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-sky-400 via-blue-500 to-emerald-400">
                    </div>

                    <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-sky-400/10 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 h-40 w-40 rounded-full bg-emerald-400/10 blur-3xl"></div>

                    <div
                        class="mb-5 inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-rose-700 lg:hidden">
                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                        Dado de baja
                    </div>

                    <div class="relative">
                        <div
                            class="mb-4 inline-flex items-center gap-2 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-medium text-sky-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 14h-2v-2h2Zm0-4h-2V7h2Z" />
                            </svg>
                            Estado administrativo
                        </div>

                        <h1
                            class="text-balance text-3xl font-black tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                            Acceso deshabilitado
                            <span
                                class="bg-gradient-to-r from-sky-600 via-blue-600 to-emerald-500 bg-clip-text text-transparent">
                                por baja administrativa
                            </span>
                        </h1>

                        <p class="mt-5 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base sm:leading-5">
                            Tu cuenta ha sido marcada como
                            <span class="font-semibold text-slate-900">dada de baja</span> por el área administrativa,
                            por lo que en este momento no puedes ingresar ni utilizar las funciones del sistema.
                            Si lo deseas, puedes solicitar una revisión.
                        </p>
                    </div>

                    {{-- Datos del usuario --}}
                    <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                Usuario
                            </p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 sm:text-base">
                                {{ $user->username ?? 'Usuario' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                Rol
                            </p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 sm:text-base">
                                {{ $user?->roles?->first()?->name ?? 'Sin rol asignado' }}
                            </p>
                        </div>
                    </div>

                    {{-- Tarjetas informativas --}}
                    <div class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                            <div
                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900">Cuenta identificada</h3>
                            <p class="mt-1 text-xs leading-6 text-slate-500">
                                El sistema detectó tu perfil y restringió el acceso automáticamente.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                            <div
                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path d="M6 6h12v12H6z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900">Acceso restringido</h3>
                            <p class="mt-1 text-xs leading-6 text-slate-500">
                                No podrás entrar a módulos ni consultar información mientras el estado siga activo.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                            <div
                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M12 2a10 10 0 1 0 10 10A10.012 10.012 0 0 0 12 2Zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4Z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900">Solicitud de revisión</h3>
                            <p class="mt-1 text-xs leading-6 text-slate-500">
                                Puedes pedir una validación administrativa si consideras que existe un error.
                            </p>
                        </div>
                    </div>

                    {{-- Acciones --}}

                    <form method="POST" action="{{ route('logout') }}" class="h-full">
                        @csrf


                        <button type="submit"
                            class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200  px-4 py-3 text-sm font-semibold text-slate-700 transition  focus:outline-none focus:ring-2 focus:ring-slate-200 bg-rose-100 text-rose-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path d="M13 3h-2v10h2z" />
                                <path d="M17.83 5.17A7 7 0 1 1 6.17 16.83l1.41-1.41a5 5 0 1 0 7.07-7.07z" />
                            </svg>
                            Cerrar sesión
                        </button>



                    </form>



                </div>

                {{-- Panel derecho --}}
                <div class="flex items-center justify-center">
                    <div
                        class="relative w-full max-w-2xl overflow-hidden rounded-[32px] border-soft glass-card p-5 shadow-[0_25px_70px_-35px_rgba(15,23,42,0.18)]">
                        <div
                            class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-rose-400 via-sky-400 to-emerald-400">
                        </div>

                        <div class="absolute -top-14 right-0 h-36 w-36 rounded-full bg-sky-400/10 blur-3xl"></div>
                        <div class="absolute -bottom-14 left-0 h-36 w-36 rounded-full bg-blue-400/10 blur-3xl"></div>

                        <div class="relative">
                            <div
                                class="mb-4 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">
                                SWCE · Control de acceso
                            </div>

                            <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-slate-50 p-4 sm:p-6">
                                <img src="https://pagedone.io/asset/uploads/1718004199.png" alt="Cuenta deshabilitada"
                                    class="floaty mx-auto h-auto w-full max-w-md object-contain" loading="lazy"
                                    decoding="async">
                            </div>

                            <div class="mt-5 rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                                <p class="text-sm font-semibold text-slate-900">
                                    Estatus actual del acceso
                                </p>
                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Tu perfil se encuentra inactivo dentro del sistema escolar. Cuando el área
                                    correspondiente reactive tu cuenta, podrás ingresar nuevamente con normalidad.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <footer class="mx-auto mt-10 max-w-6xl px-2 text-center">
                <div
                    class="rounded-2xl border border-slate-200 bg-white/70 px-4 py-4 text-sm text-slate-500 shadow-sm">
                    © {{ date('Y') }} Sistema Web de Control Escolar — Centro Universitario Moctezuma A.C.
                </div>
            </footer>
        </div>
    </section>
</body>

</html>
