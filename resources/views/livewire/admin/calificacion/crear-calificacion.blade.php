    <div x-data="{
        enviarCalificacion(alumno, cuatrimestre, generacion) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `La calificación del alumno en el ${cuatrimestre}° cuatrimestre se enviará a su correo asignado.`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Sí, enviar'
                }).then((r) => { if (r.isConfirmed) { @this.call('enviarCalificacion', alumno, cuatrimestre, generacion); } });
            },

    }" class="w-full">
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

                <div class="mt-7">
                    <button type="button" wire:click="limpiarFiltros"
                        class="items-center  gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-indigo-600 text-white px-4 py-2 text-sm font-semibold shadow hover:opacity-95">
                        Limpiar filtros
                    </button>
                </div>



            </div>

            {{-- Info --}}
            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Materias --}}
                <div
                    class="group rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 shadow-sm hover:shadow-md transition overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600"></div>

                    <div class="p-4 flex items-center gap-4">
                        <div
                            class="h-12 w-12 rounded-2xl bg-sky-50 dark:bg-sky-900/20 border border-sky-100 dark:border-sky-900/40 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-sky-700 dark:text-sky-300"
                                viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M4 6a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v13a1 1 0 0 1-1.447.894L13 17.118l-3.553 1.776A1 1 0 0 1 8 18V6H6a2 2 0 0 1-2-2z" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                Materias cargadas
                            </div>
                            <div class="mt-0.5 text-2xl font-bold text-neutral-900 dark:text-neutral-100">
                                {{ count($materias) }}
                            </div>
                            <div class="mt-1 text-[11px] text-neutral-500 dark:text-neutral-400">
                                Se muestran según licenciatura y cuatrimestre
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alumnos --}}
                <div
                    class="group rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 shadow-sm hover:shadow-md transition overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500"></div>

                    <div class="p-4 flex items-center gap-4">
                        <div
                            class="h-12 w-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-900/40 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-emerald-700 dark:text-emerald-300" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M16 11c1.657 0 3-1.79 3-4s-1.343-4-3-4-3 1.79-3 4 1.343 4 3 4ZM8 11c1.657 0 3-1.79 3-4S9.657 3 8 3 5 4.79 5 7s1.343 4 3 4Zm0 2c-2.67 0-8 1.34-8 4v2h10v-2c0-1.27.49-2.4 1.28-3.24C10.18 13.3 8.92 13 8 13Zm8 0c-.92 0-2.18.3-3.28.76.79.84 1.28 1.97 1.28 3.24v2h10v-2c0-2.66-5.33-4-8-4Z" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                Alumnos cargados
                            </div>
                            <div class="mt-0.5 text-2xl font-bold text-neutral-900 dark:text-neutral-100">
                                {{ count($inscripciones) }}
                            </div>
                            <div class="mt-1 text-[11px] text-neutral-500 dark:text-neutral-400">
                                Filtrados por licenciatura, generación y cuatrimestre
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Celdas --}}
                <div
                    class="group rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 shadow-sm hover:shadow-md transition overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-fuchsia-500 via-purple-500 to-indigo-600"></div>

                    <div class="p-4 flex items-center gap-4">
                        <div
                            class="h-12 w-12 rounded-2xl bg-fuchsia-50 dark:bg-fuchsia-900/20 border border-fuchsia-100 dark:border-fuchsia-900/40 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-fuchsia-700 dark:text-fuchsia-300" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M3 3h18v2H3V3Zm2 4h14a2 2 0 0 1 2 2v12H3V9a2 2 0 0 1 2-2Zm2 3v2h3v-2H7Zm0 4v2h3v-2H7Zm5-4v2h5v-2h-5Zm0 4v2h5v-2h-5Z" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                Total de celdas
                            </div>
                            <div class="mt-0.5 text-2xl font-bold text-neutral-900 dark:text-neutral-100">
                                {{ $this->totalCeldas }}
                            </div>
                            <div class="mt-1 text-[11px] text-neutral-500 dark:text-neutral-400">
                                Alumnos × materias
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-end">
                    <div
                        class="w-full rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 shadow-sm overflow-hidden">
                        {{-- Barra superior --}}
                        <div class="h-1.5 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600"></div>

                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-xs font-medium text-neutral-600 dark:text-neutral-300">
                                        Captura
                                    </div>
                                    <div class="mt-1 text-2xl font-bold text-neutral-900 dark:text-neutral-100">
                                        {{ $this->porcentajeCaptura }}%
                                    </div>

                                    <div class="mt-1 text-[11px] text-neutral-500 dark:text-neutral-400">
                                        {{ $this->celdasCapturadas }} de {{ $this->totalCeldas }} celdas capturadas
                                    </div>
                                </div>

                                <div
                                    class="shrink-0 h-11 w-11 rounded-2xl bg-sky-50 dark:bg-sky-900/20 border border-sky-100 dark:border-sky-900/40 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-sky-700 dark:text-sky-300" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path
                                            d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 15h-2v-6h2Zm0-8h-2V7h2Z" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Barra de progreso --}}
                            <div class="mt-3">
                                <div class="h-3 w-full rounded-full bg-neutral-100 dark:bg-neutral-900 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-indigo-500"
                                        style="width: {{ $this->porcentajeCaptura }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="px-5 pt-5">
            <div
                class="rounded-2xl border shadow-sm px-4 py-3 flex items-center justify-between gap-4
                        {{ $hayCambios
                            ? 'border-amber-200 dark:border-amber-900 bg-amber-50 dark:bg-amber-950/30'
                            : 'border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950/40' }}">

                <div class="flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                        @if ($hayCambios)
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-600"></span>
                        @else
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-600"></span>
                        @endif
                    </span>

                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                            {{ $hayCambios ? 'Hay cambios por guardar' : 'No hay cambios por guardar' }}
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-300">
                            {{ $hayCambios ? 'Guarda para aplicar los cambios.' : 'Todo está al día.' }}
                        </div>
                    </div>
                </div>


            </div>
        </div>


        {{-- Tabla --}}
        <div
            class="mt-6 rounded-2xl border bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-700 shadow-sm overflow-hidden">

            {{-- Contenedor relativo para que el loading solo cubra la tabla --}}
            <div class="relative">

                {{-- Overlay de carga SOLO para acciones específicas --}}
                <div wire:loading.flex
                    wire:target="licenciatura_id,generacion_id,cuatrimestre_id,limpiarFiltros,guardarCalificaciones"
                    class="absolute inset-0 z-10 items-center justify-center bg-white/70 dark:bg-neutral-950/60 backdrop-blur-sm">

                    <div
                        class="rounded-2xl bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 shadow-lg px-5 py-4 flex items-center gap-3">
                        <div
                            class="h-5 w-5 rounded-full border-2 border-neutral-300 dark:border-neutral-700 border-t-neutral-900 dark:border-t-white animate-spin">
                        </div>
                        <div class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">Cargando…</div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-neutral-50 dark:bg-neutral-950/60 text-white">
                            <tr class="text-neutral-600 dark:text-neutral-300">
                                <th class="px-4 py-3 text-left font-semibold w-12 text-white">#</th>
                                <th class="px-4 py-3 text-left font-semibold text-white">MATRÍCULA</th>
                                <th class="px-4 py-3 text-left font-semibold text-white">ALUMNO</th>

                                @foreach ($materias as $m)
                                    <th class="px-4 py-3 text-left font-semibold whitespace-nowrap text-white">
                                        {{ mb_strtoupper($m['materia']) }}
                                        <div class="text-[11px] font-normal text-neutral-400 dark:text-neutral-500">
                                            {{ $m['profesor'] }}
                                        </div>
                                    </th>
                                @endforeach

                                <th class="px-4 py-3 text-left font-semibold text-white">PROMEDIO</th>
                                <th class="px-4 py-3 text-right font-semibold w-44 text-white">ACCIONES</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                            @forelse ($inscripciones as $index => $fila)
                                @php($insId = (int) $fila['inscripcion_id'])

                                <tr class="hover:bg-neutral-50/70 dark:hover:bg-neutral-950/40">
                                    <td class="px-4 py-3 text-neutral-700 dark:text-neutral-200">{{ $index + 1 }}
                                    </td>

                                    <td class="px-4 py-3 font-medium text-neutral-900 dark:text-neutral-100">
                                        {{ $fila['matricula'] }}
                                    </td>

                                    <td class="px-4 py-3 text-neutral-700 dark:text-neutral-200">
                                        {{ $fila['alumno'] }}
                                    </td>

                                    @foreach ($materias as $m)
                                        @php($asigId = (int) $m['id'])
                                        <td class="px-4 py-3 text-center">
                                            <input type="number" min="0" max="10" step="0.1"
                                                wire:model.lazy="calificaciones.{{ $insId }}.{{ $asigId }}"
                                                wire:change="marcarCambio"
                                                class="w-24 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-3 py-1.5 text-center text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-sky-300"
                                                placeholder="0.0" />
                                        </td>
                                    @endforeach

                                    <td
                                        class="px-4 py-3 font-semibold text-neutral-900 dark:text-neutral-100 text-center">
                                        {{ $this->promedioFila($insId) }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                class="inline-flex items-center justify-center rounded-xl bg-blue-600 text-white px-3 py-2 text-xs font-semibold shadow hover:opacity-95">
                                                Boleta
                                            </button>
                                            <x-button variant="primary"
                                                class="bg-green-600 hover:bg-green-700 text-white rounded-xl"
                                                @click="enviarCalificacion({{ $insId }}, {{ $this->cuatrimestre_id }}, {{ $this->generacion_id }})">
                                                <div class="flex items-center gap-2">
                                                    <flux:icon.send />
                                                    <span>Enviar</span>
                                                </div>
                                            </x-button>


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
                            <div
                                class="flex items-center justify-between text-xs text-neutral-600 dark:text-neutral-300">
                                <span>
                                    Calificaciones introducidas: {{ $this->celdasCapturadas }} de
                                    {{ $this->totalCeldas }}
                                    ({{ $this->porcentajeCaptura }}%)
                                </span>
                            </div>

                            <div
                                class="mt-2 h-3 w-full rounded-full bg-neutral-100 dark:bg-neutral-950 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-indigo-500"
                                    style="width: {{ $this->porcentajeCaptura }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="button" wire:click="guardarCalificaciones" @disabled(!$hayCambios)
                                class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-sky-400 to-indigo-500 text-white px-6 py-3 text-sm font-semibold shadow disabled:opacity-60">
                                Guardar calificaciones
                            </button>


                        </div>
                    </div>
                </div>

            </div>
        </div>



    </div>
