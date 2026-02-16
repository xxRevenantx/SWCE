<div>
    <section class="w-full">
        {{-- Encabezado --}}
        <div class="sticky top-0 z-10">
            <div
                class="rounded-2xl border border-neutral-200/60 dark:border-neutral-700/60 bg-gradient-to-r from-[#E4F6FF] to-[#F2EFFF] dark:from-[#0b1220] dark:to-[#121a2a] shadow-lg p-5">
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">Asignación de Horarios</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    Asigna el horario por licenciatura, generación y cuatrimestre.
                </p>
            </div>
        </div>

        {{-- Filtros --}}
        <div
            class="mt-6 rounded-2xl border bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-700 shadow-sm p-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Licenciatura --}}
                <flux:select wire:model.live="licenciatura_id" placeholder="Selecciona una licenciatura...">
                    <flux:select.option value="0">--Selecciona una licenciatura---</flux:select.option>
                    @foreach ($licenciaturas as $lic)
                        <flux:select.option value="{{ $lic->id }}">{{ $lic->nombre }}</flux:select.option>
                    @endforeach
                </flux:select>

                {{-- Generación --}}
                <flux:select wire:model.live="generacion_id" placeholder="Selecciona una generación..."
                    :disabled="!$licenciatura_id">
                    <flux:select.option value="0">--Selecciona una generación---</flux:select.option>
                    @foreach ($generaciones as $gen)
                        <flux:select.option value="{{ $gen->id }}">{{ $gen->generacion }}</flux:select.option>
                    @endforeach
                </flux:select>

                {{-- Cuatrimestre --}}
                <flux:select wire:model.live="cuatrimestre_id" placeholder="Selecciona un cuatrimestre..."
                    :disabled="!$licenciatura_id || !$generacion_id">
                    <flux:select.option value="0">--Selecciona un cuatrimestre---</flux:select.option>
                    @foreach ($cuatrimestres as $cuat)
                        <flux:select.option value="{{ $cuat->id }}">{{ $cuat->nombre_cuatrimestre }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                {{-- Botón para limpiar --}}
                <flux:button type="button" class="btn-azul" wire:click="limpiarFiltros">
                    Limpiar filtros
                </flux:button>
            </div>

            @php
                $filtrosListos = $licenciatura_id && $generacion_id && $cuatrimestre_id;

                // Permite encontrar rápido la materia/profesor por id
                $materiasPorId = collect($materias)->keyBy('id');
            @endphp

            {{-- Mensaje de ayuda --}}
            <div class="mt-4 text-sm text-neutral-600 dark:text-neutral-300">
                @if (!$filtrosListos)
                    Selecciona licenciatura, generación y cuatrimestre para poder asignar materias.
                @else
                    Ya se puede asignar materias en la tabla.
                @endif
            </div>
        </div>

        {{-- Tabla del horario --}}
        <div
            class="mt-6 rounded-2xl border bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-[960px] w-full border-collapse">
                    <thead class="bg-neutral-50 dark:bg-neutral-800/60">
                        <tr class="text-left text-sm">
                            <th class="w-40 px-4 py-3 font-semibold dark:text-neutral-200">HORA</th>
                            @foreach ($dias as $dia)
                                <th class="px-4 py-3 font-semibold dark:text-neutral-200">
                                    {{ mb_strtoupper($dia->dia, 'UTF-8') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="text-sm">
                        @foreach ($horas as $hora)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td
                                    class="px-4 py-3 font-semibold text-neutral-700 dark:text-neutral-200 whitespace-nowrap">
                                    {{ $hora }}
                                </td>

                                {{-- Cada celda tiene un select y una pastilla de profesor --}}
                                @foreach ($dias as $dia)
                                    @php
                                        // Id seleccionado en esa celda
                                        $seleccion = (string) ($horario[$dia->id][$hora] ?? '0');

                                        // Datos de la asignación seleccionada (si existe)
                                        $asignacionSeleccionada =
                                            $seleccion !== '0' ? $materiasPorId[$seleccion] ?? null : null;

                                        // Texto del profesor para la pastilla
                                        $textoProfesor = '';
                                        if ($asignacionSeleccionada && $asignacionSeleccionada->profesor) {
                                            $textoProfesor = trim(
                                                ($asignacionSeleccionada->profesor->nombre ?? '') .
                                                    ' ' .
                                                    ($asignacionSeleccionada->profesor->apellidos ?? ''),
                                            );
                                        }

                                        // Color del profesor (si existe en la tabla profesores)
                                        $colorProfesor = $asignacionSeleccionada?->profesor?->color ?? null;
                                    @endphp

                                    <td class="px-4 py-2 align-top">
                                        {{-- Select de materia --}}
                                        <select
                                            class="w-full rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-800 dark:text-neutral-100 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400/40 disabled:opacity-60"
                                            @disabled(!$filtrosListos)
                                            wire:change="actualizarHorario({{ $dia->id }}, '{{ $hora }}', $event.target.value)">
                                            <option value="0" @selected($seleccion === '0')>
                                                --Selecciona una opción--
                                            </option>

                                            @foreach ($materias as $asig)
                                                @php
                                                    $nombreMateria = $asig->materia->nombre ?? 'Materia';

                                                    // Si tu materia tiene clave, aquí se puede mostrar como (LN840)
                                                    $claveMateria = $asig->materia->clave ?? null;

                                                    $textoMateria = $claveMateria
                                                        ? $nombreMateria . ' (' . $claveMateria . ')'
                                                        : $nombreMateria;
                                                @endphp

                                                <option value="{{ $asig->id }}" @selected($seleccion === (string) $asig->id)>
                                                    {{ $textoMateria }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{-- Pastilla de profesor --}}
                                        @if ($seleccion !== '0' && $textoProfesor !== '')
                                            <div class="mt-2 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold shadow-sm border border-black/5"
                                                style="{{ $colorProfesor ? 'background-color:' . $colorProfesor . '; color:#111827;' : 'background-color:#E5E7EB; color:#111827;' }}">
                                                Profesor: {{ $textoProfesor }}
                                            </div>
                                        @else
                                            <div class="mt-2 text-[11px] text-neutral-400">
                                                Sin profesor asignado
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
