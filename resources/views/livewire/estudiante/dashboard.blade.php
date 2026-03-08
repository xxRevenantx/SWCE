<div class="space-y-6">
    {{-- Encabezado --}}
    <section
        class="overflow-hidden rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
        <div class="h-1.5 w-full bg-gradient-to-r from-sky-500 via-blue-600 to-fuchsia-500"></div>

        <div class="p-5 sm:p-6 lg:p-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-sky-50 px-3 py-1 text-xs font-medium text-sky-700 ring-1 ring-sky-200 dark:bg-sky-500/10 dark:text-sky-300 dark:ring-sky-500/20">
                        Panel del estudiante
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-neutral-900 dark:text-white">
                        Bienvenido, {{ $nombre_estudiante }}
                    </h1>

                    <p class="text-sm sm:text-base text-neutral-600 dark:text-neutral-300">
                        Aquí puedes consultar tu información escolar, horario, avances y avisos importantes.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 min-w-full lg:min-w-[420px]">
                    <div
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-4">
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Matrícula</p>
                        <p class="mt-1 text-sm font-semibold text-neutral-900 dark:text-white">{{ $matricula }}</p>
                    </div>

                    <div
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-4">
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Estado de inscripción</p>
                        <p class="mt-1 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                            {{ $estado_inscripcion }}</p>
                    </div>

                    <div
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-4 sm:col-span-2">
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Licenciatura / Cuatrimestre</p>
                        <p class="mt-1 text-sm font-semibold text-neutral-900 dark:text-white">
                            {{ $licenciatura }} — {{ $cuatrimestre }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tarjetas resumen --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach ($resumen as $item)
            <article
                class="relative overflow-hidden rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r {{ $item['color'] }}"></div>

                <div class="p-5">
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $item['titulo'] }}</p>
                    <div class="mt-3 flex items-end justify-between gap-3">
                        <h2 class="text-3xl font-bold text-neutral-900 dark:text-white">
                            {{ $item['valor'] }}
                        </h2>

                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200">
                            @switch($item['icono'])
                                @case('academic-cap')
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.26 10.147a60.438 60.438 0 0 1 15.48 0M6.75 12a45.075 45.075 0 0 0 10.5 0m-12 3.75a48.108 48.108 0 0 1 13.5 0M12 20.25h.008v.008H12v-.008Z" />
                                    </svg>
                                @break

                                @case('book-open')
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.253v13m0-13C10.832 5.483 9.246 5 7.5 5 5.55 5 3.75 5.6 2.25 6.625v11.15C3.75 16.75 5.55 16.15 7.5 16.15c1.746 0 3.332.483 4.5 1.253m0-11.15C13.168 5.483 14.754 5 16.5 5c1.95 0 3.75.6 5.25 1.625v11.15c-1.5-1.025-3.3-1.625-5.25-1.625-1.746 0-3.332.483-4.5 1.253" />
                                    </svg>
                                @break

                                @case('check-badge')
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9 12.75 2.25 2.25 4.5-4.5M7.5 3.75h9a3 3 0 0 1 3 3v4.5a3 3 0 0 1-.879 2.121l-4.5 4.5A3 3 0 0 1 12 18.75a3 3 0 0 1-2.121-.879l-4.5-4.5A3 3 0 0 1 4.5 11.25v-4.5a3 3 0 0 1 3-3Z" />
                                    </svg>
                                @break

                                @default
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                            @endswitch
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    {{-- Contenido central --}}
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Horario de hoy --}}
        <article
            class="xl:col-span-2 rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div
                class="flex items-center justify-between border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <div>
                    <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Clases de hoy</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Consulta tus clases programadas.</p>
                </div>
            </div>

            <div class="p-5">
                @if (count($clases_hoy) > 0)
                    <div class="space-y-3">
                        @foreach ($clases_hoy as $clase)
                            <div
                                class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-4">
                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                                            {{ $clase['materia'] }}
                                        </p>
                                        <p class="text-sm text-neutral-600 dark:text-neutral-300">
                                            {{ $clase['profesor'] }}
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap gap-2 text-sm">
                                        <span
                                            class="rounded-full bg-sky-100 px-3 py-1 font-medium text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                            {{ $clase['hora'] }}
                                        </span>
                                        <span
                                            class="rounded-full bg-violet-100 px-3 py-1 font-medium text-violet-700 dark:bg-violet-500/10 dark:text-violet-300">
                                            {{ $clase['aula'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 p-8 text-center">
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            No tienes clases programadas para hoy.
                        </p>
                    </div>
                @endif
            </div>
        </article>

        {{-- Avisos --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Avisos importantes</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Mantente al tanto de tus pendientes.</p>
            </div>

            <div class="p-5 space-y-4">
                @foreach ($avisos as $aviso)
                    <div
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                                    {{ $aviso['titulo'] }}</p>
                                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">
                                    {{ $aviso['descripcion'] }}</p>
                            </div>
                            <span
                                class="shrink-0 rounded-full bg-neutral-200 px-2.5 py-1 text-xs font-medium text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                {{ $aviso['fecha'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>
    </section>

    {{-- Segunda fila --}}
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Documentación --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Estado de documentación</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Revisa qué archivos están pendientes.</p>
            </div>

            <div class="p-5 space-y-3">
                @foreach ($documentacion as $documento)
                    <div
                        class="flex items-center justify-between rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 px-4 py-3">
                        <p class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                            {{ $documento['nombre'] }}
                        </p>

                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $this->obtenerClaseBadgeEstado($documento['estado']) }}">
                            {{ $documento['estado'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </article>

        {{-- Calificaciones recientes --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Últimas calificaciones</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Resumen reciente de evaluaciones.</p>
            </div>

            <div class="p-5 space-y-3">
                @foreach ($calificaciones_recientes as $calificacion)
                    <div
                        class="flex items-center justify-between rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 px-4 py-3">
                        <p class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                            {{ $calificacion['materia'] }}
                        </p>

                        <span
                            class="rounded-full bg-sky-100 px-3 py-1 text-sm font-bold text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                            {{ $calificacion['calificacion'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </article>

        {{-- Progreso académico --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Progreso académico</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Avance general dentro de la carrera.</p>
            </div>

            <div class="p-5">
                <div
                    class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">Materias completadas</p>
                        <p class="text-lg font-bold text-neutral-900 dark:text-white">
                            {{ $progreso_academico['materias_cursadas'] }}/{{ $progreso_academico['materias_totales'] }}
                        </p>
                    </div>

                    <div class="mt-4 h-3 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-800">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-blue-600"
                            style="width: {{ $progreso_academico['porcentaje'] }}%">
                        </div>
                    </div>

                    <p class="mt-3 text-sm font-medium text-sky-700 dark:text-sky-300">
                        {{ $progreso_academico['porcentaje'] }}% de avance
                    </p>
                </div>
            </div>
        </article>
    </section>

</div>
