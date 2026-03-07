<div x-data="{
    show: false,
    loading: false,
    panelVistaPrevia: false,
    urlVistaPrevia: null,
    tituloVistaPrevia: 'Vista previa',
    nombreArchivoVistaPrevia: '',
    tipoDocumentoVistaPrevia: '',
    estadoVistaPrevia: '',

    abrirVistaPrevia(url, titulo = 'Vista previa', nombre = '', tipo = '', estado = '') {
        this.urlVistaPrevia = url;
        this.tituloVistaPrevia = titulo;
        this.nombreArchivoVistaPrevia = nombre;
        this.tipoDocumentoVistaPrevia = tipo;
        this.estadoVistaPrevia = estado;
        this.panelVistaPrevia = !!url;
    },

    cerrarVistaPrevia() {
        this.panelVistaPrevia = false;
        this.urlVistaPrevia = null;
        this.tituloVistaPrevia = 'Vista previa';
        this.nombreArchivoVistaPrevia = '';
        this.tipoDocumentoVistaPrevia = '';
        this.estadoVistaPrevia = '';
    }
}" x-cloak x-trap.noscroll="show" x-show="show"
    @abrir-modal-documentos.window="show = true; loading = true" @documentos-cargados.window="loading = false"
    @abrir-vista-previa.window="
        abrirVistaPrevia(
            $event.detail.url,
            $event.detail.titulo,
            $event.detail.nombre,
            $event.detail.tipo,
            $event.detail.estado
        )
    "
    @cerrar-vista-previa.window="cerrarVistaPrevia()"
    @cerrar-modal-documentos.window="
        show = false;
        loading = false;
        cerrarVistaPrevia();
        $wire.cerrarModal()
    "
    @keydown.escape.window="
        if (panelVistaPrevia) {
            cerrarVistaPrevia();
        } else {
            show = false;
            loading = false;
            $wire.cerrarModal();
        }
    "
    class="fixed inset-0 z-50 flex items-center justify-center" aria-live="polite">
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-neutral-900/70 backdrop-blur-sm" x-show="show" x-transition.opacity
        @click.self="show = false; loading = false; cerrarVistaPrevia(); $wire.cerrarModal()"></div>

    <div class="relative w-[98vw] sm:w-[95vw] md:w-[94vw] max-w-7xl mx-4 sm:mx-6 bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
        role="dialog" aria-modal="true" aria-labelledby="titulo-modal-documentos" x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2" wire:ignore.self>
        {{-- Acento --}}
        <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500"></div>

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 px-5 py-4 sm:px-6 sm:py-5">
            <h2 id="titulo-modal-documentos" class="text-lg sm:text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                Documentos del alumno
                @if ($nombreAlumno)
                    <flux:badge color="indigo" class="align-middle">{{ $nombreAlumno }}</flux:badge>
                @endif
            </h2>

            <button @click="show = false; loading = false; cerrarVistaPrevia(); $wire.cerrarModal()" type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-full text-zinc-500 hover:text-zinc-800 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-neutral-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                aria-label="Cerrar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Contenido --}}
        <div class="px-5 sm:px-6 pb-4 sm:pb-6">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 h-[75vh]">
                {{-- Columna izquierda --}}
                <div class="xl:col-span-7 2xl:col-span-6 overflow-y-auto pr-1">
                    <div class="grid grid-cols-1 gap-5">

                        {{-- CURP --}}
                        <div x-data="{
                            subiendo: false,
                            terminado: false,
                            progreso: 0,
                            vistaPrevia: null,
                            nombreTemporal: '',
                        
                            cambiarArchivo(evento) {
                                const archivo = evento.target.files[0];
                        
                                if (this.vistaPrevia) {
                                    URL.revokeObjectURL(this.vistaPrevia);
                                    this.vistaPrevia = null;
                                }
                        
                                if (archivo) {
                                    this.vistaPrevia = URL.createObjectURL(archivo);
                                    this.nombreTemporal = archivo.name;
                                } else {
                                    this.nombreTemporal = '';
                                }
                            },
                        
                            limpiarVistaPrevia() {
                                if (this.vistaPrevia) {
                                    URL.revokeObjectURL(this.vistaPrevia);
                                    this.vistaPrevia = null;
                                }
                                this.nombreTemporal = '';
                            },
                        
                            inicioSubida() {
                                this.subiendo = true;
                                this.terminado = false;
                                this.progreso = 0;
                            },
                        
                            progresoSubida(evento) {
                                this.progreso = evento.detail.progress;
                            },
                        
                            finSubida() {
                                this.progreso = 100;
                                this.terminado = true;
                        
                                setTimeout(() => {
                                    this.subiendo = false;
                                    this.terminado = false;
                                    this.progreso = 0;
                                }, 1000);
                            },
                        
                            errorSubida() {
                                this.subiendo = false;
                                this.terminado = false;
                                this.progreso = 0;
                            }
                        }" x-on:livewire-upload-start="inicioSubida()"
                            x-on:livewire-upload-progress="progresoSubida($event)"
                            x-on:livewire-upload-finish="finSubida()" x-on:livewire-upload-error="errorSubida()"
                            class="rounded-2xl border border-zinc-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
                            <div class="h-1 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500">
                            </div>

                            <div class="p-5">
                                <div class="mb-5 flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-300 dark:ring-indigo-400/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z" />
                                            </svg>
                                        </div>

                                        <div>
                                            <h3
                                                class="text-lg sm:text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                                                CURP <span class="text-sm font-medium text-zinc-500">(PDF)</span>
                                            </h3>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                                Sube, previsualiza, reemplaza o elimina el documento.
                                            </p>
                                        </div>
                                    </div>

                                    @if ($curp_guardado)
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            Guardado
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-sm font-medium text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300">
                                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                            Sin guardar
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    <div class="flex flex-wrap gap-3">
                                        <label
                                            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm sm:text-base font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                            <span>＋</span>
                                            {{ $curp_guardado ? 'Reemplazar CURP' : 'Subir CURP' }}
                                            <input type="file" wire:model="curp_archivo" accept="application/pdf"
                                                class="hidden" @change="cambiarArchivo($event)">
                                        </label>

                                        <template x-if="vistaPrevia">
                                            <button type="button"
                                                @click="$dispatch('abrir-vista-previa', {
                                                    url: vistaPrevia,
                                                    titulo: 'Vista previa de CURP seleccionada',
                                                    nombre: nombreTemporal,
                                                    tipo: 'CURP',
                                                    estado: 'Seleccionado'
                                                })"
                                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-sky-300 dark:border-sky-700 bg-sky-50 dark:bg-sky-900/20 px-5 py-3 text-sm sm:text-base font-medium text-sky-700 dark:text-sky-300 transition hover:bg-sky-100 dark:hover:bg-sky-900/30">
                                                <span>👁</span>
                                                Vista previa
                                            </button>
                                        </template>

                                        @if ($curp_guardado)
                                            <button type="button"
                                                @click="$dispatch('abrir-vista-previa', {
                                                    url: '{{ $this->obtenerUrlDocumento($curp_guardado) }}',
                                                    titulo: 'Vista previa de CURP guardada',
                                                    nombre: '{{ $this->obtenerNombreArchivo($curp_guardado) }}',
                                                    tipo: 'CURP',
                                                    estado: 'Guardado'
                                                })"
                                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-5 py-3 text-sm sm:text-base font-medium text-emerald-700 dark:text-emerald-300 transition hover:bg-zinc-50 dark:hover:bg-neutral-800">
                                                <span>👁</span>
                                                Ver guardado
                                            </button>

                                            <button type="button" wire:click="eliminarDocumento('curp')"
                                                @click="
                                                    limpiarVistaPrevia();
                                                    $dispatch('cerrar-vista-previa')
                                                "
                                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-5 py-3 text-sm sm:text-base font-medium text-rose-600 dark:text-rose-300 transition hover:bg-rose-50 dark:hover:bg-rose-500/10">
                                                <span>🗑</span>
                                                Eliminar
                                            </button>
                                        @endif
                                    </div>

                                    <div x-show="subiendo || terminado" x-transition class="space-y-2">
                                        <div
                                            class="flex items-center justify-between text-xs sm:text-sm text-zinc-600 dark:text-zinc-300">
                                            <span
                                                x-text="terminado ? 'Carga completada' : 'Subiendo archivo...'"></span>
                                            <span x-text="progreso + '%'"></span>
                                        </div>

                                        <div
                                            class="h-3 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-neutral-800">
                                            <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-indigo-600 transition-all duration-300"
                                                :style="`width: ${progreso}%`"></div>
                                        </div>
                                    </div>

                                    <div wire:loading wire:target="curp_archivo"
                                        class="flex items-center gap-2 rounded-lg border border-zinc-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 py-2">
                                        <svg class="h-5 w-5 animate-spin text-blue-600 dark:text-blue-400"
                                            viewBox="0 0 24 24" fill="none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z">
                                            </path>
                                        </svg>
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">Cargando CURP…</span>
                                    </div>

                                    @if ($curp_archivo)
                                        <div
                                            class="rounded-xl border border-sky-200 dark:border-sky-500/20 bg-sky-50 dark:bg-sky-500/10 p-4">
                                            <p class="text-sm text-zinc-700 dark:text-zinc-200">
                                                <span class="font-semibold">Archivo:</span>
                                                {{ $curp_archivo->getClientOriginalName() }}
                                            </p>
                                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                                                <span class="font-semibold">Tamaño:</span>
                                                {{ number_format($curp_archivo->getSize() / 1024, 1) }} KB
                                            </p>

                                            <div
                                                class="pt-4 border-t border-zinc-200 dark:border-neutral-800 mt-4 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                                                <flux:button type="button" wire:click="$set('curp_archivo', null)"
                                                    @click="
                                                        limpiarVistaPrevia();
                                                        $dispatch('cerrar-vista-previa')
                                                    "
                                                    class="cursor-pointer">
                                                    Cancelar
                                                </flux:button>

                                                <flux:button variant="primary" type="button"
                                                    wire:click="guardarDocumento('curp')"
                                                    class="w-full sm:w-auto cursor-pointer"
                                                    wire:loading.attr="disabled" wire:target="guardarDocumento">
                                                    Guardar CURP
                                                </flux:button>
                                            </div>
                                        </div>
                                    @elseif ($curp_guardado)
                                        <div
                                            class="rounded-xl border border-zinc-200 dark:border-neutral-800 bg-zinc-50 dark:bg-neutral-800/40 p-4">
                                            <p class="text-sm text-zinc-700 dark:text-zinc-300">
                                                <span class="font-semibold">Archivo:</span>
                                                {{ $this->obtenerNombreArchivo($curp_guardado) }}
                                            </p>
                                        </div>
                                    @else
                                        <p class="text-sm text-rose-500">No se ha subido ningún archivo.</p>
                                    @endif

                                    @error('curp_archivo')
                                        <p class="text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ACTA DE NACIMIENTO --}}
                        <div x-data="{
                            subiendo: false,
                            terminado: false,
                            progreso: 0,
                            vistaPrevia: null,
                            nombreTemporal: '',
                        
                            cambiarArchivo(evento) {
                                const archivo = evento.target.files[0];
                        
                                if (this.vistaPrevia) {
                                    URL.revokeObjectURL(this.vistaPrevia);
                                    this.vistaPrevia = null;
                                }
                        
                                if (archivo) {
                                    this.vistaPrevia = URL.createObjectURL(archivo);
                                    this.nombreTemporal = archivo.name;
                                } else {
                                    this.nombreTemporal = '';
                                }
                            },
                        
                            limpiarVistaPrevia() {
                                if (this.vistaPrevia) {
                                    URL.revokeObjectURL(this.vistaPrevia);
                                    this.vistaPrevia = null;
                                }
                                this.nombreTemporal = '';
                            },
                        
                            inicioSubida() {
                                this.subiendo = true;
                                this.terminado = false;
                                this.progreso = 0;
                            },
                        
                            progresoSubida(evento) {
                                this.progreso = evento.detail.progress;
                            },
                        
                            finSubida() {
                                this.progreso = 100;
                                this.terminado = true;
                        
                                setTimeout(() => {
                                    this.subiendo = false;
                                    this.terminado = false;
                                    this.progreso = 0;
                                }, 1000);
                            },
                        
                            errorSubida() {
                                this.subiendo = false;
                                this.terminado = false;
                                this.progreso = 0;
                            }
                        }" x-on:livewire-upload-start="inicioSubida()"
                            x-on:livewire-upload-progress="progresoSubida($event)"
                            x-on:livewire-upload-finish="finSubida()" x-on:livewire-upload-error="errorSubida()"
                            class="rounded-2xl border border-zinc-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
                            <div class="h-1 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500">
                            </div>

                            <div class="p-5">
                                <div class="mb-5 flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-300 dark:ring-indigo-400/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z" />
                                            </svg>
                                        </div>

                                        <div>
                                            <h3
                                                class="text-lg sm:text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                                                ACTA DE NACIMIENTO <span
                                                    class="text-sm font-medium text-zinc-500">(PDF)</span>
                                            </h3>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                                Sube, previsualiza, reemplaza o elimina el documento.
                                            </p>
                                        </div>
                                    </div>

                                    @if ($acta_nacimiento_guardado)
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            Guardado
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-sm font-medium text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300">
                                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                            Sin guardar
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    <div class="flex flex-wrap gap-3">
                                        <label
                                            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm sm:text-base font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                            <span>＋</span>
                                            {{ $acta_nacimiento_guardado ? 'Reemplazar Acta de Nacimiento' : 'Subir Acta de Nacimiento' }}
                                            <input type="file" wire:model="acta_nacimiento_archivo"
                                                accept="application/pdf" class="hidden"
                                                @change="cambiarArchivo($event)">
                                        </label>

                                        <template x-if="vistaPrevia">
                                            <button type="button"
                                                @click="$dispatch('abrir-vista-previa', {
                                                    url: vistaPrevia,
                                                    titulo: 'Vista previa de Acta seleccionada',
                                                    nombre: nombreTemporal,
                                                    tipo: 'Acta de nacimiento',
                                                    estado: 'Seleccionado'
                                                })"
                                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-sky-300 dark:border-sky-700 bg-sky-50 dark:bg-sky-900/20 px-5 py-3 text-sm sm:text-base font-medium text-sky-700 dark:text-sky-300 transition hover:bg-sky-100 dark:hover:bg-sky-900/30">
                                                <span>👁</span>
                                                Vista previa
                                            </button>
                                        </template>

                                        @if ($acta_nacimiento_guardado)
                                            <button type="button"
                                                @click="$dispatch('abrir-vista-previa', {
                                                    url: '{{ $this->obtenerUrlDocumento($acta_nacimiento_guardado) }}',
                                                    titulo: 'Vista previa de Acta guardada',
                                                    nombre: '{{ $this->obtenerNombreArchivo($acta_nacimiento_guardado) }}',
                                                    tipo: 'Acta de nacimiento',
                                                    estado: 'Guardado'
                                                })"
                                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-5 py-3 text-sm sm:text-base font-medium text-emerald-700 dark:text-emerald-300 transition hover:bg-zinc-50 dark:hover:bg-neutral-800">
                                                <span>👁</span>
                                                Ver guardado
                                            </button>

                                            <button type="button" wire:click="eliminarDocumento('acta_nacimiento')"
                                                @click="
                                                    limpiarVistaPrevia();
                                                    $dispatch('cerrar-vista-previa')
                                                "
                                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-5 py-3 text-sm sm:text-base font-medium text-rose-600 dark:text-rose-300 transition hover:bg-rose-50 dark:hover:bg-rose-500/10">
                                                <span>🗑</span>
                                                Eliminar
                                            </button>
                                        @endif
                                    </div>

                                    <div x-show="subiendo || terminado" x-transition class="space-y-2">
                                        <div
                                            class="flex items-center justify-between text-xs sm:text-sm text-zinc-600 dark:text-zinc-300">
                                            <span
                                                x-text="terminado ? 'Carga completada' : 'Subiendo archivo...'"></span>
                                            <span x-text="progreso + '%'"></span>
                                        </div>

                                        <div
                                            class="h-3 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-neutral-800">
                                            <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-indigo-600 transition-all duration-300"
                                                :style="`width: ${progreso}%`"></div>
                                        </div>
                                    </div>

                                    <div wire:loading wire:target="acta_nacimiento_archivo"
                                        class="flex items-center gap-2 rounded-lg border border-zinc-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 py-2">
                                        <svg class="h-5 w-5 animate-spin text-blue-600 dark:text-blue-400"
                                            viewBox="0 0 24 24" fill="none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z">
                                            </path>
                                        </svg>
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">Cargando acta…</span>
                                    </div>

                                    @if ($acta_nacimiento_archivo)
                                        <div
                                            class="rounded-xl border border-sky-200 dark:border-sky-500/20 bg-sky-50 dark:bg-sky-500/10 p-4">
                                            <p class="text-sm text-zinc-700 dark:text-zinc-200">
                                                <span class="font-semibold">Archivo:</span>
                                                {{ $acta_nacimiento_archivo->getClientOriginalName() }}
                                            </p>
                                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                                                <span class="font-semibold">Tamaño:</span>
                                                {{ number_format($acta_nacimiento_archivo->getSize() / 1024, 1) }} KB
                                            </p>

                                            <div
                                                class="pt-4 border-t border-zinc-200 dark:border-neutral-800 mt-4 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                                                <flux:button type="button"
                                                    wire:click="$set('acta_nacimiento_archivo', null)"
                                                    @click="
                                                        limpiarVistaPrevia();
                                                        $dispatch('cerrar-vista-previa')
                                                    "
                                                    class="cursor-pointer">
                                                    Cancelar
                                                </flux:button>

                                                <flux:button variant="primary" type="button"
                                                    wire:click="guardarDocumento('acta_nacimiento')"
                                                    class="w-full sm:w-auto cursor-pointer"
                                                    wire:loading.attr="disabled" wire:target="guardarDocumento">
                                                    Guardar Acta
                                                </flux:button>
                                            </div>
                                        </div>
                                    @elseif ($acta_nacimiento_guardado)
                                        <div
                                            class="rounded-xl border border-zinc-200 dark:border-neutral-800 bg-zinc-50 dark:bg-neutral-800/40 p-4">
                                            <p class="text-sm text-zinc-700 dark:text-zinc-300">
                                                <span class="font-semibold">Archivo:</span>
                                                {{ $this->obtenerNombreArchivo($acta_nacimiento_guardado) }}
                                            </p>
                                        </div>
                                    @else
                                        <p class="text-sm text-rose-500">No se ha subido ningún archivo.</p>
                                    @endif

                                    @error('acta_nacimiento_archivo')
                                        <p class="text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- CERTIFICADO DE ESTUDIOS --}}
                        <div x-data="{
                            subiendo: false,
                            terminado: false,
                            progreso: 0,
                            vistaPrevia: null,
                            nombreTemporal: '',
                        
                            cambiarArchivo(evento) {
                                const archivo = evento.target.files[0];
                        
                                if (this.vistaPrevia) {
                                    URL.revokeObjectURL(this.vistaPrevia);
                                    this.vistaPrevia = null;
                                }
                        
                                if (archivo) {
                                    this.vistaPrevia = URL.createObjectURL(archivo);
                                    this.nombreTemporal = archivo.name;
                                } else {
                                    this.nombreTemporal = '';
                                }
                            },
                        
                            limpiarVistaPrevia() {
                                if (this.vistaPrevia) {
                                    URL.revokeObjectURL(this.vistaPrevia);
                                    this.vistaPrevia = null;
                                }
                                this.nombreTemporal = '';
                            },
                        
                            inicioSubida() {
                                this.subiendo = true;
                                this.terminado = false;
                                this.progreso = 0;
                            },
                        
                            progresoSubida(evento) {
                                this.progreso = evento.detail.progress;
                            },
                        
                            finSubida() {
                                this.progreso = 100;
                                this.terminado = true;
                        
                                setTimeout(() => {
                                    this.subiendo = false;
                                    this.terminado = false;
                                    this.progreso = 0;
                                }, 1000);
                            },
                        
                            errorSubida() {
                                this.subiendo = false;
                                this.terminado = false;
                                this.progreso = 0;
                            }
                        }" x-on:livewire-upload-start="inicioSubida()"
                            x-on:livewire-upload-progress="progresoSubida($event)"
                            x-on:livewire-upload-finish="finSubida()" x-on:livewire-upload-error="errorSubida()"
                            class="rounded-2xl border border-zinc-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
                            <div class="h-1 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500">
                            </div>

                            <div class="p-5">
                                <div class="mb-5 flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-300 dark:ring-indigo-400/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z" />
                                            </svg>
                                        </div>

                                        <div>
                                            <h3
                                                class="text-lg sm:text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                                                CERTIFICADO DE ESTUDIOS <span
                                                    class="text-sm font-medium text-zinc-500">(PDF)</span>
                                            </h3>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                                Sube, previsualiza, reemplaza o elimina el documento.
                                            </p>
                                        </div>
                                    </div>

                                    @if ($certificado_estudios_guardado)
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            Guardado
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-sm font-medium text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300">
                                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                            Sin guardar
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    <div class="flex flex-wrap gap-3">
                                        <label
                                            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm sm:text-base font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                            <span>＋</span>
                                            {{ $certificado_estudios_guardado ? 'Reemplazar Certificado de Estudios' : 'Subir Certificado de Estudios' }}
                                            <input type="file" wire:model="certificado_estudios_archivo"
                                                accept="application/pdf" class="hidden"
                                                @change="cambiarArchivo($event)">
                                        </label>

                                        <template x-if="vistaPrevia">
                                            <button type="button"
                                                @click="$dispatch('abrir-vista-previa', {
                                                    url: vistaPrevia,
                                                    titulo: 'Vista previa de Certificado seleccionado',
                                                    nombre: nombreTemporal,
                                                    tipo: 'Certificado de estudios',
                                                    estado: 'Seleccionado'
                                                })"
                                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-sky-300 dark:border-sky-700 bg-sky-50 dark:bg-sky-900/20 px-5 py-3 text-sm sm:text-base font-medium text-sky-700 dark:text-sky-300 transition hover:bg-sky-100 dark:hover:bg-sky-900/30">
                                                <span>👁</span>
                                                Vista previa
                                            </button>
                                        </template>

                                        @if ($certificado_estudios_guardado)
                                            <button type="button"
                                                @click="$dispatch('abrir-vista-previa', {
                                                    url: '{{ $this->obtenerUrlDocumento($certificado_estudios_guardado) }}',
                                                    titulo: 'Vista previa de Certificado guardado',
                                                    nombre: '{{ $this->obtenerNombreArchivo($certificado_estudios_guardado) }}',
                                                    tipo: 'Certificado de estudios',
                                                    estado: 'Guardado'
                                                })"
                                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-5 py-3 text-sm sm:text-base font-medium text-emerald-700 dark:text-emerald-300 transition hover:bg-zinc-50 dark:hover:bg-neutral-800">
                                                <span>👁</span>
                                                Ver guardado
                                            </button>

                                            <button type="button"
                                                wire:click="eliminarDocumento('certificado_estudios')"
                                                @click="
                                                    limpiarVistaPrevia();
                                                    $dispatch('cerrar-vista-previa')
                                                "
                                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-5 py-3 text-sm sm:text-base font-medium text-rose-600 dark:text-rose-300 transition hover:bg-rose-50 dark:hover:bg-rose-500/10">
                                                <span>🗑</span>
                                                Eliminar
                                            </button>
                                        @endif
                                    </div>

                                    <div x-show="subiendo || terminado" x-transition class="space-y-2">
                                        <div
                                            class="flex items-center justify-between text-xs sm:text-sm text-zinc-600 dark:text-zinc-300">
                                            <span
                                                x-text="terminado ? 'Carga completada' : 'Subiendo archivo...'"></span>
                                            <span x-text="progreso + '%'"></span>
                                        </div>

                                        <div
                                            class="h-3 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-neutral-800">
                                            <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-indigo-600 transition-all duration-300"
                                                :style="`width: ${progreso}%`"></div>
                                        </div>
                                    </div>

                                    <div wire:loading wire:target="certificado_estudios_archivo"
                                        class="flex items-center gap-2 rounded-lg border border-zinc-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 py-2">
                                        <svg class="h-5 w-5 animate-spin text-blue-600 dark:text-blue-400"
                                            viewBox="0 0 24 24" fill="none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z">
                                            </path>
                                        </svg>
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">Cargando
                                            certificado…</span>
                                    </div>

                                    @if ($certificado_estudios_archivo)
                                        <div
                                            class="rounded-xl border border-sky-200 dark:border-sky-500/20 bg-sky-50 dark:bg-sky-500/10 p-4">
                                            <p class="text-sm text-zinc-700 dark:text-zinc-200">
                                                <span class="font-semibold">Archivo:</span>
                                                {{ $certificado_estudios_archivo->getClientOriginalName() }}
                                            </p>
                                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                                                <span class="font-semibold">Tamaño:</span>
                                                {{ number_format($certificado_estudios_archivo->getSize() / 1024, 1) }}
                                                KB
                                            </p>

                                            <div
                                                class="pt-4 border-t border-zinc-200 dark:border-neutral-800 mt-4 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                                                <flux:button type="button"
                                                    wire:click="$set('certificado_estudios_archivo', null)"
                                                    @click="
                                                        limpiarVistaPrevia();
                                                        $dispatch('cerrar-vista-previa')
                                                    "
                                                    class="cursor-pointer">
                                                    Cancelar
                                                </flux:button>

                                                <flux:button variant="primary" type="button"
                                                    wire:click="guardarDocumento('certificado_estudios')"
                                                    class="w-full sm:w-auto cursor-pointer"
                                                    wire:loading.attr="disabled" wire:target="guardarDocumento">
                                                    Guardar Certificado
                                                </flux:button>
                                            </div>
                                        </div>
                                    @elseif ($certificado_estudios_guardado)
                                        <div
                                            class="rounded-xl border border-zinc-200 dark:border-neutral-800 bg-zinc-50 dark:bg-neutral-800/40 p-4">
                                            <p class="text-sm text-zinc-700 dark:text-zinc-300">
                                                <span class="font-semibold">Archivo:</span>
                                                {{ $this->obtenerNombreArchivo($certificado_estudios_guardado) }}
                                            </p>
                                        </div>
                                    @else
                                        <p class="text-sm text-rose-500">No se ha subido ningún archivo.</p>
                                    @endif

                                    @error('certificado_estudios_archivo')
                                        <p class="text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Columna derecha --}}
                <div class="xl:col-span-5 2xl:col-span-6">
                    <div
                        class="h-full rounded-2xl border border-zinc-200 dark:border-neutral-800 bg-zinc-50 dark:bg-neutral-900/60 overflow-hidden shadow-sm">
                        <div
                            class="flex items-center justify-between gap-3 border-b border-zinc-200 dark:border-neutral-800 px-4 py-3 bg-white dark:bg-neutral-900">
                            <div>
                                <h3 class="text-sm sm:text-base font-semibold text-zinc-900 dark:text-zinc-100"
                                    x-text="tituloVistaPrevia"></h3>
                                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                                    Previsualización del documento seleccionado
                                </p>
                            </div>

                            <button type="button" x-show="panelVistaPrevia" @click="cerrarVistaPrevia()"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-full text-zinc-500 hover:text-zinc-800 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-neutral-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div
                            class="border-b border-zinc-200 dark:border-neutral-800 bg-white/80 dark:bg-neutral-900/80 px-4 py-3 space-y-2">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                                <div
                                    class="rounded-xl border border-zinc-200 dark:border-neutral-800 bg-zinc-50 dark:bg-neutral-800/40 px-3 py-2">
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Documento</p>
                                    <p class="font-medium text-zinc-800 dark:text-zinc-100"
                                        x-text="tipoDocumentoVistaPrevia || '—'"></p>
                                </div>

                                <div
                                    class="rounded-xl border border-zinc-200 dark:border-neutral-800 bg-zinc-50 dark:bg-neutral-800/40 px-3 py-2">
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Estado</p>
                                    <p class="font-medium text-zinc-800 dark:text-zinc-100"
                                        x-text="estadoVistaPrevia || '—'"></p>
                                </div>

                                <div
                                    class="rounded-xl border border-zinc-200 dark:border-neutral-800 bg-zinc-50 dark:bg-neutral-800/40 px-3 py-2">
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Archivo</p>
                                    <p class="font-medium text-zinc-800 dark:text-zinc-100 truncate"
                                        x-text="nombreArchivoVistaPrevia || '—'"></p>
                                </div>
                            </div>
                        </div>

                        <div class="h-[calc(75vh-148px)] bg-zinc-100 dark:bg-neutral-800">
                            <template x-if="panelVistaPrevia && urlVistaPrevia">
                                <iframe :src="urlVistaPrevia" class="h-full w-full bg-white"
                                    frameborder="0"></iframe>
                            </template>

                            <template x-if="!panelVistaPrevia || !urlVistaPrevia">
                                <div class="flex h-full items-center justify-center p-8">
                                    <div class="text-center">
                                        <div
                                            class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-zinc-200 dark:bg-neutral-700 text-zinc-500 dark:text-zinc-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z" />
                                            </svg>
                                        </div>

                                        <h4 class="text-base font-semibold text-zinc-800 dark:text-zinc-100">
                                            Sin vista previa
                                        </h4>
                                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                            Selecciona un archivo o presiona “Ver guardado”.
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div
                class="pt-5 mt-5 border-t border-zinc-200 dark:border-neutral-800 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <flux:button @click="show = false; loading = false; cerrarVistaPrevia(); $wire.cerrarModal()"
                    type="button" class="cursor-pointer">
                    Cerrar
                </flux:button>
            </div>
        </div>

        {{-- Loader interno del modal --}}
        <div x-show="loading"
            class="absolute inset-0 z-20 flex items-center justify-center bg-white/70 dark:bg-neutral-900/70 backdrop-blur rounded-2xl">
            <div
                class="flex items-center gap-3 rounded-xl bg-white dark:bg-neutral-900 px-4 py-3 ring-1 ring-neutral-200 dark:ring-neutral-800 shadow">
                <svg class="h-5 w-5 animate-spin text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="none"
                    aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <span class="text-sm text-neutral-800 dark:text-neutral-200">Cargando…</span>
            </div>
        </div>
    </div>
</div>
