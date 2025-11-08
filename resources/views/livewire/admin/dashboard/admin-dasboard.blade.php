<div class="flex w-full flex-1 flex-col gap-6 ">




    {{-- 2) TARJETAS RESUMEN SUPERIOR --}}
    <div class="grid gap-4 sm:grid-cols-2">
        {{-- Profesores activos --}}
       {{-- Profesores activos --}}
<div class="relative overflow-hidden rounded-2xl p-5 sm:p-6 hover:shadow-lg transition-shadow
            bg-gradient-to-br from-rose-400 via-pink-400 to-orange-300">
    {{-- burbujas decorativas --}}
    <span class="pointer-events-none absolute -right-8 -top-8 h-36 w-36 rounded-full bg-white/25 blur-2xl"></span>
    <span class="pointer-events-none absolute left-8 -bottom-10 h-48 w-48 rounded-full bg-white/20 blur-3xl"></span>
    <span class="pointer-events-none absolute right-20 bottom-6 h-28 w-28 rounded-full bg-white/20 blur-2xl"></span>

    <div class="relative flex items-center gap-3">
        <div class="p-2.5 rounded-xl bg-white/25 ring-1 ring-white/30 text-white">
            <flux:icon.user class="w-5 h-5" />
        </div>
        <div class="flex-1">
            <p class=" text-white/90">Profesores Activos</p>
            <p class="text-3xl font-extrabold text-white leading-tight drop-shadow-sm">
                10
            </p>

        </div>
    </div>
</div>



       {{-- Generaciones activas --}}
<div class="relative overflow-hidden rounded-2xl p-5 sm:p-6 hover:shadow-lg transition-shadow
            bg-gradient-to-br from-sky-400 via-blue-500 to-indigo-500">
    {{-- burbujas decorativas --}}
    <span class="pointer-events-none absolute -right-8 -top-10 h-40 w-40 rounded-full bg-white/25 blur-2xl"></span>
    <span class="pointer-events-none absolute left-10 bottom-0 h-48 w-48 rounded-full bg-white/20 blur-3xl"></span>
    <span class="pointer-events-none absolute right-24 bottom-8 h-28 w-28 rounded-full bg-white/20 blur-2xl"></span>

    <p class="relative  text-white/90 mb-3">Generaciones Activas</p>
    <div class="relative flex flex-wrap gap-2">
        @foreach ($generacionesActivas as $generaciones)
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-sm font-bold uppercase
                         bg-white/20 ring-1 ring-white/30 text-white">
                {{ $generaciones->generacion }}
            </span>
        @endforeach
    </div>
</div>

    </div>

    {{--  ACTIVOS / BAJAS --}}
    <div class="grid auto-rows-min md:grid-cols-2 gap-4">
        {{-- Activos --}}
        <div
            x-data="{
                open: JSON.parse(localStorage.getItem('localesActivos')) ?? false,
                toggle(){ this.open = !this.open; localStorage.setItem('localesActivos', JSON.stringify(this.open)); }
            }"
            class="relative rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-5 sm:p-6"
        >
            <div class="flex items-start justify-between gap-4">
                <div class="w-full rounded-2xl border border-indigo-400 dark:bg-[#183c6c3c] text-neutral-800 shadow-lg shadow-slate-950/20 p-5
                    hover:shadow-xl hover:shadow-slate-100 transition-all bg-[#eff6ff]  dark:text-slate-200 dark:shadow-black/20 dark:hover:shadow-black/10">
                         <!-- Header -->
                    <div class="flex items-center gap-3">
                        <!-- Icono -->
                        <div class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-500/15 ring-1 ring-blue-400/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        </div>
                        <h3 class="text-2xl font-bold tracking-tight">Alumnos Activos</h3>
                    </div>
                    <div class="flex items-center justify-between align-center ">

                    <div class="mt-3 flex items-baseline gap-3">
                        <span class="text-5xl font-extrabold leading-none tracking-tight">4</span>
                        <span class="text-sm text-slate-400">34H | 39M</span>
                    </div>


                    <button @click="toggle"
                            class="shrink-0 inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200 focus:outline-none">
                            <template x-if="!open"><span class="flex items-center">Ver detalle
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </span></template>
                            <template x-if="open"><span class="flex items-center">Ocultar
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                            </span></template>
                        </button>

                    </div>

                    </div>


            </div>

            <div x-show="open" x-collapse class="mt-4">
                <div class="space-y-3">

                    {{-- @foreach ($resumenPorLicenciatura as $resumen)
                        @php
                            $t = max(1, (int)$resumen['hombres'] + (int)$resumen['mujeres']);
                            $pctH = round(($resumen['hombres'] / $t) * 100);
                            $pctM = 100 - $pctH;
                        @endphp
                        <div class="rounded-xl ring-1 ring-neutral-200 dark:ring-neutral-700 p-3 sm:p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold truncate text-neutral-900 dark:text-neutral-100">{{ $resumen['licenciatura'] }}</p>
                                    <div class="mt-1 flex flex-wrap gap-2 text-xs">
                                        <flux:badge color="zinc">{{ $resumen['hombres'] }} H</flux:badge>
                                        <flux:badge color="zinc">{{ $resumen['mujeres'] }} M</flux:badge>
                                    </div>
                                </div>
                                <flux:badge color="green">Total: {{ $resumen['total'] }}</flux:badge>
                            </div>
                            <div class="mt-3 h-2 w-full rounded-full bg-neutral-200 dark:bg-neutral-700 overflow-hidden flex">
                                <div class="h-full bg-blue-500" style="width: {{ $pctH }}%"></div>
                                <div class="h-full bg-pink-500" style="width: {{ $pctM }}%"></div>
                            </div>
                        </div>
                    @endforeach --}}
                </div>
            </div>
        </div>

        {{--  Bajas --}}
        <div
            x-data="{
                open: JSON.parse(localStorage.getItem('localesBajas')) ?? false,
                toggle(){ this.open = !this.open; localStorage.setItem('localesBajas', JSON.stringify(this.open)); }
            }"
            class="relative rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-5 sm:p-6"
        >
            <div class="flex items-start justify-between gap-4">
                <div class="w-full rounded-2xl border p-5
                        bg-[#FEF2F2] text-neutral-800 border-red-300 shadow-lg shadow-slate-950/20
                        hover:shadow-xl transition-all
                        dark:bg-[#3b2f2f] dark:text-white/90 dark:border-red-400/40
                        dark:ring-1 dark:ring-inset dark:ring-red-500/10 dark:shadow-black/30">
                         <!-- Header -->
                    <div class="flex items-center gap-3">
                        <!-- Icono -->
                        <div class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-red-500/15 ring-1 ring-red-400/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <line x1="17" y1="8" x2="23" y2="14"/>
                                <line x1="23" y1="8" x2="17" y2="14"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold tracking-tight">Alumnos Inactivos</h3>
                    </div>
                    <div class="flex items-center justify-between align-center ">

                    <div class="mt-3 flex items-baseline gap-3">
                        <span class="text-5xl font-extrabold leading-none tracking-tight">4</span>
                        <span class="text-sm text-slate-400">34H | 39M</span>
                    </div>


                    <button @click="toggle"
                            class="shrink-0 inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200 focus:outline-none">
                            <template x-if="!open"><span class="flex items-center">Ver detalle
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </span></template>
                            <template x-if="open"><span class="flex items-center">Ocultar
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                            </span></template>
                        </button>

                    </div>

                    </div>


            </div>

            <div x-show="open" x-collapse class="mt-4">
                <div class="space-y-3">
                    {{-- @foreach ($resumenPorLicenciaturaBaja as $resumen)
                        @php
                            $t = max(1, (int)$resumen['hombres'] + (int)$resumen['mujeres']);
                            $pctH = round(($resumen['hombres'] / $t) * 100);
                            $pctM = 100 - $pctH;
                        @endphp
                        <div class="rounded-xl ring-1 ring-neutral-200 dark:ring-neutral-700 p-3 sm:p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold truncate text-neutral-900 dark:text-neutral-100">{{ $resumen['licenciatura'] }}</p>
                                    <div class="mt-1 flex flex-wrap gap-2 text-xs">
                                        <flux:badge color="red">{{ $resumen['hombres'] }} H</flux:badge>
                                        <flux:badge color="red">{{ $resumen['mujeres'] }} M</flux:badge>
                                    </div>
                                </div>
                                <flux:badge color="zinc">Total: {{ $resumen['total'] }}</flux:badge>
                            </div>
                            <div class="mt-3 h-2 w-full rounded-full bg-neutral-200 dark:bg-neutral-700 overflow-hidden flex">
                                <div class="h-full bg-rose-500" style="width: {{ $pctH }}%"></div>
                                <div class="h-full bg-amber-400" style="width: {{ $pctM }}%"></div>
                            </div>
                        </div>
                        <flux:separator variant="subtle" />
                    @endforeach --}}
                </div>
            </div>
        </div>
    </div>





    {{-- 5) GRÁFICA (Chart.js) --}}
    <div
        x-data
        x-init="$nextTick(() => renderGraficaAlumnos())"
        class="bg-white rounded-2xl p-6 shadow border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 mt-2"
    >
        <h2 class="text-xl sm:text-2xl font-bold mb-4 text-neutral-800 dark:text-white">
            Gráfica de Alumnos por Licenciatura
        </h2>
        <div class="relative h-[360px] sm:h-[420px] lg:h-[520px]">
            <canvas id="graficaAlumnos" class="!w-full !h-full"></canvas>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function renderGraficaAlumnos() {
            const ctx = document.getElementById('graficaAlumnos');
            if (!ctx) return;

            if (window.graficaAlumnosInstance) {
                window.graficaAlumnosInstance.destroy();
            }

            // Datos ficticios de prueba
            const labels = ['Nutrición', 'Administración Empresarial', 'Criminología', 'Ciencias de la Educación', 'Ciencias Políticas y Administración Pública', 'Cultura Física y Deportes'];
            const dataHombres = [45, 32, 28, 38, 52, 25, 40, 30, 22, 35, 48];
            const dataMujeres = [35, 28, 42, 45, 48, 38, 36, 28, 40, 32, 20];
            const dataHombresBajas = [5, 3, 4, 6, 7, 2, 5, 3, 2, 4, 6];
            const dataMujeresBajas = [3, 2, 5, 4, 6, 3, 4, 2, 3, 3, 2];


            window.graficaAlumnosInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        { label: 'Activos Hombres', data: dataHombres, backgroundColor: '#3B82F6' },
                        { label: 'Activos Mujeres', data: dataMujeres, backgroundColor: '#60A5FA' },
                        { label: 'Bajas Hombres',   data: dataHombresBajas, backgroundColor: '#F87171' },
                        { label: 'Bajas Mujeres',   data: dataMujeresBajas, backgroundColor: '#FCA5A5' },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } },
                        tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y ?? 0}` } }
                    },
                    scales: {
                        x: { stacked: true, ticks: { maxRotation: 0, autoSkip: true } },
                        y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        }
    </script>


 {{-- <script>
        function renderGraficaAlumnos() {
            const ctx = document.getElementById('graficaAlumnos');
            if (!ctx) return;

            if (window.graficaAlumnosInstance) {
                window.graficaAlumnosInstance.destroy();
            }

            const labels = @js($licenciaturas->pluck('nombre'));
            const dataHombres         = @js(collect($resumenPorLicenciatura)->pluck('hombres'));
            const dataMujeres        = @js(collect($resumenPorLicenciatura)->pluck('mujeres'));
            const dataHombresBajas   = @js(collect($resumenPorLicenciaturaBaja)->pluck('hombres'));
            const dataMujeresBajas    = @js(collect($resumenPorLicenciaturaBaja)->pluck('mujeres'));


            window.graficaAlumnosInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        { label: 'Activos Hombres', data: dataHombresLocales,      backgroundColor: '#3B82F6' },
                        { label: 'Activos Mujeres', data: dataMujeresLocales,      backgroundColor: '#60A5FA' },
                        { label: 'Bajas Hombres',   data: dataHombresBajasLocales, backgroundColor: '#F87171' },
                        { label: 'Bajas Mujeres',   data: dataMujeresBajasLocales, backgroundColor: '#FCA5A5' },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } },
                        tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y ?? 0}` } }
                    },
                    scales: {
                        x: { stacked: true, ticks: { maxRotation: 0, autoSkip: true } },
                        y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        }
    </script> --}}
</div>
