<div class="w-full mx-auto">

    <!-- BARRA SUPERIOR -->
    <div
        class="relative overflow-visible rounded-2xl border border-neutral-200 bg-white shadow-lg dark:border-neutral-700 dark:bg-neutral-800 mb-4">


        <div class="overflow-hidden rounded-t-2xl">
            <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500"></div>
        </div>

        <div class="p-3 sm:p-3">
            <div class="md:flex md:justify-between gap-4">
                <!-- Fecha -->
                <div
                    class="flex items-center gap-2 w-full sm:w-auto justify-center lg:justify-start text-neutral-700 dark:text-neutral-100">
                    <div
                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                        <flux:icon.calendar />
                    </div>
                    <span class="font-medium">{{ now()->translatedFormat('d \d\e F \d\e Y') }}</span>
                </div>

                <!-- Widgets -->
                <div class="w-full sm:w-auto flex flex-col lg:flex-row items-center gap-3 mt-2 sm:mt-0">



                    @if (auth()->user()?->hasRole('Estudiante') && auth()->user()?->change_password === false)
                        <!-- Notificación de seguridad -->
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open"
                                class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl border border-amber-200 bg-amber-50 text-amber-600 shadow-sm transition hover:bg-amber-100 hover:scale-105 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300 dark:hover:bg-amber-500/20">
                                <flux:icon.bell class="w-5 h-5" />

                                <!-- Puntito de alerta -->
                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                        !
                                    </span>
                                </span>
                            </button>

                            <!-- Panel de notificación -->
                            <div x-cloak x-show="open" @click.outside="open = false" x-transition
                                class="absolute right-0 top-full z-[9999] mt-3 w-80 overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">

                                <div
                                    class="bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 px-4 py-3 text-white">
                                    <div class="flex items-center gap-2">
                                        <div class="rounded-lg bg-white/20 p-2">
                                            <flux:icon.shield-exclamation class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold">Notificación de seguridad</p>
                                            <p class="text-xs text-white/90">Protege tu cuenta</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <p class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                                        Se recomienda cambiar tu contraseña para mayor seguridad.
                                    </p>

                                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">
                                        Mantener una contraseña actualizada ayuda a proteger tu cuenta y tu información
                                        personal dentro del sistema escolar.
                                    </p>

                                    <div class="mt-4 flex items-center justify-between gap-2">

                                        <a href="{{ route('settings.password') }}"
                                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow transition hover:bg-indigo-700">
                                            <flux:icon.key class="w-4 h-4" />
                                            Cambiar contraseña
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif (auth()->user()?->hasRole('Estudiante') && auth()->user()?->change_password === true)
                        <!-- Notificación de seguridad -->

                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open"
                                class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-600 shadow-sm transition hover:bg-emerald-100 hover:scale-105 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300 dark:hover:bg-emerald-500/20">
                                <flux:icon.bell class="w-5 h-5" />


                            </button>

                            <!-- Panel de notificación -->
                            <div x-cloak x-show="open" @click.outside="open = false" x-transition
                                class="absolute right-0 top-full z-[9999] mt-3 w-80 overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">

                                <div
                                    class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-4 py-3 text-white">
                                    <div class="flex items-center gap-2">
                                        <div class="rounded-lg bg-white/20 p-2">
                                            <flux:icon.check-circle class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold">Estado de la cuenta</p>
                                            <p class="text-xs text-white/90">Todo se encuentra en orden</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <p class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                                        No hay ningún aviso importante por el momento.
                                    </p>

                                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">
                                        Tu cuenta y tu información dentro del sistema se encuentran correctas
                                        actualmente.
                                    </p>

                                    <div class="mt-4 flex items-center justify-between gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                            Sin pendientes
                                        </span>

                                        <span
                                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow">
                                            <flux:icon.check class="w-4 h-4" />
                                            Todo correcto
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif




                    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                        <flux:radio value="light" icon="sun"></flux:radio>
                        <flux:radio value="dark" icon="moon"></flux:radio>
                    </flux:radio.group>

                    <!-- Chips -->
                    <div class="inline-flex items-center gap-2">
                        <div
                            class="rounded-xl px-3 py-2 border border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-700/40 text-sm text-neutral-800 dark:text-neutral-100">
                            Ciclo escolar
                            <flux:badge color="indigo" class="ml-2">2025-2026</flux:badge>
                        </div>
                    </div>



                    <!-- Avatar -->
                    @if (auth()->user()->photo)
                        <div class="relative w-10 h-10 hidden lg:block">
                            @if (auth()->user()->photo && file_exists(storage_path('app/public/profile-photos/' . auth()->user()->photo)))
                                <div
                                    class="w-full h-full rounded-full overflow-hidden border-4 border-white shadow ring-1 ring-neutral-200 dark:ring-neutral-700">
                                    <img src="{{ asset('storage/profile-photos/' . auth()->user()->photo) }}"
                                        alt="Avatar" class="w-full h-full object-cover">
                                </div>
                            @else
                                <flux:avatar circle badge badge:circle badge:color="green"
                                    :initials="auth()->user()->initials()" :name="auth()->user()->username" />
                            @endif
                            <span
                                class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-neutral-800 rounded-full shadow-md"></span>
                        </div>
                        <div class="w-full text-center lg:hidden">
                            <span
                                class="block font-semibold text-neutral-800 dark:text-neutral-100">{{ auth()->user()->username }}</span>
                        </div>
                    @else
                        <flux:avatar badge badge:color="green" />

                        <div class="w-full text-center lg:hidden">
                            <span
                                class="block font-semibold text-neutral-800 dark:text-neutral-100">{{ auth()->user()->username }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
