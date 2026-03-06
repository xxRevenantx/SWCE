<div x-data="{
    insIds: @js(collect($inscripciones)->pluck('inscripcion_id')->values()->all()),
    asigIds: @js(collect($materias)->pluck('id')->values()->all()),

    mostrarModalPdf: false,
    pdfModalUrl: '',

    enviarCalificacion(inscripcionId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'La calificación del alumno se enviará a su correo asignado.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, enviar'
        }).then((r) => {
            if (r.isConfirmed) {
                @this.call('enviarCalificacion', inscripcionId);
            }
        });
    },

    focusDown(insId, asigId) {
        const rowIndex = this.insIds.indexOf(insId);
        const colIndex = this.asigIds.indexOf(asigId);

        if (rowIndex === -1 || colIndex === -1) return;

        const nextRowIndex = rowIndex + 1;
        if (nextRowIndex >= this.insIds.length) return;

        const nextInsId = this.insIds[nextRowIndex];
        const nextAsigId = this.asigIds[colIndex];

        const el = document.getElementById(`cal-${nextInsId}-${nextAsigId}`);
        if (el) {
            el.focus();
            if (typeof el.select === 'function') el.select();
        }
    },

    cerrarPdf() {
        this.mostrarModalPdf = false;
        this.pdfModalUrl = '';
        document.body.classList.remove('overflow-hidden');
    }
}" x-on:keydown.escape.window="cerrarPdf()" class="w-full">
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
                    class="items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-indigo-600 text-white px-4 py-2 text-sm font-semibold shadow hover:opacity-95">
                    Limpiar filtros
                </button>
            </div>
        </div>

        {{-- Estado cambios --}}
        <div
            class="mt-5 rounded-2xl border shadow-sm px-4 py-3 flex items-center justify-between gap-4
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
        <div class="relative">
            {{-- Overlay de carga --}}
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
                    <thead class="bg-neutral-50 dark:bg-neutral-950/60">
                        <tr class="text-neutral-600 dark:text-neutral-300">
                            <th class="px-4 py-3 text-left font-semibold w-12 text-white">#</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">MATRÍCULA</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">ALUMNO</th>

                            @foreach ($materias as $m)
                                <th class="px-4 py-2 text-center text-white font-semibold">
                                    {{ mb_strtoupper($m['materia']) }}
                                    <div class="text-[11px] font-normal text-neutral-400 dark:text-neutral-500">
                                        {{ $m['profesor'] }}
                                    </div>
                                </th>
                            @endforeach

                            <th class="px-4 py-3 text-center font-semibold text-white">PROMEDIO</th>
                            <th class="px-4 py-3 text-center font-semibold w-44 text-white">ACCIONES</th>
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

                                    <td class="px-4 py-3 text-center">
                                        <div class="w-24 mx-auto">
                                            <input id="cal-{{ $insId }}-{{ $asigId }}" type="number"
                                                min="0" max="10" step="0.1"
                                                wire:model.lazy="calificaciones.{{ $insId }}.{{ $asigId }}"
                                                wire:change="marcarCambio"
                                                @keydown.enter.prevent="focusDown({{ $insId }}, {{ $asigId }})"
                                                class="w-24 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-3 py-1.5 text-center text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-sky-300"
                                                placeholder="0.0" />

                                            @error('calificaciones.' . $insId . '.' . $asigId)
                                                <div class="mt-1 text-[11px] text-red-600 dark:text-red-300 leading-tight">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </td>
                                @endforeach

                                <td class="px-4 py-3 font-semibold text-neutral-900 dark:text-neutral-100 text-center">
                                    {{ $this->promedioFila($insId) }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Boleta --}}
                                        <a href="{{ route('admin.pdf.boletaCalificacion', $insId) }}" target="_blank"
                                            rel="noopener"
                                            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-semibold">
                                            Boleta
                                        </a>

                                        <button type="button"
                                            class="inline-flex items-center gap-2 rounded-xl bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm font-semibold"
                                            @click="enviarCalificacion({{ $insId }})">
                                            <flux:icon.send />
                                            <span>Enviar</span>
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

            {{-- Acciones inferiores --}}
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
                                style="width: {{ $this->porcentajeCaptura }}%"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        {{-- Botón PDF --}}
                        <button type="button" {{ $this->puedeGenerarPdf ? '' : 'disabled' }}
                            data-url="{{ $this->puedeGenerarPdf ? $this->pdfUrl : '' }}"
                            x-on:click.prevent="
                                const url = $el.dataset.url;
                                if (!url) return;

                                pdfModalUrl = url;
                                mostrarModalPdf = true;
                                document.body.classList.add('overflow-hidden');
                            "
                            class="{{ $this->clasePdf }}">
                            PDF
                        </button>

                        {{-- Botón Guardar --}}
                        <button type="button" wire:click="guardarCalificaciones"
                            {{ $this->puedeGuardar ? '' : 'disabled' }} class="{{ $this->claseGuardar }}">
                            Guardar calificaciones
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal PDF --}}
    <div x-cloak x-show="mostrarModalPdf" x-transition.opacity.duration.200ms
        class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6"
        aria-labelledby="titulo-modal-pdf-calificaciones" aria-modal="true" role="dialog">
        {{-- Fondo --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="cerrarPdf()"></div>

        {{-- Panel --}}
        <div x-show="mostrarModalPdf" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95 blur-sm"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100 blur-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100 blur-0"
            x-transition:leave-end="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95 blur-sm"
            class="relative w-full max-w-6xl overflow-hidden rounded-2xl bg-white dark:bg-neutral-900 shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
            {{-- Encabezado del modal --}}
            <div
                class="flex items-center justify-between gap-3 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-5 py-4 text-white">
                <div>
                    <h2 id="titulo-modal-pdf-calificaciones" class="text-base sm:text-lg font-semibold">
                        Vista previa del PDF de calificaciones
                    </h2>

                </div>

                <div class="flex items-center gap-2">
                    <a :href="pdfModalUrl" target="_blank" rel="noopener"
                        class="inline-flex items-center rounded-xl bg-white/15 px-4 py-2 text-sm font-medium text-white hover:bg-white/20 transition">
                        Abrir en pestaña
                    </a>

                    <button type="button" @click="cerrarPdf()"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-white hover:bg-white/20 transition"
                        aria-label="Cerrar modal">
                        ✕
                    </button>
                </div>
            </div>

            {{-- Contenido del modal --}}
            <div class="p-4 sm:p-5 bg-neutral-100 dark:bg-neutral-950">
                <div
                    class="overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <iframe :src="mostrarModalPdf ? pdfModalUrl : ''" class="h-[75vh] w-full"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
