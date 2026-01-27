<div>
    {{-- HEADER --}}
    <div
        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-5 py-4 text-white">
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Matricula</h1>
            <p class="text-white/90 text-sm">Gestión de alumnos</p>
        </div>

        <div class="p-4 sm:p-6 space-y-5">
            {{-- FILTROS --}}
            <div class="flex items-center gap-3">
                <svg class="h-7 w-7 text-neutral-700 dark:text-neutral-200" viewBox="0 0 24 24" fill="none"
                    aria-hidden="true">
                    <path d="M3 5h18l-7 8v5l-4 2v-7L3 5z" stroke="currentColor" stroke-width="2"
                        stroke-linejoin="round" />
                </svg>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Filtrar por:</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Licenciatura --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-2">
                        Licenciatura
                    </label>
                    <select wire:model.live="filtrar_licenciatura"
                        class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900
                               px-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">--Selecciona una licenciatura--</option>
                        @foreach ($licenciaturas as $l)
                            <option value="{{ $l->id }}">{{ $l->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Generación --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-2">
                        Generación
                    </label>
                    <select wire:model.live="filtrar_generacion"
                        class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900
                               px-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">--Selecciona una generación--</option>
                        @foreach ($generaciones as $g)
                            <option value="{{ $g->id }}">{{ $g->generacion }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Cuatrimestre --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-2">
                        Cuatrimestre
                    </label>
                    <select wire:model.live="filtrar_cuatrimestre"
                        class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900
                               px-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">--Selecciona un cuatrimestre--</option>
                        @foreach ($cuatrimestres as $c)
                            <option value="{{ $c->id }}">{{ $c->no_cuatrimestre }}° {{ $c->nombre_cuatrimestre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- BUSCADOR + LIMPIAR --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-end">
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-2">
                        Buscar Estudiante
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-neutral-500">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor"
                                    stroke-width="2" />
                                <path d="M16.5 16.5 21 21" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>

                        <input wire:model.live.debounce.400ms="search" type="text"
                            placeholder="Buscar estudiante (Nombre, Apellido Paterno, Apellido Materno, CURP, Matrícula)"
                            class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900
                                   pl-12 pr-3 py-2 text-sm text-neutral-900 dark:text-neutral-100
                                   focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Filtros</span>
                    <button type="button" wire:click="limpiarFiltros"
                        class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold text-white
                               bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-600 hover:to-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 5h18l-7 8v5l-4 2v-7L3 5z" stroke="currentColor" stroke-width="2"
                                stroke-linejoin="round" />
                        </svg>
                        Limpiar filtros
                    </button>
                </div>
            </div>

            {{-- BOTÓN LISTA PDF --}}
            <div>
                <button type="button" wire:click="exportarPdf"
                    class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold text-white
                           bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Lista PDF
                </button>
            </div>

            {{-- TABLA --}}
            <div class="overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-800">
                <div class="overflow-x-auto bg-white dark:bg-neutral-900">
                    <table class="min-w-full text-sm">
                        <thead class="bg-neutral-100 dark:bg-neutral-800/60 text-neutral-700 dark:text-neutral-200">
                            <tr class="text-left">
                                <th class="px-4 py-3 font-semibold">#</th>
                                <th class="px-4 py-3 font-semibold">FOTO</th>
                                <th class="px-4 py-3 font-semibold">MATRÍCULA</th>
                                <th class="px-4 py-3 font-semibold">FOLIO</th>
                                <th class="px-4 py-3 font-semibold">CURP</th>
                                <th class="px-4 py-3 font-semibold">NOMBRE COMPLETO</th>
                                <th class="px-4 py-3 font-semibold">GÉNERO</th>
                                <th class="px-4 py-3 font-semibold">CUATRIMESTRE</th>
                                <th class="px-4 py-3 font-semibold">GENERACIÓN</th>
                                <th class="px-4 py-3 font-semibold">STATUS</th>
                                <th class="px-4 py-3 font-semibold text-right">ACCIONES</th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-neutral-200 dark:divide-neutral-800 text-neutral-800 dark:text-neutral-100">
                            @forelse ($registros as $i => $row)
                                @php
                                    // ✅ Ajusta a tu estructura real
                                    $alumno = $row->alumno ?? null;

                                    $nombre = trim(
                                        ($alumno->nombre ?? '') .
                                            ' ' .
                                            ($alumno->apellido_paterno ?? '') .
                                            ' ' .
                                            ($alumno->apellido_materno ?? ''),
                                    );

                                    $sexo = $alumno->sexo ?? ($alumno->genero ?? '');
                                    $curp = $alumno->curp ?? ($row->curp ?? '—');

                                    $cuatriTxt = $row->cuatrimestre
                                        ? $row->cuatrimestre->no_cuatrimestre . '° CUATRIMESTRE'
                                        : '—';

                                    $genTxt = $row->generacion->generacion ?? '—';

                                    $activo =
                                        (string) ($row->status ?? 'true') === 'true' || (int) ($row->status ?? 1) === 1;
                                @endphp

                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/40">
                                    <td class="px-4 py-3">{{ $registros->firstItem() + $i }}</td>

                                    <td class="px-4 py-3">
                                        <div
                                            class="h-8 w-8 rounded-full bg-neutral-200 dark:bg-neutral-700 overflow-hidden">
                                            @if (!empty($alumno?->foto))
                                                <img src="{{ asset('storage/' . $alumno->foto) }}" alt="Foto"
                                                    class="h-full w-full object-cover">
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">{{ $row->matricula ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $row->folio ?? '—' }}</td>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $curp }}</td>
                                    <td class="px-4 py-3">{{ $nombre !== '' ? $nombre : '—' }}</td>
                                    <td class="px-4 py-3">{{ $sexo !== '' ? strtoupper($sexo) : '—' }}</td>
                                    <td class="px-4 py-3">{{ $cuatriTxt }}</td>
                                    <td class="px-4 py-3">{{ $genTxt }}</td>

                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $activo ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300' }}">
                                            {{ $activo ? 'Activo' : 'Baja' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            {{-- EDITAR --}}
                                            <button type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-amber-400 hover:bg-amber-500 text-white
                                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-400"
                                                title="Editar" {{-- wire:click="$dispatch('editarMatricula', { id: {{ $row->id }} })" --}}>
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    aria-hidden="true">
                                                    <path d="M12 20h9" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" />
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5Z"
                                                        stroke="currentColor" stroke-width="2"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </button>

                                            {{-- ELIMINAR --}}
                                            <button type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-rose-500 hover:bg-rose-600 text-white
                                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500"
                                                title="Eliminar" {{-- wire:click="eliminar({{ $row->id }})" --}}>
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    aria-hidden="true">
                                                    <path d="M3 6h18" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" />
                                                    <path d="M8 6V4h8v2" stroke="currentColor" stroke-width="2"
                                                        stroke-linejoin="round" />
                                                    <path d="M6 6l1 16h10l1-16" stroke="currentColor" stroke-width="2"
                                                        stroke-linejoin="round" />
                                                    <path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11"
                                        class="px-6 py-10 text-center text-neutral-500 dark:text-neutral-400">
                                        No se encontraron estudiantes con los filtros actuales.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                @if ($registros->hasPages())
                    <div
                        class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                        {{ $registros->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- LOADER (opcional) --}}
    <div wire:loading class="fixed inset-0 z-50 grid place-items-center bg-black/20 backdrop-blur-sm">
        <div
            class="rounded-2xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 shadow-xl px-6 py-5 flex items-center gap-3">
            <svg class="h-6 w-6 animate-spin text-neutral-700 dark:text-neutral-200" viewBox="0 0 24 24"
                fill="none" aria-hidden="true">
                <path d="M12 2v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <path d="M12 18v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".4" />
                <path d="M4.93 4.93l2.83 2.83" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <path d="M16.24 16.24l2.83 2.83" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    opacity=".4" />
                <path d="M2 12h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <path d="M18 12h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".4" />
                <path d="M4.93 19.07l2.83-2.83" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    opacity=".4" />
                <path d="M16.24 7.76l2.83-2.83" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
            <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">Cargando…</span>
        </div>
    </div>
</div>
