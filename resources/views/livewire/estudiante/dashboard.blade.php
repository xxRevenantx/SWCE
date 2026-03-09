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

        {{-- PANEL BIENVENIDO --}}
        <div class="relative p-5 sm:p-6 lg:p-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3">
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-white/70 dark:bg-white/5 backdrop-blur-sm px-3 py-1 text-xs font-medium text-sky-700 ring-1 ring-white/60 dark:text-sky-300 dark:ring-white/10 shadow-sm">
                        <flux:icon.sparkles class="h-4 w-4" />
                        Panel del estudiante
                    </div>

                    <div class="space-y-1">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-neutral-900 dark:text-white">
                            @if ($sexo_estudiante === 'F')
                                Bienvenida, {{ $nombre_estudiante }}
                            @else
                                Bienvenido, {{ $nombre_estudiante }}
                            @endif
                        </h1>

                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-full lg:min-w-[420px]">
                    {{-- Matrícula --}}
                    <div
                        class="group relative overflow-hidden rounded-3xl border border-white/60 dark:border-white/10 bg-white/70 dark:bg-white/5 backdrop-blur-md p-5 shadow-sm transition hover:shadow-md">
                        <div
                            class="absolute top-0 right-0 h-20 w-20 rounded-full bg-sky-100/50 blur-2xl dark:bg-sky-500/10">
                        </div>

                        <div class="relative flex items-start gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-100 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400">
                                <flux:icon.identification class="h-5 w-5" />
                            </div>

                            <div class="min-w-0">
                                <p
                                    class="text-[11px] font-medium uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                    Matrícula
                                </p>
                                <p class="mt-2 text-base font-bold text-neutral-900 dark:text-white break-words">
                                    {{ $matricula }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Estado de inscripción --}}
                    <div
                        class="group relative overflow-hidden rounded-3xl border border-white/60 dark:border-white/10 bg-white/70 dark:bg-white/5 backdrop-blur-md p-5 shadow-sm transition hover:shadow-md">
                        <div class="relative flex items-start gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl
                            {{ $estado_inscripcion === 'Activa'
                                ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                                : 'bg-rose-100 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                <flux:icon.shield-check class="h-5 w-5" />
                            </div>

                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-[11px] font-medium uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                    Estado de inscripción
                                </p>

                                <div class="mt-2">
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset
                                    {{ $estado_inscripcion === 'Activa'
                                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20'
                                        : 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20' }}">
                                        {{ $estado_inscripcion }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Licenciatura / Cuatrimestre --}}
                    <div
                        class="relative overflow-hidden rounded-3xl border border-white/60 dark:border-white/10 bg-white/70 dark:bg-white/5 backdrop-blur-md p-5 shadow-sm sm:col-span-2 transition hover:shadow-md">
                        <div class="absolute inset-y-0 right-0 w-32 bg-indigo-100/20 blur-2xl dark:bg-indigo-500/10">
                        </div>

                        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start gap-3">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                                    <flux:icon.academic-cap class="h-5 w-5" />
                                </div>

                                <div class="min-w-0">
                                    <p
                                        class="text-[11px] font-medium uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                        Licenciatura
                                    </p>
                                    <p
                                        class="mt-2 text-sm sm:text-base font-bold text-neutral-900 dark:text-white break-words">
                                        {{ $licenciatura }}
                                    </p>
                                </div>
                            </div>

                            <div class="sm:text-right">
                                <p
                                    class="text-[11px] font-medium uppercase tracking-[0.18em] text-neutral-500 dark:text-neutral-400">
                                    Cuatrimestre
                                </p>
                                <span
                                    class="mt-2 inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-400">
                                    {{ $cuatrimestre }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tarjetas resumen del alumno --}}
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
                                @case('academic-cap')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 14.25 3.75 9.75 12 5.25l8.25 4.5L12 14.25Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 11.25v3.375c0 .621.504 1.125 1.125 1.125h8.25c.621 0 1.125-.504 1.125-1.125V11.25" />
                                    </svg>
                                @break

                                @case('book-open')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.253v13m0-13C10.832 5.483 9.246 5 7.5 5 5.55 5 3.75 5.6 2.25 6.625v11.15C3.75 16.75 5.55 16.15 7.5 16.15c1.746 0 3.332.483 4.5 1.253m0-11.15C13.168 5.483 14.754 5 16.5 5c1.95 0 3.75.6 5.25 1.625v11.15c-1.5-1.025-3.3-1.625-5.25-1.625-1.746 0-3.332.483-4.5 1.253" />
                                    </svg>
                                @break

                                @case('check-badge')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 2.25 2.25 4.5-4.5" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.5 3.75h9a3 3 0 0 1 3 3v4.5a3 3 0 0 1-.879 2.121l-4.5 4.5A3 3 0 0 1 12 18.75a3 3 0 0 1-2.121-.879l-4.5-4.5A3 3 0 0 1 4.5 11.25v-4.5a3 3 0 0 1 3-3Z" />
                                    </svg>
                                @break

                                @default
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                            @endswitch
                        </div>
                    </div>

                    <div class="mt-4">
                        <h2 class="text-3xl font-bold tracking-tight text-white">
                            {{ $item['valor'] }}
                        </h2>
                    </div>

                    <div class="mt-4">
                        @if ($item['titulo'] === 'Promedio general')
                            <p class="text-xs font-medium text-white/80">
                                Resultado actual del alumno
                            </p>
                        @elseif ($item['titulo'] === 'Materias inscritas')
                            <p class="text-xs font-medium text-white/80">
                                Carga académica del periodo
                            </p>
                        @elseif ($item['titulo'] === 'Materias aprobadas')
                            <p class="text-xs font-medium text-white/80">
                                Materias completadas con éxito
                            </p>
                        @else
                            <p class="text-xs font-medium text-white/80">
                                Materias aún por concluir
                            </p>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </section>



    {{-- Segunda fila --}}
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Documentación --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Estado de documentación</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Revisa qué archivos están pendientes.
                </p>
            </div>

            <div class="p-5 space-y-3">
                @if (count($documentacion) > 0)
                    @foreach ($documentacion as $documento)
                        <div
                            class="flex items-center justify-between rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 px-4 py-3">
                            <p class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                                {{ $documento['nombre'] }}
                            </p>

                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $this->obtenerClaseBadgeEstado($documento['estado']) }}">
                                {{ $documento['estado'] }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <div
                        class="rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 p-8 text-center">
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            No hay documentos para mostrar.
                        </p>
                    </div>
                @endif
            </div>
        </article>

        {{-- Últimas calificaciones --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Últimas calificaciones</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Resumen reciente de evaluaciones.
                </p>
            </div>

            <div class="p-5 space-y-5">
                <div
                    class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                            Gráfica de calificaciones
                        </p>
                        <span class="text-xs text-neutral-500 dark:text-neutral-400">
                            Últimos registros
                        </span>
                    </div>

                    <div class="relative h-80" wire:ignore>
                        <div id="graficaCalificaciones"></div>
                    </div>
                </div>


            </div>
        </article>

        {{-- Progreso académico --}}
        <article
            class="rounded-3xl border border-neutral-200/70 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Progreso académico</h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Avance general dentro de la carrera.
                </p>
            </div>

            <div class="p-5 space-y-5">
                <div
                    class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">Materias completadas</p>

                        <p class="text-lg font-bold text-neutral-900 dark:text-white">
                            {{ $progreso_academico['materias_cursadas'] ?? 0 }}/{{ $progreso_academico['materias_totales'] ?? 0 }}
                        </p>
                    </div>

                    <div class="mt-4 h-3 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-800">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-blue-600"
                            style="width: {{ $progreso_academico['porcentaje'] ?? 0 }}%">
                        </div>
                    </div>

                    <p class="mt-3 text-sm font-medium text-sky-700 dark:text-sky-300">
                        {{ $progreso_academico['porcentaje'] ?? 0 }}% de avance
                    </p>
                </div>

                <div
                    class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                            Gráfica de materias
                        </p>
                        <span class="text-xs text-neutral-500 dark:text-neutral-400">
                            Completadas vs pendientes
                        </span>
                    </div>

                    <div class="relative h-64" wire:ignore>
                        <div id="graficaProgreso"></div>
                    </div>
                </div>
            </div>
        </article>
    </section>
</div>

<script>
    if (!window.dashboardEstudianteApexRegistrado) {
        window.dashboardEstudianteApexRegistrado = true;

        window.apexCalificaciones = null;
        window.apexProgreso = null;

        function limpiarPalabras(texto) {
            if (!texto) return 'Sin materia';

            const palabrasIgnoradas = [
                'de', 'del', 'la', 'las', 'el', 'los', 'y', 'e', 'en', 'para', 'con'
            ];

            return texto
                .replace(/\s+/g, ' ')
                .trim()
                .split(' ')
                .filter(palabra => !palabrasIgnoradas.includes(palabra.toLowerCase()))
                .join(' ');
        }

        function crearEtiquetaCorta(texto, maxPalabras = 2, maxCaracteres = 20) {
            if (!texto) return 'Sin materia';

            const limpio = limpiarPalabras(texto);
            const palabras = limpio.split(' ');
            let corto = palabras.slice(0, maxPalabras).join(' ');

            if (limpio.length <= maxCaracteres) {
                return limpio;
            }

            if (corto.length > maxCaracteres) {
                corto = corto.substring(0, maxCaracteres - 3).trim() + '...';
            } else {
                corto = corto + '...';
            }

            return corto;
        }

        function obtenerDatosDashboardEstudiante() {
            return {
                categoriasCalificaciones: @js($grafica_calificaciones['categorias'] ?? []),
                seriesCalificaciones: @js($grafica_calificaciones['series'] ?? []),
                labelsProgreso: @js($grafica_progreso['labels'] ?? []),
                seriesProgreso: @js($grafica_progreso['series'] ?? []),
            };
        }

        function renderGraficaCalificaciones() {
            const contenedor = document.querySelector('#graficaCalificaciones');
            if (!contenedor || typeof ApexCharts === 'undefined') return;

            if (window.apexCalificaciones) {
                window.apexCalificaciones.destroy();
                window.apexCalificaciones = null;
            }

            const datos = obtenerDatosDashboardEstudiante();
            const categoriasCortas = datos.categoriasCalificaciones.map(item => crearEtiquetaCorta(item));

            const opciones = {
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: false
                    },
                    fontFamily: 'inherit'
                },
                series: [{
                    name: 'Calificación',
                    data: datos.seriesCalificaciones
                }],
                colors: ['#60A5FA', '#38BDF8', '#818CF8', '#34D399', '#FBBF24'],
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: '52%',
                        distributed: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                xaxis: {
                    categories: categoriasCortas,
                    labels: {
                        rotate: 0,
                        trim: false,
                        style: {
                            fontSize: '11px',
                            fontWeight: 500,
                            colors: '#6B7280'
                        }
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    min: 0,
                    max: 10,
                    tickAmount: 10,
                    labels: {
                        style: {
                            colors: '#6B7280'
                        }
                    }
                },
                grid: {
                    borderColor: 'rgba(148, 163, 184, 0.14)',
                    strokeDashArray: 0
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return 'Calificación: ' + value;
                        }
                    },
                    x: {
                        formatter: function(value, {
                            dataPointIndex
                        }) {
                            return datos.categoriasCalificaciones[dataPointIndex] ?? value;
                        }
                    }
                }
            };

            window.apexCalificaciones = new ApexCharts(contenedor, opciones);
            window.apexCalificaciones.render();
        }

        function renderGraficaProgreso() {
            const contenedor = document.querySelector('#graficaProgreso');
            if (!contenedor || typeof ApexCharts === 'undefined') return;

            if (window.apexProgreso) {
                window.apexProgreso.destroy();
                window.apexProgreso = null;
            }

            const datos = obtenerDatosDashboardEstudiante();

            const opciones = {
                chart: {
                    type: 'donut',
                    height: 250,
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: false
                    },
                    fontFamily: 'inherit'
                },
                series: datos.seriesProgreso,
                labels: datos.labelsProgreso,
                colors: ['#3B82F6', '#E5E7EB'],
                legend: {
                    position: 'bottom',
                    fontSize: '12px'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 0
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%'
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + ' materias';
                        }
                    }
                }
            };

            window.apexProgreso = new ApexCharts(contenedor, opciones);
            window.apexProgreso.render();
        }

        function renderGraficasDashboardEstudiante() {
            requestAnimationFrame(() => {
                setTimeout(() => {
                    renderGraficaCalificaciones();
                    renderGraficaProgreso();
                }, 80);
            });
        }

        document.addEventListener('livewire:init', () => {
            renderGraficasDashboardEstudiante();

            Livewire.hook('morph.updated', () => {
                renderGraficasDashboardEstudiante();
            });
        });

        document.addEventListener('livewire:navigated', () => {
            renderGraficasDashboardEstudiante();
        });

        document.addEventListener('DOMContentLoaded', () => {
            renderGraficasDashboardEstudiante();
        });
    }
</script>
