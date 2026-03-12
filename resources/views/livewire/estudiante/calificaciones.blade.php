<div class="space-y-6">
    {{-- ENCABEZADO PRINCIPAL --}}
    <section
        class="relative overflow-hidden rounded-[32px] border border-white/60 bg-white/80 shadow-[0_20px_60px_-25px_rgba(15,23,42,0.28)] backdrop-blur-xl dark:border-white/5 dark:bg-neutral-900/80">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.16),transparent_28%),radial-gradient(circle_at_top_right,rgba(59,130,246,0.16),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(168,85,247,0.14),transparent_28%)]">
        </div>
        <div class="absolute -top-20 right-0 h-72 w-72 rounded-full bg-sky-400/10 blur-3xl"></div>
        <div class="absolute -bottom-20 left-0 h-72 w-72 rounded-full bg-indigo-400/10 blur-3xl"></div>

        <div class="relative p-6 sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-4">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-sky-200/70 bg-sky-50/80 px-3 py-1 text-xs font-semibold text-sky-700 dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-300">
                        Módulo del estudiante
                    </div>

                    <div class="space-y-2">
                        <h1 class="text-2xl font-black tracking-tight text-neutral-900 sm:text-3xl dark:text-white">
                            Calificaciones académicas
                        </h1>
                        <p class="max-w-2xl text-sm leading-6 text-neutral-600 dark:text-neutral-300">
                            Consulta el desempeño por materia, revisa el promedio general y da seguimiento a tus
                            resultados de manera clara y ordenada.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        <div
                            class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/5">
                            <p
                                class="text-xs font-medium uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                                Estudiante
                            </p>
                            <p class="mt-1 text-sm font-bold text-neutral-900 dark:text-white">
                                {{ $nombre_estudiante }}
                            </p>
                        </div>

                        <div
                            class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/5">
                            <p
                                class="text-xs font-medium uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                                Matrícula
                            </p>
                            <p class="mt-1 text-sm font-bold text-neutral-900 dark:text-white">
                                {{ $matricula }}
                            </p>
                        </div>

                        <div
                            class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/5">
                            <p
                                class="text-xs font-medium uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                                Licenciatura
                            </p>
                            <p class="mt-1 text-sm font-bold text-neutral-900 dark:text-white">
                                {{ $licenciatura }}
                            </p>
                        </div>

                        <div
                            class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/5">
                            <p
                                class="text-xs font-medium uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                                Cuatrimestre
                            </p>
                            <p class="mt-1 text-sm font-bold text-neutral-900 dark:text-white">
                                {{ $cuatrimestre }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="w-full max-w-sm">
                    <div
                        class="relative overflow-hidden rounded-3xl border border-sky-200/70 bg-gradient-to-br from-sky-500 via-blue-600 to-indigo-700 p-6 text-white shadow-[0_25px_60px_-25px_rgba(37,99,235,0.70)]">
                        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
                        <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>

                        <div class="relative">
                            <p class="text-sm font-medium text-white/80">
                                Promedio general
                            </p>

                            <div class="mt-3 flex items-end gap-2">
                                <span class="text-5xl font-black tracking-tight">
                                    {{ $promedio_general }}
                                </span>
                                <span class="mb-1 text-sm font-semibold text-white/80">
                                    / 10
                                </span>
                            </div>

                            <p class="mt-3 text-sm text-white/80">
                                Rendimiento global del periodo actual.
                            </p>

                            <div class="mt-5 h-3 w-full overflow-hidden rounded-full bg-white/20">
                                <div class="h-full rounded-full bg-white"
                                    style="width: {{ min((float) $promedio_general * 10, 100) }}%">
                                </div>
                            </div>

                            <p class="mt-2 text-xs text-white/75">
                                Avance académico general del estudiante
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TARJETAS DE RESUMEN --}}
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article
            class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total de materias</p>
                    <h3 class="mt-2 text-3xl font-black text-neutral-900 dark:text-white">
                        {{ $total_materias }}
                    </h3>
                </div>
                <div class="rounded-2xl bg-sky-100 p-3 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v11.494m-5.247-8.247h10.494" />
                    </svg>
                </div>
            </div>
        </article>

        <article
            class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Aprobadas</p>
                    <h3 class="mt-2 text-3xl font-black text-emerald-600 dark:text-emerald-400">
                        {{ $materias_aprobadas }}
                    </h3>
                </div>
                <div
                    class="rounded-2xl bg-emerald-100 p-3 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                    </svg>
                </div>
            </div>
        </article>

        <article
            class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Reprobadas</p>
                    <h3 class="mt-2 text-3xl font-black text-rose-600 dark:text-rose-400">
                        {{ $materias_reprobadas }}
                    </h3>
                </div>
                <div class="rounded-2xl bg-rose-100 p-3 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </article>

        <article
            class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Pendientes</p>
                    <h3 class="mt-2 text-3xl font-black text-amber-600 dark:text-amber-400">
                        {{ $materias_pendientes }}
                    </h3>
                </div>
                <div class="rounded-2xl bg-amber-100 p-3 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
        </article>
    </section>

    {{-- PANEL DE BÚSQUEDA --}}
    <section
        class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-bold text-neutral-900 dark:text-white">
                    Detalle de calificaciones
                </h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Busca por clave, materia, profesor o estado.
                </p>
            </div>

            <div class="w-full lg:max-w-md">
                <label for="buscar" class="sr-only">Buscar</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-neutral-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>

                    <input id="buscar" type="text" wire:model.live.debounce.300ms="buscar"
                        placeholder="Buscar materia, profesor o clave..."
                        class="h-12 w-full rounded-2xl border border-neutral-200 bg-neutral-50 pl-11 pr-4 text-sm text-neutral-800 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-sky-500 dark:focus:bg-neutral-900">
                </div>
            </div>
        </div>
    </section>

    {{-- TABLA ESCRITORIO --}}
    <section
        class="hidden overflow-hidden rounded-3xl border border-neutral-200/70 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 xl:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
                <thead class="bg-neutral-50/80 dark:bg-neutral-800/60">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                            Materia
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                            Profesor
                        </th>
                        <th
                            class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                            P1
                        </th>
                        <th
                            class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                            P2
                        </th>
                        <th
                            class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                            P3
                        </th>
                        <th
                            class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                            Final
                        </th>
                        <th
                            class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                            Asistencia
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                            Estado
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                    @forelse ($this->calificacionesFiltradas as $fila)
                        <tr class="transition hover:bg-neutral-50/80 dark:hover:bg-white/[0.03]">
                            <td class="px-6 py-5 align-top">
                                <div class="space-y-2">
                                    <div>
                                        <p class="text-sm font-bold text-neutral-900 dark:text-white">
                                            {{ $fila['materia'] }}
                                        </p>
                                        <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                            {{ $fila['clave'] }}
                                        </p>
                                    </div>

                                    @php
                                        $avance = is_numeric($fila['final']) ? min($fila['final'] * 10, 100) : 0;
                                    @endphp

                                    <div class="max-w-[220px]">
                                        <div
                                            class="h-2 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-800">
                                            <div class="h-full rounded-full bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-600"
                                                style="width: {{ $avance }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5 align-top">
                                <p class="text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $fila['profesor'] }}
                                </p>
                            </td>

                            <td
                                class="px-4 py-5 text-center text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                {{ $fila['parcial_1'] ?? '—' }}
                            </td>

                            <td
                                class="px-4 py-5 text-center text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                {{ $fila['parcial_2'] ?? '—' }}
                            </td>

                            <td
                                class="px-4 py-5 text-center text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                {{ $fila['parcial_3'] ?? '—' }}
                            </td>

                            <td class="px-4 py-5 text-center">
                                <span @class([
                                    'inline-flex min-w-[64px] items-center justify-center rounded-2xl px-3 py-1.5 text-sm font-bold',
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' =>
                                        is_numeric($fila['final']) && $fila['final'] >= 8,
                                    'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300' =>
                                        is_numeric($fila['final']) && $fila['final'] >= 6 && $fila['final'] < 8,
                                    'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' =>
                                        is_numeric($fila['final']) && $fila['final'] < 6,
                                    'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300' => !is_numeric(
                                        $fila['final']),
                                ])>
                                    {{ $fila['final'] ?? '—' }}
                                </span>
                            </td>

                            <td
                                class="px-4 py-5 text-center text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                {{ $fila['asistencia'] }}%
                            </td>

                            <td class="px-6 py-5 text-center">
                                <span @class([
                                    'inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset',
                                    'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20' =>
                                        $fila['estado'] === 'Aprobada',
                                    'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20' =>
                                        $fila['estado'] === 'Reprobada',
                                    'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20' =>
                                        $fila['estado'] === 'Pendiente',
                                ])>
                                    {{ $fila['estado'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">
                                <div class="mx-auto max-w-md space-y-2">
                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-neutral-900 dark:text-white">
                                        No se encontraron resultados
                                    </h3>
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                        Intenta con otro término de búsqueda para localizar una materia.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- TARJETAS MÓVIL --}}
    <section class="grid gap-4 xl:hidden">
        @forelse ($this->calificacionesFiltradas as $fila)
            @php
                $avance = is_numeric($fila['final']) ? min($fila['final'] * 10, 100) : 0;
            @endphp

            <article
                class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex flex-col gap-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-neutral-900 dark:text-white">
                                {{ $fila['materia'] }}
                            </h3>
                            <p class="mt-1 text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                {{ $fila['clave'] }}
                            </p>
                        </div>

                        <span @class([
                            'inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset',
                            'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20' =>
                                $fila['estado'] === 'Aprobada',
                            'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20' =>
                                $fila['estado'] === 'Reprobada',
                            'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20' =>
                                $fila['estado'] === 'Pendiente',
                        ])>
                            {{ $fila['estado'] }}
                        </span>
                    </div>

                    <div class="rounded-2xl bg-neutral-50 p-4 dark:bg-neutral-800/70">
                        <p class="text-xs font-medium uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                            Profesor
                        </p>
                        <p class="mt-1 text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                            {{ $fila['profesor'] }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <div class="rounded-2xl border border-neutral-200 p-3 dark:border-neutral-700">
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Parcial 1</p>
                            <p class="mt-1 text-lg font-bold text-neutral-900 dark:text-white">
                                {{ $fila['parcial_1'] ?? '—' }}</p>
                        </div>

                        <div class="rounded-2xl border border-neutral-200 p-3 dark:border-neutral-700">
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Parcial 2</p>
                            <p class="mt-1 text-lg font-bold text-neutral-900 dark:text-white">
                                {{ $fila['parcial_2'] ?? '—' }}</p>
                        </div>

                        <div class="rounded-2xl border border-neutral-200 p-3 dark:border-neutral-700">
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Parcial 3</p>
                            <p class="mt-1 text-lg font-bold text-neutral-900 dark:text-white">
                                {{ $fila['parcial_3'] ?? '—' }}</p>
                        </div>

                        <div class="rounded-2xl border border-neutral-200 p-3 dark:border-neutral-700">
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Final</p>
                            <p class="mt-1 text-lg font-black text-sky-600 dark:text-sky-400">
                                {{ $fila['final'] ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div
                            class="flex items-center justify-between text-xs font-medium text-neutral-500 dark:text-neutral-400">
                            <span>Desempeño</span>
                            <span>{{ is_numeric($fila['final']) ? $fila['final'] . '/10' : 'Sin capturar' }}</span>
                        </div>

                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-800">
                            <div class="h-full rounded-full bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-600"
                                style="width: {{ $avance }}%">
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-between rounded-2xl bg-neutral-50 px-4 py-3 dark:bg-neutral-800/70">
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">Asistencia</span>
                        <span
                            class="text-sm font-bold text-neutral-900 dark:text-white">{{ $fila['asistencia'] }}%</span>
                    </div>
                </div>
            </article>
        @empty
            <div
                class="rounded-3xl border border-dashed border-neutral-300 bg-white px-6 py-12 text-center dark:border-neutral-700 dark:bg-neutral-900">
                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-base font-bold text-neutral-900 dark:text-white">
                    No se encontraron resultados
                </h3>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                    Prueba con otra búsqueda para mostrar las materias.
                </p>
            </div>
        @endforelse
    </section>
</div>
