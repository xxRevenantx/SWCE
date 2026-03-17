<div class="space-y-6">
    {{-- Encabezado principal del tablero --}}
    <section
        class="relative overflow-hidden rounded-3xl border border-sky-100/70 dark:border-sky-900/40 bg-gradient-to-br from-sky-50 via-white to-fuchsia-50 dark:from-slate-950 dark:via-[#0b1220] dark:to-[#1a1033] shadow-sm">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-16 -left-10 h-56 w-56 rounded-full bg-sky-300/20 blur-3xl dark:bg-sky-500/10">
            </div>
            <div class="absolute top-0 right-0 h-64 w-64 rounded-full bg-fuchsia-300/20 blur-3xl dark:bg-fuchsia-500/10">
            </div>
            <div class="absolute bottom-0 left-1/3 h-40 w-40 rounded-full bg-blue-300/20 blur-3xl dark:bg-blue-500/10">
            </div>
        </div>

        <div class="relative h-1.5 w-full bg-gradient-to-r from-sky-500 via-blue-600 to-fuchsia-500"></div>

        <div class="relative p-5 sm:p-6 lg:p-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3">
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-white/70 dark:bg-white/5 backdrop-blur-sm px-3 py-1 text-xs font-medium text-sky-700 ring-1 ring-white/60 dark:text-sky-300 dark:ring-white/10 shadow-sm">
                        <flux:icon.sparkles class="h-4 w-4" />
                        Panel del profesor
                    </div>

                    <div class="space-y-1">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-neutral-900 dark:text-white">
                            Bienvenido, {{ $nombre_profesor }}
                        </h1>

                        <p class="text-sm sm:text-base text-neutral-600 dark:text-neutral-300 max-w-2xl">
                            Administra tus clases, grupos y carga académica desde un solo lugar.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-full lg:min-w-[440px]">
                    {{-- Perfil --}}
                    <div
                        class="group relative overflow-hidden rounded-3xl border border-white/60 dark:border-white/10 bg-white/70 dark:bg-white/5 backdrop-blur-md p-5 shadow-sm transition hover:shadow-md">
                        <div
                            class="absolute top-0 right-0 h-20 w-20 rounded-full bg-sky-100/50 blur-2xl dark:bg-sky-500/10">
                        </div>

                        <div class="relative flex items-start gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-100 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400">
                                <flux:icon.user-circle class="h-5 w-5" />
                            </div>

                            <div class="min-w-0">
                                <p
                                    class="text-[11px] font-medium uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                    Perfil
                                </p>
                                <p class="mt-2 text-base font-bold text-neutral-900 dark:text-white break-words">
                                    {{ $perfil }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div
                        class="group relative overflow-hidden rounded-3xl border border-white/60 dark:border-white/10 bg-white/70 dark:bg-white/5 backdrop-blur-md p-5 shadow-sm transition hover:shadow-md">
                        <div class="relative flex items-start gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl
                                {{ $estado_profesor === 'Activo'
                                    ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                                    : 'bg-rose-100 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                <flux:icon.shield-check class="h-5 w-5" />
                            </div>

                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-[11px] font-medium uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                    Estado
                                </p>

                                <div class="mt-2">
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset
                                        {{ $estado_profesor === 'Activo'
                                            ? 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20'
                                            : 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20' }}">
                                        {{ $estado_profesor }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Teléfono / color --}}
                    <div
                        class="relative overflow-hidden rounded-3xl border border-white/60 dark:border-white/10 bg-white/70 dark:bg-white/5 backdrop-blur-md p-5 shadow-sm sm:col-span-2 transition hover:shadow-md">
                        <div class="absolute inset-y-0 right-0 w-32 bg-indigo-100/20 blur-2xl dark:bg-indigo-500/10">
                        </div>

                        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start gap-3">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                                    <flux:icon.phone class="h-5 w-5" />
                                </div>

                                <div class="min-w-0">
                                    <p
                                        class="text-[11px] font-medium uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                        Teléfono
                                    </p>
                                    <p
                                        class="mt-2 text-sm sm:text-base font-bold text-neutral-900 dark:text-white break-words">
                                        {{ $telefono }}
                                    </p>
                                </div>
                            </div>

                            <div class="sm:text-right">
                                <p
                                    class="text-[11px] font-medium uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                    Color asignado
                                </p>

                                <div
                                    class="mt-2 inline-flex items-center gap-2 rounded-full bg-neutral-100 dark:bg-white/5 px-3 py-1.5">
                                    <span class="h-3.5 w-3.5 rounded-full ring-2 ring-white/70 dark:ring-white/10"
                                        style="background-color: {{ $color_profesor }}"></span>
                                    <span class="text-xs font-semibold text-neutral-700 dark:text-neutral-300">
                                        {{ $color_profesor }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tarjetas resumen --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach ($resumen as $item)
            <article
                class="relative overflow-hidden rounded-[20px] shadow-sm min-h-[150px] p-5 text-white {{ $item['color'] }}">
                <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="pointer-events-none absolute right-8 top-10 h-20 w-20 rounded-full bg-white/10"></div>
                <div class="pointer-events-none absolute -bottom-8 left-16 h-24 w-24 rounded-full bg-white/10"></div>

                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-[13px] font-medium text-white/80">
                                {{ $item['titulo'] }}
                            </p>
                        </div>

                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white">
                            @switch($item['icono'])
                                @case('book-open')
                                    <flux:icon.book-open class="h-5 w-5" />
                                @break

                                @case('users')
                                    <flux:icon.users class="h-5 w-5" />
                                @break

                                @case('calendar-days')
                                    <flux:icon.calendar-days class="h-5 w-5" />
                                @break

                                @default
                                    <flux:icon.clock class="h-5 w-5" />
                            @endswitch
                        </div>
                    </div>

                    <div class="mt-4">
                        <h2 class="text-3xl font-bold tracking-tight text-white">
                            {{ $item['valor'] }}
                        </h2>
                    </div>

                    <div class="mt-4">
                        <p class="text-xs font-medium text-white/80">
                            {{ $item['descripcion'] }}
                        </p>
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    {{-- Segunda fila --}}
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Clases de hoy --}}
        <article
            class="xl:col-span-2 rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Clases de hoy</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Sesiones programadas para {{ $dia_actual }}.
                </p>
            </div>

            <div class="p-5">
                @if (count($clases_hoy) > 0)
                    <div class="space-y-4">
                        @foreach ($clases_hoy as $clase)
                            <div
                                class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-4">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h4 class="text-sm sm:text-base font-bold text-neutral-900 dark:text-white">
                                                {{ $clase['materia'] }}
                                            </h4>

                                            <span
                                                class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-1 text-[11px] font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-400">
                                                {{ $clase['clave'] }}
                                            </span>
                                        </div>

                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <span
                                                class="inline-flex items-center rounded-full bg-fuchsia-100 px-2.5 py-1 text-[11px] font-semibold text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-400">
                                                {{ $clase['grupo'] }}
                                            </span>

                                            <span
                                                class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400">
                                                {{ $clase['cuatrimestre'] }}
                                            </span>

                                            <span
                                                class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                                {{ $clase['licenciatura'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <div
                                        class="rounded-2xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 px-4 py-3 min-w-[180px]">
                                        <p
                                            class="text-[11px] uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                            Horario
                                        </p>
                                        <p class="mt-1 text-sm font-bold text-neutral-900 dark:text-white">
                                            {{ $clase['hora'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 p-8 text-center">
                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-100 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400">
                            <flux:icon.calendar-days class="h-6 w-6" />
                        </div>

                        <h4 class="mt-4 text-sm font-semibold text-neutral-900 dark:text-white">
                            No hay clases registradas para hoy
                        </h4>

                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                            Cuando existan bloques asignados para este día, aparecerán aquí.
                        </p>
                    </div>
                @endif
            </div>
        </article>

        {{-- Próximos bloques --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Próximos bloques</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Vista rápida de tus siguientes sesiones.
                </p>
            </div>

            <div class="p-5 space-y-3">
                @if (count($proximas_clases) > 0)
                    @foreach ($proximas_clases as $item)
                        <div
                            class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 px-4 py-3">
                            <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                                {{ $item['materia'] }}
                            </p>

                            <div class="mt-2 flex items-center justify-between gap-3">
                                <span
                                    class="inline-flex items-center rounded-full bg-fuchsia-100 px-2.5 py-1 text-[11px] font-semibold text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-400">
                                    {{ $item['dia'] }}
                                </span>

                                <span class="text-xs font-medium text-neutral-600 dark:text-neutral-300">
                                    {{ $item['hora'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div
                        class="rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 p-8 text-center">
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            No hay próximos bloques para mostrar.
                        </p>
                    </div>
                @endif
            </div>
        </article>
    </section>

    {{-- Tercera fila --}}
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Resumen docente --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Resumen docente</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Indicadores rápidos de tu actividad.
                </p>
            </div>

            <div class="p-5 space-y-3">
                @foreach ($resumen_academico as $item)
                    <div
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 px-4 py-3">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                                    {{ $item['titulo'] }}
                                </p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $item['descripcion'] }}
                                </p>
                            </div>

                            <span class="text-xl font-bold {{ $item['clase'] }}">
                                {{ $item['valor'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

        {{-- Agenda del profesor --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Agenda del día</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Vista breve de tu carga actual.
                </p>
            </div>

            <div class="p-5">
                @php
                    $totalHoy = collect($resumen)->firstWhere('titulo', 'Clases de hoy')['valor'] ?? 0;
                    $totalBloques = collect($resumen)->firstWhere('titulo', 'Bloques asignados')['valor'] ?? 0;
                    $porcentajeHoy = $totalBloques > 0 ? round(($totalHoy / $totalBloques) * 100) : 0;
                @endphp

                <div
                    class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">
                            Carga del día
                        </p>

                        <p class="text-lg font-bold text-neutral-900 dark:text-white">
                            {{ $totalHoy }}/{{ $totalBloques }}
                        </p>
                    </div>

                    <div class="mt-4 h-3 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-800">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-500 via-blue-600 to-fuchsia-500"
                            style="width: {{ $porcentajeHoy }}%">
                        </div>
                    </div>

                    <p class="mt-3 text-sm font-medium text-sky-700 dark:text-sky-300">
                        {{ $porcentajeHoy }}% del total de bloques
                    </p>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4">
                        <p class="text-[11px] uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                            Materias
                        </p>
                        <p class="mt-2 text-2xl font-bold text-neutral-900 dark:text-white">
                            {{ collect($resumen)->firstWhere('titulo', 'Materias asignadas')['valor'] ?? 0 }}
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4">
                        <p class="text-[11px] uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                            Grupos
                        </p>
                        <p class="mt-2 text-2xl font-bold text-neutral-900 dark:text-white">
                            {{ collect($resumen)->firstWhere('titulo', 'Grupos atendidos')['valor'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>
        </article>

        {{-- Accesos rápidos --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Accesos rápidos</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Enlaces frecuentes del panel docente.
                </p>
            </div>

            <div class="p-5 space-y-3">
                @foreach ($accesos_rapidos as $item)
                    <a href="{{ $item['url'] }}"
                        class="group block rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-4 transition hover:-translate-y-0.5 hover:shadow-sm">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br {{ $item['clase'] }} text-white shadow-sm">
                                @switch($item['icono'])
                                    @case('calendar-days')
                                        <flux:icon.calendar-days class="h-5 w-5" />
                                    @break

                                    @case('book-open')
                                        <flux:icon.book-open class="h-5 w-5" />
                                    @break

                                    @default
                                        <flux:icon.user-circle class="h-5 w-5" />
                                @endswitch
                            </div>

                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                                    {{ $item['titulo'] }}
                                </p>
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $item['descripcion'] }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </article>
    </section>
</div>
