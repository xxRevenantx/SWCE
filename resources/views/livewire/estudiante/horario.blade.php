<div class="space-y-6">
    {{-- ENCABEZADO --}}
    <section
        class="relative overflow-hidden rounded-[28px] border border-neutral-200/70 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-500 via-blue-600 to-indigo-700 opacity-[0.10]"></div>
        <div class="absolute -top-16 -right-16 h-56 w-56 rounded-full bg-sky-400/20 blur-3xl"></div>
        <div class="absolute -bottom-16 -left-16 h-56 w-56 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
        <div class="h-1.5 w-full bg-gradient-to-r from-sky-500 via-blue-600 to-fuchsia-500"></div>

        <div class="relative p-5 sm:p-6 lg:p-7">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                <div class="space-y-4">
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-200 dark:bg-sky-500/10 dark:text-sky-300 dark:ring-sky-500/20">
                        Horario del estudiante
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-white sm:text-3xl">
                            Mi horario semanal
                        </h1>
                        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">
                            Consulta tus clases, materias y profesores asignados de forma clara y ordenada.
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3 shadow-sm backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/80">
                        <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Alumno</p>
                        <p class="mt-1 text-sm font-semibold text-neutral-900 dark:text-white">
                            {{ $nombre_estudiante }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:w-[520px]">
                    <div
                        class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/80">
                        <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Matrícula</p>
                        <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">{{ $matricula }}</p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/80">
                        <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Licenciatura
                        </p>
                        <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">{{ $licenciatura }}</p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/80">
                        <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Cuatrimestre
                        </p>
                        <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">{{ $cuatrimestre }}</p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-neutral-800 dark:bg-neutral-900/80">
                        <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Generación</p>
                        <p class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">{{ $generacion }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- RESUMEN --}}
    <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article
            class="relative overflow-hidden rounded-[26px] bg-gradient-to-br from-sky-400 via-blue-500 to-blue-600 p-6 shadow-[0_12px_30px_-12px_rgba(59,130,246,0.45)]">
            <div class="absolute -top-6 right-6 h-20 w-20 rounded-full bg-white/10"></div>
            <div class="absolute top-10 right-16 h-14 w-14 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-6 left-16 h-24 w-24 rounded-full bg-white/10"></div>

            <div
                class="absolute right-6 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 backdrop-blur-sm">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z" />
                </svg>
            </div>

            <div class="relative z-10">
                <p class="text-sm font-medium text-white/95">Materias</p>
                <p class="mt-8 text-5xl font-bold tracking-tight text-white">{{ $total_materias }}</p>
                <p class="mt-4 text-sm text-white/90">Materias distintas asignadas</p>
            </div>
        </article>

        <article
            class="relative overflow-hidden rounded-[26px] bg-gradient-to-br from-emerald-400 via-teal-400 to-cyan-400 p-6 shadow-[0_12px_30px_-12px_rgba(45,212,191,0.45)]">
            <div class="absolute -top-6 right-6 h-20 w-20 rounded-full bg-white/10"></div>
            <div class="absolute top-10 right-16 h-14 w-14 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-6 left-16 h-24 w-24 rounded-full bg-white/10"></div>

            <div
                class="absolute right-6 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 backdrop-blur-sm">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h12m-9-9h7.5m-7.5 4.5h4.5m5.25 8.25-3-3m0 0a3.75 3.75 0 1 0-5.303 0a3.75 3.75 0 0 0 5.303 0Z" />
                </svg>
            </div>

            <div class="relative z-10">
                <p class="text-sm font-medium text-white/95">Bloques semanales</p>
                <p class="mt-8 text-5xl font-bold tracking-tight text-white">{{ $total_bloques }}</p>
                <p class="mt-4 text-sm text-white/90">Total de clases registradas</p>
            </div>
        </article>

        <article
            class="relative overflow-hidden rounded-[26px] bg-gradient-to-br from-fuchsia-400 via-pink-500 to-rose-500 p-6 shadow-[0_12px_30px_-12px_rgba(236,72,153,0.45)]">
            <div class="absolute -top-6 right-6 h-20 w-20 rounded-full bg-white/10"></div>
            <div class="absolute top-10 right-16 h-14 w-14 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-6 left-16 h-24 w-24 rounded-full bg-white/10"></div>

            <div
                class="absolute right-6 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 backdrop-blur-sm">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                </svg>
            </div>

            <div class="relative z-10">
                <p class="text-sm font-medium text-white/95">Clases hoy</p>
                <p class="mt-8 text-5xl font-bold tracking-tight text-white">{{ $materias_hoy }}</p>
                <p class="mt-4 text-sm text-white/90">Bloques asignados para el día actual</p>
            </div>
        </article>

        <article
            class="relative overflow-hidden rounded-[26px] bg-gradient-to-br from-violet-400 via-purple-400 to-indigo-500 p-6 shadow-[0_12px_30px_-12px_rgba(139,92,246,0.45)]">
            <div class="absolute -top-6 right-6 h-20 w-20 rounded-full bg-white/10"></div>
            <div class="absolute top-10 right-16 h-14 w-14 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-6 left-16 h-24 w-24 rounded-full bg-white/10"></div>

            <div
                class="absolute right-6 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 backdrop-blur-sm">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V8.25A2.25 2.25 0 0 1 5.25 6h13.5A2.25 2.25 0 0 1 21 8.25v10.5A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75ZM3 10.5h18" />
                </svg>
            </div>

            <div class="relative z-10">
                <p class="text-sm font-medium text-white/95">Día actual</p>
                <p class="mt-8 text-3xl font-bold tracking-tight text-white">
                    {{ $dia_actual ? $this->formatearDia($dia_actual) : 'Sin clases' }}
                </p>
                <p class="mt-4 text-sm text-white/90">Se resalta dentro del horario semanal</p>
            </div>
        </article>
    </section>

    {{-- BUSCADOR --}}
    <section
        class="overflow-hidden rounded-[28px] border border-neutral-200/70 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
        <div class="border-b border-neutral-200/70 px-5 py-4 dark:border-neutral-800">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white">Buscar dentro del horario</h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Filtra por profesor, clave, materia, hora o día.
                    </p>
                </div>

                <div class="w-full lg:max-w-xl">
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
                            class="w-full rounded-2xl border border-neutral-200 bg-white py-3 pl-11 pr-12 text-sm text-neutral-900 shadow-sm outline-none ring-0 placeholder:text-neutral-400 focus:border-sky-400 focus:ring-4 focus:ring-sky-100 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder:text-neutral-500 dark:focus:border-sky-500 dark:focus:ring-sky-500/20">

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
            <div class="bg-sky-50/70 px-5 py-3 text-sm text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                Mostrando resultados para:
                <span class="font-semibold">{{ $search }}</span>
            </div>
        @endif
    </section>

    {{-- TABLA DESKTOP --}}
    <section class="hidden lg:block">
        <div
            class="overflow-hidden rounded-[28px] border border-neutral-200/70 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div
                class="flex items-center justify-between border-b border-neutral-200/70 px-6 py-4 dark:border-neutral-800">
                <div>
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white">Horario semanal</h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Vista organizada por días y bloques de horario.
                    </p>
                </div>
            </div>

            @if (count($horas))
                <div class="overflow-x-auto">
                    <table class="min-w-full border-separate border-spacing-0">
                        <thead>
                            <tr>
                                <th
                                    class="sticky left-0 z-20 border-b border-neutral-200 bg-neutral-50 px-4 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300">
                                    Hora
                                </th>

                                @foreach ($dias as $dia)
                                    <th
                                        class="border-b px-4 py-4 text-left text-xs font-semibold uppercase tracking-wide
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
                                        @endphp

                                        <td
                                            class="border-b px-3 py-3 align-top
                                            {{ $dia === $dia_actual ? 'border-sky-100 bg-sky-50/40 dark:border-sky-500/10 dark:bg-sky-500/5' : 'border-neutral-200 dark:border-neutral-800' }}">
                                            @if ($celda)
                                                <div class="relative overflow-hidden rounded-[26px] p-5 shadow-[0_14px_32px_-14px_rgba(0,0,0,0.35)] ring-1 ring-white/10 transition duration-300 hover:-translate-y-1 hover:shadow-[0_20px_38px_-16px_rgba(0,0,0,0.42)]"
                                                    style="
                                                        background:
                                                            radial-gradient(circle at top right, rgba(255,255,255,0.16), transparent 28%),
                                                            radial-gradient(circle at 80% 30%, rgba(255,255,255,0.10), transparent 18%),
                                                            linear-gradient(135deg, {{ $celda['color'] }} 0%, color-mix(in srgb, {{ $celda['color'] }} 78%, #111827 22%) 100%);
                                                        color: {{ $this->obtenerColorTexto($celda['color']) }};
                                                    ">
                                                    <div
                                                        class="absolute -top-6 right-5 h-20 w-20 rounded-full bg-white/10">
                                                    </div>
                                                    <div
                                                        class="absolute top-12 right-16 h-12 w-12 rounded-full bg-white/10">
                                                    </div>
                                                    <div
                                                        class="absolute -bottom-6 left-16 h-24 w-24 rounded-full bg-white/10">
                                                    </div>
                                                    <div
                                                        class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-black/10">
                                                    </div>

                                                    <div class="relative z-10 flex items-start justify-between gap-3">
                                                        <div class="min-w-0">
                                                            <p class="text-lg font-bold leading-tight">
                                                                {{ $celda['materia'] }}
                                                            </p>

                                                            <p class="mt-1 text-sm font-medium opacity-90">
                                                                {{ $celda['clave'] }}
                                                            </p>
                                                        </div>

                                                        @if ($dia === $dia_actual)
                                                            <span
                                                                class="shrink-0 rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white backdrop-blur-sm">
                                                                Hoy
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="relative z-10 mt-5 h-px w-full bg-white/20"></div>

                                                    <div class="relative z-10 mt-5 space-y-2 text-sm">
                                                        <p>
                                                            <span class="font-semibold">Horario:</span>
                                                            {{ $celda['hora'] }}
                                                        </p>

                                                        <p class="leading-relaxed">
                                                            <span class="font-semibold">Profesor:</span>
                                                            {{ $celda['profesor'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @else
                                                <div
                                                    class="flex min-h-[170px] items-center justify-center rounded-[26px] border border-dashed border-neutral-200 bg-neutral-50 text-sm text-neutral-400 dark:border-neutral-700 dark:bg-neutral-800/40 dark:text-neutral-500">
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

    {{-- VISTA MÓVIL --}}
    <section class="space-y-4 lg:hidden">
        @if (count($horas))
            @foreach ($dias as $dia)
                <article
                    class="overflow-hidden rounded-3xl border shadow-sm
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
                            @endphp

                            @if ($celda)
                                @php
                                    $hayRegistros = true;
                                @endphp

                                <div class="relative overflow-hidden rounded-[26px] p-5 shadow-[0_14px_32px_-14px_rgba(0,0,0,0.35)] ring-1 ring-white/10"
                                    style="
                                        background:
                                            radial-gradient(circle at top right, rgba(255,255,255,0.16), transparent 28%),
                                            radial-gradient(circle at 80% 30%, rgba(255,255,255,0.10), transparent 18%),
                                            linear-gradient(135deg, {{ $celda['color'] }} 0%, color-mix(in srgb, {{ $celda['color'] }} 78%, #111827 22%) 100%);
                                        color: {{ $this->obtenerColorTexto($celda['color']) }};
                                    ">
                                    <div class="absolute -top-6 right-5 h-20 w-20 rounded-full bg-white/10"></div>
                                    <div class="absolute top-12 right-16 h-12 w-12 rounded-full bg-white/10"></div>
                                    <div class="absolute -bottom-6 left-16 h-24 w-24 rounded-full bg-white/10"></div>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-black/10">
                                    </div>

                                    <div class="relative z-10">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-lg font-bold leading-tight">
                                                    {{ $celda['materia'] }}
                                                </p>

                                                <p class="mt-1 text-sm font-medium opacity-90">
                                                    {{ $celda['clave'] }}
                                                </p>
                                            </div>

                                            @if ($dia === $dia_actual)
                                                <span
                                                    class="shrink-0 rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white backdrop-blur-sm">
                                                    Hoy
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-5 h-px w-full bg-white/20"></div>

                                        <div class="mt-5 space-y-2 text-sm">
                                            <p>
                                                <span class="font-semibold">Horario:</span>
                                                {{ $celda['hora'] }}
                                            </p>

                                            <p class="leading-relaxed">
                                                <span class="font-semibold">Profesor:</span>
                                                {{ $celda['profesor'] }}
                                            </p>
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
</div>
