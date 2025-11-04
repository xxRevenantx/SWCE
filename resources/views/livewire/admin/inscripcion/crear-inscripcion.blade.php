<div
  x-data="wizard({ initial: (localStorage.getItem('alumnoTabs') || 'generales'), persistKey: 'alumnoTabs' })"
  x-id="['tab']"
  x-init="init()"
  @ir-a-step.window="go($event.detail.step); $nextTick(() => {
      // asegura altura correcta y foco visual
      attachObserver();
      document.getElementById(panelId($event.detail.step))?.scrollIntoView({behavior:'smooth', block:'start'});
  })"
  @errores-por-step.window="bad = $event.detail.summary; $nextTick(() => attachObserver())"
  class="w-full"
>
  <style>
    .no-scrollbar::-webkit-scrollbar{display:none}
    .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
    [x-cloak]{ display:none !important; }

    /* Contenedor de paneles: aísla y optimiza el cambio de altura */
    .stage{
    position: relative;
    overflow: hidden;      /* importante si las secciones son absolute */
    contain: layout paint style;
    will-change: height;
    transition: height .2s ease;
    }

    /* Mientras hay transición, simplifica pintura (menos costo) */
    .motioning .card-surface {
      box-shadow: none !important;
      border-color: transparent !important;
    }

    /* Header: sin backdrop-blur (muy costoso en dark) */
    .hdr { backdrop-filter: none; }

    @media (prefers-reduced-motion: reduce) {
      .stage { transition: none; }
    }
  </style>

  <!-- Encabezado -->
  <header class="sticky top-0 z-20 mb-4 w-full">
    <div
      class="hdr rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-sm
             bg-gradient-to-r from-[#E4F6FF] to-[#F2EFFF]
             dark:bg-gradient-to-r dark:from-[#111318] dark:to-[#111318]"
    >
      <div class="px-4 py-4">
        <h1 class="text-lg font-bold text-neutral-900 dark:text-white">INSCRIPCIÓN DE ESTUDIANTES</h1>
        <p class="text-sm text-neutral-700 dark:text-neutral-300">Formulario para registrar nuevos estudiantes</p>
      </div>

      <!-- Stepper + Progreso -->
      <div class="px-4 pb-4">
        <div class="relative">
          <div class="h-1 w-full rounded-full bg-neutral-200 dark:bg-neutral-700"></div>
          <div
            class="absolute inset-y-0 left-0 h-1 rounded-full
                   bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600
                   dark:from-sky-400 dark:via-blue-500 dark:to-indigo-500
                   transition-[width] duration-200"
            :style="`width: ${progress}%`"
            aria-hidden="true"
          ></div>
        </div>

        <nav
          class="mt-3 relative overflow-x-auto no-scrollbar"
          role="tablist"
          aria-label="Secciones del formulario"
          @keydown.right.prevent="focusNext()"
          @keydown.left.prevent="focusPrev()"
        >
          <ul class="inline-flex items-center gap-2 md:gap-3">
            <template x-for="step in steps" :key="step.name">
              <li>
                <button
                  class="group relative flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-medium
                         focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500"
                  role="tab"
                  :tabindex="is(step.name) ? 0 : -1"
                  :id="tabId(step.name)"
                  :aria-controls="panelId(step.name)"
                  :aria-selected="is(step.name)"
                  @click="go(step.name)"
                  @keydown.enter.prevent="go(step.name)"
                >
                  <span
                    class="flex h-6 w-6 items-center justify-center rounded-full border text-xs font-semibold"
                    :class="isCompleted(step.name)
                      ? 'border-blue-600 bg-blue-600 text-white'
                      : (is(step.name)
                          ? 'border-blue-600 text-blue-600 dark:border-sky-400 dark:text-sky-400'
                          : 'border-neutral-300 text-neutral-500 dark:border-neutral-700 dark:text-neutral-300')"
                  >
                    <span x-text="indexOf(step.name)+1"></span>
                  </span>

                  <span
                    class="whitespace-nowrap text-neutral-700 dark:text-neutral-200"
                    :class="is(step.name) ? 'text-neutral-900 dark:text-white' : ''"
                    x-text="step.label"
                  ></span>

                  <span
                    class="absolute inset-x-2 -bottom-[3px] h-[3px] rounded-full
                           bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600
                           dark:from-sky-400 dark:via-blue-500 dark:to-indigo-500
                           transition-opacity duration-200"
                    :class="is(step.name) ? 'opacity-100' : 'opacity-0'"
                  ></span>
                </button>
              </li>
            </template>
          </ul>
        </nav>
      </div>
    </div>
  </header>

  <!-- Contenido -->
  <div>
<div class="stage" x-ref="stage" wire:ignore.self>


      <!-- Generales -->
      <section
        x-cloak
        x-show="is('generales')"
        x-transition:enter="transition transform-gpu ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-x-4"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition transform-gpu ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-4"
        role="tabpanel"
        :id="panelId('generales')"
        :aria-labelledby="tabId('generales')"
        data-panel="generales"
        class="absolute inset-0 w-full"

      >
        <div  class="card-surface rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm w-full">
          <div class="w-full rounded-t-2xl border-b border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white">
            <h2 class="font-semibold">Datos generales</h2>
          </div>

          <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <flux:field>
              <flux:label badge="Requerido">Usuario</flux:label>
              <flux:select wire:model="user_id" placeholder="Selecciona un usuario...">
                <flux:select.option value="">--Selecciona un usuario--</flux:select.option>
                @foreach($usuarios as $usuario)
                  <flux:select.option value="{{ $usuario->id }}">
                    {{ $usuario->username }}
                  </flux:select.option>
                @endforeach
              </flux:select>
              <flux:error name="username" />
            </flux:field>

            <flux:field>
              <flux:label badge="Requerido">CURP</flux:label>
              <flux:input wire:model="CURP" placeholder="CURP" />
              <flux:error name="curp" />
            </flux:field>

            <flux:field>
              <flux:label badge="Opcional">Matrícula</flux:label>
              <flux:input wire:model="matricula" placeholder="Matrícula" />
              <flux:error name="matricula" />
            </flux:field>

            <flux:field>
              <flux:label badge="Opcional">Folio</flux:label>
              <flux:input wire:model="folio" placeholder="Folio" />
              <flux:error name="folio" />
            </flux:field>
          </div>

          <div class="px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <flux:field>
              <flux:label badge="Requerido">Nombre</flux:label>
              <flux:input wire:model="nombre" placeholder="Nombre" />
              <flux:error name="nombre" />
            </flux:field>

            <flux:field>
              <flux:label badge="Opcional">Apellido paterno</flux:label>
              <flux:input wire:model="apellido_paterno" placeholder="Apellido paterno" />
              <flux:error name="apellido_paterno" />
            </flux:field>

            <flux:field>
              <flux:label badge="Opcional">Apellido materno</flux:label>
              <flux:input wire:model="apellido_materno" placeholder="Apellido materno" />
              <flux:error name="apellido_materno" />
            </flux:field>

            <flux:field>
              <flux:label badge="Requerido">Fecha de nacimiento</flux:label>
              <flux:input wire:model="fecha_nacimiento" type="date" />
              <flux:error name="fecha_nacimiento" disabled />
            </flux:field>
          </div>

          <div class="px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-4">
            <flux:field>
              <flux:label badge="Requerido">Sexo</flux:label>
              <flux:select wire:model="sexo" placeholder="Selecciona una opción...">
                <flux:select.option value="">--Selecciona una opción--</flux:select.option>
                <flux:select.option value="M">Masculino</flux:select.option>
                <flux:select.option value="F">Femenino</flux:select.option>
                <flux:select.option value="O">Otro</flux:select.option>
              </flux:select>
              <flux:error name="sexo" />
            </flux:field>

             {{-- País --}}
  <flux:field>
    <flux:label badge="Opcional">País de nacimiento</flux:label>
    <flux:select wire:model.live="pais_nacimiento"
                 placeholder="Selecciona un país..."
                 wire:key="country-select">
       <flux:select.option value="">--Selecciona un país de Nacimiento--</flux:select.option>
      @foreach($countries as $country)
        <flux:select.option value="{{ $country['id'] }}">
          {{ $country['name'] }}
        </flux:select.option>
      @endforeach
    </flux:select>
    <flux:error name="pais_nacimiento" />
  </flux:field>

  {{-- Estado (depende de país) --}}
  <flux:field>
    <flux:label badge="Opcional">Estado de nacimiento</flux:label>
    <flux:select wire:model.live="estado_nacimiento"

                 placeholder="{{ empty($states) ? 'Selecciona primero un país' : 'Selecciona un estado...' }}"
                 wire:key="state-select-{{ $pais_nacimiento ?? 'none' }}">
      <flux:select.option value="">--Selecciona un estado de Nacimiento--</flux:select.option>
      @foreach($states as $state)
        <flux:select.option value="{{ $state['id'] }}">
          {{ $state['name'] }}
        </flux:select.option>
      @endforeach
    </flux:select>
    <flux:error name="estado_nacimiento" />
  </flux:field>

  {{-- Ciudad (depende de estado) --}}
  <flux:field>
    <flux:label badge="Opcional">Lugar de nacimiento</flux:label>
    <flux:select wire:model.live="lugar_nacimiento"

                 placeholder="{{ empty($cities) ? 'Selecciona primero un estado' : 'Selecciona una ciudad...' }}"
                 wire:key="city-select-{{ $estado_nacimiento ?? 'none' }}">
        <flux:select.option value="">--Selecciona una ciudad de Nacimiento--</flux:select.option>
      @foreach($cities as $city)
        <flux:select.option value="{{ $city['id'] }}">
          {{ $city['name'] }}
        </flux:select.option>
      @endforeach
    </flux:select>
    <flux:error name="lugar_nacimiento" />
  </flux:field>
          </div>

          <!-- Controles -->
          <div class="flex items-center justify-between gap-3 px-4 sm:px-6 pt-5 pb-2">
            <flux:button type="button" class="cancelar-btn" disabled>Anterior</flux:button>
            <div class="flex items-center gap-3">
              <flux:button type="button" class="guardar-btn" @click="next()">Siguiente</flux:button>
            </div>
          </div>
        </div>
      </section>

      <!-- Contacto -->
      <section
        x-cloak
        x-show="is('contacto')"
        x-transition:enter="transition transform-gpu ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-x-4"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition transform-gpu ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-4"
        role="tabpanel"
        :id="panelId('contacto')"
        :aria-labelledby="tabId('contacto')"
        data-panel="contacto"
        class="absolute inset-0 w-full"
      >
        <div class="card-surface rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm w-full">
          <div class="w-full rounded-t-2xl border-b border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white">
            <h2 class="font-semibold">Datos de contacto</h2>
          </div>

          <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <flux:field>
              <flux:label badge="Opcional">Calle</flux:label>
              <flux:input wire:model="calle" placeholder="Ingresa la calle" />
              <flux:error name="calle" />
            </flux:field>

            {{-- NUMERO EXTERIOR --}}
            <flux:field>
              <flux:label badge="Opcional">Número exterior</flux:label>
              <flux:input wire:model="numero_exterior" placeholder="Ingresa el número exterior" />
              <flux:error name="numero_exterior" />
            </flux:field>
            {{-- NUMERO INTERIOR --}}
            <flux:field>
              <flux:label badge="Opcional">Número interior</flux:label>
              <flux:input wire:model="numero_interior" placeholder="Ingresa el número interior" />
              <flux:error name="numero_interior" />
            </flux:field>
                           {{-- COLONIA  --}}
            <flux:field>
              <flux:label badge="Opcional">Colonia</flux:label>
              <flux:input wire:model="colonia" placeholder="Ingresa la colonia" />
              <flux:error name="colonia" />
            </flux:field>
          </div>
         <div class="px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- CODIGO POSTAL --}}
            <flux:field>
              <flux:label badge="Opcional">Código postal</flux:label>
              <flux:input wire:model="codigo_postal" placeholder="Ingresa el código postal" />
              <flux:error name="codigo_postal" />
            </flux:field>
            {{-- MUNICIPIO --}}
            <flux:field>
              <flux:label badge="Opcional">Municipio</flux:label>
              <flux:input wire:model="municipio" placeholder="Ingresa el municipio" />
              <flux:error name="municipio" />
            </flux:field>

            {{-- ESTADO DE RESIDENCIA --}}
            <flux:field>
              <flux:label badge="Opcional">Estado de residencia</flux:label>
                <flux:select wire:model="estado_residencia"
                             placeholder="Selecciona un estado...">
                  <flux:select.option>--Selecciona un estado--</flux:select.option>
                    @foreach($states as $state_residencia)
                        <flux:select.option value="{{ $state_residencia['id'] }}">
                        {{ $state_residencia['name'] }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
            {{-- CIUDAD DE RESIDENCIA --}}
            <flux:field>
              <flux:label badge="Opcional">Ciudad de residencia</flux:label>
                <flux:select wire:model="ciudad_residencia"
                             placeholder="Selecciona una ciudad...">
                  <flux:select.option>--Selecciona una ciudad--</flux:select.option>
                    @foreach($cities as $city_residencia)
                        <flux:select.option value="{{ $city_residencia['id'] }}">
                        {{ $city_residencia['name'] }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
          </div>

        <div class="p-6 sm:px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- CELULAR --}}
            <flux:field>
              <flux:label badge="Opcional">Celular</flux:label>
              <flux:input wire:model="celular" placeholder="Ingresa el número de celular" />
              <flux:error name="celular" />
            </flux:field>
            {{-- TELÉFONO FIJO --}}
            <flux:field>
              <flux:label badge="Opcional">Teléfono fijo</flux:label>
              <flux:input wire:model="telefono_fijo" placeholder="Ingresa el número de teléfono fijo" />
              <flux:error name="telefono_fijo" />
            </flux:field>
            {{-- EMAIL --}}
            <flux:field>
              <flux:label badge="Requerido">Correo electrónico</flux:label>
              <flux:input wire:model="email" type="email" placeholder="Ingresa el correo electrónico" />
              <flux:error name="email" />
            </flux:field>
            {{-- TUTOR --}}
            <flux:field>
              <flux:label badge="Opcional" >Tutor</flux:label>
              <flux:input wire:model="tutor" placeholder="Ingresa el nombre del tutor" />
              <flux:error name="tutor" />
            </flux:field>


          </div>

          <!-- Controles -->
          <div class="flex items-center justify-between gap-3 px-4 sm:px-6 pt-5 pb-2">
            <flux:button type="button" class="cancelar-btn" disabled>Anterior</flux:button>
            <div class="flex items-center gap-3">
              <flux:button type="button" class="guardar-btn" @click="next()">Siguiente</flux:button>
            </div>
          </div>
        </div>
      </section>

      <!-- Escolares -->
      <section
        x-cloak
        x-show="is('escolares')"
        x-transition:enter="transition transform-gpu ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-x-4"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition transform-gpu ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-4"
        role="tabpanel"
        :id="panelId('escolares')"
        :aria-labelledby="tabId('escolares')"
        data-panel="escolares"
        class="absolute inset-0 w-full"
      >
        <div class="card-surface rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">


            <div class="rounded-t-2xl border-b border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white">
            <h2 class="font-semibold">Datos escolares</h2>
          </div>

          <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-4 items-center">
            <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">

            {{-- BACHILLERATO PROCEDENTE --}}
            <flux:field>
              <flux:label>Bachillerato procedente</flux:label>
              <flux:input wire:model="bachillerato_procedente" placeholder="Ingresa el nombre del bachillerato" />
              <flux:error name="bachillerato_procedente" />
            </flux:field>

            {{-- LICENCIATURA --}}
            <flux:field>
              <flux:label>Licenciatura</flux:label>
              <flux:input wire:model="licenciatura" placeholder="Ingresa el nombre de la licenciatura" />
              <flux:error name="licenciatura" />
            </flux:field>
            {{-- GENERACIÓN --}}

              <flux:field>
                <flux:label>Generación</flux:label>
                <flux:select wire:model="generacion" placeholder="Selecciona una generación...">
                  <flux:select.option>--Selecciona una generación--</flux:select.option>
                  <flux:select.option value="2020-2024">2020-2024</flux:select.option>
                  <flux:select.option value="2021-2025">2021-2025</flux:select.option>
                  <flux:select.option value="2022-2026">2022-2026</flux:select.option>
                  <flux:select.option value="2023-2027">2023-2027</flux:select.option>
                </flux:select>
                <flux:error name="generacion" />
              </flux:field>

              {{-- CUATRIMESTRE --}}
              <flux:field>
                <flux:label>Cuatrimestre</flux:label>
                <flux:select wire:model="cuatrimestre" placeholder="Selecciona un cuatrimestre...">
                  <flux:select.option>--Selecciona un cuatrimestre--</flux:select.option>
                  <flux:select.option value="1">1</flux:select.option>
                  <flux:select.option value="2">2</flux:select.option>
                  <flux:select.option value="3">3</flux:select.option>
                  <flux:select.option value="4">4</flux:select.option>
                  <flux:select.option value="5">5</flux:select.option>
                  <flux:select.option value="6">6</flux:select.option>
                  <flux:select.option value="7">7</flux:select.option>
                  <flux:select.option value="8">8</flux:select.option>
                </flux:select>
                <flux:error name="cuatrimestre" />
              </flux:field>

            </div>


            <div class="md:col-span-2 lg:col-span-1">
              <div class="rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 p-4 sm:p-5">
                <p class="text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-3">Foto del estudiante</p>
                <input type="file" accept="image/png,image/jpeg"
                       class="block w-full text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-neutral-100 file:px-3 file:py-2 hover:file:bg-neutral-200 dark:file:bg-neutral-800 dark:hover:file:bg-neutral-700" />
                <p class="mt-2 text-xs text-neutral-500">Subir una imagen JPG o PNG</p>

                <div class="mt-4">
                  <label class="flex items-center gap-3">
                    <input type="checkbox" class="peer sr-only">
                    <span class="relative h-6 w-10 rounded-full bg-neutral-300 peer-checked:bg-blue-600 transition">
                      <span class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition peer-checked:translate-x-4"></span>
                    </span>
                    <span class="text-sm text-neutral-700 dark:text-neutral-300">Status</span>
                  </label>
                </div>


              </div>
            </div>

            </div>


         <!-- Controles -->
          <div class="flex items-center justify-between gap-3 px-4 sm:px-6 pb-5">
            <button
              type="button"
              class="rounded-xl border border-neutral-300 dark:border-neutral-700 px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-800"
              @click="prev()"
            >
              Anterior
            </button>
            <div class="flex items-center gap-3">
              <flux:button
                type="button"
                class="guardar-btn"
                @click="submit()"
              >
                Guardar inscripción
              </flux:button>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('wizard', ({ initial = 'generales', persistKey = null } = {}) => ({
    current: initial,
    persistKey,
    steps: [
      { name: 'generales', label: 'Datos generales' },
      { name: 'contacto',  label: 'Datos de contacto' },
      { name: 'escolares', label: 'Datos escolares' },
    ],

     bad: { generales:0, contacto:0, escolares:0 },

    // --- NUEVO: observer ---
    ro: null,
    panelSel(name){ return `section[data-panel="${name}"]`; },
    currentCard(){
      const el = this.$root.querySelector(this.panelSel(this.current));
      return el?.firstElementChild || el; // la .card-surface
    },
    attachObserver(){
      // desconecta anterior
      if (this.ro) this.ro.disconnect();
      const card = this.currentCard();
      const stage = this.$refs.stage;
      if (!card || !stage) return;

      // altura inicial inmediata
      stage.style.height = (card.scrollHeight || card.offsetHeight || 0) + 'px';

      // observa cambios de tamaño del panel visible
      this.ro = new ResizeObserver((entries) => {
        for (const e of entries) {
          // usar contentRect es más estable y sin reflow
          const h = Math.ceil(e.contentRect.height);
          if (h > 0) stage.style.height = h + 'px';
        }
      });
      this.ro.observe(card);
    },

    // --- ciclo de vida ---
    init(){
      const hash = (location.hash || '').replace('#','');
      if (hash && this.names().includes(hash)) this.current = hash;

      try {
        const saved = localStorage.getItem(this.persistKey);
        if (saved && this.names().includes(saved)) this.current = saved;
      } catch {}

      // espera a que quite x-cloak y pinte
      this.$nextTick(() => {
        requestAnimationFrame(() => this.attachObserver());
      });

      // reajusta en cambios de viewport
      window.addEventListener('resize', () => this.$nextTick(() => this.attachObserver()));
    },

    // --- helpers ---
    names(){ return this.steps.map(s => s.name); },
    index(){ return this.names().indexOf(this.current); },
    indexOf(n){ return this.names().indexOf(n); },
    is(n){ return this.current === n; },
    isCompleted(n){ return this.indexOf(n) < this.index(); },
    get progress(){
      const i = this.index(), total = this.steps.length - 1;
      return (i <= 0) ? 0 : Math.round((i / total) * 100);
    },

    // --- navegación ---
    go(name){
      if (!this.names().includes(name) || name === this.current) return;
      const stage = this.$refs.stage;
      const root  = this.$root;

      root.classList.add('motioning');

      requestAnimationFrame(() => {
        this.current = name;
        this.persist();
        history.replaceState(null, '', `#${name}`);

        // reatacha el observer al nuevo panel
        this.$nextTick(() => this.attachObserver());

        setTimeout(() => root.classList.remove('motioning'), 220);
      });
    },
    next(){ const i = this.index(); if (i < this.steps.length - 1) this.go(this.steps[i+1].name); },
    prev(){ const i = this.index(); if (i > 0) this.go(this.steps[i-1].name); },

    // --- persistencia ---
    persist(){ if (!this.persistKey) return; try { localStorage.setItem(this.persistKey, this.current); } catch {} },

    // ya no necesitas measure()/resize(), el observer se encarga
    validate(){ return true; },
    submit(){ @this.call('guardarInscripcion'); },
  }));
});

</script>
