<div class="space-y-6">
    <div
        class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
                    Mi horario
                </h2>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Consulta las materias asignadas por día y horario.
                </p>
            </div>

            @if ($profesor)
                <div
                    class="rounded-xl bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                    {{ $profesor->nombre_completo }}
                </div>
            @endif
        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <div>
                <label for="licenciatura_id"
                    class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Licenciatura
                </label>

                <select id="licenciatura_id" wire:model.live="licenciatura_id"
                    class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                    <option value="">Todas las licenciaturas</option>
                    @foreach ($licenciaturas as $licenciatura)
                        <option value="{{ $licenciatura->id }}">{{ $licenciatura->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="cuatrimestre_id"
                    class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Cuatrimestre
                </label>

                <select id="cuatrimestre_id" wire:model.live="cuatrimestre_id"
                    class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                    <option value="">Todos los cuatrimestres</option>
                    @foreach ($cuatrimestres as $cuatrimestre)
                        <option value="{{ $cuatrimestre->id }}">{{ $cuatrimestre->nombre_cuatrimestre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="generacion_id"
                    class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Generación
                </label>

                <select id="generacion_id" wire:model.live="generacion_id"
                    class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                    <option value="">Todas las generaciones</option>
                    @foreach ($generaciones as $generacion)
                        <option value="{{ $generacion->id }}">{{ $generacion->generacion }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div
        class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr>
                        <th
                            class="sticky left-0 z-20 border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-left text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            Hora
                        </th>

                        @foreach ($dias as $dia)
                            <th
                                class="border-b border-r border-neutral-200 bg-neutral-100 px-4 py-4 text-center text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                {{ $dia->dia }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @forelse ($horasDisponibles as $hora)
                        <tr>
                            <td
                                class="sticky left-0 z-10 border-b border-r border-neutral-200 bg-white px-4 py-4 text-sm font-semibold text-neutral-700 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200">
                                {{ $hora }}
                            </td>

                            @foreach ($dias as $dia)
                                @php
                                    $celda = $matrizHorario[$hora][$dia->id] ?? null;
                                @endphp

                                <td
                                    class="min-w-[240px] border-b border-r border-neutral-200 p-3 align-top dark:border-neutral-700">
                                    @if ($celda)
                                        @php
                                            $colorFondo = $celda['color'] ?? '#2563eb';
                                            $colorTexto = $this->obtenerColorTexto($colorFondo);
                                        @endphp

                                        <div class="rounded-2xl p-4 shadow-sm ring-1 ring-black/5"
                                            style="background-color: {{ $colorFondo }}; color: {{ $colorTexto }};">
                                            <div class="text-sm font-bold leading-tight">
                                                {{ $celda['materia'] }}
                                            </div>

                                            <div class="mt-2 text-xs font-medium opacity-90">
                                                {{ $celda['licenciatura'] }}
                                            </div>

                                            <div class="mt-1 text-xs opacity-90">
                                                {{ $celda['cuatrimestre'] }}
                                            </div>

                                            <div class="mt-1 text-xs opacity-90">
                                                Generación: {{ $celda['generacion'] }}
                                            </div>

                                            <div class="mt-2 text-xs font-semibold opacity-90">
                                                {{ $celda['hora'] }}
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="flex min-h-[140px] items-center justify-center rounded-2xl border border-dashed border-neutral-300 bg-neutral-50 text-sm text-neutral-400 dark:border-neutral-700 dark:bg-neutral-800/40 dark:text-neutral-500">
                                            Sin clase
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($dias) + 1 }}"
                                class="px-4 py-10 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                No hay horarios asignados para este profesor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
