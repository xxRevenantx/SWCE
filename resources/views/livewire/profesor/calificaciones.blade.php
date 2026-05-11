<div class="space-y-6">
    {{-- Encabezado principal --}}
    <section
        class="overflow-hidden rounded-3xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
        <div class="h-1.5 w-full bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600"></div>

        <div class="grid gap-6 p-5 lg:grid-cols-[1fr_auto] lg:items-center lg:p-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-400">
                    Panel docente
                </p>
                <h1 class="mt-2 text-2xl font-black tracking-tight text-neutral-950 dark:text-white sm:text-3xl">
                    Captura de calificaciones
                </h1>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-neutral-600 dark:text-neutral-400">
                    Consulta tus grupos asignados, registra calificaciones de forma individual o actualízalas mediante
                    una plantilla de Excel exportada desde este mismo módulo.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:min-w-[520px]">
                <div
                    class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-800 dark:bg-neutral-950/60">
                    <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400">Registros</p>
                    <p class="mt-2 text-2xl font-black text-neutral-950 dark:text-white">{{ $total_registros }}</p>
                </div>

                <div
                    class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-500/20 dark:bg-emerald-500/10">
                    <p class="text-xs font-medium text-emerald-700 dark:text-emerald-300">Capturadas</p>
                    <p class="mt-2 text-2xl font-black text-emerald-700 dark:text-emerald-300">{{ $capturadas }}</p>
                </div>

                <div
                    class="rounded-2xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/20 dark:bg-amber-500/10">
                    <p class="text-xs font-medium text-amber-700 dark:text-amber-300">Pendientes</p>
                    <p class="mt-2 text-2xl font-black text-amber-700 dark:text-amber-300">{{ $pendientes }}</p>
                </div>

                <div
                    class="rounded-2xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-500/20 dark:bg-blue-500/10">
                    <p class="text-xs font-medium text-blue-700 dark:text-blue-300">Promedio</p>
                    <p class="mt-2 text-2xl font-black text-blue-700 dark:text-blue-300">{{ $promedio_general }}</p>
                </div>
            </div>
        </div>

        <div class="border-t border-neutral-200 px-5 pb-5 pt-4 dark:border-neutral-800 lg:px-6">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <div class="h-3 overflow-hidden rounded-full bg-neutral-100 dark:bg-neutral-800">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 transition-all duration-500"
                            style="width: {{ $porcentaje_captura }}%"></div>
                    </div>
                </div>
                <span
                    class="shrink-0 rounded-full bg-neutral-100 px-3 py-1 text-xs font-bold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    {{ $porcentaje_captura }}% capturado
                </span>
            </div>
        </div>
    </section>

    {{-- Importar y exportar --}}
    <section class="grid gap-5 xl:grid-cols-[1.1fr_0.9fr]">
        <div
            class="rounded-3xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900 lg:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-lg font-black text-neutral-950 dark:text-white">
                        Importar calificaciones
                    </h2>
                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                        Actualiza varias calificaciones desde Excel sin capturarlas una por una.
                    </p>
                </div>

                <button type="button" wire:click="exportarCalificaciones" wire:loading.attr="disabled"
                    wire:target="exportarCalificaciones"
                    class="inline-flex items-center justify-center rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-bold text-blue-700 transition hover:bg-blue-100 disabled:cursor-not-allowed disabled:opacity-60 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20">
                    <span wire:loading.remove wire:target="exportarCalificaciones">Exportar plantilla Excel</span>
                    <span wire:loading wire:target="exportarCalificaciones">Exportando...</span>
                </button>
            </div>

            <div
                class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-700/40 dark:bg-amber-900/20 dark:text-amber-200">
                <p class="font-semibold">Indicaciones para importar calificaciones</p>

                <p class="mt-1">
                    Para evitar errores en la importación, no se deben modificar las columnas
                    <strong>inscripcion_id</strong> ni <strong>asignacion_materia_id</strong>,
                    ya que estos campos identifican al alumno y la materia asignada.
                    El docente únicamente debe capturar o actualizar la columna
                    <strong>calificacion</strong>.
                </p>

                <p class="mt-1">
                    Si se modifican columnas de identificación, el sistema podrá rechazar el archivo
                    o ignorar esos cambios para proteger la integridad de la información.
                </p>
            </div>

            <div class="mt-5 grid gap-3 lg:grid-cols-[1fr_auto] lg:items-start">
                <div>
                    <label for="archivoCalificaciones"
                        class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                        Archivo de calificaciones
                    </label>
                    <input id="archivoCalificaciones" type="file" wire:model="archivoCalificaciones"
                        accept=".xlsx,.xls,.csv"
                        class="w-full rounded-2xl border border-neutral-300 bg-white px-3 py-2.5 text-sm text-neutral-700 shadow-sm file:mr-3 file:rounded-xl file:border-0 file:bg-neutral-100 file:px-3 file:py-2 file:text-sm file:font-bold file:text-neutral-700 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-200 dark:file:bg-neutral-800 dark:file:text-neutral-200 dark:focus:ring-blue-500/20" />

                    @error('archivoCalificaciones')
                        <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="button" wire:click="importarCalificaciones" wire:loading.attr="disabled"
                    wire:target="archivoCalificaciones,importarCalificaciones"
                    class="inline-flex h-[46px] items-center justify-center rounded-2xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-5 text-sm font-bold text-white shadow-lg transition hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-60 lg:mt-7">
                    <span wire:loading.remove wire:target="archivoCalificaciones,importarCalificaciones">Importar
                        archivo</span>
                    <span wire:loading wire:target="archivoCalificaciones,importarCalificaciones">Procesando...</span>
                </button>
            </div>
            @if (!empty($erroresImportacion))
                <div
                    class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-700/40 dark:bg-red-900/20 dark:text-red-200">
                    <p class="font-semibold">Observaciones encontradas durante la importación:</p>

                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($erroresImportacion as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div
            class="rounded-3xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900 lg:p-6">
            <h2 class="text-lg font-black text-neutral-950 dark:text-white">
                Recomendaciones de captura
            </h2>
            <div class="mt-4 space-y-3 text-sm leading-6 text-neutral-600 dark:text-neutral-400">
                <div
                    class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-800 dark:bg-neutral-950/60">
                    Exporta primero el listado filtrado para evitar capturar alumnos o materias que no corresponden a tu
                    asignación.
                </div>
                <div
                    class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-800 dark:bg-neutral-950/60">
                    Usa el buscador y los filtros para trabajar por licenciatura, cuatrimestre, materia o generación.
                </div>
                <div
                    class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-800 dark:bg-neutral-950/60">
                    Después de importar, revisa las observaciones para identificar filas omitidas o datos fuera del
                    rango permitido.
                </div>
            </div>
        </div>
    </section>

    {{-- Filtros --}}
    <section
        class="rounded-3xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900 lg:p-6">
        <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-black text-neutral-950 dark:text-white">Filtros de consulta</h2>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Filtra la información antes de capturar, importar o exportar calificaciones.
                </p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="xl:col-span-1">
                <label for="buscar"
                    class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">Buscar</label>
                <input id="buscar" type="search" wire:model.live.debounce.400ms="buscar"
                    class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition placeholder:text-neutral-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:ring-blue-500/20"
                    placeholder="Alumno, matrícula o materia">
            </div>

            <div>
                <label for="licenciatura_id"
                    class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">Licenciatura</label>
                <select id="licenciatura_id" wire:model.live="licenciatura_id"
                    class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:ring-blue-500/20">
                    <option value="">Todas</option>
                    @foreach ($licenciaturas as $licenciaturaItem)
                        <option value="{{ $licenciaturaItem['id'] }}">{{ $licenciaturaItem['nombre'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="cuatrimestre_id"
                    class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">Cuatrimestre</label>
                <select id="cuatrimestre_id" wire:model.live="cuatrimestre_id"
                    class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:ring-blue-500/20">
                    <option value="">Todos</option>
                    @foreach ($cuatrimestres as $cuatrimestreItem)
                        <option value="{{ $cuatrimestreItem['id'] }}">{{ $cuatrimestreItem['nombre_cuatrimestre'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="materia_id"
                    class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">Materia</label>
                <select id="materia_id" wire:model.live="materia_id"
                    class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:ring-blue-500/20">
                    <option value="">Todas</option>
                    @foreach ($materias as $materiaItem)
                        <option value="{{ $materiaItem['id'] }}">{{ $materiaItem['nombre'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="generacion_id"
                    class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">Generación</label>
                <select id="generacion_id" wire:model.live="generacion_id"
                    class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:ring-blue-500/20">
                    <option value="">Todas</option>
                    @foreach ($generaciones as $generacionItem)
                        <option value="{{ $generacionItem['id'] }}">{{ $generacionItem['generacion'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    {{-- Tabla de calificaciones --}}
    <section
        class="relative overflow-hidden rounded-3xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
        <div wire:loading.flex
            wire:target="buscar,licenciatura_id,cuatrimestre_id,materia_id,generacion_id,importarCalificaciones"
            class="absolute inset-0 z-30 hidden items-center justify-center bg-white/80 backdrop-blur-sm dark:bg-neutral-900/80">
            <div
                class="flex items-center gap-3 rounded-2xl border border-neutral-200 bg-white px-5 py-4 shadow-xl dark:border-neutral-800 dark:bg-neutral-900">
                <svg class="h-5 w-5 animate-spin text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="none"
                    aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">Actualizando
                    información...</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr>
                        <th
                            class="border-b border-neutral-200 bg-neutral-50 px-4 py-4 text-left text-xs font-black uppercase tracking-wider text-neutral-600 dark:border-neutral-800 dark:bg-neutral-950/60 dark:text-neutral-300">
                            Alumno</th>
                        <th
                            class="border-b border-neutral-200 bg-neutral-50 px-4 py-4 text-left text-xs font-black uppercase tracking-wider text-neutral-600 dark:border-neutral-800 dark:bg-neutral-950/60 dark:text-neutral-300">
                            Materia</th>
                        <th
                            class="border-b border-neutral-200 bg-neutral-50 px-4 py-4 text-left text-xs font-black uppercase tracking-wider text-neutral-600 dark:border-neutral-800 dark:bg-neutral-950/60 dark:text-neutral-300">
                            Grupo académico</th>
                        <th
                            class="border-b border-neutral-200 bg-neutral-50 px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-neutral-600 dark:border-neutral-800 dark:bg-neutral-950/60 dark:text-neutral-300">
                            Calificación</th>
                        <th
                            class="border-b border-neutral-200 bg-neutral-50 px-4 py-4 text-left text-xs font-black uppercase tracking-wider text-neutral-600 dark:border-neutral-800 dark:bg-neutral-950/60 dark:text-neutral-300">
                            Fecha</th>
                        <th
                            class="border-b border-neutral-200 bg-neutral-50 px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-neutral-600 dark:border-neutral-800 dark:bg-neutral-950/60 dark:text-neutral-300">
                            Acción</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($registros as $item)
                        <tr class="group transition hover:bg-blue-50/50 dark:hover:bg-blue-500/5">
                            <td class="border-b border-neutral-200 px-4 py-4 align-top dark:border-neutral-800">
                                <div class="font-bold text-neutral-950 dark:text-white">
                                    {{ $item->alumno_nombre }} {{ $item->alumno_apellido_paterno }}
                                    {{ $item->alumno_apellido_materno }}
                                </div>
                                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    Matrícula: {{ $item->matricula ?: 'Sin matrícula' }}
                                </div>
                            </td>

                            <td class="border-b border-neutral-200 px-4 py-4 align-top dark:border-neutral-800">
                                <div class="font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ $item->materia }}
                                </div>
                            </td>

                            <td class="border-b border-neutral-200 px-4 py-4 align-top dark:border-neutral-800">
                                <div class="text-sm font-medium text-neutral-800 dark:text-neutral-200">
                                    {{ $item->licenciatura }}
                                </div>
                                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $item->cuatrimestre }} · {{ $item->generacion }}
                                </div>
                            </td>

                            <td
                                class="border-b border-neutral-200 px-4 py-4 text-center align-top dark:border-neutral-800">
                                <span
                                    class="inline-flex min-w-20 items-center justify-center rounded-full px-3 py-1 text-sm font-black ring-1 {{ $this->aplicarColorCalificacion($item->calificacion) }}">
                                    {{ $item->calificacion !== null ? number_format((float) $item->calificacion, 2) : 'Pendiente' }}
                                </span>
                            </td>

                            <td class="border-b border-neutral-200 px-4 py-4 align-top dark:border-neutral-800">
                                @if ($item->fecha_captura)
                                    <span class="text-sm text-neutral-700 dark:text-neutral-300">
                                        {{ \Carbon\Carbon::parse($item->fecha_captura)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-sm text-neutral-400 dark:text-neutral-500">Sin fecha</span>
                                @endif
                            </td>

                            <td
                                class="border-b border-neutral-200 px-4 py-4 text-center align-top dark:border-neutral-800">
                                <button type="button"
                                    @click="$dispatch('abrir-modal-calificacion');
                                            $wire.abrirModal(
                                                {{ $item->calificacion_id ? $item->calificacion_id : 'null' }},
                                                {{ $item->inscripcion_id }},
                                                {{ $item->asignacion_materia_id }},
                                                @js(trim($item->alumno_nombre . ' ' . $item->alumno_apellido_paterno . ' ' . $item->alumno_apellido_materno)),
                                                @js($item->matricula ?: ''),
                                                @js($item->materia),
                                                @js($item->licenciatura),
                                                @js($item->cuatrimestre),
                                                @js($item->generacion),
                                                {{ $item->calificacion !== null ? $item->calificacion : 'null' }},
                                                @js($item->fecha_captura ?: '')
                                            )"
                                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-200 dark:focus-visible:ring-blue-500/20">
                                    {{ $this->textoBoton($item->calificacion) }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div
                                    class="mx-auto max-w-md rounded-3xl border border-dashed border-neutral-300 bg-neutral-50 p-8 dark:border-neutral-700 dark:bg-neutral-800/50">
                                    <div class="text-base font-black text-neutral-800 dark:text-white">
                                        No se encontraron registros
                                    </div>
                                    <div class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                        Ajusta los filtros o revisa si tienes materias asignadas.
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($registros, 'links'))
            <div class="border-t border-neutral-200 px-4 py-4 dark:border-neutral-800">
                {{ $registros->links() }}
            </div>
        @endif
    </section>

    {{-- Modal editar o capturar --}}
    <div x-data="{ show: false, loading: false }" x-cloak x-trap.noscroll="show" x-show="show"
        @abrir-modal-calificacion.window="show = true; loading = true" @calificacion-cargada.window="loading = false"
        @cerrar-modal-calificacion.window="show = false; loading = false; $wire.cerrarModal()"
        @keydown.escape.window="show = false; loading = false; $wire.cerrarModal()"
        class="fixed inset-0 z-50 flex items-center justify-center" aria-live="polite">
        <div class="absolute inset-0 bg-neutral-950/70 backdrop-blur-sm" x-show="show"
            x-transition.opacity.duration.200ms @click.self="show = false; loading = false; $wire.cerrarModal()"></div>

        <div class="relative mx-4 w-[92vw] max-w-3xl overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-black/5 dark:bg-neutral-900 dark:ring-white/10 sm:mx-6"
            role="dialog" aria-modal="true" aria-labelledby="titulo-modal-calificacion" x-show="show"
            x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95 blur-sm"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100 blur-0"
            x-transition:leave="transform ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100 blur-0"
            x-transition:leave-end="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95 blur-sm" wire:ignore.self>
            <div class="h-1.5 w-full bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600"></div>

            <div class="flex items-start justify-between gap-3 px-5 pb-3 pt-5 sm:px-6">
                <div class="min-w-0">
                    <h2 id="titulo-modal-calificacion"
                        class="text-xl font-black text-neutral-950 dark:text-white sm:text-2xl">
                        {{ $calificacion_id ? 'Editar calificación' : 'Capturar calificación' }}
                    </h2>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                        Registra el valor correspondiente al alumno seleccionado.
                    </p>
                </div>

                <button @click="show = false; loading = false; $wire.cerrarModal()" type="button"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full text-neutral-500 transition hover:bg-neutral-100 hover:text-neutral-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-200"
                    aria-label="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="max-h-[75vh] overflow-y-auto px-5 pb-5 sm:px-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div
                        class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-800 dark:bg-neutral-950/60">
                        <p
                            class="text-xs font-bold uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                            Alumno</p>
                        <p class="mt-2 text-sm font-black text-neutral-950 dark:text-white">{{ $alumno }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">{{ $matricula }}</p>
                    </div>

                    <div
                        class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-800 dark:bg-neutral-950/60">
                        <p
                            class="text-xs font-bold uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                            Materia</p>
                        <p class="mt-2 text-sm font-black text-neutral-950 dark:text-white">{{ $materia }}</p>
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">{{ $licenciatura }} ·
                            {{ $cuatrimestre }} · {{ $generacion }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="calificacion_modal"
                        class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                        Calificación
                    </label>

                    <input id="calificacion_modal" type="number" step="0.01" min="0" max="10"
                        wire:model.live="calificacion"
                        class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:ring-blue-500/20"
                        placeholder="Ejemplo: 9.50">

                    @error('calificacion')
                        <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div
                    class="mt-6 rounded-2xl border border-dashed border-neutral-300 bg-neutral-50 p-4 text-sm leading-6 text-neutral-600 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-neutral-300">
                    La calificación se guarda para la combinación exacta de alumno y materia asignada. Si ya existe,
                    se actualiza; si no existe, se crea un nuevo registro.
                </div>

                <div class="mt-6 flex flex-col justify-end gap-2 pt-1 sm:flex-row">
                    <button type="button"
                        class="w-full rounded-xl border border-neutral-300 px-4 py-2.5 text-sm font-bold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800 sm:w-auto"
                        @click="show = false; loading = false; $wire.cerrarModal()">
                        Cancelar
                    </button>

                    <button type="button" wire:click="guardarCalificacion" wire:loading.attr="disabled"
                        wire:target="guardarCalificacion"
                        class="w-full rounded-xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg transition hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-70 sm:w-auto">
                        <span wire:loading.remove wire:target="guardarCalificacion">Guardar calificación</span>
                        <span wire:loading wire:target="guardarCalificacion">Guardando...</span>
                    </button>
                </div>
            </div>

            <div x-show="loading"
                class="absolute inset-0 z-20 flex items-center justify-center rounded-3xl bg-white/70 backdrop-blur dark:bg-neutral-900/70">
                <div
                    class="flex items-center gap-3 rounded-xl bg-white px-4 py-3 shadow ring-1 ring-neutral-200 dark:bg-neutral-900 dark:ring-neutral-800">
                    <svg class="h-5 w-5 animate-spin text-blue-600 dark:text-blue-400" viewBox="0 0 24 24"
                        fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span class="text-sm text-neutral-800 dark:text-neutral-200">Cargando…</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Notificaciones --}}
    <script>
        window.addEventListener('notificacion', event => {
            const data = event.detail[0] ?? event.detail;

            Swal.fire({
                toast: true,
                position: data.position ?? 'top-end',
                icon: data.tipo ?? 'success',
                title: data.mensaje ?? 'Operación realizada correctamente.',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#171717' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#171717',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-neutral-200 dark:border-neutral-700'
                }
            });
        });
    </script>
</div>
