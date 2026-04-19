<div class="space-y-6">
    <section
        class="relative overflow-hidden rounded-[28px] border border-white/60 bg-white/80 shadow-xl backdrop-blur-xl dark:border-neutral-800/80 dark:bg-neutral-900/80">
        <div class="h-1.5 w-full bg-gradient-to-r from-sky-500 via-blue-600 to-fuchsia-500"></div>

        <div class="relative p-5 sm:p-6">
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.10),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(217,70,239,0.10),transparent_28%)]">
            </div>

            <div class="relative flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-white">
                        Materias del profesor
                    </h2>

                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        Filtra por licenciatura, cuatrimestre y generación para consultar las materias relacionadas.
                    </p>
                </div>
            </div>

            <div class="relative mt-6 grid grid-cols-1 gap-4 xl:grid-cols-5">
                <div>


                    <flux:select label="Licenciatura" id="licenciatura_id" wire:model.live="licenciatura_id">
                        <flux:select.option value="">Selecciona una licenciatura</flux:select.option>

                        @foreach ($licenciaturas as $licenciatura)
                            <flux:select.option value="{{ $licenciatura->id }}">
                                {{ $licenciatura->nombre }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div>


                    <flux:select label="Cuatrimestre" id="cuatrimestre_id" wire:model.live="cuatrimestre_id">
                        <flux:select.option value="">Selecciona un cuatrimestre</flux:select.option>

                        @foreach ($cuatrimestres as $cuatrimestre)
                            <flux:select.option value="{{ $cuatrimestre->id }}">
                                {{ $cuatrimestre->nombre_cuatrimestre }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div>


                    <flux:select label="Generación" id="generacion_id" wire:model.live="generacion_id">
                        <flux:select.option value="">Selecciona una generación</flux:select.option>

                        @foreach ($generaciones as $generacion)
                            <flux:select.option value="{{ $generacion->id }}">
                                {{ $generacion->generacion }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="xl:col-span-2">
                    <label for="search" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Buscar materia
                    </label>

                    <div class="relative">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-neutral-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>

                        <input id="search" type="text" wire:model.live.debounce.400ms="search"
                            placeholder="Buscar por clave o nombre..." @disabled(!$this->filtrosCompletos)
                            class="w-full rounded-2xl border border-neutral-300 bg-white py-2 pl-12 pr-4 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-200 disabled:cursor-not-allowed disabled:bg-neutral-100 disabled:text-neutral-400 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:disabled:bg-neutral-800/60 dark:disabled:text-neutral-500">
                    </div>
                </div>
            </div>

            <div class="relative mt-5 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" wire:click="limpiarFiltros"
                        class="inline-flex items-center justify-center rounded-2xl bg-neutral-900 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:scale-[1.01] hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                        Limpiar filtros
                    </button>
                </div>

                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    @if ($this->filtrosCompletos)
                        Mostrando materias únicas del grupo seleccionado.
                    @else
                        Selecciona los tres filtros para mostrar la tabla.
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section
        class="relative overflow-hidden rounded-[28px] border border-white/60 bg-white/80 shadow-xl backdrop-blur-xl dark:border-neutral-800/80 dark:bg-neutral-900/80">

        <div wire:loading.flex
            wire:target="licenciatura_id,cuatrimestre_id,generacion_id,search,limpiarFiltros,exportarExcelMateria"
            class="absolute inset-0 z-30 hidden items-center justify-center bg-white/75 backdrop-blur-sm dark:bg-neutral-900/75">
            <div
                class="flex flex-col items-center gap-4 rounded-2xl border border-blue-100 bg-white px-8 py-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">
                <div class="relative flex h-16 w-16 items-center justify-center">
                    <div class="absolute h-16 w-16 rounded-full border-4 border-blue-100 dark:border-neutral-700"></div>
                    <div
                        class="absolute h-16 w-16 animate-spin rounded-full border-4 border-transparent border-t-blue-600 border-r-cyan-500">
                    </div>
                    <div class="h-6 w-6 animate-pulse rounded-full bg-blue-600"></div>
                </div>

                <div class="text-center">
                    <p class="text-sm font-semibold text-neutral-800 dark:text-white">
                        Cargando materias
                    </p>
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                        Actualizando resultados...
                    </p>
                </div>
            </div>
        </div>

        @if (!$this->filtrosCompletos)
            <div class="p-6 sm:p-8">
                <div
                    class="rounded-[24px] border-2 border-dashed border-neutral-300 bg-neutral-50 px-6 py-16 text-center dark:border-neutral-700 dark:bg-neutral-800/40">
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">
                        Aún no se muestra la tabla
                    </h3>

                    <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                        Selecciona licenciatura, cuatrimestre y generación para ver las materias correspondientes.
                    </p>
                </div>
            </div>
        @else
            <div class="border-b border-neutral-200 px-5 py-4 dark:border-neutral-700 sm:px-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-800 dark:text-white">
                            Materias encontradas
                        </h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                            Total en esta página: {{ $materias->count() }} |
                            Total general: {{ $materias->total() }}
                        </p>
                    </div>

                    <div class="text-xs text-neutral-500 dark:text-neutral-400">
                        Página {{ $materias->currentPage() }} de {{ $materias->lastPage() }}
                    </div>
                </div>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full border-separate border-spacing-0">
                    <thead>
                        <tr>
                            <th
                                class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                #
                            </th>
                            <th
                                class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                Clave
                            </th>
                            <th
                                class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                Materia
                            </th>
                            <th
                                class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                Licenciatura
                            </th>
                            <th
                                class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                Cuatrimestre
                            </th>
                            <th
                                class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                Generación
                            </th>
                            <th
                                class="border-b border-neutral-200 bg-neutral-100 px-4 py-4 text-center text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                Exportar
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($materias as $index => $materia)
                            <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                                <td
                                    class="border-b border-r border-neutral-200 px-4 py-4 text-sm text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">
                                    {{ ($materias->currentPage() - 1) * $materias->perPage() + $index + 1 }}
                                </td>

                                <td
                                    class="border-b border-r border-neutral-200 px-4 py-4 text-sm text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">
                                    {{ $materia->clave ?? 'Sin clave' }}
                                </td>

                                <td
                                    class="border-b border-r border-neutral-200 px-4 py-4 text-sm font-semibold text-neutral-900 dark:border-neutral-700 dark:text-white">
                                    {{ $materia->materia }}
                                </td>

                                <td
                                    class="border-b border-r border-neutral-200 px-4 py-4 text-sm text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">
                                    {{ $materia->licenciatura ?? 'Sin licenciatura' }}
                                </td>

                                <td
                                    class="border-b border-r border-neutral-200 px-4 py-4 text-sm text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">
                                    {{ $materia->cuatrimestre ?? 'Sin cuatrimestre' }}
                                </td>

                                <td
                                    class="border-b border-r border-neutral-200 px-4 py-4 text-sm text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">
                                    {{ $materia->generacion ?? 'Sin generación' }}
                                </td>

                                <td class="border-b border-neutral-200 px-4 py-4 dark:border-neutral-700">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ $this->getPdfMateriaUrl($materia->asignacion_materia_id) }}"
                                            target="_blank"
                                            class="inline-flex items-center rounded-xl bg-gradient-to-r from-rose-500 to-red-600 px-3 py-2 text-xs font-semibold text-white shadow-md transition hover:scale-[1.02]">
                                            PDF
                                        </a>

                                        <button type="button"
                                            wire:click="exportarExcelMateria({{ $materia->asignacion_materia_id }})"
                                            class="inline-flex items-center rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 px-3 py-2 text-xs font-semibold text-white shadow-md transition hover:scale-[1.02]">
                                            Excel
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-4 py-10 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    No se encontraron materias con los filtros y búsqueda aplicada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="grid gap-4 p-4 sm:p-6 lg:hidden">
                @forelse ($materias as $index => $materia)
                    <article
                        class="rounded-[24px] border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-sky-600 dark:text-sky-300">
                                    Materia {{ ($materias->currentPage() - 1) * $materias->perPage() + $index + 1 }}
                                </p>

                                <h3 class="mt-2 text-base font-bold text-neutral-900 dark:text-white">
                                    {{ $materia->materia }}
                                </h3>
                            </div>

                            <span
                                class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300">
                                {{ $materia->clave ?? 'Sin clave' }}
                            </span>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3 text-sm">
                            <div>
                                <p class="text-neutral-400">Licenciatura</p>
                                <p class="font-medium text-neutral-800 dark:text-neutral-200">
                                    {{ $materia->licenciatura ?? 'Sin licenciatura' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-neutral-400">Cuatrimestre</p>
                                <p class="font-medium text-neutral-800 dark:text-neutral-200">
                                    {{ $materia->cuatrimestre ?? 'Sin cuatrimestre' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-neutral-400">Generación</p>
                                <p class="font-medium text-neutral-800 dark:text-neutral-200">
                                    {{ $materia->generacion ?? 'Sin generación' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ $this->getPdfMateriaUrl($materia->asignacion_materia_id) }}" target="_blank"
                                class="inline-flex items-center rounded-xl bg-gradient-to-r from-rose-500 to-red-600 px-3 py-2 text-xs font-semibold text-white shadow-md">
                                PDF
                            </a>

                            <button type="button"
                                wire:click="exportarExcelMateria({{ $materia->asignacion_materia_id }})"
                                class="inline-flex items-center rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 px-3 py-2 text-xs font-semibold text-white shadow-md">
                                Excel
                            </button>
                        </div>
                    </article>
                @empty
                    <div
                        class="rounded-[24px] border-2 border-dashed border-neutral-300 bg-neutral-50 px-6 py-12 text-center dark:border-neutral-700 dark:bg-neutral-800/40">
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            No se encontraron materias con los filtros y búsqueda aplicada.
                        </p>
                    </div>
                @endforelse
            </div>

            @if ($materias->hasPages())
                <div class="border-t border-neutral-200 px-5 py-4 dark:border-neutral-700 sm:px-6">
                    {{ $materias->links() }}
                </div>
            @endif
        @endif
    </section>
</div>
