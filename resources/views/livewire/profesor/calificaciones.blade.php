<div x-data="{ show: @entangle('mostrarModal') }" class="space-y-6">
    {{-- Encabezado --}}
    <div
        class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-neutral-900 dark:text-white">
                    Mis calificaciones
                </h2>
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    Captura y actualiza las calificaciones de tus materias asignadas.
                </p>
            </div>

            @if ($profesor)
                <div
                    class="inline-flex items-center gap-3 rounded-2xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm font-semibold text-indigo-700 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300">
                    <span
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-white">
                        {{ substr($profesor->nombre ?? 'P', 0, 1) }}
                    </span>

                    <div class="leading-tight">
                        <div>{{ $profesor->nombre ?? '' }} {{ $profesor->apellido_paterno ?? '' }}
                            {{ $profesor->apellido_materno ?? '' }}</div>
                        <div class="text-xs font-medium opacity-80">Profesor activo</div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Resumen --}}
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div
                class="rounded-2xl border border-neutral-200 bg-gradient-to-br from-white to-neutral-50 p-4 shadow-sm dark:border-neutral-700 dark:from-neutral-900 dark:to-neutral-800">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                    Registros
                </p>
                <p class="mt-3 text-3xl font-black text-neutral-900 dark:text-white">
                    {{ $total_registros }}
                </p>
            </div>

            <div
                class="rounded-2xl border border-neutral-200 bg-gradient-to-br from-white to-neutral-50 p-4 shadow-sm dark:border-neutral-700 dark:from-neutral-900 dark:to-neutral-800">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                    Promedio general
                </p>
                <p class="mt-3 text-3xl font-black text-neutral-900 dark:text-white">
                    {{ $promedio_general }}
                </p>
            </div>

            <div
                class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-4 shadow-sm dark:border-emerald-500/20 dark:from-emerald-500/10 dark:to-neutral-900">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600 dark:text-emerald-300">
                    Capturadas
                </p>
                <p class="mt-3 text-3xl font-black text-emerald-700 dark:text-emerald-300">
                    {{ $capturadas }}
                </p>
            </div>

            <div
                class="rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-4 shadow-sm dark:border-amber-500/20 dark:from-amber-500/10 dark:to-neutral-900">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-600 dark:text-amber-300">
                    Pendientes
                </p>
                <p class="mt-3 text-3xl font-black text-amber-700 dark:text-amber-300">
                    {{ $pendientes }}
                </p>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="xl:col-span-2">
                <label for="buscar" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Buscar
                </label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-neutral-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                        </svg>
                    </span>

                    <input id="buscar" type="text" wire:model.live.debounce.400ms="buscar"
                        placeholder="Alumno, matrícula o materia..."
                        class="w-full rounded-2xl border border-neutral-300 bg-white py-3 pl-11 pr-4 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:focus:ring-indigo-500/20">
                </div>
            </div>

            <div>
                <label for="licenciatura_id"
                    class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Licenciatura
                </label>

                <select id="licenciatura_id" wire:model.live="licenciatura_id" wire:loading.attr="disabled"
                    wire:target="buscar,licenciatura_id,cuatrimestre_id,materia_id,generacion_id"
                    class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:focus:ring-indigo-500/20">
                    <option value="">Todas</option>

                    @foreach ($licenciaturas as $licenciaturaItem)
                        <option value="{{ $licenciaturaItem->id }}">{{ $licenciaturaItem->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="cuatrimestre_id"
                    class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Cuatrimestre
                </label>

                <select id="cuatrimestre_id" wire:model.live="cuatrimestre_id" wire:loading.attr="disabled"
                    wire:target="buscar,licenciatura_id,cuatrimestre_id,materia_id,generacion_id"
                    class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:focus:ring-indigo-500/20">
                    <option value="">Todos</option>

                    @foreach ($cuatrimestres as $cuatrimestreItem)
                        <option value="{{ $cuatrimestreItem->id }}">{{ $cuatrimestreItem->nombre_cuatrimestre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="generacion_id"
                    class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Generación
                </label>

                <select id="generacion_id" wire:model.live="generacion_id" wire:loading.attr="disabled"
                    wire:target="buscar,licenciatura_id,cuatrimestre_id,materia_id,generacion_id"
                    class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:focus:ring-indigo-500/20">
                    <option value="">Todas</option>

                    @foreach ($generaciones as $generacionItem)
                        <option value="{{ $generacionItem->id }}">{{ $generacionItem->generacion }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="materia_id" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Materia
                </label>

                <select id="materia_id" wire:model.live="materia_id" wire:loading.attr="disabled"
                    wire:target="buscar,licenciatura_id,cuatrimestre_id,materia_id,generacion_id"
                    class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:focus:ring-indigo-500/20">
                    <option value="">Todas las materias</option>

                    @foreach ($materias as $materiaItem)
                        <option value="{{ $materiaItem->id }}">{{ $materiaItem->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div
        class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
        {{-- Loader --}}
        <div wire:loading.flex wire:target="buscar,licenciatura_id,cuatrimestre_id,materia_id,generacion_id"
            class="absolute inset-0 z-30 hidden items-center justify-center bg-white/80 backdrop-blur-sm dark:bg-neutral-900/80">
            <div
                class="flex flex-col items-center gap-4 rounded-2xl border border-indigo-100 bg-white px-8 py-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">
                <div class="relative flex h-16 w-16 items-center justify-center">
                    <div class="absolute h-16 w-16 rounded-full border-4 border-indigo-100 dark:border-neutral-700">
                    </div>
                    <div
                        class="absolute h-16 w-16 animate-spin rounded-full border-4 border-transparent border-t-indigo-600 border-r-cyan-500">
                    </div>
                    <div class="h-6 w-6 animate-pulse rounded-full bg-indigo-600"></div>
                </div>

                <div class="text-center">
                    <p class="text-sm font-semibold text-neutral-800 dark:text-white">
                        Cargando registros
                    </p>
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                        Actualizando alumnos y materias...
                    </p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr>
                        <th
                            class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            Alumno
                        </th>
                        <th
                            class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            Materia
                        </th>
                        <th
                            class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            Grupo académico
                        </th>
                        <th
                            class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-center text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            Calificación
                        </th>
                        <th
                            class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            Fecha
                        </th>
                        <th
                            class="border-b border-neutral-200 bg-neutral-100 px-4 py-4 text-center text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            Acción
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($registros as $item)
                        <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/40">
                            <td
                                class="border-b border-r border-neutral-200 px-4 py-4 align-top dark:border-neutral-700">
                                <div class="font-semibold text-neutral-900 dark:text-white">
                                    {{ $item->alumno_nombre }}
                                    {{ $item->alumno_apellido_paterno }}
                                    {{ $item->alumno_apellido_materno }}
                                </div>

                                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $item->matricula ?: 'Sin matrícula' }}
                                </div>
                            </td>

                            <td
                                class="border-b border-r border-neutral-200 px-4 py-4 align-top dark:border-neutral-700">
                                <div class="font-medium text-neutral-800 dark:text-neutral-200">
                                    {{ $item->materia }}
                                </div>
                            </td>

                            <td
                                class="border-b border-r border-neutral-200 px-4 py-4 align-top dark:border-neutral-700">
                                <div class="text-sm text-neutral-800 dark:text-neutral-200">
                                    {{ $item->licenciatura }}
                                </div>
                                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $item->cuatrimestre }} · {{ $item->generacion }}
                                </div>
                            </td>

                            <td
                                class="border-b border-r border-neutral-200 px-4 py-4 text-center dark:border-neutral-700">
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-sm font-bold ring-1 {{ $this->aplicarColorCalificacion($item->calificacion) }}">
                                    {{ $item->calificacion !== null ? number_format((float) $item->calificacion, 2) : 'Pendiente' }}
                                </span>
                            </td>

                            <td
                                class="border-b border-r border-neutral-200 px-4 py-4 text-sm text-neutral-700 dark:border-neutral-700 dark:text-neutral-300">
                                {{ $item->fecha_captura ?: 'Sin fecha' }}
                            </td>

                            <td class="border-b border-neutral-200 px-4 py-4 text-center dark:border-neutral-700">
                                <button type="button"
                                    wire:click="abrirModal(
                                        {{ $item->calificacion_id ? $item->calificacion_id : 'null' }},
                                        {{ $item->inscripcion_id }},
                                        {{ $item->asignacion_materia_id }},
                                        '{{ addslashes($item->alumno_nombre . ' ' . $item->alumno_apellido_paterno . ' ' . $item->alumno_apellido_materno) }}',
                                        '{{ addslashes($item->matricula ?: '') }}',
                                        '{{ addslashes($item->materia) }}',
                                        '{{ addslashes($item->licenciatura) }}',
                                        '{{ addslashes($item->cuatrimestre) }}',
                                        '{{ addslashes($item->generacion) }}',
                                        {{ $item->calificacion !== null ? $item->calificacion : 'null' }},
                                        '{{ $item->fecha_captura ?: '' }}'
                                    )"
                                    class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:scale-[1.02] {{ $item->calificacion === null ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-indigo-600 hover:bg-indigo-700' }}">
                                    {{ $this->textoBoton($item->calificacion) }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div
                                    class="mx-auto max-w-md rounded-2xl border border-dashed border-neutral-300 bg-neutral-50 p-8 dark:border-neutral-700 dark:bg-neutral-800/50">
                                    <div class="text-base font-semibold text-neutral-800 dark:text-white">
                                        No se encontraron registros
                                    </div>
                                    <div class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                        Ajusta los filtros o revisa si el profesor tiene materias asignadas.
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($registros, 'links'))
            <div class="border-t border-neutral-200 px-4 py-4 dark:border-neutral-700">
                {{ $registros->links() }}
            </div>
        @endif
    </div>

    {{-- Modal editar / capturar --}}
    <div x-cloak x-show="show" x-trap.noscroll="show" @keydown.escape.window="show = false; $wire.cerrarModal()"
        class="fixed inset-0 z-50">
        {{-- Overlay --}}
        <div x-show="show" x-transition.opacity.duration.200ms
            class="absolute inset-0 bg-neutral-900/70 backdrop-blur-sm" @click="show = false; $wire.cerrarModal()">
        </div>

        {{-- Panel --}}
        <div class="relative flex min-h-screen items-center justify-center p-4 sm:p-6">
            <div x-show="show" x-transition:enter="transform ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95 blur-sm"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100 blur-0"
                x-transition:leave="transform ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100 blur-0"
                x-transition:leave-end="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95 blur-sm"
                class="relative w-full max-w-3xl overflow-hidden rounded-2xl border border-white/10 bg-white shadow-2xl ring-1 ring-black/5 dark:bg-neutral-900"
                role="dialog" aria-modal="true">
                {{-- Barra superior --}}
                <div class="bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-6 py-4 text-white">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold">
                                {{ $calificacion_id ? 'Editar calificación' : 'Capturar calificación' }}
                            </h3>
                            <p class="mt-1 text-sm text-white/90">
                                Registra la calificación del alumno seleccionado.
                            </p>
                        </div>

                        <button type="button" @click="show = false; $wire.cerrarModal()"
                            class="rounded-xl bg-white/10 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/20">
                            ✕
                        </button>
                    </div>
                </div>

                <div class="max-h-[75vh] overflow-y-auto p-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div
                            class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                Alumno
                            </p>
                            <p class="mt-2 text-sm font-bold text-neutral-900 dark:text-white">
                                {{ $alumno }}
                            </p>
                            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                {{ $matricula }}
                            </p>
                        </div>

                        <div
                            class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                Materia
                            </p>
                            <p class="mt-2 text-sm font-bold text-neutral-900 dark:text-white">
                                {{ $materia }}
                            </p>
                            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                {{ $licenciatura }} · {{ $cuatrimestre }} · {{ $generacion }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="calificacion_modal"
                                class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Calificación
                            </label>

                            <input id="calificacion_modal" type="number" step="0.01" min="0"
                                max="10" wire:model.live="calificacion"
                                class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:focus:ring-indigo-500/20"
                                placeholder="Ejemplo: 9.50">

                            @error('calificacion')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fecha_captura_modal"
                                class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Fecha de captura
                            </label>

                            <input id="fecha_captura_modal" type="date" wire:model.live="fecha_captura"
                                class="w-full rounded-2xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:focus:ring-indigo-500/20">

                            @error('fecha_captura')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div
                        class="mt-6 rounded-2xl border border-dashed border-neutral-300 bg-neutral-50 p-4 text-sm text-neutral-600 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-neutral-300">
                        La calificación se guarda para la combinación exacta de alumno y materia asignada. Si ya existe,
                        se actualiza; si no existe, se crea.
                    </div>
                </div>

                <div
                    class="flex items-center justify-end gap-3 border-t border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    <button type="button" @click="show = false; $wire.cerrarModal()"
                        class="rounded-xl border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">
                        Cancelar
                    </button>

                    <button type="button" wire:click="guardarCalificacion" wire:loading.attr="disabled"
                        wire:target="guardarCalificacion"
                        class="rounded-xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-70">
                        <span wire:loading.remove wire:target="guardarCalificacion">
                            Guardar calificación
                        </span>

                        <span wire:loading wire:target="guardarCalificacion">
                            Guardando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerta simple --}}
    <script>
        window.addEventListener('notificacion', event => {
            const data = event.detail[0] ?? event.detail;
            alert(data.mensaje);
        });
    </script>
</div>
