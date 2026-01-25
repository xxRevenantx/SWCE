<div x-data="{ show: false, loading: false }" x-cloak x-trap.noscroll="show" x-show="show"
    @abrir-modal-editar.window="show = true; loading = true" @editar-cargado.window="loading = false"
    @cerrar-modal-editar.window="
      show = false;
      loading = false;
      $wire.cerrarModal()
  "
    @keydown.escape.window="show = false; $wire.cerrarModal()" class="fixed inset-0 z-50 flex items-center justify-center"
    aria-live="polite">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-neutral-900/70 backdrop-blur-sm" x-show="show" x-transition.opacity
        @click.self="show = false; $wire.cerrarModal()"></div>


    <!-- Modal (modal-pro) -->
    <div class="relative w-[92vw] sm:w-[88vw] md:w-[70vw] max-w-2xl mx-4 sm:mx-6 bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
        role="dialog" aria-modal="true" aria-labelledby="titulo-modal-generacion" x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2" wire:ignore.self>
        <!-- Top accent -->
        <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500"></div>

        <!-- Header -->
        <div class="px-5 sm:px-6 pt-4 pb-3 flex items-start justify-between gap-3">
            <div class="min-w-0">
                <h2 id="titulo-modal-materia" class="text-xl sm:text-2xl font-bold text-neutral-900 dark:text-white">
                    Editar Materia
                </h2>
                <div class="flex flex-wrap items-center gap-2">
                    <flux:badge color="indigo" size="sm">📘 {{ $nombre ?: '—' }}</flux:badge>
                    <flux:badge color="violet" size="sm">🔑 {{ $clave ?: '—' }}</flux:badge>

                    <flux:badge color="blue" size="sm">🎓 {{ $licenciatura_nombre ?: '—' }}</flux:badge>
                    <flux:badge color="purple" size="sm">🗓️ {{ $cuatrimestre_nombre ?: '—' }}</flux:badge>

                    <flux:badge color="{{ ($calificable ?? '') === 'si' ? 'emerald' : 'zinc' }}" size="sm">
                        ✅ Calificable: {{ ($calificable ?? '') === 'si' ? 'Sí' : 'No' }}
                    </flux:badge>

                    <flux:badge color="amber" size="sm">⭐ {{ is_numeric($creditos) ? $creditos : '—' }} créditos
                    </flux:badge>
                </div>

            </div>

            <button @click="show = false; $wire.cerrarModal()" type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-full text-zinc-500 hover:text-zinc-800 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-neutral-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                aria-label="Cerrar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form wire:submit.prevent="actualizarMateria" class="group">

            <!-- Content -->
            <div class="p-5 sm:p-6 lg:p-8">


                <!-- Grid de inputs -->
                <flux:field>
                    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-4">
                        <flux:input type="text" badge="Requerido" label="Materia"
                            placeholder="Ej. Cálculo Diferencial" wire:model.live="nombre" autofocus />

                        <flux:input type="text" variant="filled" badge="Requerido" label="URL"
                            placeholder="generado-automaticamente" wire:model="slug" />

                        <flux:select badge="Requerido" label="Licenciatura" placeholder="Selecciona una licenciatura"
                            wire:model="licenciatura_id">
                            <flux:select.option value="">{{ __('--Selecciona una licenciatura--') }}
                            </flux:select.option>
                            @foreach ($licenciaturas as $licenciatura)
                                <flux:select.option value="{{ $licenciatura->id }}">{{ $licenciatura->nombre }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select badge="Requerido" label="Cuatrimestre" placeholder="Selecciona un cuatrimestre"
                            wire:model="cuatrimestre_id">
                            <flux:select.option value="">{{ __('--Selecciona un cuatrimestre--') }}
                            </flux:select.option>
                            @foreach ($cuatrimestres as $cuatrimestre)
                                <flux:select.option value="{{ $cuatrimestre->id }}">
                                    {{ $cuatrimestre->nombre_cuatrimestre }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:input type="text" badge="Requerido" label="Clave" placeholder="Ej. MAT-101"
                            wire:model="clave" />

                        <flux:input type="number" min="0" step="1" badge="Requerido" label="Créditos"
                            placeholder="Ej. 8" wire:model="creditos" />

                        <flux:select badge="Requerido" label="Calificable" wire:model="calificable">
                            <flux:select.option value="">{{ __('--Selecciona una opción--') }}
                            </flux:select.option>
                            <flux:select.option value="si">Sí</flux:select.option>
                            <flux:select.option value="no">No</flux:select.option>
                        </flux:select>
                    </div>
                </flux:field>

                <!-- Loader interno -->
                <div x-show="loading"
                    class="absolute inset-0 z-20 flex items-center justify-center bg-white/70 dark:bg-neutral-900/70 backdrop-blur rounded-2xl">
                    <div
                        class="flex items-center gap-3 rounded-xl bg-white dark:bg-neutral-900 px-4 py-3 ring-1 ring-neutral-200 dark:ring-neutral-800 shadow">
                        <svg class="h-5 w-5 animate-spin text-blue-600 dark:text-blue-400" viewBox="0 0 24 24"
                            fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <span class="text-sm text-neutral-800 dark:text-neutral-200">Cargando…</span>
                    </div>
                </div>


                <!-- Acciones (abajo de los inputs) -->
                <div class="mt-6 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2">
                    <button type="button" @click="show = false; $wire.cerrarModal()"
                        class="inline-flex justify-center rounded-xl px-4 py-2.5 border border-neutral-200 dark:border-neutral-700
                                       bg-white dark:bg-neutral-800 text-neutral-700 dark:text-neutral-100
                                       hover:bg-neutral-50 dark:hover:bg-neutral-700
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-300 dark:focus:ring-offset-neutral-900">
                        Cancelar
                    </button>

                    <flux:button variant="primary" type="submit" class="w-full sm:w-auto cursor-pointer guardar-btn"
                        wire:loading.attr="disabled" wire:target="actualizarMateria">
                        {{ __('Guardar') }}
                    </flux:button>
                </div>
            </div>



        </form>
    </div>
