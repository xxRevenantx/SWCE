<div
    x-data ="{
        destroyLicenciatura(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `Esta acción no podrá revertirse`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563EB',
                cancelButtonColor: '#EF4444',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('eliminarLicenciatura', id);
                }
            })
        }
    }"
    class="space-y-4"
>
    <!-- Header -->
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Buscar Licenciatura</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Administra y edita las licenciaturas registradas.</p>
    </div>

    <!-- Busqueda -->
    <div
        class="flex flex-col md:flex-row md:items-center gap-3 md:gap-4 rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white/70 dark:bg-neutral-900/60 p-3 md:p-4 shadow-sm"
    >
        <div class="w-full ">
            <label for="buscar-lic" class="sr-only">Buscar Licenciatura</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none">
                        <path stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                              d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                    </svg>
                </div>
                <flux:input
                    id="buscar-lic"
                    type="text"
                    wire:model.live="search"
                    placeholder="Buscar por nombre, nombre corto o RVOE…"
                    class="pl-10 w-full"
                />
            </div>
        </div>

    </div>

    <!-- List / Table container -->
    <div class="relative">
        <!-- Loader -->

         <div
                    wire:loading.delay
                   wire:target="search"
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


        <!-- Desktop table -->
                    <div
                    class="transition ease-out duration-200"
                    wire:loading.class="blur-sm opacity-80 pointer-events-none"
                    wire:target="search"
                >
        <div class="overflow-hidden  rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                   <thead>
                        <tr>
                            <th class="px-4 py-3 text-center  font-semibold uppercase">#</th>
                            <th class="px-4 py-3 text-center  font-semibold uppercase">Logo</th>
                            <th class="px-4 py-3 text-left  font-semibold uppercase">Nombre de la Licenciatura</th>
                            <th class="px-4 py-3 text-left  font-semibold uppercase">Nombre corto</th>
                            <th class="px-4 py-3 text-left  font-semibold uppercase">RVOE</th>
                            <th class="px-4 py-3 text-center  font-semibold uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                        @if($licenciaturas->isEmpty())
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="mx-auto w-full max-w-md">
                                        <div class="rounded-2xl border border-dashed border-gray-300 dark:border-neutral-700 p-6">
                                            <div class="mb-2 font-semibold">No hay licenciaturas</div>
                                            <p class="text-sm">Intenta ajustar tu búsqueda o crea una nueva licenciatura.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($licenciaturas as $key => $licenciatura)
                                <tr class="hover:bg-gray-50/60 dark:hover:bg-neutral-800/40">
                                    <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-200">{{ $key+1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center">
                                            @if ($licenciatura->logo)
                                                <img src="{{ asset('storage/licenciaturas/' . $licenciatura->logo) }}"
                                                     alt="{{ $licenciatura->nombre }}"
                                                     class="h-10 w-10 rounded-lg object-cover ring-1 ring-gray-200 dark:ring-neutral-700">
                                            @else
                                                <img src="{{ asset('imagenes_publicas/logo-letra.png') }}"
                                                     alt="Default"
                                                     class="h-10 w-10 rounded-lg object-cover ring-1 ring-gray-200 dark:ring-neutral-700">
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $licenciatura->nombre }}</div>
                                        @if($licenciatura->slug)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">/{{ $licenciatura->slug }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-800 dark:text-gray-200">{{ $licenciatura->nombre_corto }}</td>
                                    <td class="px-4 py-3">
                                        @if($licenciatura->RVOE)
                                            <span class="inline-flex items-center rounded-full border border-emerald-300/60 bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300 dark:border-emerald-700/50">
                                                RVOE: {{ $licenciatura->RVOE }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full border border-gray-300/70 bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-neutral-800/60 dark:text-gray-300 dark:border-neutral-700">
                                                Sin RVOE
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1">
                                             <flux:button
                                                        variant="primary"
                                                        class="cursor-pointer bg-amber-500 hover:bg-amber-600 text-white"
                                                        @click="$dispatch('abrir-modal-editar');
                                                         Livewire.dispatch('editarModal', { id: {{ $licenciatura->id }} }); ">
                                                        <flux:icon.square-pen class="w-3.5 h-3.5" />
                                                            <!-- ícono -->
                                                </flux:button>

                                            <flux:button
                                                variant="danger"
                                                class="cursor-pointer bg-rose-600 hover:bg-rose-700 text-white p-1"
                                                @click="destroyLicenciatura({{ $licenciatura->id }}, '{{ $licenciatura->nombre }}')">
                                                 <flux:icon.trash-2 class="w-3.5 h-3.5" />
                                            </flux:button>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        </div>


    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $licenciaturas->links() }}
    </div>

    <!-- Modal editar -->
    <livewire:admin.licenciatura.editar-licenciatura />
</div>


