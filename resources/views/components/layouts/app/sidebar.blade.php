<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 ">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        @can('admin.dashboard')
            <a href="{{ route('admin.dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse"
                wire:navigate>
                <x-app-logo />
            </a>
        @endcan

        @can('profesor.dashboard')
            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>
        @endcan

        @can('estudiante.dashboard')
            <a href="{{ route('estudiante.dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse"
                wire:navigate>
                <x-app-logo />
            </a>
        @endcan


        <!-- Plataforma -->
        <flux:navlist variant="outline" class="text-[15px] sm:text-base">
            <flux:navlist.group :heading="__('Plataforma')" class="grid gap-y-2.5 sm:gap-y-3">
                @can('admin.dashboard')
                    <flux:navlist.item class="py-4" icon="home" :href="route('admin.dashboard')"
                        :current="request()->routeIs('admin.dashboard')" wire:navigate>
                        Dashboard
                    </flux:navlist.item>
                @endcan

                @can('profesor.dashboard')
                    <flux:navlist.item class="py-4" icon="home" :href="route('profesor.dashboard')"
                        :current="request()->routeIs('profesor.dashboard')" wire:navigate>
                        Panel del Profesor
                    </flux:navlist.item>
                    <flux:navlist.item class="py-4" icon="home" wire:navigate>
                        Mi horario
                    </flux:navlist.item>
                @endcan

                @can('estudiante.dashboard')
                    <flux:navlist.item class="py-4" icon="home" :href="route('estudiante.dashboard')"
                        :current="request()->routeIs('estudiante.dashboard')" wire:navigate>
                        Panel del Estudiante
                    </flux:navlist.item>
                @endcan
            </flux:navlist.group>
        </flux:navlist>

        <!-- Administración -->
        @can('admin.administracion')
            <flux:navlist class="text-[15px] sm:text-base">
                <flux:navlist.group :heading="__('Administración')" class="grid gap-y-4 sm:gap-y-3">
                    <flux:navlist.item class="py-4" icon="users" :href="route('usuarios.index')"
                        :current="request()->routeIs('usuarios.index')" wire:navigate>
                        {{ __('Usuarios') }}
                    </flux:navlist.item>

                    <flux:navlist.item class="py-4" icon="graduation-cap" :href="route('licenciaturas.index')"
                        :current="request()->routeIs('licenciaturas.index')" wire:navigate>
                        {{ __('Licenciaturas') }}
                    </flux:navlist.item>

                    <flux:navlist.item class="py-4" icon="layout-dashboard" :href="route('cuatrimestres.index')"
                        :current="request()->routeIs('cuatrimestres.index')" wire:navigate>
                        {{ __('Cuatrimestres') }}
                    </flux:navlist.item>


                    <flux:navlist class="w-64">

                        <flux:navlist.group icon="user-plus" heading="Generaciones" expandable>
                            <flux:navlist.item class="py-4" icon="users-round" :href="route('generaciones')"
                                :current="request()->routeIs('generaciones')" wire:navigate>
                                {{ __('Generaciones') }}
                            </flux:navlist.item>
                            <flux:navlist.item class="py-4" icon="users-round" :href="route('asignacion_generaciones')"
                                :current="request()->routeIs('asignacion_generaciones')" wire:navigate>
                                {{ __('Asignaciones') }}
                            </flux:navlist.item>
                        </flux:navlist.group>
                    </flux:navlist>




                    <flux:navlist class="w-64">

                        <flux:navlist.group icon="user-plus" heading="Inscripciones" expandable>
                            <flux:navlist.item :href="route('inscripciones.index')"
                                :current="request()->routeIs('inscripciones.index')" wire:navigate>Inscripción
                            </flux:navlist.item>
                            <flux:navlist.item href="#">Matrícula</flux:navlist.item>
                        </flux:navlist.group>
                    </flux:navlist>



                    <flux:navlist.item class="py-4" icon="teachers" :href="route('profesores.index')"
                        :current="request()->routeIs('profesores.index')" wire:navigate>
                        {{ __('Profesores') }}
                    </flux:navlist.item>

                    <flux:navlist.item class="py-4" icon="book" :href="route('materias.index')"
                        :current="request()->routeIs('materias.index')" wire:navigate>
                        {{ __('Materias') }}
                    </flux:navlist.item>

                    <flux:navlist.item class="py-4" icon="calendar-days" :href="route('horarios.index')"
                        :current="request()->routeIs('horarios.index')" wire:navigate>
                        {{ __('Horarios') }}
                    </flux:navlist.item>

                    <flux:navlist.item class="py-4" icon="book-check" wire:navigate>
                        {{ __('Calificaciones') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
        @endcan

        {{-- @can('admin.licenciaturas')
            <flux:navlist >
                <flux:navlist.group :heading="__('Licenciaturas')" class="grid ">
                    <flux:navlist.item icon="book" :href="route('licenciaturas.index')" :current="request()->routeIs('licenciaturas.index')" wire:navigate>{{ __('Licenciaturas') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
            @endcan
         @can('admin.cuatrimestres')
            <flux:navlist >
                <flux:navlist.group :heading="__('Cuatrimestres')" class="grid ">
                    <flux:navlist.item icon="book" :href="route('cuatrimestres.index')" :current="request()->routeIs('cuatrimestres.index')" wire:navigate>{{ __('Cuatrimestres') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
            @endcan --}}

        <flux:spacer />


        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            @if (auth()->user()->photo)
                <flux:profile circle badge badge:circle badge:color="green"
                    src="{{ asset('storage/profile-photos/' . auth()->user()->photo) }}"
                    :initials="auth()->user()->initials()" :name="auth()->user()->username"
                    icon-trailing="chevrons-up-down" />
            @else
                <flux:profile circle badge badge:circle badge:color="green" src="{{ asset('storage/default.png') }}"
                    :initials="auth()->user()->initials()" :name="auth()->user()->username"
                    icon-trailing="chevrons-up-down" />
            @endif


            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    <flux:avatar circle :initials="auth()->user()->initials()"
                                        :name="auth()->user()->username" />
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->username }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @once
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('alpine:init', () => {

                Alpine.data('graficaAlumnos', (payload = null) => ({
                    loading: true,
                    chart: null,
                    payload,

                    init() {
                        this.safeInit();
                    },

                    safeInit() {
                        this.loading = true;

                        this.$nextTick(() => {
                            requestAnimationFrame(() => {
                                if (!window.Chart || !this.$refs.canvas) {
                                    setTimeout(() => this.safeInit(), 120);
                                    return;
                                }
                                this.crearGrafica();
                            });
                        });
                    },

                    destruir() {
                        try {
                            if (this.chart) {
                                this.chart.destroy();
                                this.chart = null;
                            }
                            if (window.Chart && this.$refs.canvas) {
                                const existing = Chart.getChart(this.$refs.canvas);
                                if (existing) existing.destroy();
                            }
                        } catch (e) {}
                    },

                    crearGrafica() {
                        const canvas = this.$refs.canvas;
                        if (!canvas) {
                            this.loading = false;
                            return;
                        }

                        const ctx = canvas.getContext('2d');
                        if (!ctx) {
                            this.loading = false;
                            return;
                        }

                        this.destruir();

                        // ✅ DATOS FICTICIOS (DEFAULT)
                        const fake = {
                            labels: [
                                'Nutrición',
                                'Administración Empresarial',
                                'Criminología',
                                'Ciencias de la Educación',
                                'Ciencias Políticas y Administración Pública',
                                'Cultura Física y Deportes'
                            ],
                            dataHombres: [45, 32, 28, 38, 52, 25],
                            dataMujeres: [35, 28, 42, 45, 48, 38],
                            dataHombresBajas: [5, 3, 4, 6, 7, 2],
                            dataMujeresBajas: [3, 2, 5, 4, 6, 3],
                        };

                        const labels = (this.payload?.labels?.length ? this.payload.labels : fake.labels);

                        // ✅ Normaliza: mismo largo que labels y todo a Number
                        const norm = (arr) => Array.from({
                            length: labels.length
                        }, (_, i) => {
                            const v = arr?.[i];
                            const n = Number(v);
                            return Number.isFinite(n) ? n : 0;
                        });

                        const dataHombres = norm(this.payload?.dataHombres?.length ? this.payload
                            .dataHombres : fake.dataHombres);
                        const dataMujeres = norm(this.payload?.dataMujeres?.length ? this.payload
                            .dataMujeres : fake.dataMujeres);
                        const dataHombresBajas = norm(this.payload?.dataHombresBajas?.length ? this.payload
                            .dataHombresBajas : fake.dataHombresBajas);
                        const dataMujeresBajas = norm(this.payload?.dataMujeresBajas?.length ? this.payload
                            .dataMujeresBajas : fake.dataMujeresBajas);

                        this.chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels,
                                datasets: [{
                                        label: 'Activos Hombres',
                                        data: dataHombres,
                                        backgroundColor: '#3B82F6',
                                        borderColor: '#2563EB',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Activos Mujeres',
                                        data: dataMujeres,
                                        backgroundColor: '#60A5FA',
                                        borderColor: '#3B82F6',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Bajas Hombres',
                                        data: dataHombresBajas,
                                        backgroundColor: '#F87171',
                                        borderColor: '#EF4444',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Bajas Mujeres',
                                        data: dataMujeresBajas,
                                        backgroundColor: '#FCA5A5',
                                        borderColor: '#FB7185',
                                        borderWidth: 1
                                    },
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: false, // ✅ ayuda en SPA/navigate
                                interaction: {
                                    mode: 'index',
                                    intersect: false
                                },
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 12,
                                            usePointStyle: true
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (c) => `${c.dataset.label}: ${c.parsed.y ?? 0}`
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        stacked: true
                                    },
                                    y: {
                                        stacked: true,
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        // ✅ Forzar repaint (clave cuando entra por wire:navigate)
                        setTimeout(() => {
                            try {
                                this.chart.resize();
                                this.chart.update();
                            } catch (e) {}
                        }, 80);

                        this.loading = false;
                    }
                }));

                // ✅ IMPORTANTÍSIMO: al navegar con wire:navigate
                document.addEventListener('livewire:navigated', () => {
                    document.querySelectorAll('[data-chart="alumnos-lic"]').forEach((el) => {
                        if (el._x_dataStack && el._x_dataStack[0]) {
                            try {
                                el._x_dataStack[0].safeInit();
                            } catch (e) {}
                        }
                    });
                });
            });
        </script>

    @endonce


    @fluxScripts
</body>

</html>
