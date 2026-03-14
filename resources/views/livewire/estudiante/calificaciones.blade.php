<div x-data="{
    mostrarModalBoleta: false,
    urlBoleta: '',
    abrirBoleta(url) {
        this.urlBoleta = url;
        this.mostrarModalBoleta = true;
        document.body.classList.add('overflow-hidden');
    },
    cerrarBoleta() {
        this.mostrarModalBoleta = false;
        this.urlBoleta = '';
        document.body.classList.remove('overflow-hidden');
    }
}" @keydown.escape.window="cerrarBoleta()" class="space-y-6">
    {{-- MODAL PRO | BOLETA --}}
    <div x-cloak x-show="mostrarModalBoleta" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6">
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="cerrarBoleta()"></div>

        {{-- Panel --}}
        <div x-show="mostrarModalBoleta" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95 blur-sm"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100 blur-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100 blur-0"
            x-transition:leave-end="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95 blur-sm" @click.stop
            role="dialog" aria-modal="true"
            class="relative z-10 flex w-full max-w-7xl flex-col overflow-hidden rounded-3xl border border-white/10 bg-white shadow-2xl ring-1 ring-black/5 dark:bg-neutral-950 dark:ring-white/10">

            {{-- Barra superior --}}
            <div
                class="flex items-center justify-between bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-5 py-4 text-white sm:px-6">
                <div>
                    <h3 class="text-base font-bold sm:text-lg">
                        Vista previa de boleta
                    </h3>
                    <p class="text-xs text-white/80 sm:text-sm">
                        Consulta tu boleta en PDF sin salir del sistema.
                    </p>
                </div>

                <button type="button" @click="cerrarBoleta()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 text-white transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Contenido --}}
            <div class="max-h-[85vh] overflow-hidden bg-neutral-100 dark:bg-neutral-900">
                <template x-if="urlBoleta">
                    <iframe :src="urlBoleta" class="h-[78vh] w-full bg-white" frameborder="0"></iframe>
                </template>
            </div>

            {{-- Footer --}}
            <div
                class="flex flex-col gap-3 border-t border-neutral-200 bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 dark:border-neutral-800 dark:bg-neutral-950">
                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                    Si el PDF tarda un poco, espera unos segundos mientras carga la vista previa.
                </p>

                <div class="flex items-center justify-end gap-3">
                    <a :href="urlBoleta" target="_blank"
                        class="inline-flex items-center justify-center rounded-2xl border border-neutral-200 px-4 py-2.5 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-50 focus:outline-none focus:ring-4 focus:ring-sky-500/10 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">
                        Abrir en pestaña nueva
                    </a>

                    <button type="button" @click="cerrarBoleta()"
                        class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-[0_10px_30px_-12px_rgba(37,99,235,0.80)] transition hover:shadow-[0_18px_40px_-18px_rgba(37,99,235,0.85)] focus:outline-none focus:ring-4 focus:ring-sky-500/20">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ENCABEZADO --}}
    <section
        class="relative overflow-hidden rounded-[32px] border border-white/60 bg-white/80 shadow-[0_20px_60px_-25px_rgba(15,23,42,0.28)] backdrop-blur-xl dark:border-white/5 dark:bg-neutral-900/80">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.16),transparent_28%),radial-gradient(circle_at_top_right,rgba(59,130,246,0.16),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(168,85,247,0.14),transparent_28%)]">
        </div>
        <div class="absolute -top-20 right-0 h-72 w-72 rounded-full bg-sky-400/10 blur-3xl"></div>
        <div class="absolute -bottom-20 left-0 h-72 w-72 rounded-full bg-indigo-400/10 blur-3xl"></div>

        <div class="relative p-6 sm:p-8">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                <div class="space-y-4">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-sky-200/70 bg-sky-50/80 px-3 py-1 text-xs font-semibold text-sky-700 dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-300">
                        Historial académico
                    </div>

                    <div class="space-y-2">
                        <h1 class="text-2xl font-black tracking-tight text-neutral-900 sm:text-3xl dark:text-white">
                            Calificaciones
                        </h1>
                        <p class="max-w-2xl text-sm leading-6 text-neutral-600 dark:text-neutral-300">
                            Consulta tus materias, estados académicos y calificaciones organizadas por cuatrimestre.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
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
                                Calculado con base en el promedio de cada cuatrimestre con calificaciones registradas.
                            </p>

                            <div class="mt-5 h-3 w-full overflow-hidden rounded-full bg-white/20">
                                <div class="h-full rounded-full bg-white"
                                    style="width: {{ min((float) $promedio_general * 10, 100) }}%">
                                </div>
                            </div>

                            <p class="mt-2 text-xs text-white/75">
                                Rendimiento académico general
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TARJETAS RESUMEN --}}
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <article
            class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Cuatrimestres</p>
            <h3 class="mt-2 text-3xl font-black text-neutral-900 dark:text-white">
                {{ $total_cuatrimestres }}
            </h3>
        </article>

        <article
            class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Materias</p>
            <h3 class="mt-2 text-3xl font-black text-neutral-900 dark:text-white">
                {{ $total_materias }}
            </h3>
        </article>

        <article
            class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Aprobadas</p>
            <h3 class="mt-2 text-3xl font-black text-emerald-600 dark:text-emerald-400">
                {{ $materias_aprobadas }}
            </h3>
        </article>

        <article
            class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Reprobadas</p>
            <h3 class="mt-2 text-3xl font-black text-rose-600 dark:text-rose-400">
                {{ $materias_reprobadas }}
            </h3>
        </article>

        <article
            class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Pendientes</p>
            <h3 class="mt-2 text-3xl font-black text-amber-600 dark:text-amber-400">
                {{ $materias_pendientes }}
            </h3>
        </article>
    </section>

    {{-- BÚSQUEDA --}}
    <section
        class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-bold text-neutral-900 dark:text-white">
                    Historial por cuatrimestre
                </h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Busca por cuatrimestre, materia, profesor, estado o fecha de captura.
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
                        placeholder="Buscar..."
                        class="h-12 w-full rounded-2xl border border-neutral-200 bg-neutral-50 pl-11 pr-4 text-sm text-neutral-800 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-sky-500 dark:focus:bg-neutral-900">
                </div>
            </div>
        </div>
    </section>

    {{-- BLOQUES POR CUATRIMESTRE --}}
    <section class="space-y-5">
        @forelse ($cuatrimestresPaginados as $cuatrimestre)



            <div x-data="{ abierto: {{ $loop->first ? 'true' : 'false' }} }"
                class="overflow-hidden rounded-3xl border border-neutral-200/70 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">

                <div class="flex items-start justify-between gap-4 px-5 py-5 sm:px-6">
                    <div class="min-w-0 flex-1 space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-bold text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                {{ $cuatrimestre['no_cuatrimestre'] }}° cuatrimestre
                            </span>

                            <span
                                class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                {{ $cuatrimestre['generacion'] }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-lg font-black text-neutral-900 dark:text-white">
                                {{ $cuatrimestre['nombre_cuatrimestre'] }}
                            </h3>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                            <div class="rounded-2xl bg-neutral-50 px-4 py-3 dark:bg-neutral-800/70">
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">Promedio</p>
                                <p class="mt-1 text-lg font-black text-sky-600 dark:text-sky-400">
                                    {{ $cuatrimestre['promedio'] }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-neutral-50 px-4 py-3 dark:bg-neutral-800/70">
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">Aprobadas</p>
                                <p class="mt-1 text-lg font-black text-emerald-600 dark:text-emerald-400">
                                    {{ $cuatrimestre['aprobadas'] }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-neutral-50 px-4 py-3 dark:bg-neutral-800/70">
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">Reprobadas</p>
                                <p class="mt-1 text-lg font-black text-rose-600 dark:text-rose-400">
                                    {{ $cuatrimestre['reprobadas'] }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-neutral-50 px-4 py-3 dark:bg-neutral-800/70">
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">Pendientes</p>
                                <p class="mt-1 text-lg font-black text-amber-600 dark:text-amber-400">
                                    {{ $cuatrimestre['pendientes'] }}
                                </p>
                            </div>

                            <div
                                class="rounded-2xl border border-sky-200/70 bg-gradient-to-br from-sky-50 to-blue-50 px-4 py-3 dark:border-sky-500/20 dark:from-sky-500/10 dark:to-indigo-500/10">
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">Boleta</p>

                                <button type="button"
                                    @click.stop="abrirBoleta('{{ route('estudiante.pdf.mi-boleta', ['cuatrimestre' => $cuatrimestre['no_cuatrimestre']]) }}')"
                                    class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-3 py-2.5 text-sm font-semibold text-white shadow-[0_10px_30px_-12px_rgba(37,99,235,0.80)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_40px_-18px_rgba(37,99,235,0.85)] focus:outline-none focus:ring-4 focus:ring-sky-500/20">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-8.25A2.25 2.25 0 0 0 17.25 3.75H6.75A2.25 2.25 0 0 0 4.5 6v12a2.25 2.25 0 0 0 2.25 2.25h5.25m7.5-6-3-3m3 3-3 3m3-3H9" />
                                    </svg>
                                    Ver boleta
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0">
                        <button type="button" @click="abierto = !abierto"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-neutral-200 bg-white text-neutral-500 transition hover:bg-neutral-50 hover:text-neutral-700 focus:outline-none focus:ring-4 focus:ring-sky-500/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white">
                            <svg x-show="!abierto" x-cloak class="h-6 w-6" fill="none" stroke="currentColor"
                                stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                            </svg>

                            <svg x-show="abierto" x-cloak class="h-6 w-6" fill="none" stroke="currentColor"
                                stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 15 7-7 7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div x-show="abierto" x-cloak x-transition.opacity.duration.200ms
                    class="border-t border-neutral-200/70 dark:border-neutral-800">
                    @if (count($cuatrimestre['materias']) > 0)
                        <div class="hidden overflow-x-auto xl:block">
                            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
                                <thead class="bg-neutral-50/80 dark:bg-neutral-800/60">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-white dark:text-white">
                                            Materia
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-white dark:text-white">
                                            Profesor
                                        </th>
                                        <th
                                            class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-white dark:text-white">
                                            Créditos
                                        </th>
                                        <th
                                            class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-white dark:text-white">
                                            Calificación
                                        </th>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-white dark:text-white">
                                            Fecha captura
                                        </th>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-white dark:text-white">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                                    @foreach ($cuatrimestre['materias'] as $fila)
                                        <tr class="transition hover:bg-neutral-50/80 dark:hover:bg-white/[0.03]">
                                            <td class="px-6 py-5 align-top">
                                                <div class="space-y-2">
                                                    <div>
                                                        <p class="text-sm font-bold text-neutral-900 dark:text-white">
                                                            {{ $fila['materia'] }}
                                                        </p>
                                                        <p
                                                            class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                                            {{ $fila['clave'] }}
                                                        </p>
                                                    </div>

                                                    <div class="max-w-[220px]">
                                                        <div
                                                            class="h-2 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-800">
                                                            <div class="h-full rounded-full bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-600"
                                                                style="width: {{ $fila['avance'] }}%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-5 text-sm text-neutral-700 dark:text-neutral-300">
                                                {{ $fila['profesor'] }}
                                            </td>

                                            <td
                                                class="px-4 py-5 text-center text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                                {{ $fila['creditos'] }}
                                            </td>

                                            <td class="px-4 py-5 text-center">
                                                <span @class([
                                                    'inline-flex min-w-[78px] items-center justify-center rounded-2xl px-3 py-1.5 text-sm font-bold',
                                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' =>
                                                        $fila['estado'] === 'Aprobada',
                                                    'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' =>
                                                        $fila['estado'] === 'Reprobada',
                                                    'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300' =>
                                                        $fila['estado'] === 'Pendiente',
                                                ])>
                                                    {{ $fila['calificacion_texto'] }}
                                                </span>
                                            </td>

                                            <td
                                                class="px-6 py-5 text-center text-sm text-neutral-700 dark:text-neutral-300">
                                                {{ \Carbon\Carbon::parse($fila['fecha_captura'])->format('d/m/Y') }}
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="grid gap-4 p-5 xl:hidden">
                            @foreach ($cuatrimestre['materias'] as $fila)
                                <article
                                    class="rounded-3xl border border-neutral-200/70 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                                    <div class="flex flex-col gap-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <h4 class="text-base font-bold text-neutral-900 dark:text-white">
                                                    {{ $fila['materia'] }}
                                                </h4>
                                                <p
                                                    class="mt-1 text-xs font-medium text-neutral-500 dark:text-neutral-400">
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
                                            <p
                                                class="text-xs font-medium uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                                                Profesor
                                            </p>
                                            <p
                                                class="mt-1 text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                                {{ $fila['profesor'] }}
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div
                                                class="rounded-2xl border border-neutral-200 p-3 dark:border-neutral-700">
                                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    Calificación
                                                </p>
                                                <p class="mt-1 text-lg font-black text-sky-600 dark:text-sky-400">
                                                    {{ $fila['calificacion_texto'] }}
                                                </p>
                                            </div>

                                            <div
                                                class="rounded-2xl border border-neutral-200 p-3 dark:border-neutral-700">
                                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    Créditos
                                                </p>
                                                <p class="mt-1 text-lg font-bold text-neutral-900 dark:text-white">
                                                    {{ $fila['creditos'] }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <div
                                                class="flex items-center justify-between text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                                <span>Avance visual</span>
                                                <span>
                                                    {{ $fila['calificacion_texto'] === '—' ? 'Sin captura' : $fila['calificacion_texto'] . ' / 10' }}
                                                </span>
                                            </div>

                                            <div
                                                class="h-2.5 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-800">
                                                <div class="h-full rounded-full bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-600"
                                                    style="width: {{ $fila['avance'] }}%">
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="flex items-center justify-between rounded-2xl bg-neutral-50 px-4 py-3 dark:bg-neutral-800/70">
                                            <span class="text-sm text-neutral-500 dark:text-neutral-400">
                                                Fecha de captura
                                            </span>
                                            <span class="text-sm font-bold text-neutral-900 dark:text-white">
                                                {{ $fila['fecha_captura'] }}
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="mx-auto max-w-lg space-y-3">
                                <div
                                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6M8 4h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" />
                                    </svg>
                                </div>

                                <h3 class="text-base font-bold text-neutral-900 dark:text-white">
                                    Este cuatrimestre aún no tiene materias o calificaciones capturadas
                                </h3>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div
                class="rounded-3xl border border-dashed border-neutral-300 bg-white px-6 py-14 text-center dark:border-neutral-700 dark:bg-neutral-900">
                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6M8 4h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-bold text-neutral-900 dark:text-white">
                    Aún no hay historial académico disponible
                </h3>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                    Cuando existan inscripciones, materias y calificaciones registradas, se mostrarán aquí agrupadas
                    por cuatrimestre.
                </p>
            </div>
        @endforelse
    </section>

    {{-- PAGINACIÓN --}}
    @if ($cuatrimestresPaginados->hasPages())
        <section
            class="rounded-3xl border border-neutral-200/70 bg-white p-4 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            {{ $cuatrimestresPaginados->links() }}
        </section>
    @endif
</div>
