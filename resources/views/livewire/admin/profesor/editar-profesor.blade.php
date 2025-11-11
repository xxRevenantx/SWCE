<div
  x-data="{ show: false, loading: false }"
  x-cloak
  x-trap.noscroll="show"
  x-show="show"
  @abrir-modal-editar.window="show = true; loading = true"
  @editar-cargado.window="loading = false"
  @cerrar-modal-editar.window="
      show = false;
      loading = false;
      $wire.cerrarModal()
  "
  @keydown.escape.window="show = false; $wire.cerrarModal()"
  class="fixed inset-0 z-50 flex items-center justify-center"
  aria-live="polite"
>
  <!-- Overlay -->
  <div class="absolute inset-0 bg-neutral-900/70 backdrop-blur-sm"
       x-show="show" x-transition.opacity
       @click.self="show = false; $wire.cerrarModal()"></div>

  <!-- Modal -->
  <div
      class="relative w-[92vw] sm:w-[88vw] md:w-[90vw] max-w-2xl mx-4 sm:mx-6 bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
      role="dialog" aria-modal="true" aria-labelledby="titulo-modal-generacion"
      x-show="show"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 scale-95 translate-y-2"
      x-transition:enter-end="opacity-100 scale-100 translate-y-0"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100 scale-100 translate-y-0"
      x-transition:leave-end="opacity-0 scale-95 translate-y-2"
      wire:ignore.self
  >
    <!-- Top accent -->
    <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500"></div>

    <!-- Header -->
    <div class="px-5 sm:px-6 pt-4 pb-3 flex items-start justify-between gap-3">
      <div class="min-w-0">
        <h2 id="titulo-modal-generacion" class="text-xl sm:text-2xl font-bold text-neutral-900 dark:text-white">
          Editar Profesor
        </h2>
        <p class="text-sm text-neutral-600 dark:text-neutral-400">
          <span class="inline-flex items-center gap-2">
            <flux:badge color="indigo">{{ $nombre }} {{ $apellido_paterno }} {{ $apellido_materno }}</flux:badge>
          </span>
        </p>
      </div>

      <button
        @click="show = false; $wire.cerrarModal()"
        type="button"
        class="inline-flex h-9 w-9 items-center justify-center rounded-full text-zinc-500 hover:text-zinc-800 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-neutral-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
        aria-label="Cerrar"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <!-- Body -->
    <form wire:submit.prevent="actualizarProfesor" class="px-5 sm:px-6 pb-5 relative">
      <div>
        <flux:field>
          <!-- Grid de campos -->
          <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
            <!-- Uploader con drag & drop + preview -->
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
                wire:model.live="foto_nueva" {{-- IMPORTANT: usar foto_nueva --}}
                accept="image/jpeg,image/jpg,image/png"
              >

              <!-- Loader -->
              <div wire:loading wire:target="foto_nueva" class="mt-3 flex items-center gap-2 text-blue-600 dark:text-blue-400">
                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Cargando imagen…</span>
              </div>

              @if ($foto_nueva)
                {{-- PREVIEW: nueva subida (temporal) --}}
                <div class="mt-5 flex items-center justify-between gap-4 rounded-xl border border-gray-200 dark:border-neutral-800 p-3">
                  <div class="flex items-center gap-3">
                    <button type="button" class="relative block" @click="open=true" aria-label="Ampliar vista previa">
                      <img
                        src="{{ $foto_nueva->temporaryUrl() }}"
                        alt="Vista previa de la foto"
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
                      wire:click="$set('foto_nueva', null)"
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

                <!-- Modal de ampliación -->
                <div x-show="open" x-transition.opacity class="fixed inset-0 z-50" aria-modal="true" role="dialog">
                  <div class="absolute inset-0 bg-neutral-900/70 backdrop-blur-sm" @click="open=false"></div>
                  <div class="relative z-10 flex min-h-full items-center justify-center p-4">
                    <div x-show="open" x-transition class="relative max-w-[92vw] sm:max-w-[85vw] md:max-w-[70vw]">
                      <img
                        src="{{ $foto_nueva->temporaryUrl() }}"
                        alt="Foto ampliada"
                        class="max-h-[80vh] max-w-full rounded-2xl shadow-2xl ring-1 ring-white/10 object-contain"
                      >
                      <button
                        type="button"
                        @click="open=false"
                        class="absolute -top-3 -right-3 inline-flex items-center justify-center rounded-full bg-white/90 dark:bg-neutral-900/90 text-gray-800 dark:text-gray-200 shadow-lg ring-1 ring-black/10 backdrop-blur px-2 py-2 hover:scale-105 transition"
                        aria-label="Cerrar" title="Cerrar"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              @elseif ($foto)
                {{-- PREVIEW: foto actual desde storage --}}
                @php
                  $fotoUrl = asset('storage/profesores/' . $foto);
                @endphp
                <div class="mt-5 flex items-center gap-3">
                  <img
                    src="{{ $fotoUrl }}"
                    alt="Foto actual"
                    class="h-16 w-16 sm:h-20 sm:w-20 rounded-full object-cover ring-1 ring-gray-200 dark:ring-neutral-700"
                    onerror="this.src='{{ asset('images/avatar-placeholder.png') }}'"
                  >
                  <div class="text-sm text-gray-600 dark:text-gray-300">
                    Foto actual
                  </div>
                </div>
              @endif
            </div>
          </div>

          <!-- Campos del formulario -->
          <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-4">
              <flux:select badge="Requerido" label="Usuario" wire:model.live="user_id">
                <option value="">--Seleccione un usuario--</option>
                @foreach($usuarios as $key => $usuario)
                  <option value="{{ $usuario->id }}">{{ $key+1 }}.- {{ $usuario->username }} => {{ $usuario->email }}</option>
                @endforeach
              </flux:select>

              <flux:input class="uppercase" type="text" badge="Requerido" label="Nombre" placeholder="Nombre" wire:model.live="nombre" />
              <flux:input class="uppercase" type="text" badge="Opcional" label="Apellido Materno" placeholder="Apellido Materno" wire:model="apellido_materno" />
              <flux:input class="uppercase" type="text" badge="Opcional" label="Perfil" placeholder="Perfil" wire:model="perfil" />
            </div>

            <div class="space-y-4">
              <flux:input
                type="text"
                label="CURP"
                placeholder="CURP"
                badge="Requerido"
                wire:model.live="CURP"
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

        <!-- Acciones -->
        <div class="mt-6 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2">
          <flux:button
            variant="primary"
            type="button"
            class="cancelar-btn"
            @click="show = false; $wire.cerrarModal()"
          >
            Cancelar
          </flux:button>

          <flux:button variant="primary" type="submit" class="w-full sm:w-auto cursor-pointer guardar-btn">
            {{ __('Guardar') }}
          </flux:button>
        </div>
      </div>

      <!-- Loader interno -->
      <div
        x-show="loading"
        class="absolute inset-0 z-20 flex items-center justify-center bg-white/70 dark:bg-neutral-900/70 backdrop-blur rounded-2xl"
      >
        <div class="flex items-center gap-3 rounded-xl bg-white dark:bg-neutral-900 px-4 py-3 ring-1 ring-neutral-200 dark:ring-neutral-800 shadow">
          <svg class="h-5 w-5 animate-spin text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
          </svg>
          <span class="text-sm text-neutral-800 dark:text-neutral-200">Cargando…</span>
        </div>
      </div>
    </form>
  </div>
</div>
