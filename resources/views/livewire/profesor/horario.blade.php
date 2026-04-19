<div x-data="{
    mostrarModalPdf: false,
    pdfModalUrl: '',
    cargandoPdf: false,
    cerrarPdf() {
        this.mostrarModalPdf = false;
        this.pdfModalUrl = '';
        this.cargandoPdf = false;
        document.body.classList.remove('overflow-hidden');
    },
    abrirPdf(url) {
        if (!url) return;
        this.cargandoPdf = true;
        this.pdfModalUrl = url;
        this.mostrarModalPdf = true;
        document.body.classList.add('overflow-hidden');
    }
}" x-on:keydown.escape.window="cerrarPdf()" class="space-y-6">
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
                    {{ $profesor->nombre }} {{ $profesor->apellido_paterno }} {{ $profesor->apellido_materno }}
                </div>
            @endif
        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div>


                <flux:select label="Licenciatura" id="licenciatura_id" wire:model.live="licenciatura_id">
                    <flux:select.option value="">Todas las licenciaturas</flux:select.option>

                    @foreach ($licenciaturas as $licenciatura)
                        <flux:select.option value="{{ $licenciatura->id }}">
                            {{ $licenciatura->nombre }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div>


                <flux:select label="Cuatrimestre" id="cuatrimestre_id" wire:model.live="cuatrimestre_id">
                    <flux:select.option value="">Todos los cuatrimestres</flux:select.option>

                    @foreach ($cuatrimestres as $cuatrimestre)
                        <flux:select.option value="{{ $cuatrimestre->id }}">
                            {{ $cuatrimestre->nombre_cuatrimestre }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div>


                <flux:select label="Generación" id="generacion_id" wire:model.live="generacion_id">
                    <flux:select.option value="">Todas las generaciones</flux:select.option>

                    @foreach ($generaciones as $generacion)
                        <flux:select.option value="{{ $generacion->id }}">
                            {{ $generacion->generacion }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div class="flex items-end">
                <button type="button" wire:click="limpiarFiltros"
                    class="inline-flex w-full items-center justify-center rounded-xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-300">
                    Limpiar filtros
                </button>
            </div>
        </div>

        <div class="mt-4">
            <button type="button" x-on:click="abrirPdf('{{ $this->pdfUrl }}')" class="{{ $this->clasePdf }}">
                Ver horario en PDF
            </button>
        </div>
    </div>

    <div
        class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
        <div wire:loading.flex wire:target="licenciatura_id,cuatrimestre_id,generacion_id,limpiarFiltros"
            class="absolute inset-0 z-30 hidden items-center justify-center bg-white/80 backdrop-blur-sm dark:bg-neutral-900/80">
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
                        Cargando horario
                    </p>
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                        Actualizando materias, días y horarios...
                    </p>
                </div>
            </div>
        </div>

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
                                {{ mb_strtoupper($dia->dia, 'UTF-8') }}
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
                                    $celda = $matrizHorario[$hora][$dia->id] ?? [];
                                @endphp

                                <td
                                    class="min-w-[260px] border-b border-r border-neutral-200 p-3 align-top dark:border-neutral-700">
                                    @if (count($celda) > 0)
                                        <div class="space-y-3">
                                            @foreach ($celda as $clase)
                                                @php
                                                    $colorFondo = $clase['color'] ?? '#334155';
                                                    $colorTexto = $this->obtenerColorTexto($colorFondo);
                                                @endphp

                                                <div class="rounded-2xl p-4 shadow-sm ring-1 ring-black/5 transition hover:scale-[1.01]"
                                                    style="background-color: {{ $colorFondo }}; color: {{ $colorTexto }};">
                                                    <div
                                                        class="inline-flex rounded-full bg-white/20 px-2 py-1 text-[11px] font-semibold">
                                                        {{ $clase['licenciatura'] }}
                                                    </div>

                                                    <div class="mt-3 text-sm font-bold leading-tight">
                                                        {{ $clase['materia'] }}
                                                    </div>

                                                    <div class="mt-2 text-xs opacity-90">
                                                        {{ $clase['cuatrimestre'] }}
                                                    </div>

                                                    <div class="mt-1 text-xs opacity-90">
                                                        Generación: {{ $clase['generacion'] }}
                                                    </div>

                                                    <div class="mt-2 text-xs font-semibold opacity-90">
                                                        {{ $clase['hora'] }}
                                                    </div>
                                                </div>
                                            @endforeach
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

    <div x-cloak x-show="mostrarModalPdf" x-transition.opacity.duration.200ms
        class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6" aria-labelledby="titulo-modal-pdf"
        aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" x-on:click="cerrarPdf()"></div>

        <div x-show="mostrarModalPdf" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95 blur-sm"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100 blur-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100 blur-0"
            x-transition:leave-end="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95 blur-sm"
            class="relative w-full max-w-6xl overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/10 dark:bg-neutral-900 dark:ring-white/10">
            <div
                class="flex items-center justify-between gap-3 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-5 py-4 text-white">
                <div>
                    <h2 id="titulo-modal-pdf" class="text-base font-semibold sm:text-lg">
                        Vista previa del horario en PDF
                    </h2>
                </div>

                <div class="flex items-center gap-2">
                    <a x-bind:href="pdfModalUrl" target="_blank" rel="noopener"
                        class="inline-flex items-center rounded-xl bg-white/15 px-4 py-2 text-sm font-medium text-white transition hover:bg-white/20">
                        Abrir en pestaña
                    </a>

                    <button type="button" x-on:click="cerrarPdf()"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-white transition hover:bg-white/20"
                        aria-label="Cerrar modal">
                        ✕
                    </button>
                </div>
            </div>

            <div class="bg-neutral-100 p-4 dark:bg-neutral-950 sm:p-5">
                <div
                    class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">

                    <div x-show="cargandoPdf" x-transition.opacity
                        class="absolute inset-0 z-20 flex items-center justify-center bg-white/90 backdrop-blur-sm dark:bg-neutral-900/90">
                        <div
                            class="flex flex-col items-center gap-4 rounded-2xl border border-sky-100 bg-white px-8 py-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">
                            <div class="relative flex h-16 w-16 items-center justify-center">
                                <div
                                    class="absolute h-16 w-16 rounded-full border-4 border-sky-100 dark:border-neutral-700">
                                </div>
                                <div
                                    class="absolute h-16 w-16 animate-spin rounded-full border-4 border-transparent border-t-sky-600 border-r-blue-500">
                                </div>
                                <div class="h-6 w-6 animate-pulse rounded-full bg-sky-600"></div>
                            </div>

                            <div class="text-center">
                                <p class="text-sm font-semibold text-neutral-800 dark:text-white">
                                    Cargando documento
                                </p>
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    Espera mientras se genera la vista previa del PDF...
                                </p>
                            </div>
                        </div>
                    </div>

                    <iframe x-bind:src="mostrarModalPdf ? pdfModalUrl : ''" x-on:load="cargandoPdf = false"
                        class="h-[75vh] w-full">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
