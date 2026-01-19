<div class="flex w-full flex-1 flex-col gap-6 ">

    {{-- 2) TARJETAS RESUMEN SUPERIOR --}}
    <div class="grid gap-4 sm:grid-cols-2">

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
                            hover:shadow-xl hover:shadow-slate-100 transition-all bg-[#eff6ff]
                            dark:text-slate-200 dark:shadow-black/20 dark:hover:shadow-black/10">
                    <div class="flex items-center gap-3">
                        <div class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-500/15 ring-1 ring-blue-400/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold tracking-tight">Alumnos Activos</h3>
                    </div>

                    <div class="flex items-center justify-between align-center">
                        <div class="mt-3 flex items-baseline gap-3">
                            <span class="text-5xl font-extrabold leading-none tracking-tight">4</span>
                            <span class="text-sm text-slate-400">34H | 39M</span>
                        </div>

                        <button @click="toggle"
                            class="shrink-0 inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200 focus:outline-none">
                            <template x-if="!open">
                                <span class="flex items-center">Ver detalle
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </template>
                            <template x-if="open">
                                <span class="flex items-center">Ocultar
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                    </svg>
                                </span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="open" x-collapse class="mt-4">
                <div class="space-y-3">
                    {{-- detalle --}}
                </div>
            </div>
        </div>

        {{-- Bajas --}}
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
                    <div class="flex items-center gap-3">
                        <div class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-red-500/15 ring-1 ring-red-400/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <line x1="17" y1="8" x2="23" y2="14" />
                                <line x1="23" y1="8" x2="17" y2="14" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold tracking-tight">Alumnos Inactivos</h3>
                    </div>

                    <div class="flex items-center justify-between align-center">
                        <div class="mt-3 flex items-baseline gap-3">
                            <span class="text-5xl font-extrabold leading-none tracking-tight">4</span>
                            <span class="text-sm text-slate-400">34H | 39M</span>
                        </div>

                        <button @click="toggle"
                            class="shrink-0 inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200 focus:outline-none">
                            <template x-if="!open">
                                <span class="flex items-center">Ver detalle
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </template>
                            <template x-if="open">
                                <span class="flex items-center">Ocultar
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                    </svg>
                                </span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="open" x-collapse class="mt-4">
                <div class="space-y-3">
                    {{-- detalle --}}
                </div>
            </div>
        </div>

    </div>

    {{-- 5) GRÁFICA (Chart.js) --}}
    <div
        wire:ignore
        x-data="graficaAlumnos()"
        x-init="init()"
        class="relative bg-white rounded-2xl p-6 shadow border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 mt-2"
    >
        {{-- Loader sobre la card --}}
        <div
            x-show="loading"
            x-transition.opacity
            class="absolute inset-0 z-[70] flex items-center justify-center
                   bg-white/10 dark:bg-neutral-950/30
                   backdrop-blur-md backdrop-saturate-150"
        >
            <div class="flex flex-col items-center gap-3">
                <div class="h-12 w-12 rounded-full border-4 border-sky-500 border-t-transparent animate-spin"></div>
                <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                    Cargando gráfica de alumnos...
                </p>
            </div>
        </div>

        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl sm:text-2xl font-bold text-neutral-800 dark:text-white">
                Gráfica de Alumnos por Licenciatura
            </h2>

            <button
                type="button"
                @click.prevent="recargar()"
                :disabled="loading || cooldown"
                :class="(loading || cooldown) ? 'pointer-events-none opacity-60' : ''"
                @dblclick.prevent
                class="relative group inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2.5
                       text-sm font-semibold text-white shadow-lg shadow-slate-950/10
                       bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600
                       hover:shadow-xl hover:shadow-blue-500/20
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400
                       dark:focus:ring-offset-neutral-900"
                aria-label="Recargar gráfica"
                title="Recargar gráfica"
            >
                <span class="relative inline-flex h-6 w-6 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/20">
                    <svg x-show="!(loading)" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300 group-hover:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12a9 9 0 1 1-2.64-6.36" />
                        <path d="M21 3v6h-6" />
                    </svg>

                    <svg x-show="loading" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v3a5 5 0 0 0-5 5H4z"></path>
                    </svg>
                </span>

                <span x-text="loading ? 'Recargando…' : (cooldown ? 'Espera…' : 'Recargar gráfica')"></span>

                <span class="pointer-events-none absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition
                             bg-gradient-to-r from-white/0 via-white/20 to-white/0"></span>
            </button>
        </div>

        <div class="relative h-[360px] sm:h-[420px] lg:h-[520px]">
            <canvas x-ref="canvas" class="!w-full !h-full"></canvas>
        </div>
    </div>

    {{-- Scripts SOLO una vez --}}
    @once
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            (function () {
                const registerGrafica = () => {
                    if (!window.Alpine) return;

                    Alpine.data('graficaAlumnos', () => ({
                        loading: true,
                        cooldown: false, // ✅ bloqueo 2s
                        chart: null,

                        init() {
                            this.$nextTick(() => {
                                requestAnimationFrame(() => this.crearGrafica());
                            });
                        },

                        recargar() {
                            // ✅ si está bloqueado, no hace nada
                            if (this.cooldown) return;

                            // ✅ bloqueo inmediato por 2 segundos
                            this.cooldown = true;
                            setTimeout(() => { this.cooldown = false; }, 1000);

                            this.loading = true;

                            // Destruir chart actual (si existe)
                            if (this.chart) {
                                try { this.chart.destroy(); } catch (e) {}
                                this.chart = null;
                            }

                            // por si existe otra instancia detectada por Chart.js
                            const canvas = this.$refs.canvas;
                            if (canvas && window.Chart) {
                                const existing = Chart.getChart(canvas);
                                if (existing) {
                                    try { existing.destroy(); } catch (e) {}
                                }
                            }

                            this.$nextTick(() => {
                                requestAnimationFrame(() => this.crearGrafica(true));
                            });
                        },

                        crearGrafica(force = false) {
                            const canvas = this.$refs.canvas;

                            if (!canvas || !window.Chart) {
                                this.loading = false;
                                return;
                            }

                            const ctx = canvas.getContext('2d');
                            if (!ctx) {
                                this.loading = false;
                                return;
                            }

                            // === DATOS DE PRUEBA ===
                            const labels = [
                                'Nutrición',
                                'Administración Empresarial',
                                'Criminología',
                                'Ciencias de la Educación',
                                'Ciencias Políticas y Administración Pública',
                                'Cultura Física y Deportes'
                            ];

                            const dataHombres      = [45, 32, 28, 38, 52, 25];
                            const dataMujeres      = [35, 28, 42, 45, 48, 38];
                            const dataHombresBajas = [5,  3,  4,  6,  7,  2];
                            const dataMujeresBajas = [3,  2,  5,  4,  6,  3];

                            this.chart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels,
                                    datasets: [
                                        { label: 'Activos Hombres', data: dataHombres, backgroundColor: '#3B82F6', borderColor: '#2563EB', borderWidth: 1 },
                                        { label: 'Activos Mujeres', data: dataMujeres, backgroundColor: '#60A5FA', borderColor: '#3B82F6', borderWidth: 1 },
                                        { label: 'Bajas Hombres', data: dataHombresBajas, backgroundColor: '#F87171', borderColor: '#EF4444', borderWidth: 1 },
                                        { label: 'Bajas Mujeres', data: dataMujeresBajas, backgroundColor: '#FCA5A5', borderColor: '#FB7185', borderWidth: 1 },
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    interaction: { mode: 'index', intersect: false },
                                    plugins: {
                                        legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } },
                                        tooltip: { callbacks: { label: (c) => `${c.dataset.label}: ${c.parsed.y ?? 0}` } }
                                    },
                                    scales: {
                                        x: { stacked: true },
                                        y: { stacked: true, beginAtZero: true }
                                    }
                                }
                            });

                            this.loading = false;
                        }
                    }));
                };

                if (window.Alpine) registerGrafica();
                else document.addEventListener('alpine:init', registerGrafica);
            })();
        </script>
    @endonce

</div>
