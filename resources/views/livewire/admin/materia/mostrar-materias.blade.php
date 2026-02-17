<div x-data="{
    destroyMateria(id, nombre) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: `Esta acción no podrá revertirse.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563EB',
            cancelButtonColor: '#EF4444',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar'
        }).then((r) => r.isConfirmed && @this.call('eliminarMateria', id))
    },
}" class="space-y-5">
    <!-- Encabezado -->
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Materias</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Filtra, busca, edita o elimina materias por licenciatura,
            cuatrimestre y si es calificable.</p>
    </div>


    <!-- Búsqueda + Filtros (Flux UI) -->
    <div
        class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4
           rounded-2xl border border-gray-200 dark:border-neutral-800
           bg-white/70 dark:bg-neutral-900/60 p-3 md:p-4 shadow-sm">

        {{-- Licenciatura --}}
        <div class="w-full md:w-[28%]">
            <flux:field>
                <flux:label>Licenciatura</flux:label>

                <flux:select wire:model.live="filtrar_licenciatura">
                    <flux:select.option value="">--Selecciona una licenciatura--</flux:select.option>
                    @foreach ($licenciaturas as $lic)
                        <flux:select.option value="{{ $lic->id }}">{{ $lic->nombre }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>

        {{-- Cuatrimestre --}}
        <div class="w-full md:w-[24%]">
            <flux:field>
                <flux:label>Cuatrimestre</flux:label>

                <flux:select wire:model.live="filtrar_cuatrimestre">
                    <flux:select.option value="">--Selecciona un cuatrimestre--</flux:select.option>
                    @foreach ($cuatrimestres as $c)
                        <flux:select.option value="{{ $c->id }}">{{ $c->nombre_cuatrimestre }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>

        {{-- Calificable --}}
        <div class="w-full md:w-[20%]">
            <flux:field>
                <flux:label>Calificable</flux:label>

                <flux:select wire:model.live="filtrar_calificable">
                    <flux:select.option value="">--Selecciona una opción--</flux:select.option>
                    <flux:select.option value="1">Sí</flux:select.option>
                    <flux:select.option value="0">No</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        {{-- Buscador --}}
        <div class="w-full md:flex-1">
            <flux:field>
                <flux:label class="sr-only">Buscar</flux:label>

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none">
                            <path stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                        </svg>
                    </div>

                    <flux:input type="text" wire:model.live="search" placeholder="Buscar por materia o clave"
                        class="pl-10 w-full" />
                </div>
            </flux:field>
        </div>
    </div>




    <div class="relative">
        <!-- Loader overlay -->
        <div wire:loading.delay
            wire:target="search, filtrar_licenciatura, filtrar_cuatrimestre, filtrar_calificable, eliminarMateria"
            class="absolute inset-0 z-10 grid place-items-center rounded-xl bg-white/70 dark:bg-neutral-900/70 backdrop-blur"
            aria-live="polite" aria-busy="true">
            <div
                class="flex items-center gap-3 rounded-xl bg-white dark:bg-neutral-900 px-4 py-3 ring-1 ring-gray-200 dark:ring-neutral-800 shadow">
                <svg class="h-5 w-5 animate-spin text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="none"
                    aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-200">Cargando…</span>
            </div>
        </div>



        <div class="transition ease-out duration-200" wire:loading.class="blur-sm opacity-80 pointer-events-none"
            wire:target="search, eliminarGeneracion">
            <!-- Tabla (desktop) -->
            <div
                class="overflow-hidden  rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                <div class="overflow-x-auto max-h-[70vh]">
                    <table class="w-full text-sm ">
                        <thead>
                            <tr>

                                <th class="px-4 py-3 text-center font-semibold">#</th>
                                <th class="px-4 py-3 text-center font-semibold">Materia</th>
                                <th class="px-4 py-3 text-center font-semibold">url</th>
                                <th class="px-4 py-3 text-center font-semibold">Clave</th>
                                <th class="px-4 py-3 text-center font-semibold">Créditos</th>
                                <th class="px-4 py-3 text-center font-semibold">Cuatrimestre</th>
                                <th class="px-4 py-3 text-center font-semibold">Calificable</th>
                                <th class="px-4 py-3 text-center font-semibold">Acciones</th>
                            </tr>
                        </thead>

                        @php
                            $licActual = null;
                            $contadorGrupo = 0; // si quieres # reiniciado por licenciatura
                        @endphp

                        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                            @forelse($materias as $materia)
                                @php
                                    $licId = $materia->licenciatura_id;
                                    $licNombre = $materia->licenciatura?->nombre ?? 'SIN LICENCIATURA';
                                @endphp

                                {{--  Encabezado del grupo --}}
                                @if ($licActual !== $licId)
                                    @php
                                        $licActual = $licId;
                                        $contadorGrupo = 0;
                                    @endphp

                                    <tr class="bg-neutral-100 dark:bg-neutral-900/60">
                                        <td colspan="8" class="px-4 py-2.5">
                                            <div class="inline-flex items-center gap-2">
                                                <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                                                <span
                                                    class="text-[11px] font-extrabold tracking-wide text-neutral-700 dark:text-neutral-200">
                                                    {{ mb_strtoupper($licNombre) }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                @php $contadorGrupo++; @endphp

                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-900/40">
                                    {{-- # (reinicia por licenciatura) --}}
                                    <td class="px-4 py-3 text-sm">{{ $contadorGrupo }}</td>

                                    <td class="px-4 py-3 text-sm text-left">{{ $materia->nombre }}</td>
                                    <td class="px-4 py-3 text-sm text-left">{{ $materia->slug }}</td>
                                    <td class="px-4 py-3 text-sm text-center">{{ $materia->clave }}</td>
                                    <td class="px-4 py-3 text-sm text-center">{{ $materia->creditos }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        {{ $materia->cuatrimestre?->no_cuatrimestre ? $materia->cuatrimestre->no_cuatrimestre . '° CUATRIMESTRE' : '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-center">
                                        @if ($materia->calificable)
                                            <span
                                                class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">
                                                SÍ
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-lg bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-700">
                                                NO
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        {{-- BOTONES DE ELIMINAR Y EDITAR --}}
                                        <div class="flex items-center justify-center gap-1">
                                            <flux:button variant="primary"
                                                class="cursor-pointer bg-amber-500 hover:bg-amber-600 text-white"
                                                @click="$dispatch('abrir-modal-editar');
                                                         Livewire.dispatch('editarModal', { id: {{ $materia->id }} }); ">
                                                <flux:icon.square-pen class="w-3.5 h-3.5" />
                                                <!-- ícono -->
                                            </flux:button>

                                            <flux:button variant="danger"
                                                class="cursor-pointer bg-rose-600 hover:bg-rose-700 text-white p-1"
                                                @click="destroyMateria({{ $materia->id }}, '{{ $materia->nombre }}')">
                                                <flux:icon.trash-2 class="w-3.5 h-3.5" />
                                            </flux:button>

                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-sm text-neutral-500">
                                        Sin materias que coincidan con los filtros o la búsqueda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
    <!-- Paginación -->
    <div class="mt-5">
        {{ $materias->links() }}
    </div>



    <!-- Modal editar -->
    <livewire:admin.materia.editar-materia />
</div>
