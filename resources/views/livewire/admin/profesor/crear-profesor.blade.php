<div>
    <!-- Header -->
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Profesor</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Formulario para crear un nuevo profesor.</p>
    </div>

    <div x-data="{ open: false }" class="my-4">
        <!-- Toggle -->
        <button
            type="button"
            @click="open = !open"
            :aria-expanded="open"
            aria-controls="panel-crear-profesor"
            class="group inline-flex items-center gap-2 rounded-2xl px-4 py-2.5
                   bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400
                   dark:focus:ring-offset-neutral-900"
        >
            <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-white/15">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M5 19h4l10-10-4-4L5 15v4m14.7-11.3a1 1 0 000-1.4l-2-2a1 1 0 00-1.4 0l-1.6 1.6 3.4 3.4 1.6-1.6z"/>
                </svg>
            </span>
            <span class="font-medium">{{ __('Nuevo profesor') }}</span>
            <span class="ml-1 transition-transform duration-200" :class="open ? 'rotate-180' : 'rotate-0'">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 15.5l-6-6h12l-6 6z"/>
                </svg>
            </span>
        </button>

        <!-- Panel -->
        <div
            id="panel-crear-profesor"
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-1 scale-[0.98]"
            class="relative mt-4"
        >
            <form wire:submit.prevent="crearProfesor" class="group">
                <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-lg overflow-hidden">
                    <div class="degradado"></div>

                    <div class="p-5 sm:p-6 lg:p-8">

                        <flux:field>
                        <!-- Grid de campos -->
                        <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4">

                           <!-- Uploader con drag & drop + preview con modal -->
                            <div
                            x-data="{
                                open:false,
                                onDrop(e){
                                e.preventDefault();
                                const files = e.dataTransfer.files;
                                if (!files || !files.length) return;
                                $refs.fileInput.files = files;
                                $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            }"
                            x-on:keydown.escape.window="open=false"
                            class="rounded-2xl border border-dashed border-gray-300 dark:border-neutral-700 p-5 sm:p-6 bg-white dark:bg-neutral-900"
                            >

                            <!-- Zona Drag & Drop -->
                            <div
                                class="group relative flex flex-col items-center justify-center gap-3 rounded-xl border border-dashed border-gray-300 dark:border-neutral-700 bg-gradient-to-br from-gray-50 to-white dark:from-neutral-900 dark:to-neutral-950 p-6 text-center cursor-pointer hover:border-blue-400/70 hover:from-blue-50 hover:to-white dark:hover:border-blue-500/40 transition"
                                @click="$refs.fileInput.click()"
                                @dragover.prevent
                                @dragenter.prevent
                                @drop="onDrop"
                            >
                                <div class="inline-flex size-12 items-center justify-center rounded-full ring-1 ring-gray-200/70 dark:ring-neutral-700/70 shadow-sm">
                                <!-- Ícono -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 15v4a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-4"/>
                                    <path d="M12 3v12m0 0 4-4m-4 4-4-4" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                </div>

                                <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">Sube la foto del profesor</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Arrastra y suelta la imagen aquí o
                                    <span class="text-blue-600 dark:text-blue-400 underline underline-offset-4">haz clic para seleccionar</span>
                                </p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recomendado: hasta 1MB (JPG o PNG)</p>
                                </div>
                            </div>

                            <!-- Input oculto (Livewire) -->
                            <input
                                x-ref="fileInput"
                                type="file"
                                class="hidden"
                                wire:model.live="foto"
                                accept="image/jpeg,image/jpg,image/png"
                            >

                            <!-- Loader -->
                            <div wire:loading wire:target="foto" class="mt-3 flex items-center gap-2 text-blue-600 dark:text-blue-400">
                                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Cargando imagen…</span>
                            </div>

                            @if ($foto)
                                <!-- Tarjeta de vista previa -->
                                <div class="mt-5 flex items-center justify-between gap-4 rounded-xl border border-gray-200 dark:border-neutral-800 p-3">
                                <div class="flex items-center gap-3">
                                    <button
                                    type="button"
                                    class="relative block"
                                    @click="open=true"
                                    aria-label="Ampliar vista previa"
                                    >
                                    <img
                                        src="{{ $foto->temporaryUrl() }}"
                                        alt="{{ __('Vista previa de la foto') }}"
                                        class="h-16 w-16 sm:h-20 sm:w-20 rounded-full object-cover ring-1 ring-gray-200 dark:ring-neutral-700 hover:scale-[1.02] transition"
                                    >
                                    <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 text-[10px] px-2 py-0.5 rounded-full bg-gray-900 text-white dark:bg-white dark:text-gray-900">
                                        Vista previa
                                    </span>
                                    </button>

                                    <div class="text-sm">
                                    <p class="font-medium text-gray-800 dark:text-gray-200">Archivo listo</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Haz clic en la miniatura para ampliarla</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button
                                    type="button"
                                    wire:click="$set('foto', null)"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 dark:border-neutral-700 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-neutral-800 transition"
                                    >
                                    Quitar
                                    </button>
                                    <button
                                    type="button"
                                    @click="open=true"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 transition"
                                    >
                                    Ampliar
                                    </button>
                                </div>
                                </div>

                                <!-- Modal con fondo difuminado y zoom -->
                                <div
                                x-show="open"
                                x-transition.opacity
                                class="fixed inset-0 z-50"
                                aria-modal="true"
                                role="dialog"
                                >
                                <!-- Overlay con blur -->
                                <div
                                    class="absolute inset-0 bg-neutral-900/70 backdrop-blur-sm"
                                    @click="open=false"
                                ></div>

                                <!-- Contenido -->
                                <div class="relative z-10 flex min-h-full items-center justify-center p-4">
                                    <div
                                    x-show="open"
                                    x-transition
                                    class="relative max-w-[92vw] sm:max-w-[85vw] md:max-w-[70vw]"
                                    >
                                    <img
                                        src="{{ $foto->temporaryUrl() }}"
                                        alt="{{ __('Foto ampliada') }}"
                                        class="max-h-[80vh] max-w-full rounded-2xl shadow-2xl ring-1 ring-white/10 object-contain"
                                    >

                                    <!-- Cerrar -->
                                    <button
                                        type="button"
                                        @click="open=false"
                                        class="absolute -top-3 -right-3 inline-flex items-center justify-center rounded-full bg-white/90 dark:bg-neutral-900/90 text-gray-800 dark:text-gray-200 shadow-lg ring-1 ring-black/10 backdrop-blur px-2 py-2 hover:scale-105 transition"
                                        aria-label="Cerrar"
                                        title="Cerrar"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    </div>
                                </div>
                                </div>
                            @endif
                            </div>


                            <!-- Columna 2: con espacio vertical -->
                            <div class="space-y-4">
                            <flux:select badge="Requerido" label="Usuario" wire:model.live="user_id">
                                <option value="">--Seleccione un usuario--</option>
                                @foreach($usuarios as $key => $usuario)
                                <option value="{{ $usuario->id }}">{{ $key+1 }}.- {{ $usuario->username }} => {{ $usuario->email }}</option>
                                @endforeach
                            </flux:select>

                            <flux:input type="text" badge="Requerido" label="Nombre" placeholder="Nombre" wire:model.live="nombre" />
                            <flux:input type="text" badge="Opcional" label="Apellido Materno" placeholder="Apellido Materno" wire:model="apellido_materno" />

                            <flux:input type="text" badge="Opcional" label="Perfil" placeholder="Perfil" wire:model="perfil" />
                            </div>

                            <!-- Columna 3: con espacio vertical -->
                            <div class="space-y-4">
                            <flux:input
                                type="text"
                                label="CURP"
                                placeholder="CURP"
                                badge="Requerido"
                                wire:model.live="CURP"
                                value="{{ $CURP }}"
                            />

                            <div wire:loading wire:target="CURP" class="md:col-span-2 flex items-center gap-2 text-blue-600">
                                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Cargando datos del CURP…</span>
                            </div>


                             <flux:input type="text" badge="Requerido" label="Apellido Paterno" placeholder="Apellido Paterno" wire:model="apellido_paterno" />
                            <flux:input type="text" badge="Opcional" label="Teléfono" placeholder="Teléfono" wire:model="telefono" />
                            <flux:input type="color" badge="Opcional" label="Color" placeholder="Color" wire:model="color" />
                            </div>

                        </div>
                        </flux:field>

                        <!-- Acciones abajo de los inputs -->
                        <div class="mt-6 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2">
                            <flux:button
                        variant="primary"
                        type="button"
                        class="cancelar-btn"
                            @click="open=false"
                        >
                        Cancelar
                        </flux:button>

                            <flux:button variant="primary" type="submit" class="w-full sm:w-auto cursor-pointer guardar-btn">
                                {{ __('Guardar') }}
                            </flux:button>
                        </div>

                    </div>

                    <!-- Loader overlay -->


                     <div
                    class="absolute inset-0 rounded-2xl bg-white/70 dark:bg-neutral-900/60 backdrop-blur-[2px] flex items-center justify-center z-10"
                    wire:loading
                    wire:target="crearCuatrimestre"
                    >
                    <span class="inline-flex items-center gap-3 px-4 py-2 rounded-xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-800 shadow">
                        <span class="w-6 h-6 rounded-full border-2 border-neutral-300 dark:border-neutral-600 border-t-transparent animate-spin"></span>
                        <span class="text-sm font-medium text-neutral-800 dark:text-neutral-100">Procesando…</span>
                    </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
