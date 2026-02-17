<div class="w-full">
    {{-- Encabezado --}}
    <div class="sticky top-0 z-10">
        <div
            class="rounded-2xl border border-neutral-200/60 dark:border-neutral-700/60 bg-gradient-to-r from-[#E4F6FF] to-[#F2EFFF] dark:from-[#0b1220] dark:to-[#121a2a] shadow-lg p-5">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">Asignación de Calificaciones</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                Asigna calificaciones para la licenciatura, generación y cuatrimestre.
            </p>
        </div>
    </div>

    {{-- Filtros --}}
    <div
        class="mt-6 rounded-2xl border bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-700 shadow-sm p-5">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                    Filtrar por:
                </div>
            </div>

            <button type="button" wire:click="limpiarFiltros"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-indigo-600 text-white px-4 py-2 text-sm font-semibold shadow hover:opacity-95">
                Limpiar filtros
            </button>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Licenciatura --}}
            <div>
                <label class="text-xs font-medium text-neutral-600 dark:text-neutral-300">Licenciatura</label>
                <select wire:model.live="licenciatura_id"
                    class="mt-1 w-full rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-sky-300">
                    <option value="">-- Selecciona una licenciatura --</option>
                    @foreach ($licenciaturas as $l)
                        <option value="{{ $l->id }}">{{ $l->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Generación --}}
            <div>
                <label class="text-xs font-medium text-neutral-600 dark:text-neutral-300">Generación</label>
                <select wire:model.live="generacion_id" @disabled(!$licenciatura_id)
                    class="mt-1 w-full rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-sky-300 disabled:opacity-60">
                    <option value="">-- Selecciona una generación --</option>
                    @foreach ($generaciones as $g)
                        <option value="{{ $g->id }}">{{ $g->generacion }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Cuatrimestre --}}
            <div>
                <label class="text-xs font-medium text-neutral-600 dark:text-neutral-300">Cuatrimestre</label>
                <select wire:model.live="cuatrimestre_id" @disabled(!$generacion_id)
                    class="mt-1 w-full rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-sky-300 disabled:opacity-60">
                    <option value="">-- Selecciona un cuatrimestre --</option>
                    @foreach ($cuatrimestres as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre_cuatrimestre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tarjeta de captura --}}
            <div class="flex items-end">
                <div
                    class="w-full rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 px-4 py-3">
                    <div class="text-xs text-neutral-600 dark:text-neutral-300">Captura</div>
                    <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                        {{ $this->porcentajeCaptura }}%
                    </div>
                </div>
            </div>
        </div>

        {{-- Info rápida --}}
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div
                class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-4 py-3">
                <div class="text-xs text-neutral-500 dark:text-neutral-400">Materias</div>
                <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">{{ count($materias) }}</div>
            </div>
            <div
                class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-4 py-3">
                <div class="text-xs text-neutral-500 dark:text-neutral-400">Alumnos</div>
                <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">{{ count($inscripciones) }}
                </div>
            </div>
            <div
                class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-4 py-3">
                <div class="text-xs text-neutral-500 dark:text-neutral-400">Celdas</div>
                <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">{{ $this->totalCeldas }}</div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div
        class="mt-6 rounded-2xl border bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-neutral-50 dark:bg-neutral-950/60 text-white">
                    <tr class="text-neutral-600 dark:text-neutral-300">
                        <th class="px-4 py-3 text-left font-semibold w-12">#</th>
                        <th class="px-4 py-3 text-left font-semibold">MATRÍCULA</th>
                        <th class="px-4 py-3 text-left font-semibold">ALUMNO</th>

                        @foreach ($materias as $m)
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">
                                {{ mb_strtoupper($m['materia']) }}
                                <div class="text-[11px] font-normal text-neutral-400 dark:text-neutral-500">
                                    {{ $m['profesor'] }}
                                </div>
                            </th>
                        @endforeach

                        <th class="px-4 py-3 text-left font-semibold">PROMEDIO</th>
                        <th class="px-4 py-3 text-right font-semibold w-44">ACCIONES</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                    @forelse ($inscripciones as $index => $fila)
                        @php($insId = (int) $fila['inscripcion_id'])

                        <tr class="hover:bg-neutral-50/70 dark:hover:bg-neutral-950/40">
                            <td class="px-4 py-3 text-neutral-700 dark:text-neutral-200">{{ $index + 1 }}</td>

                            <td class="px-4 py-3 font-medium text-neutral-900 dark:text-neutral-100">
                                {{ $fila['matricula'] }}
                            </td>

                            <td class="px-4 py-3 text-neutral-700 dark:text-neutral-200">
                                {{ $fila['alumno'] }}
                            </td>

                            @foreach ($materias as $m)
                                @php($asigId = (int) $m['id'])
                                <td class="px-4 py-3">
                                    <input type="number" min="0" max="10" step="0.1"
                                        wire:model.lazy="calificaciones.{{ $insId }}.{{ $asigId }}"
                                        wire:change="marcarCambio"
                                        class="w-24 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-3 py-1.5 text-center text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-sky-300"
                                        placeholder="0.0" />
                                </td>
                            @endforeach

                            <td class="px-4 py-3 font-semibold text-neutral-900 dark:text-neutral-100">
                                {{ $this->promedioFila($insId) }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 text-white px-3 py-2 text-xs font-semibold shadow hover:opacity-95">
                                        Boleta
                                    </button>

                                    <button type="button"
                                        class="inline-flex items-center justify-center rounded-xl bg-emerald-600 text-white px-3 py-2 text-xs font-semibold shadow hover:opacity-95">
                                        Enviar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 5 + count($materias) }}" class="px-6 py-10">
                                <div
                                    class="rounded-2xl border border-dashed border-neutral-200 dark:border-neutral-800 p-6 text-center">
                                    <div class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">
                                        No hay datos para mostrar
                                    </div>
                                    <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                        Selecciona licenciatura, generación y cuatrimestre para cargar alumnos y
                                        materias.
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Barra de progreso + acciones --}}
        <div class="border-t border-neutral-200 dark:border-neutral-800 p-5">
            @error('calificaciones')
                <div
                    class="mb-3 rounded-2xl border border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-950/30 px-4 py-3 text-sm text-red-700 dark:text-red-200">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="w-full md:w-2/3">
                    <div class="flex items-center justify-between text-xs text-neutral-600 dark:text-neutral-300">
                        <span>
                            Calificaciones introducidas: {{ $this->celdasCapturadas }} de {{ $this->totalCeldas }}
                            ({{ $this->porcentajeCaptura }}%)
                        </span>
                    </div>

                    <div class="mt-2 h-3 w-full rounded-full bg-neutral-100 dark:bg-neutral-950 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-indigo-500"
                            style="width: {{ $this->porcentajeCaptura }}%">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" wire:click="guardarCalificaciones" @disabled(!$hayCambios)
                        class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-sky-400 to-indigo-500 text-white px-6 py-3 text-sm font-semibold shadow disabled:opacity-60">
                        Guardar calificaciones
                    </button>

                    <span class="text-xs text-neutral-500 dark:text-neutral-400">
                        {{ $hayCambios ? 'Hay cambios por guardar' : 'No hay cambios por guardar' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Cargando --}}
    <div wire:loading.flex class="fixed inset-0 z-50 items-center justify-center bg-black/20 backdrop-blur-sm">
        <div
            class="rounded-2xl bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 shadow-lg px-5 py-4 flex items-center gap-3">
            <div
                class="h-5 w-5 rounded-full border-2 border-neutral-300 dark:border-neutral-700 border-t-neutral-900 dark:border-t-white animate-spin">
            </div>
            <div class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">Cargando…</div>
        </div>
    </div>
</div>
