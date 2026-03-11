<div x-data="{
    mostrarModalPdf: false,
    pdfModalUrl: '',
    timeoutPdf: null,
    cargandoPdf: false,

    abrirPdf(url) {
        if (!url || url === '#') return;

        clearTimeout(this.timeoutPdf);

        this.cargandoPdf = true;
        this.pdfModalUrl = url;
        this.mostrarModalPdf = true;

        document.body.classList.add('overflow-hidden');
    },

    cerrarPdf() {
        this.mostrarModalPdf = false;
        document.body.classList.remove('overflow-hidden');

        clearTimeout(this.timeoutPdf);

        this.timeoutPdf = setTimeout(() => {
            this.pdfModalUrl = '';
            this.cargandoPdf = false;
        }, 220);
    },

    pdfCargado() {
        this.cargandoPdf = false;
    }
}" x-on:keydown.escape.window="cerrarPdf()">

    {{-- ENCABEZADO  --}}
    <section
        class="relative overflow-hidden rounded-[32px] border border-white/60 bg-white/80 shadow-[0_20px_60px_-25px_rgba(15,23,42,0.28)] backdrop-blur-xl dark:border-white/5 dark:bg-neutral-900/80">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,0.16),transparent_28%),radial-gradient(circle_at_top_right,rgba(59,130,246,0.16),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(168,85,247,0.14),transparent_28%)]">
        </div>
        <div class="absolute -top-20 right-0 h-72 w-72 rounded-full bg-emerald-400/10 blur-3xl"></div>
        <div class="absolute -bottom-20 left-0 h-72 w-72 rounded-full bg-sky-400/10 blur-3xl"></div>
        <div class="h-1.5 w-full bg-gradient-to-r from-emerald-500 via-sky-500 to-violet-500"></div>

        <div class="relative p-5 sm:p-6 lg:py-2">
            <div class="flex flex-col gap-6 2xl:flex-row 2xl:items-center 2xl:justify-between">
                <div class="space-y-4">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-white sm:text-3xl">
                            Mi horario
                        </h1>
                        <p class="mt-1.5 max-w-2xl text-sm leading-6 text-neutral-600 dark:text-neutral-300">
                            Consulta tus clases. Identifica rápidamente tus materias, profesores y bloques por día.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-4 xl:min-w-[560px]">
                    <div
                        class="rounded-2xl border border-white/70 bg-white/85 p-4 shadow-sm backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/75">
                        <p
                            class="text-[11px] font-semibold uppercase tracking-[0.20em] text-neutral-500 dark:text-neutral-400">
                            Licenciatura
                        </p>
                        <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">
                            {{ $licenciatura }}
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/70 bg-white/85 p-4 shadow-sm backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/75">
                        <p
                            class="text-[11px] font-semibold uppercase tracking-[0.20em] text-neutral-500 dark:text-neutral-400">
                            Cuatrimestre
                        </p>
                        <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">
                            {{ $cuatrimestre }}
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/70 bg-white/85 p-4 shadow-sm backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/75">
                        <p
                            class="text-[11px] font-semibold uppercase tracking-[0.20em] text-neutral-500 dark:text-neutral-400">
                            Generación
                        </p>
                        <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">
                            {{ $generacion }}
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/70 bg-white/85 p-4 shadow-sm backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/75">
                        <p
                            class="text-[11px] font-semibold uppercase tracking-[0.20em] text-neutral-500 dark:text-neutral-400">
                            Día actual
                        </p>
                        <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">
                            {{ $dia_actual ? $this->formatearDia($dia_actual) : 'Sin clases' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- BUSCADOR --}}
    <section
        class="overflow-hidden rounded-[30px] border border-white/60 bg-white/85 shadow-sm backdrop-blur-xl dark:border-white/5 dark:bg-neutral-900/80 mt-2">
        <div class="border-b border-neutral-200/70 px-5 py-4 dark:border-neutral-800">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white">
                        Buscar dentro del horario
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Filtra por profesor, clave, materia, hora o día.
                    </p>
                </div>

                <div class="w-full xl:max-w-2xl">
                    <div class="relative">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-neutral-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0a7 7 0 0 1 14 0Z" />
                            </svg>
                        </span>

                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Escribe profesor, materia, clave, hora o día..."
                            class="w-full rounded-[22px] border border-neutral-200/80 bg-white/90 py-3.5 pl-11 pr-12 text-sm text-neutral-900 shadow-sm outline-none placeholder:text-neutral-400 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder:text-neutral-500 dark:focus:border-emerald-500 dark:focus:ring-emerald-500/20">

                        @if ($search !== '')
                            <button type="button" wire:click="limpiarBusqueda"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-neutral-400 transition hover:text-rose-500"
                                title="Limpiar búsqueda">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($search !== '')
            <div
                class="bg-emerald-50/80 px-5 py-3 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                Mostrando resultados para:
                <span class="font-semibold">{{ $search }}</span>
            </div>
        @endif
    </section>

    {{-- DISTRIBUCIÓN POR PROFESOR --}}

    <section
        class="mt-3 overflow-hidden rounded-[30px] border border-white/60 bg-white/85 shadow-sm backdrop-blur-xl dark:border-white/5 dark:bg-neutral-900/80">
        <div x-data="{ abierto: false }" class="relative">

            {{-- Encabezado del collapse --}}
            <button type="button" @click="abierto = !abierto"
                class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left transition hover:bg-neutral-50/70 dark:hover:bg-neutral-800/50">
                <div class="flex min-w-0 items-center gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 via-fuchsia-500 to-pink-500 text-white shadow-[0_12px_30px_-12px_rgba(168,85,247,0.65)]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 0 0 3.742-.479 3 3 0 0 0-4.682-2.72m.94 3.198v.75c0 .414-.336.75-.75.75H6.75a.75.75 0 0 1-.75-.75v-.75m12 0a5.97 5.97 0 0 0-1.94-4.41M18 18.72a5.97 5.97 0 0 1-1.94-4.41m0 0a5.97 5.97 0 0 0-8.12 0m8.12 0A5.97 5.97 0 0 1 12 16.5a5.97 5.97 0 0 1-4.06-1.59m0 0A5.97 5.97 0 0 0 6 18.72m1.94-3.81a3 3 0 1 0-4.682 2.72A9.094 9.094 0 0 0 6 18.72m6-9.47a3.75 3.75 0 1 0 0-7.5a3.75 3.75 0 0 0 0 7.5Zm6 3a3 3 0 1 0 0-6a3 3 0 0 0 0 6Zm-12 0a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <h2 class="text-base font-semibold text-neutral-900 dark:text-white">
                            Distribución docente
                        </h2>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                            Consulta las materias, días y horas asignadas a cada profesor dentro de tu horario.
                        </p>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-3">
                    <div
                        class="hidden sm:inline-flex items-center rounded-full border border-violet-200 bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-700 dark:border-violet-500/20 dark:bg-violet-500/10 dark:text-violet-300">
                        {{ count($distribucion_profesores) }} profesor(es)
                    </div>

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-2xl border border-neutral-200 bg-white text-neutral-500 shadow-sm transition dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                        <svg x-show="!abierto" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>

                        <svg x-show="abierto" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                            stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                        </svg>
                    </div>
                </div>
            </button>

            {{-- Contenido del collapse --}}
            <div x-show="abierto" x-collapse x-cloak
                class="border-t border-neutral-200/70 bg-neutral-50/70 px-5 py-5 dark:border-neutral-800 dark:bg-neutral-950/30">

                @if (count($distribucion_profesores))
                    <div class="grid grid-cols-1 gap-5 2xl:grid-cols-2">
                        @foreach ($distribucion_profesores as $profesorItem)
                            <article
                                class="group relative overflow-hidden rounded-[28px] border border-white/70 bg-white/90 shadow-[0_16px_40px_-22px_rgba(15,23,42,0.18)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_20px_45px_-22px_rgba(15,23,42,0.22)] dark:border-white/5 dark:bg-neutral-900/85">

                                {{-- Línea superior --}}
                                <div class="h-1.5 w-full"
                                    style="background: linear-gradient(90deg, {{ $profesorItem['color'] }} 0%, color-mix(in srgb, {{ $profesorItem['color'] }} 70%, white 30%) 100%);">
                                </div>

                                {{-- Decoración --}}
                                <div class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full opacity-15 blur-2xl"
                                    style="background: {{ $profesorItem['color'] }};">
                                </div>

                                <div class="relative p-5">
                                    {{-- Cabecera profesor --}}
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex min-w-0 items-start gap-4">
                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-white shadow-sm"
                                                style="background: linear-gradient(135deg, {{ $profesorItem['color'] }} 0%, color-mix(in srgb, {{ $profesorItem['color'] }} 75%, #0f172a 25%) 100%);">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0a3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                </svg>
                                            </div>

                                            <div class="min-w-0">
                                                <h3
                                                    class="text-sm font-bold leading-5 text-neutral-900 dark:text-white">
                                                    {{ $profesorItem['profesor'] }}
                                                </h3>

                                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                                    <span
                                                        class="inline-flex items-center rounded-full border border-neutral-200 bg-neutral-50 px-2.5 py-1 text-[11px] font-medium text-neutral-600 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                                        {{ count($profesorItem['materias']) }} materia(s)
                                                    </span>

                                                    <span
                                                        class="inline-flex items-center rounded-full border border-neutral-200 bg-neutral-50 px-2.5 py-1 text-[11px] font-medium text-neutral-600 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                                        {{ $profesorItem['total_bloques'] }} bloque(s)
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <span class="mt-1 inline-block h-3.5 w-3.5 rounded-full shrink-0"
                                            style="background: {{ $profesorItem['color'] }};"></span>
                                    </div>

                                    {{-- Materias --}}
                                    <div class="mt-5 space-y-4">
                                        @foreach ($profesorItem['materias'] as $materiaItem)
                                            <div
                                                class="rounded-[22px] border border-neutral-200/70 bg-neutral-50/80 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="min-w-0">
                                                        <p
                                                            class="text-sm font-semibold text-neutral-900 dark:text-white">
                                                            {{ $materiaItem['materia'] }}
                                                        </p>
                                                        <p
                                                            class="mt-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                                            {{ $materiaItem['clave'] }}
                                                        </p>
                                                    </div>

                                                    <span
                                                        class="inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-[11px] font-semibold"
                                                        style="background: color-mix(in srgb, {{ $materiaItem['color'] }} 12%, white 88%); color: {{ $materiaItem['color'] }};">
                                                        {{ count($materiaItem['bloques']) }} bloque(s)
                                                    </span>
                                                </div>

                                                <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                                    @foreach ($materiaItem['bloques'] as $bloque)
                                                        <div
                                                            class="flex items-center justify-between gap-3 rounded-2xl border border-neutral-200/70 bg-white px-3 py-2.5 text-sm shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                                                            <div class="flex min-w-0 items-center gap-2">
                                                                <span class="h-2.5 w-2.5 rounded-full shrink-0"
                                                                    style="background: {{ $materiaItem['color'] }};"></span>
                                                                <span
                                                                    class="truncate font-medium text-neutral-800 dark:text-neutral-200">
                                                                    {{ $bloque['dia'] }}
                                                                </span>
                                                            </div>

                                                            <span
                                                                class="shrink-0 text-neutral-500 dark:text-neutral-400">
                                                                {{ $bloque['hora'] }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div
                        class="rounded-3xl border border-dashed border-neutral-300 bg-neutral-50 px-6 py-10 text-center dark:border-neutral-700 dark:bg-neutral-800/40">
                        <h3 class="text-base font-semibold text-neutral-900 dark:text-white">
                            No hay distribución disponible
                        </h3>
                        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                            No se encontraron profesores o bloques para mostrar en este apartado.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- CALENDARIO  DESKTOP --}}
    <section class="hidden xl:block mt-3">
        <div
            class="rounded-[32px] border border-white/60 bg-white/85 shadow-[0_20px_60px_-28px_rgba(15,23,42,0.30)] backdrop-blur-xl dark:border-white/5 dark:bg-neutral-900/80">
            <div class="grid grid-cols-[380px_minmax(0,1fr)]">
                {{-- SIDEBAR --}}
                <aside
                    class="border-r border-neutral-200/70 bg-neutral-50/70 dark:border-neutral-800 dark:bg-neutral-950/40">
                    <div class="border-b border-neutral-200/70 p-5 dark:border-neutral-800">

                        <h2 class="mt-2 text-xl font-bold text-neutral-900 dark:text-white">
                            Agenda semanal
                        </h2>

                    </div>

                    <div class="space-y-4 p-5">
                        {{-- TARJETA DÍA ACTIVO --}}
                        <div
                            class="relative overflow-hidden rounded-[28px] bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 p-5 text-white shadow-[0_16px_40px_-16px_rgba(16,185,129,0.55)]">
                            <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10"></div>
                            <div class="absolute bottom-0 left-0 h-20 w-20 rounded-full bg-white/10 blur-2xl"></div>

                            <p class="text-xs uppercase tracking-[0.18em] text-white/80">Día activo</p>
                            <p class="mt-3 text-3xl font-bold">
                                {{ $dia_actual ? $this->formatearDia($dia_actual) : 'Sin clases' }}
                            </p>
                            <p class="mt-2 text-sm text-white/85">
                                {{ $materias_hoy }} bloque(s) programado(s)
                            </p>
                        </div>

                        {{-- TARJETAS RESUMEN --}}
                        <div class="grid grid-cols-1 gap-4">
                            <article
                                class="group relative overflow-hidden rounded-[24px] border border-white/50 bg-white/85 p-4 shadow-[0_18px_40px_-22px_rgba(59,130,246,0.40)] backdrop-blur-xl transition duration-300 hover:-translate-y-1 dark:border-white/5 dark:bg-neutral-900/80">
                                <div
                                    class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500">
                                </div>
                                <div class="absolute -top-6 -right-6 h-20 w-20 rounded-full bg-sky-400/10 blur-2xl">
                                </div>

                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Materias
                                        </p>
                                        <p
                                            class="mt-3 text-4xl font-bold tracking-tight text-neutral-900 dark:text-white">
                                            {{ $total_materias }}
                                        </p>
                                        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                            Materias distintas asignadas
                                        </p>
                                    </div>

                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 ring-1 ring-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:ring-sky-500/20">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z" />
                                        </svg>
                                    </div>
                                </div>
                            </article>

                            <article
                                class="group relative overflow-hidden rounded-[24px] border border-white/50 bg-white/85 p-4 shadow-[0_18px_40px_-22px_rgba(16,185,129,0.40)] backdrop-blur-xl transition duration-300 hover:-translate-y-1 dark:border-white/5 dark:bg-neutral-900/80">
                                <div
                                    class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-emerald-400 via-teal-500 to-cyan-500">
                                </div>
                                <div
                                    class="absolute -top-6 -right-6 h-20 w-20 rounded-full bg-emerald-400/10 blur-2xl">
                                </div>

                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Bloques
                                            semanales</p>
                                        <p
                                            class="mt-3 text-4xl font-bold tracking-tight text-neutral-900 dark:text-white">
                                            {{ $total_bloques }}
                                        </p>
                                        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                            Clases registradas en la semana
                                        </p>
                                    </div>

                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h12m-9-9h7.5m-7.5 4.5h4.5m5.25 8.25-3-3m0 0a3.75 3.75 0 1 0-5.303 0a3.75 3.75 0 0 0 5.303 0Z" />
                                        </svg>
                                    </div>
                                </div>
                            </article>

                            <article
                                class="group relative overflow-hidden rounded-[24px] border border-white/50 bg-white/85 p-4 shadow-[0_18px_40px_-22px_rgba(236,72,153,0.40)] backdrop-blur-xl transition duration-300 hover:-translate-y-1 dark:border-white/5 dark:bg-neutral-900/80">
                                <div
                                    class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-fuchsia-400 via-pink-500 to-rose-500">
                                </div>
                                <div
                                    class="absolute -top-6 -right-6 h-20 w-20 rounded-full bg-fuchsia-400/10 blur-2xl">
                                </div>

                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Clases
                                            hoy</p>
                                        <p
                                            class="mt-3 text-4xl font-bold tracking-tight text-neutral-900 dark:text-white">
                                            {{ $materias_hoy }}
                                        </p>
                                        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                            Bloques del día actual
                                        </p>
                                    </div>

                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-fuchsia-50 text-fuchsia-600 ring-1 ring-fuchsia-100 dark:bg-fuchsia-500/10 dark:text-fuchsia-300 dark:ring-fuchsia-500/20">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                                        </svg>
                                    </div>
                                </div>
                            </article>

                            <article
                                class="group relative overflow-hidden rounded-[24px] border border-white/50 bg-white/85 p-4 shadow-[0_18px_40px_-22px_rgba(139,92,246,0.40)] backdrop-blur-xl transition duration-300 hover:-translate-y-1 dark:border-white/5 dark:bg-neutral-900/80">
                                <div
                                    class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-violet-400 via-purple-500 to-indigo-500">
                                </div>
                                <div class="absolute -top-6 -right-6 h-20 w-20 rounded-full bg-violet-400/10 blur-2xl">
                                </div>

                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Día
                                            activo</p>
                                        <p
                                            class="mt-3 text-2xl font-bold tracking-tight text-neutral-900 dark:text-white">
                                            {{ $dia_actual ? $this->formatearDia($dia_actual) : 'Sin clases' }}
                                        </p>
                                        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                            Se resalta dentro del calendario
                                        </p>
                                    </div>

                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-50 text-violet-600 ring-1 ring-violet-100 dark:bg-violet-500/10 dark:text-violet-300 dark:ring-violet-500/20">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V8.25A2.25 2.25 0 0 1 5.25 6h13.5A2.25 2.25 0 0 1 21 8.25v10.5A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75ZM3 10.5h18" />
                                        </svg>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <div
                            class="rounded-[28px] border border-neutral-200/70 bg-white/85 p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900/80">
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                Indicadores visuales
                            </h3>

                            <div class="mt-4 space-y-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-3.5 w-3.5 rounded-full bg-sky-500"></span>
                                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Día resaltado</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="h-3.5 w-3.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Cabecera de
                                        agenda</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="h-3.5 w-3.5 rounded-full bg-violet-500"></span>
                                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Bloques de
                                        clase</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- CONTENIDO --}}
                <div class="relative isolate min-w-0">
                    {{-- BARRA SUPERIOR --}}
                    <div class="border-b border-neutral-200/70 dark:border-neutral-800">
                        <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-6 py-4">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-3 text-white">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 backdrop-blur">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-xs uppercase tracking-[0.22em] text-white/75">
                                            Vista de calendario
                                        </p>
                                        <h3 class="text-xl font-semibold">
                                            Semana académica
                                        </h3>
                                    </div>
                                </div>

                                <div class="flex overflow-hidden rounded-2xl bg-white/10 p-1 backdrop-blur">
                                    <button type="button"
                                        x-on:click="abrirPdf('{{ $this->filtrosListos ? $this->pdfUrl : '' }}')"
                                        class="{{ $this->clasePdf }}">
                                        Ver horario en PDF
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between bg-neutral-50/80 px-6 py-4 dark:bg-neutral-900/60">
                            <div>
                                <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                                    Distribución semanal de clases
                                </p>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                    Visualiza cada bloque por día, profesor y horario.
                                </p>
                            </div>

                            <div
                                class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
                                {{ $dia_actual ? 'Hoy: ' . $this->formatearDia($dia_actual) : 'Sin día activo' }}
                            </div>
                        </div>
                    </div>

                    @if (count($horas))
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-separate border-spacing-0">
                                <thead>
                                    <tr>
                                        <th
                                            class="sticky left-0 z-30 w-[150px] border-b border-r border-neutral-200 bg-white/95 px-4 py-5 text-left text-[11px] font-semibold uppercase tracking-[0.18em] text-neutral-500 backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/95 dark:text-neutral-400">
                                            Hora
                                        </th>

                                        @foreach ($dias as $dia)
                                            <th
                                                class="border-b border-neutral-200 px-4 py-4 text-center dark:border-neutral-800
                                                {{ $dia === $dia_actual ? 'bg-sky-50/80 dark:bg-sky-500/10' : 'bg-neutral-50/60 dark:bg-neutral-900/50' }}">
                                                <div class="space-y-1">
                                                    <p
                                                        class="text-[11px] font-semibold uppercase tracking-[0.18em]
                                                        {{ $dia === $dia_actual ? 'text-sky-700 dark:text-sky-300' : 'text-neutral-500 dark:text-neutral-400' }}">
                                                        Día
                                                    </p>
                                                    <p
                                                        class="text-base font-bold
                                                        {{ $dia === $dia_actual ? 'text-sky-700 dark:text-sky-300' : 'text-neutral-900 dark:text-white' }}">
                                                        {{ $this->formatearDia($dia) }}
                                                    </p>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($horas as $hora)
                                        <tr class="align-top">
                                            <td
                                                class="sticky left-0 z-20 border-r border-b border-neutral-200 bg-white/95 px-4 py-4 backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/95">
                                                <div class="flex min-h-[170px] flex-col">
                                                    <span class="text-sm font-bold text-neutral-900 dark:text-white">
                                                        {{ $hora }}
                                                    </span>
                                                    <span class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                                        Bloque horario
                                                    </span>
                                                </div>
                                            </td>

                                            @foreach ($dias as $dia)
                                                @php
                                                    $celda = $horario[$hora][$dia] ?? null;
                                                    $esHoy = $dia === $dia_actual;
                                                    $tooltipIzquierda = in_array($dia, ['JUEVES', 'VIERNES'], true);
                                                @endphp

                                                <td
                                                    class="relative overflow-visible border-b border-neutral-200 px-3 py-3 align-top transition dark:border-neutral-800 hover:z-[150]
                                                    {{ $esHoy ? 'bg-sky-50/30 dark:bg-sky-500/5' : 'bg-white/60 dark:bg-transparent' }}">
                                                    @if ($celda)
                                                        <div
                                                            class="group relative z-40 h-full min-h-[170px] overflow-hidden rounded-[26px] border border-neutral-200/80 bg-white/92 shadow-[0_14px_34px_-18px_rgba(15,23,42,0.22)] transition duration-300 hover:z-[200] hover:-translate-y-1 hover:shadow-[0_20px_40px_-18px_rgba(15,23,42,0.28)] dark:border-neutral-700/70 dark:bg-neutral-900/90">

                                                            <div
                                                                class="pointer-events-none absolute inset-x-0 top-0 z-0 overflow-hidden rounded-t-[26px]">
                                                                <div class="h-1.5 w-full"
                                                                    style="background: linear-gradient(90deg, {{ $celda['color'] }} 0%, color-mix(in srgb, {{ $celda['color'] }} 70%, white 30%) 100%);">
                                                                </div>
                                                            </div>

                                                            <div class="pointer-events-none absolute -right-6 -top-6 z-0 h-20 w-20 rounded-full opacity-20 blur-2xl"
                                                                style="background: {{ $celda['color'] }};">
                                                            </div>

                                                            <div
                                                                class="relative z-10 flex h-full flex-col justify-between p-4 pt-6">
                                                                <div class="flex items-start justify-between gap-3">
                                                                    <div class="min-w-0">
                                                                        <p
                                                                            class="line-clamp-2 text-base font-bold leading-tight text-neutral-900 dark:text-white">
                                                                            {{ $celda['materia'] }}
                                                                        </p>

                                                                        <p
                                                                            class="mt-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                                                            {{ $celda['clave'] }}
                                                                        </p>
                                                                    </div>

                                                                    <div class="flex flex-col items-end gap-2">
                                                                        @if ($esHoy)
                                                                            <span
                                                                                class="shrink-0 rounded-full bg-sky-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                                                                Hoy
                                                                            </span>
                                                                        @endif



                                                                    </div>
                                                                </div>

                                                                <div class="mt-4">
                                                                    <div
                                                                        class="inline-flex items-center gap-2 rounded-full border border-neutral-200 bg-neutral-50 px-3 py-1.5 text-xs font-medium text-neutral-600 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                                                        <span class="h-2.5 w-2.5 rounded-full"
                                                                            style="background: {{ $celda['color'] }};"></span>
                                                                        {{ $celda['hora'] }}
                                                                    </div>

                                                                    <p class="text-xs text-neutral-400 leading-4 mt-2">
                                                                        Profesor:
                                                                        {{ $celda['profesor'] }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="flex min-h-[170px] items-center justify-center rounded-[26px] border border-dashed border-neutral-200 bg-neutral-50/80 text-sm text-neutral-400 dark:border-neutral-700 dark:bg-neutral-800/35 dark:text-neutral-500">
                                                            Sin clase
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8">
                            <div
                                class="rounded-3xl border border-dashed border-neutral-300 bg-neutral-50 p-12 text-center dark:border-neutral-700 dark:bg-neutral-800/40">
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                    No se encontraron resultados
                                </h3>
                                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                    Ajusta tu búsqueda para ver materias, profesores, días u horarios.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- TABLET --}}
    <section class="hidden lg:block xl:hidden">
        <div
            class="overflow-hidden rounded-[30px] border border-white/60 bg-white/85 shadow-sm backdrop-blur-xl dark:border-white/5 dark:bg-neutral-900/80">
            <div
                class="border-b border-neutral-200/70 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-6 py-4 dark:border-neutral-800">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-white">Horario semanal</h2>
                        <p class="text-sm text-white/80">Vista compacta tipo calendario</p>
                    </div>

                    <div class="rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold text-white backdrop-blur">
                        {{ $dia_actual ? 'Hoy: ' . $this->formatearDia($dia_actual) : 'Sin día activo' }}
                    </div>
                </div>
            </div>

            @if (count($horas))
                <div class="overflow-x-auto">
                    <table class="min-w-full border-separate border-spacing-0 relative">
                        <thead>
                            <tr>
                                <th
                                    class="sticky left-0 z-20 border-b border-neutral-200 bg-neutral-50 px-4 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300">
                                    Hora
                                </th>

                                @foreach ($dias as $dia)
                                    <th
                                        class="border-b px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide
                                        {{ $dia === $dia_actual
                                            ? 'border-sky-200 bg-sky-50 text-sky-700 dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-300'
                                            : 'border-neutral-200 bg-neutral-50 text-neutral-600 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300' }}">
                                        {{ $this->formatearDia($dia) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($horas as $hora)
                                <tr class="align-top">
                                    <td
                                        class="sticky left-0 z-10 border-b border-neutral-200 bg-white px-4 py-4 text-sm font-semibold text-neutral-900 dark:border-neutral-800 dark:bg-neutral-900 dark:text-white">
                                        {{ $hora }}
                                    </td>

                                    @foreach ($dias as $dia)
                                        @php
                                            $celda = $horario[$hora][$dia] ?? null;
                                            $esHoy = $dia === $dia_actual;
                                        @endphp

                                        <td
                                            class="border-b px-3 py-3 align-top
                                            {{ $esHoy ? 'border-sky-100 bg-sky-50/40 dark:border-sky-500/10 dark:bg-sky-500/5' : 'border-neutral-200 dark:border-neutral-800' }}">
                                            @if ($celda)
                                                <div
                                                    class="relative overflow-hidden rounded-[24px] border border-neutral-200/80 bg-white/92 p-4 shadow-[0_14px_30px_-16px_rgba(15,23,42,0.20)] transition duration-300 hover:-translate-y-0.5 dark:border-neutral-700/70 dark:bg-neutral-900/90">
                                                    <div class="absolute inset-x-0 top-0 h-1.5 rounded-t-[24px]"
                                                        style="background: linear-gradient(90deg, {{ $celda['color'] }} 0%, color-mix(in srgb, {{ $celda['color'] }} 70%, white 30%) 100%);">
                                                    </div>

                                                    <div class="relative z-10 pt-2">
                                                        <div class="flex items-start justify-between gap-2">
                                                            <div class="min-w-0">
                                                                <p
                                                                    class="text-sm font-bold leading-tight text-neutral-900 dark:text-white">
                                                                    {{ $celda['materia'] }}
                                                                </p>
                                                                <p
                                                                    class="mt-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-neutral-500 dark:text-neutral-400">
                                                                    {{ $celda['clave'] }}
                                                                </p>
                                                            </div>

                                                            @if ($esHoy)
                                                                <span
                                                                    class="rounded-full bg-sky-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                                                    Hoy
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <div
                                                            class="mt-4 h-px w-full bg-neutral-200 dark:bg-neutral-700">
                                                        </div>

                                                        <div
                                                            class="mt-3 space-y-1.5 text-xs text-neutral-600 dark:text-neutral-300">
                                                            <p>
                                                                <span
                                                                    class="font-semibold text-neutral-800 dark:text-white">Hora:</span>
                                                                {{ $celda['hora'] }}
                                                            </p>
                                                            <p>
                                                                <span
                                                                    class="font-semibold text-neutral-800 dark:text-white">Profesor:</span>
                                                                {{ $celda['profesor'] }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div
                                                    class="flex min-h-[150px] items-center justify-center rounded-[24px] border border-dashed border-neutral-200 bg-neutral-50 text-sm text-neutral-400 dark:border-neutral-700 dark:bg-neutral-800/40 dark:text-neutral-500">
                                                    Sin clase
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8">
                    <div
                        class="rounded-3xl border border-dashed border-neutral-300 bg-neutral-50 p-10 text-center dark:border-neutral-700 dark:bg-neutral-800/40">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                            No se encontraron resultados
                        </h3>
                        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                            Ajusta tu búsqueda para ver materias, profesores, días u horarios.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- MÓVIL PREMIUM --}}
    <section class="space-y-4 lg:hidden">
        @if (count($horas))
            @foreach ($dias as $dia)
                <article
                    class="overflow-hidden rounded-[28px] border shadow-sm
                    {{ $dia === $dia_actual
                        ? 'border-sky-200 bg-sky-50/40 dark:border-sky-500/20 dark:bg-sky-500/5'
                        : 'border-neutral-200/70 bg-white dark:border-neutral-800 dark:bg-neutral-900' }}">
                    <div
                        class="flex items-center justify-between border-b px-5 py-4
                        {{ $dia === $dia_actual
                            ? 'border-sky-200 bg-gradient-to-r from-sky-50 via-blue-50 to-indigo-50 dark:border-sky-500/20 dark:from-sky-500/10 dark:via-blue-500/10 dark:to-indigo-500/10'
                            : 'border-neutral-200/70 bg-gradient-to-r from-neutral-50 to-white dark:border-neutral-800 dark:from-neutral-900 dark:to-neutral-800/70' }}">
                        <h3
                            class="text-sm font-semibold uppercase tracking-[0.18em]
                            {{ $dia === $dia_actual ? 'text-sky-700 dark:text-sky-300' : 'text-neutral-700 dark:text-neutral-200' }}">
                            {{ $this->formatearDia($dia) }}
                        </h3>

                        @if ($dia === $dia_actual)
                            <span
                                class="rounded-full bg-sky-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                Hoy
                            </span>
                        @endif
                    </div>

                    <div class="space-y-3 p-4">
                        @php
                            $hayRegistros = false;
                        @endphp

                        @foreach ($horas as $hora)
                            @php
                                $celda = $horario[$hora][$dia] ?? null;
                                $esHoy = $dia === $dia_actual;
                                $tooltipIzquierda = true;
                            @endphp

                            @if ($celda)
                                @php
                                    $hayRegistros = true;
                                @endphp

                                <div
                                    class="group relative z-40 h-full min-h-[170px] overflow-visible rounded-[26px] border border-neutral-200/80 bg-white/92 shadow-[0_14px_34px_-18px_rgba(15,23,42,0.22)] transition duration-300 hover:z-[200] hover:-translate-y-1 hover:shadow-[0_20px_40px_-18px_rgba(15,23,42,0.28)] dark:border-neutral-700/70 dark:bg-neutral-900/90">

                                    <div
                                        class="pointer-events-none absolute inset-x-0 top-0 z-0 overflow-hidden rounded-t-[26px]">
                                        <div class="h-1.5 w-full"
                                            style="background: linear-gradient(90deg, {{ $celda['color'] }} 0%, color-mix(in srgb, {{ $celda['color'] }} 70%, white 30%) 100%);">
                                        </div>
                                    </div>

                                    <div class="pointer-events-none absolute -right-6 -top-6 z-0 h-20 w-20 rounded-full opacity-20 blur-2xl"
                                        style="background: {{ $celda['color'] }};">
                                    </div>

                                    <div class="relative z-10 flex h-full flex-col justify-between p-4 pt-6">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p
                                                    class="line-clamp-2 text-base font-bold leading-tight text-neutral-900 dark:text-white">
                                                    {{ $celda['materia'] }}
                                                </p>

                                                <p
                                                    class="mt-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                                    {{ $celda['clave'] }}
                                                </p>
                                            </div>

                                            <div class="flex flex-col items-end gap-2">
                                                @if ($esHoy)
                                                    <span
                                                        class="shrink-0 rounded-full bg-sky-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                                        Hoy
                                                    </span>
                                                @endif

                                                <div class="relative z-[300]">
                                                    <div class="flex h-8 w-8 items-center justify-center rounded-xl border border-neutral-200 bg-neutral-50 text-neutral-500 transition group-hover:border-transparent group-hover:text-white dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                                                        onmouseover="this.style.background='{{ $celda['color'] }}';"
                                                        onmouseout="this.style.background='';">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372A3.375 3.375 0 0 0 21 16.125V15a4.125 4.125 0 0 0-.825-2.475l-1.05-1.4A4.125 4.125 0 0 1 18.3 8.65V7.875a6.375 6.375 0 1 0-12.75 0v.775a4.125 4.125 0 0 1-.825 2.475l-1.05 1.4A4.125 4.125 0 0 0 3 15v1.125A3.375 3.375 0 0 0 6.375 19.5c.9 0 1.787-.127 2.625-.372m6 0a3 3 0 1 1-6 0m6 0a3 3 0 1 0-6 0" />
                                                        </svg>
                                                    </div>

                                                    <div
                                                        class="pointer-events-none absolute top-1/2 right-full z-[9999] mr-4 w-72 -translate-y-1/2 rounded-2xl border border-white/70 bg-white/95 p-4 opacity-0 shadow-[0_22px_60px_-18px_rgba(15,23,42,0.38)] backdrop-blur-xl transition-all duration-300 group-hover:pointer-events-auto group-hover:opacity-100 dark:border-white/10 dark:bg-neutral-900/95">
                                                        <div class="relative">
                                                            <div class="flex items-start gap-3">
                                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl text-white shadow-sm"
                                                                    style="background: linear-gradient(135deg, {{ $celda['color'] }} 0%, color-mix(in srgb, {{ $celda['color'] }} 75%, #0f172a 25%) 100%);">
                                                                    <svg class="h-5 w-5" fill="none"
                                                                        stroke="currentColor" stroke-width="1.8"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0a3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                                    </svg>
                                                                </div>

                                                                <div class="min-w-0">
                                                                    <p
                                                                        class="text-[11px] font-semibold uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                                                        Profesor asignado
                                                                    </p>
                                                                    <p
                                                                        class="mt-1 text-sm font-bold leading-5 text-neutral-900 dark:text-white">
                                                                        {{ $celda['profesor'] }}
                                                                    </p>
                                                                    <div
                                                                        class="mt-3 flex items-center gap-2 text-xs text-neutral-500 dark:text-neutral-400">
                                                                        <span
                                                                            class="inline-block h-2.5 w-2.5 rounded-full"
                                                                            style="background: {{ $celda['color'] }};"></span>
                                                                        <span>{{ $celda['hora'] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="absolute top-1/2 -right-[6px] h-3 w-3 -translate-y-1/2 rotate-45 border-r border-t border-white/70 bg-white/95 dark:border-white/10 dark:bg-neutral-900/95">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <div
                                                class="inline-flex items-center gap-2 rounded-full border border-neutral-200 bg-neutral-50 px-3 py-1.5 text-xs font-medium text-neutral-600 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                                <span class="h-2.5 w-2.5 rounded-full"
                                                    style="background: {{ $celda['color'] }};"></span>
                                                {{ $celda['hora'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if (!$hayRegistros)
                            <div
                                class="rounded-2xl border border-dashed border-neutral-300 bg-neutral-50 px-4 py-6 text-center text-sm text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800/40 dark:text-neutral-400">
                                Sin clases programadas
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        @else
            <div
                class="rounded-3xl border border-dashed border-neutral-300 bg-neutral-50 p-8 text-center dark:border-neutral-700 dark:bg-neutral-800/40">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                    No se encontraron resultados
                </h3>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                    Ajusta tu búsqueda para intentar nuevamente.
                </p>
            </div>
        @endif
    </section>

    {{-- MODAL PDF --}}
    <div x-cloak x-show="mostrarModalPdf" x-transition.opacity.duration.200ms
        class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6" aria-labelledby="titulo-modal-pdf"
        aria-modal="true" role="dialog">

        {{-- Fondo --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" x-on:click="cerrarPdf()"></div>

        {{-- Ventana --}}
        <div x-show="mostrarModalPdf" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-6xl overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/10 dark:bg-neutral-900 dark:ring-white/10">

            {{-- Encabezado del modal --}}
            <div
                class="flex items-center justify-between gap-3 border-b border-neutral-200 px-5 py-4 dark:border-neutral-800">
                <div>
                    <h2 id="titulo-modal-pdf"
                        class="text-base sm:text-lg font-semibold text-neutral-900 dark:text-white">
                        Horario en PDF
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Vista previa del horario del estudiante.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a x-bind:href="pdfModalUrl" target="_blank" rel="noopener"
                        class="inline-flex items-center rounded-xl bg-sky-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-sky-600">
                        Abrir en pestaña
                    </a>

                    <button type="button" x-on:click="cerrarPdf()"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-neutral-200 bg-white text-neutral-500 transition hover:text-rose-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                        aria-label="Cerrar modal">
                        ✕
                    </button>
                </div>
            </div>

            {{-- Cuerpo del modal --}}
            <div class="bg-neutral-100 p-4 sm:p-5 dark:bg-neutral-950">
                <div
                    class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">

                    {{-- Loader --}}
                    <div x-show="cargandoPdf" x-transition.opacity
                        class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-white/85 backdrop-blur-sm dark:bg-neutral-900/85">
                        <div class="flex flex-col items-center justify-center gap-4 px-6 text-center">
                            <div
                                class="h-14 w-14 animate-spin rounded-full border-4 border-sky-200 border-t-sky-500 dark:border-sky-900/50 dark:border-t-sky-400">
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                                    Cargando vista previa...
                                </p>
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    Espera un momento mientras se muestra el PDF.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Iframe --}}
                    <iframe x-bind:src="pdfModalUrl" x-on:load="pdfCargado()"
                        class="h-[75vh] w-full bg-white dark:bg-neutral-900">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
