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

        {{-- Botón PDF --}}
        <a href="{{ $this->filtrosListos ? $this->pdfUrl : '#' }}"
            target="{{ $this->filtrosListos ? '_blank' : '_self' }}" rel="{{ $this->filtrosListos ? 'noopener' : '' }}"
            aria-disabled="{{ $this->filtrosListos ? 'false' : 'true' }}"
            tabindex="{{ $this->filtrosListos ? '0' : '-1' }}" class="{{ $this->clasePdf }}">
            Descargar horario en PDF
        </a>

        {{-- Tabla del horario --}}
        <div
            class="mt-2 rounded-2xl border bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-700 shadow-sm overflow-hidden relative">

            {{-- LOADER (solo cuando cambian filtros / se recarga por filtros) --}}
            <div wire:loading.flex wire:target="licenciatura_id,generacion_id,cuatrimestre_id,limpiarFiltros"
                class="absolute inset-0 z-20 items-center justify-center bg-white/70 dark:bg-neutral-900/70 backdrop-blur-sm">

                <div
                    class="flex items-center gap-3 rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-5 py-4 shadow-lg">
                    <svg class="h-5 w-5 animate-spin text-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>

                    <div class="leading-tight">
                        <p class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">Cargando horario…</p>
                        <p class="text-xs text-neutral-600 dark:text-neutral-300">Aplicando filtros</p>
                    </div>
                </div>
            </div>

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
                            @php
                                $esReceso = $hora === $this->horaReceso;
                            @endphp

                            <tr wire:key="fila-{{ md5($hora) }}"
                                class="border-t border-neutral-200 dark:border-neutral-700 {{ $esReceso ? 'bg-amber-50/60 dark:bg-amber-500/10' : '' }}">
                                <td wire:key="celda-{{ $dia->id }}-{{ md5($hora) }}"
                                    class="px-4 py-2
                                    px-4 py-3 font-semibold text-neutral-700 dark:text-neutral-200 whitespace-nowrap">
                                    <div class="flex items-center justify-between gap-2">
                                        <span>{{ $hora }}</span>


                                    </div>
                                </td>

                                @foreach ($dias as $dia)
                                    @php
                                        $seleccion = (string) ($horario[$dia->id][$hora] ?? '0');
                                        $asignacionSeleccionada =
                                            $seleccion !== '0' ? $materiasPorId[$seleccion] ?? null : null;

                                        $textoProfesor = '';
                                        if ($asignacionSeleccionada && $asignacionSeleccionada->profesor) {
                                            $textoProfesor = trim(
                                                ($asignacionSeleccionada->profesor->nombre ?? '') .
                                                    ' ' .
                                                    ($asignacionSeleccionada->profesor->apellido_paterno ?? '') .
                                                    ' ' .
                                                    ($asignacionSeleccionada->profesor->apellido_materno ?? ''),
                                            );
                                        }

                                        $colorProfesor = $asignacionSeleccionada?->profesor?->color ?? null;
                                    @endphp

                                    <td class="px-4 py-2 align-top">
                                        {{-- ✅ RECESO: NO SE MUESTRA SELECT --}}
                                        @if ($esReceso)
                                            <div
                                                class="h-[44px] rounded-xl border border-dashed border-amber-300/80 dark:border-amber-400/30 bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-900 dark:text-amber-200 text-xs font-semibold">
                                                RECESO
                                            </div>
                                            <div class="mt-2 text-[11px] text-neutral-400 text-center">
                                                —
                                            </div>
                                        @else
                                            {{-- ✅ NORMAL: SÍ SE MUESTRA SELECT --}}
                                            <select
                                                class="w-full rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-800 dark:text-neutral-100 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400/40 disabled:opacity-60"
                                                @disabled(!$filtrosListos)
                                                wire:change="actualizarHorario({{ $dia->id }}, '{{ $hora }}', $event.target.value)">
                                                <option value="0" @selected($seleccion === '0')>--Selecciona una
                                                    opción--</option>

                                                @foreach ($materias as $asig)
                                                    @php
                                                        $nombreMateria = $asig->materia->nombre ?? 'Materia';
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

                                            @if ($seleccion !== '0' && $textoProfesor !== '')
                                                <div class="mt-2 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold shadow-sm border border-black/5"
                                                    style="{{ $colorProfesor ? 'background-color:' . $colorProfesor . '; color:#111827;' : 'background-color:#E5E7EB; color:#111827;' }}">
                                                    Profesor: {{ $textoProfesor }}
                                                </div>
                                            @else
                                                <div class="mt-2 text-[11px] text-neutral-400">Sin profesor asignado
                                                </div>
                                            @endif
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
