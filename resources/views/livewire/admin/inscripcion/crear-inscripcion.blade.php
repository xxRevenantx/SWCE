<div x-data="wizard({ initial: (localStorage.getItem('alumnoTabs') || 'generales'), persistKey: 'alumnoTabs' })" x-init="init()"
    @ir-a-step.window="go($event.detail.step); $nextTick(() => attachObserver())"
    @errores-por-step.window="bad = $event.detail.summary; $nextTick(() => attachObserver())" class="relative w-full">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none
        }

        .stage {
            position: relative;
            overflow: hidden;
            contain: layout paint style;
            will-change: height;
            transition: height .2s ease;
        }

        @media (prefers-reduced-motion: reduce) {
            .stage {
                transition: none;
            }
        }
    </style>

    {{-- LOADER --}}
    <div x-show="loading" x-transition.opacity
        class="absolute inset-0 z-[70] flex items-center justify-center
        bg-white/10 dark:bg-neutral-950/30 backdrop-blur-md backdrop-saturate-150">
        <div class="flex flex-col items-center gap-3">
            <div class="h-12 w-12 rounded-full border-4 border-sky-500 border-t-transparent animate-spin"></div>
            <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                Cargando inscripción…
            </p>
        </div>
    </div>

    {{-- HEADER --}}
    <header class="sticky top-0 z-20 mb-4 w-full">
        <div
            class="rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-sm
            bg-gradient-to-r from-[#E4F6FF] to-[#F2EFFF]
            dark:bg-gradient-to-r dark:from-[#111318] dark:to-[#111318]">
            <div class="px-4 py-4">
                <h1 class="text-lg font-bold text-neutral-900 dark:text-white">INSCRIPCIÓN DE ESTUDIANTES</h1>
                <p class="text-sm text-neutral-700 dark:text-neutral-300">Registro completo: Alumno + Contacto +
                    Escolares + Inscripción</p>
            </div>

            {{-- PROGRESS --}}
            <div class="px-4 pb-4">
                <div class="relative">
                    <div class="h-1 w-full rounded-full bg-neutral-200 dark:bg-neutral-700"></div>
                    <div class="absolute inset-y-0 left-0 h-1 rounded-full
                        bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600
                        transition-[width] duration-200"
                        :style="`width: ${progress}%`" aria-hidden="true"></div>
                </div>

                <nav class="mt-3 relative overflow-x-auto no-scrollbar" role="tablist" aria-label="Secciones">
                    <ul class="inline-flex items-center gap-2 md:gap-3">
                        <template x-for="step in steps" :key="step.name">
                            <li>
                                <button type="button"
                                    class="group relative flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-medium
                                    focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500"
                                    role="tab" :tabindex="is(step.name) ? 0 : -1" :aria-selected="is(step.name)"
                                    @click="go(step.name)">
                                    <span
                                        class="flex h-6 w-6 items-center justify-center rounded-full border text-xs font-semibold"
                                        :class="is(step.name) ?
                                            'border-blue-600 bg-blue-600 text-white' :
                                            'border-neutral-300 text-neutral-500 dark:border-neutral-700 dark:text-neutral-300'">
                                        <span x-text="indexOf(step.name)+1"></span>
                                    </span>

                                    <span class="whitespace-nowrap"
                                        :class="is(step.name) ? 'text-neutral-900 dark:text-white' :
                                            'text-neutral-700 dark:text-neutral-200'"
                                        x-text="step.label"></span>

                                    <span x-show="bad[step.name] > 0"
                                        class="ml-1 rounded-full bg-rose-600 px-2 py-0.5 text-[11px] text-white"
                                        x-text="bad[step.name]"></span>
                                </button>
                            </li>
                        </template>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    {{-- BODY --}}
    <div class="stage" x-ref="stage" wire:ignore.self>
        {{-- ====================== STEP 1: GENERALES ====================== --}}
        <section x-cloak x-show="is('generales')" data-panel="generales" class="absolute inset-0 w-full">
            <div
                class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm w-full">
                <div
                    class="w-full rounded-t-2xl border-b border-neutral-200 dark:border-neutral-800
                    bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white">
                    <h2 class="font-semibold">Datos del alumno (tabla alumnos)</h2>
                </div>

                <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <flux:field>
                        <flux:label badge="Requerido">Usuario</flux:label>
                        <flux:select wire:model="user_id">
                            <flux:select.option value="">-- Selecciona --</flux:select.option>
                            @foreach ($usuarios as $usuario)
                                <flux:select.option value="{{ $usuario->id }}">{{ $usuario->username }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="user_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">CURP</flux:label>
                        <flux:input wire:model="curp" placeholder="18 caracteres" />
                        <flux:error name="curp" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">Nombre</flux:label>
                        <flux:input wire:model="nombre" placeholder="Nombre(s)" />
                        <flux:error name="nombre" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">Apellido paterno</flux:label>
                        <flux:input wire:model="apellido_paterno" placeholder="Opcional" />
                        <flux:error name="apellido_paterno" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Opcional">Apellido materno</flux:label>
                        <flux:input wire:model="apellido_materno" placeholder="Opcional" />
                        <flux:error name="apellido_materno" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">Fecha nacimiento</flux:label>
                        <flux:input type="date" wire:model="fecha_nacimiento" />
                        <flux:error name="fecha_nacimiento" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">Sexo</flux:label>
                        <flux:select wire:model="sexo">
                            <flux:select.option value="">-- Selecciona --</flux:select.option>
                            <flux:select.option value="M">Masculino</flux:select.option>
                            <flux:select.option value="F">Femenino</flux:select.option>
                        </flux:select>
                        <flux:error name="sexo" />
                    </flux:field>
                </div>

                <div class="flex items-center justify-between gap-3 px-4 sm:px-6 pt-3 pb-5">
                    <flux:button type="button" :disabled="true">Anterior</flux:button>
                    <flux:button type="button" @click="next()">Siguiente</flux:button>
                </div>
            </div>
        </section>

        {{-- ====================== STEP 2: CONTACTO ====================== --}}
        <section x-cloak x-show="is('contacto')" data-panel="contacto" class="absolute inset-0 w-full">
            <div
                class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm w-full">
                <div
                    class="w-full rounded-t-2xl border-b border-neutral-200 dark:border-neutral-800
                    bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white">
                    <h2 class="font-semibold">Datos de contacto (tabla datos_contactos)</h2>
                </div>

                <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <flux:field>
                        <flux:label badge="Opcional">Calle</flux:label>
                        <flux:input wire:model="calle" placeholder="Calle" />
                        <flux:error name="calle" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Opcional">Núm. exterior</flux:label>
                        <flux:input wire:model="numero_exterior" placeholder="Opcional" />
                        <flux:error name="numero_exterior" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Opcional">Núm. interior</flux:label>
                        <flux:input wire:model="numero_interior" placeholder="Opcional" />
                        <flux:error name="numero_interior" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Opcional">Colonia</flux:label>
                        <flux:input wire:model="colonia" placeholder="Colonia" />
                        <flux:error name="colonia" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Opcional">Municipio</flux:label>
                        <flux:input wire:model="municipio" placeholder="Municipio" />
                        <flux:error name="municipio" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Opcional">Código postal</flux:label>
                        <flux:input wire:model="codigo_postal" placeholder="CP" />
                        <flux:error name="codigo_postal" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Opcional">Celular</flux:label>
                        <flux:input wire:model="celular" placeholder="Celular" />
                        <flux:error name="celular" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Opcional">Teléfono</flux:label>
                        <flux:input wire:model="telefono" placeholder="Opcional" />
                        <flux:error name="telefono" />
                    </flux:field>
                </div>

                <div class="px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <flux:field>
                        <flux:label>País</flux:label>
                        <flux:select wire:model.live="pais_id" wire:key="pais-select">
                            <flux:select.option value="">-- Selecciona --</flux:select.option>
                            @foreach ($countries as $country)
                                <flux:select.option value="{{ $country['id'] }}">{{ $country['name'] }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="pais_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Estado</flux:label>
                        <flux:select wire:model.live="estado_id" wire:key="estado-select-{{ $pais_id ?? 'none' }}">
                            <flux:select.option value="">-- Selecciona --</flux:select.option>
                            @foreach ($states as $state)
                                <flux:select.option value="{{ $state['id'] }}">{{ $state['name'] }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="estado_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Ciudad</flux:label>
                        <flux:select wire:model.live="ciudad_id" wire:key="ciudad-select-{{ $estado_id ?? 'none' }}">
                            <flux:select.option value="">-- Selecciona --</flux:select.option>
                            @foreach ($cities as $city)
                                <flux:select.option value="{{ $city['id'] }}">{{ $city['name'] }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="ciudad_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">Bachillerato procedente</flux:label>
                        <flux:input wire:model="bachillerato_procedente" placeholder="Nombre del bachillerato" />
                        <flux:error name="bachillerato_procedente" />
                    </flux:field>
                </div>

                <div class="flex items-center justify-between gap-3 px-4 sm:px-6 pt-3 pb-5">
                    <flux:button type="button" @click="prev()">Anterior</flux:button>
                    <flux:button type="button" @click="next()">Siguiente</flux:button>
                </div>
            </div>
        </section>

        {{-- ====================== STEP 3: ESCOLARES ====================== --}}
        <section x-cloak x-show="is('escolares')" data-panel="escolares" class="absolute inset-0 w-full">
            <div
                class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm w-full">
                <div
                    class="w-full rounded-t-2xl border-b border-neutral-200 dark:border-neutral-800
                    bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white">
                    <h2 class="font-semibold">Datos escolares + Inscripción (datos_escolares + inscripciones)</h2>
                </div>

                <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <flux:field>
                        <flux:label badge="Requerido">Matrícula</flux:label>
                        <flux:input wire:model="matricula" placeholder="Matrícula" />
                        <flux:error name="matricula" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Opcional">Folio</flux:label>
                        <flux:input wire:model="folio" placeholder="Opcional" />
                        <flux:error name="folio" />
                    </flux:field>

                    <flux:field class="lg:col-span-2">
                        <flux:label badge="Opcional">Foto</flux:label>
                        <input type="file" accept="image/png,image/jpeg"
                            class="block w-full text-sm
                            file:mr-4 file:rounded-lg file:border-0 file:bg-neutral-100 file:px-3 file:py-2 hover:file:bg-neutral-200
                            dark:file:bg-neutral-800 dark:hover:file:bg-neutral-700"
                            wire:model="foto" />
                        <p class="mt-2 text-xs text-neutral-500">JPG/PNG hasta 2MB</p>
                        <flux:error name="foto" />
                    </flux:field>
                </div>

                <div class="px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <flux:field>
                        <flux:label badge="Requerido">Licenciatura</flux:label>
                        <flux:select wire:model="licenciatura_id">
                            <flux:select.option value="">-- Selecciona --</flux:select.option>
                            @foreach ($licenciaturas as $lic)
                                <flux:select.option value="{{ $lic->id }}">{{ $lic->nombre }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="licenciatura_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">Generación</flux:label>
                        <flux:select wire:model="generacion_id">
                            <flux:select.option value="">-- Selecciona --</flux:select.option>
                            @foreach ($generaciones as $gen)
                                <flux:select.option value="{{ $gen->id }}">{{ $gen->generacion }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="generacion_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">Cuatrimestre</flux:label>
                        <flux:select wire:model="cuatrimestre_id">
                            <flux:select.option value="">-- Selecciona --</flux:select.option>
                            @foreach ($cuatrimestres as $cuat)
                                <flux:select.option value="{{ $cuat->id }}">
                                    {{ $cuat->no_cuatrimestre ?? $cuat->id }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="cuatrimestre_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">Fecha inscripción</flux:label>
                        <flux:input type="date" wire:model="fecha_inscripcion" />
                        <flux:error name="fecha_inscripcion" />
                    </flux:field>
                </div>

                <div class="px-4 sm:px-6 pt-2">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" class="peer sr-only" wire:model="status">
                        <span
                            class="relative h-6 w-10 rounded-full bg-neutral-300 peer-checked:bg-blue-600 transition">
                            <span
                                class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition peer-checked:translate-x-4"></span>
                        </span>
                        <span class="text-sm text-neutral-700 dark:text-neutral-300">Status activo</span>
                    </label>
                </div>

                <div class="flex items-center justify-between gap-3 px-4 sm:px-6 pt-4 pb-5">
                    <flux:button type="button" @click="prev()">Anterior</flux:button>
                    <flux:button type="button" @click="submit()" wire:loading.attr="disabled"
                        wire:target="guardarInscripcion,foto">
                        <span wire:loading.remove wire:target="guardarInscripcion">Guardar inscripción</span>
                        <span wire:loading wire:target="guardarInscripcion">Guardando…</span>
                    </flux:button>
                </div>
            </div>
        </section>
    </div>

    {{-- ALERTAS --}}
    <script>
        document.addEventListener('livewire:init', () => {
            window.addEventListener('inscripcion-creada', () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Listo',
                        text: 'Inscripción creada correctamente.'
                    });
                }
            });

            window.addEventListener('inscripcion-error', (e) => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: e?.detail?.message ?? 'Ocurrió un error.'
                    });
                }
            });

            window.addEventListener('catalogos-actualizados', () => {
                requestAnimationFrame(() => window.dispatchEvent(new Event('resize')));
            });
        });
    </script>

    {{-- WIZARD --}}
    <script>
        (function() {
            const registerWizard = () => {
                if (!window.Alpine) return;

                window.Alpine.data('wizard', ({
                    initial = 'generales',
                    persistKey = null
                } = {}) => ({
                    current: initial,
                    persistKey,
                    loading: true,

                    steps: [{
                            name: 'generales',
                            label: 'Datos generales'
                        },
                        {
                            name: 'contacto',
                            label: 'Contacto'
                        },
                        {
                            name: 'escolares',
                            label: 'Escolares'
                        },
                    ],

                    bad: {
                        generales: 0,
                        contacto: 0,
                        escolares: 0
                    },

                    ro: null,
                    panelSel(name) {
                        return `section[data-panel="${name}"]`;
                    },
                    currentCard() {
                        const el = this.$root.querySelector(this.panelSel(this.current));
                        return el?.firstElementChild || el;
                    },
                    attachObserver() {
                        if (this.ro) this.ro.disconnect();
                        const card = this.currentCard();
                        const stage = this.$refs.stage;
                        if (!card || !stage) return;

                        stage.style.height = (card.scrollHeight || card.offsetHeight || 0) + 'px';

                        this.ro = new ResizeObserver((entries) => {
                            for (const e of entries) {
                                const h = Math.ceil(e.contentRect.height);
                                if (h > 0) stage.style.height = h + 'px';
                            }
                        });
                        this.ro.observe(card);
                    },

                    init() {
                        try {
                            const saved = localStorage.getItem(this.persistKey);
                            if (saved && this.names().includes(saved)) this.current = saved;
                        } catch {}

                        this.$nextTick(() => {
                            requestAnimationFrame(() => {
                                this.attachObserver();
                                this.loading = false;
                            });
                        });

                        window.addEventListener('resize', () => this.$nextTick(() => this
                            .attachObserver()));
                    },

                    names() {
                        return this.steps.map(s => s.name);
                    },
                    index() {
                        return this.names().indexOf(this.current);
                    },
                    indexOf(n) {
                        return this.names().indexOf(n);
                    },
                    is(n) {
                        return this.current === n;
                    },
                    get progress() {
                        const i = this.index(),
                            total = this.steps.length - 1;
                        return (i <= 0) ? 0 : Math.round((i / total) * 100);
                    },

                    go(name) {
                        if (!this.names().includes(name) || name === this.current) return;
                        this.current = name;

                        if (this.persistKey) {
                            try {
                                localStorage.setItem(this.persistKey, this.current);
                            } catch {}
                        }

                        this.$nextTick(() => this.attachObserver());
                    },
                    next() {
                        const i = this.index();
                        if (i < this.steps.length - 1) this.go(this.steps[i + 1].name);
                    },
                    prev() {
                        const i = this.index();
                        if (i > 0) this.go(this.steps[i - 1].name);
                    },

                    submit() {
                        @this.call('guardarInscripcion');
                    },
                }));
            };

            if (window.Alpine) registerWizard();
            else document.addEventListener('alpine:init', registerWizard);
        })();
    </script>
</div>
