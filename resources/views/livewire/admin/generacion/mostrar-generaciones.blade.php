<div
    x-data="{
        destroyGeneracion(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `Esta acción no podrá revertirse.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563EB',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminar'
            }).then((r) => r.isConfirmed && @this.call('eliminarGeneracion', id))
        },
    }"
    class="space-y-5"
>
    <!-- Encabezado -->
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Generaciones</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Busca, edita o elimina generaciones.</p>
    </div>


    <!-- Busqueda -->
    <div
        class="flex flex-col md:flex-row md:items-center gap-3 md:gap-4 rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white/70 dark:bg-neutral-900/60 p-3 md:p-4 shadow-sm"
    >
        <div class="w-full ">
            <label for="buscar-generacion" class="sr-only">Buscar Generación</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none">
                        <path stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                              d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                    </svg>
                </div>
                <flux:input
                    id="buscar-generacion"
                    type="text"
                    wire:model.live="search"
                    placeholder="Buscar generación..."
                    class="pl-10 w-full"
                />
            </div>
        </div>

    </div>



            <div class="relative">
                <!-- Loader overlay -->
                <div
                    wire:loading.delay
                    wire:target="search, eliminarCuatrimestre"
                    class="absolute inset-0 z-10 grid place-items-center rounded-xl bg-white/70 dark:bg-neutral-900/70 backdrop-blur"
                    aria-live="polite"
                    aria-busy="true"
                >
                    <div class="flex items-center gap-3 rounded-xl bg-white dark:bg-neutral-900 px-4 py-3 ring-1 ring-gray-200 dark:ring-neutral-800 shadow">
                        <svg class="h-5 w-5 animate-spin text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <span class="text-sm text-gray-700 dark:text-gray-200">Cargando…</span>
                    </div>
                </div>

                <!-- WRAPPER que se desenfoca mientras se busca -->
                <div
                    class="transition ease-out duration-200"
                    wire:loading.class="blur-sm opacity-80 pointer-events-none"
                    wire:target="search, eliminarGeneracion"
                >
                    <!-- Tabla (desktop) -->
                    <div class="overflow-hidden  rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                        <div class="overflow-x-auto max-h-[70vh]">
                            <table class="w-full text-sm ">
                                <thead>
                                    <tr>

                                        <th class="px-4 py-3 text-center font-semibold">#</th>
                                        <th class="px-4 py-3 text-center font-semibold">Generacion</th>
                                        <th class="px-4 py-3 text-center font-semibold">Status</th>
                                        <th class="px-4 py-3 text-center font-semibold">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                                    @forelse($generaciones as $key => $generacion)
                                        <tr class="transition-colors hover:bg-gray-50/70 dark:hover:bg-neutral-800/50 }}">

                                            <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">{{ $key + 1 }}</td>
                                            <td class="px-4 py-3 text-center text-gray-900 dark:text-white">{{ $generacion->generacion }}</td>
                                            <td class="px-4 py-3 text-center text-gray-900 dark:text-white">
                                                @if($generacion->status === 'true')
                                                    <x-badge>Activo</x-badge>
                                                @else
                                                    <x-badge color="red">Inactivo</x-badge>
                                                @endif

                                            </td>

                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-center gap-2">


                                                            <flux:button
                                                            variant="primary"
                                                            class="cursor-pointer bg-amber-500 hover:bg-amber-600 text-white"
                                                            @click="$dispatch('abrir-modal-editar');
                                                                Livewire.dispatch('editarModal', { id: {{ $generacion->id }} });
                                                            "
                                                            >
                                                           <flux:icon.square-pen class="w-3.5 h-3.5" />
                                                            <!-- ícono -->
                                                            </flux:button>

                                                     <flux:button
                                                            variant="danger"
                                                            class="cursor-pointer bg-rose-600 hover:bg-rose-700 text-white p-1"
                                                              @click="destroyGeneracion({{ $generacion->id }}, '{{ $generacion->generacion }}')"
                                                        >
                                                            <flux:icon.trash-2 class="w-3.5 h-3.5" />
                                                        </flux:button>

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                                <div class="mx-auto w-full max-w-md">
                                                    <div class="rounded-2xl border border-dashed border-gray-300 dark:border-neutral-700 p-6">
                                                        <div class="mb-1 text-base font-semibold">No hay generaciones disponibles</div>
                                                        <p class="text-sm">Ajusta tu búsqueda o crea un nuevo registro.</p>
                                                    </div>
                                                </div>
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
                {{ $generaciones->links() }}
            </div>



    <!-- Modal editar -->
    <livewire:admin.generacion.editar-generacion />
</div>
