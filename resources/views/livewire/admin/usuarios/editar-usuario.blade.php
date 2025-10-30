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
      // limpia los props en Livewire después de iniciar el cierre
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


    <div
        class="relative w-[92vw] sm:w-[88vw] md:w-[70vw] max-w-2xl mx-4 sm:mx-6 bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
        role="dialog" aria-modal="true" aria-labelledby="titulo-modal-cuatrimestre"
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        wire:ignore.self
    >

    <div class="degradado"></div>

    <!-- Header -->
    <div class="px-4 sm:px-6 py-4 flex items-center justify-between border-b border-neutral-200 dark:border-neutral-700">
      <h2 id="editar-usuario-title" class="text-base sm:text-lg font-semibold text-neutral-900 dark:text-neutral-100">
        Editar Usuario
        <flux:badge color="indigo" class="ml-1 align-middle">{{ $username }}</flux:badge>
      </h2>

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

        <form
        x-on:submit="loading = true"
        wire:submit.prevent="actualizarUsuario"
        class="px-5 sm:px-6 pb-5"
        >
      <flux:field>
        <div class="px-4 sm:px-6 py-5 space-y-6">

          <!-- Campos -->
          <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">
            <div>
              <flux:input
                wire:model.live="username"
                badge="Requerido"
                :label="__('Nombre de usuario')"
                type="text"
                placeholder="Nombre de usuario"
                autocomplete="username"
              />
              {{-- @error('username')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror --}}
            </div>



            <div>
              <flux:input
                wire:model.live="email"
                badge="Requerido"
                :label="__('Email')"
                type="email"
                placeholder="correo@ejemplo.com"
                autocomplete="email"
              />
              {{-- @error('email')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror --}}
            </div>
          </div>

          <!-- Status -->
          <div class="space-y-2">


            @if($rol_name !== "Admin")
             <flux:label>Status</flux:label>
              <flux:switch wire:model.live="status" />
            @endif
          </div>

           @if($rol_name !== "Admin")
          <!-- Roles -->
          <div class="space-y-2">
            <flux:checkbox.group wire:model.live="rol" badge="Requerido" label="Listado de roles">
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-2">
                    @foreach ($roles->where('name', '!=', 'Admin') as $rolItem)
                        <label class="flex items-center gap-2 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-neutral-50/60 dark:bg-neutral-900/30 px-3 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800 cursor-pointer">
                            <flux:checkbox value="{{ $rolItem->id }}" />
                            <span class="text-sm text-neutral-800 dark:text-neutral-100">{{ $rolItem->name }}</span>
                        </label>
                    @endforeach
                </div>
            </flux:checkbox.group>

          </div>

          @endif
          <!-- Acciones -->
          <div class="mt-2 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2">


             <flux:button
                  variant="primary"
                  type="button"
                  class="cancelar-btn"
                    @click="show = false; $wire.cerrarModal()"
                >
                  Cancelar
                </flux:button>

            <flux:button
              variant="primary"
              type="submit"
              class="cursor-pointer guardar-btn "
              wire:loading.attr="disabled"
              wire:target="actualizarUsuario"
            >
              <span wire:loading.remove wire:target="actualizarUsuario">{{ __('Actualizar') }}</span>
              <span wire:loading wire:target="actualizarUsuario" class="inline-flex items-center gap-2">
                <span class="w-4 h-4 rounded-full border-2 border-white/70 border-t-transparent animate-spin"></span>
                Guardando…
              </span>
            </flux:button>
          </div>
        </div>
      </flux:field>


        <!-- Loader interno -->
             <div x-show="loading"
                    class="absolute inset-0 z-20 flex items-center justify-center bg-white/70 dark:bg-neutral-900/70 backdrop-blur rounded-2xl">
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
