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
                    <h2 class="font-semibold">Datos Generales</h2>
                </div>

                {{-- AVISO MODO PRUEBAS CURP --}}
                @if ($curpModoPruebas)
                    <div
                        class="mx-4 sm:mx-6 mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3
                text-amber-900 shadow-sm dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-100">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold">Modo pruebas activado</p>
                                <p class="text-xs opacity-90">
                                    Los datos de CURP se generan/consultan en pruebas y <b>no</b> se usa la API real.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif


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
                        <flux:input wire:model.live="curp" placeholder="18 caracteres" />
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
                    <flux:button class="guardar-btn" type="button" @click="next()">Siguiente</flux:button>
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
                    <h2 class="font-semibold">Datos de contacto</h2>
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
                        <flux:label badge="Opcional">País</flux:label>
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
                        <flux:label badge="Opcional">Estado</flux:label>
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
                        <flux:label badge="Opcional">Ciudad</flux:label>
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
                    <flux:button class="guardar-btn" type="button" @click="next()">Siguiente</flux:button>
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
                    <h2 class="font-semibold">Datos escolares</h2>
                </div>


                <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">


                    <flux:field>
                        <flux:label badge="Requerido">Matrícula</flux:label>

                        <flux:input wire:model.live="matricula" placeholder="Matrícula" />



                        <flux:error name="matricula" />
                    </flux:field>


                    <flux:field>
                        <flux:label badge="Requerido">Folio</flux:label>
                        <flux:input wire:model="folio" placeholder="Folio" />
                        <flux:error name="folio" />
                    </flux:field>

                    <flux:field class="lg:col-span-2">
                        <flux:label badge="Opcional">Foto del estudiante</flux:label>

                        <div x-data
                            class="relative rounded-2xl border-2 border-dashed border-neutral-200 dark:border-neutral-800
               bg-neutral-50/70 dark:bg-neutral-900/40 p-4 sm:p-5
               hover:bg-neutral-50 dark:hover:bg-neutral-900/60
               transition-colors cursor-pointer"
                            role="button" tabindex="0" @click="$refs.foto.click()"
                            @keydown.enter.prevent="$refs.foto.click()" @keydown.space.prevent="$refs.foto.click()"
                            aria-label="Seleccionar foto del estudiante">
                            <div class="flex items-start gap-4">
                                {{-- Icono --}}
                                <div
                                    class="mt-0.5 inline-flex h-10 w-10 items-center justify-center rounded-xl
                        bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-neutral-600 dark:text-neutral-300" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path
                                            d="M4 7a2 2 0 0 1 2-2h3l1-1h4l1 1h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7zm8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" />
                                    </svg>
                                </div>

                                {{-- Textos --}}
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                        Seleccionar archivo
                                    </p>
                                    <p class="mt-1 text-xs text-neutral-500">
                                        Subir imagen JPG o PNG (máx. 2MB)
                                    </p>

                                    {{-- Nombre del archivo (cuando ya se eligió uno) --}}
                                    @if ($foto)
                                        <p class="mt-2 text-xs text-neutral-600 dark:text-neutral-300 truncate">
                                            Archivo: {{ $foto->getClientOriginalName() }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Botón visual --}}
                                <div class="shrink-0">
                                    <span
                                        class="inline-flex items-center rounded-xl px-3 py-2 text-xs font-semibold
                           bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800
                           text-neutral-700 dark:text-neutral-200">
                                        Elegir
                                    </span>
                                </div>
                            </div>

                            {{-- Input real (oculto) --}}
                            <input x-ref="foto" type="file" accept="image/png,image/jpeg" class="sr-only"
                                wire:model="foto" />

                            {{-- Loader cuando sube --}}
                            <div wire:loading wire:target="foto"
                                class="absolute inset-0 rounded-2xl bg-white/60 dark:bg-neutral-900/60
                   backdrop-blur-sm flex items-center justify-center">
                                <div class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 0 1 8-8v3a5 5 0 0 0-5 5H4z"></path>
                                    </svg>
                                    Subiendo…
                                </div>
                            </div>
                        </div>

                        <flux:error name="foto" />
                    </flux:field>

                </div>

                {{-- ✅ FILA 2 (igual que la imagen): Generación | Licenciatura | Fecha | Cuatrimestre --}}
                <div class="px-4 sm:px-6 pb-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <flux:field>
                        <flux:label badge="Requerido">Generación</flux:label>
                        <flux:select wire:model="generacion_id">
                            <flux:select.option value="">Selecciona una generación…</flux:select.option>
                            @foreach ($generaciones as $gen)
                                <flux:select.option value="{{ $gen->id }}">{{ $gen->generacion }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="generacion_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">Licenciatura</flux:label>
                        <flux:select wire:model.live="licenciatura_id">
                            <flux:select.option value="">Selecciona la licenciatura…</flux:select.option>
                            @foreach ($licenciaturas as $lic)
                                <flux:select.option value="{{ $lic->id }}">{{ $lic->nombre }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="licenciatura_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Requerido">Cuatrimestre</flux:label>
                        <flux:select wire:model.live="cuatrimestre_id">
                            <flux:select.option value="">Selecciona un cuatrimestre…</flux:select.option>
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

                {{-- ✅ STATUS (debajo, como en la imagen) --}}
                <div class="px-4 sm:px-6 pb-5">
                    <flux:field>
                        <flux:label>Status</flux:label>
                        <flux:switch wire:model.live="status" />
                        <flux:error name="status" />
                    </flux:field>
                </div>

                <div
                    class="flex items-center justify-between gap-3 px-4 sm:px-6 pt-4 pb-5 border-t border-neutral-200 dark:border-neutral-800">
                    <flux:button type="button" @click="prev()">Anterior</flux:button>

                    <flux:button class="guardar-btn" type="button" @click="submit()" wire:loading.attr="disabled"
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
