<div
  x-data="wizard({
    initial: (localStorage.getItem('alumnoTabs') || 'generales'),
    persistKey: 'alumnoTabs'
  })"
  x-id="['tab']"
  x-init="init()"
  class="w-full"
>
  <style>
    .no-scrollbar::-webkit-scrollbar{display:none}
    .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
    [x-cloak]{ display:none !important; }

    /* Contenedor de paneles: aísla y optimiza el cambio de altura */
    .stage {
      position: relative;
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
    <div class="stage" x-ref="stage">
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
        <div wire:ignore class="card-surface rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm w-full">
          <div class="w-full rounded-t-2xl border-b border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white">
            <h2 class="font-semibold">Datos generales</h2>
          </div>

          <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <flux:field>
              <flux:label>Usuario</flux:label>
              <flux:select wire:model="user_id" placeholder="Selecciona un usuario...">
                <flux:select.option>Usuario 1</flux:select.option>
                <flux:select.option>Usuario 2</flux:select.option>
                <flux:select.option>Usuario 3</flux:select.option>
                <flux:select.option>Usuario 4</flux:select.option>
                <flux:select.option>Usuario 5</flux:select.option>
              </flux:select>
              <flux:error name="username" />
            </flux:field>

            <flux:field>
              <flux:label>CURP</flux:label>
              <flux:input wire:model="curp" placeholder="CURP" />
              <flux:error name="curp" />
            </flux:field>

            <flux:field>
              <flux:label>Matrícula</flux:label>
              <flux:input wire:model="matricula" placeholder="Matrícula" />
              <flux:error name="matricula" />
            </flux:field>

            <flux:field>
              <flux:label>Folio</flux:label>
              <flux:input wire:model="folio" placeholder="Folio" />
              <flux:error name="folio" />
            </flux:field>
          </div>

          <div class="px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <flux:field>
              <flux:label>Nombre</flux:label>
              <flux:input wire:model="nombre" placeholder="Nombre" />
              <flux:error name="nombre" />
            </flux:field>

            <flux:field>
              <flux:label>Apellido paterno</flux:label>
              <flux:input wire:model="apellido_paterno" placeholder="Apellido paterno" />
              <flux:error name="apellido_paterno" />
            </flux:field>

            <flux:field>
              <flux:label>Apellido materno</flux:label>
              <flux:input wire:model="apellido_materno" placeholder="Apellido materno" />
              <flux:error name="apellido_materno" />
            </flux:field>

            <flux:field>
              <flux:label>Fecha de nacimiento</flux:label>
              <flux:input wire:model="fecha_nacimiento" type="date" />
              <flux:error name="fecha_nacimiento" disabled />
            </flux:field>
          </div>

          <div class="px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-4">
            <flux:field>
              <flux:label>Sexo</flux:label>
              <flux:select wire:model="sexo" placeholder="Selecciona una opción...">
                <flux:select.option value="M">Masculino</flux:select.option>
                <flux:select.option value="F">Femenino</flux:select.option>
                <flux:select.option value="O">Otro</flux:select.option>
              </flux:select>
              <flux:error name="sexo" />
            </flux:field>

             {{-- País --}}
  <flux:field>
    <flux:label>País de nacimiento</flux:label>
    <flux:select wire:model.live="pais_nacimiento"
                 placeholder="Selecciona un país..."
                 wire:key="country-select">
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
    <flux:label>Estado de nacimiento</flux:label>
    <flux:select wire:model.live="estado_nacimiento"

                 placeholder="{{ empty($states) ? 'Selecciona primero un país' : 'Selecciona un estado...' }}"
                 wire:key="state-select-{{ $pais_nacimiento ?? 'none' }}">
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
    <flux:label>Lugar de nacimiento</flux:label>
    <flux:select wire:model.live="lugar_nacimiento"

                 placeholder="{{ empty($cities) ? 'Selecciona primero un estado' : 'Selecciona una ciudad...' }}"
                 wire:key="city-select-{{ $estado_nacimiento ?? 'none' }}">
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

          <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <label class="block">
              <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Calle</span>
              <input type="text" class="mt-1 w-full rounded-xl border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 focus:ring-sky-500 focus:border-sky-500" placeholder="Calle">
            </label>
            <label class="block">
              <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Municipio</span>
              <input type="text" class="mt-1 w-full rounded-xl border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 focus:ring-sky-500 focus:border-sky-500" placeholder="Municipio">
            </label>
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
              <button
                type="button"
                class="rounded-xl bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 px-4 py-2 text-sm font-semibold hover:opacity-90"
                @click="next()"
              >
                Siguiente
              </button>
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

          <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <label class="block">
              <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Licenciatura <sup class="text-red-500">*</sup></span>
              <select class="mt-1 w-full rounded-xl border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 focus:ring-sky-500 focus:border-sky-500">
                <option>--Selecciona una licenciatura--</option>
              </select>
            </label>

            <label class="block">
              <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Generación <sup class="text-red-500">*</sup></span>
              <select class="mt-1 w-full rounded-xl border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 focus:ring-sky-500 focus:border-sky-500">
                <option>--Selecciona una generación--</option>
              </select>
            </label>

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

                <div class="mt-5">
                  <button type="button"
                          class="w-full rounded-xl bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 px-4 py-2 text-sm font-semibold shadow hover:opacity-90"
                          @click="submit()">
                    Guardar
                  </button>
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
              <button
                type="button"
                class="rounded-xl bg-blue-600 text-white px-4 py-2 text-sm font-semibold hover:opacity-90"
                @click="submit()"
              >
                Finalizar
              </button>
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
    // Estado
    current: initial,
    persistKey,
    steps: [
      { name: 'generales', label: 'Datos generales' },
      { name: 'contacto',  label: 'Datos de contacto' },
      { name: 'escolares', label: 'Datos escolares' },
    ],

    // ---------- Lifecycle ----------
    init() {
      const hash = (location.hash || '').replace('#','');
      if (hash && this.names().includes(hash)) this.current = hash;

      try {
        const saved = localStorage.getItem(this.persistKey);
        if (saved && this.names().includes(saved)) this.current = saved;
      } catch {}

      this.$nextTick(() => this.resize());
    },

    // ---------- Helpers ----------
    names(){ return this.steps.map(s => s.name); },
    index(){ return this.names().indexOf(this.current); },
    indexOf(n){ return this.names().indexOf(n); },
    is(n){ return this.current === n; },
    isCompleted(n){ return this.indexOf(n) < this.index(); },

    get progress(){
      const i = this.index(), total = this.steps.length - 1;
      return (i <= 0) ? 0 : Math.round((i / total) * 100);
    },

    panelSel(name){ return `section[data-panel="${name}"]`; },

    measure(name){
      const el = this.$root.querySelector(this.panelSel(name));
      const card = el?.firstElementChild;
      return (card?.scrollHeight || el?.scrollHeight || 0) || 0;
    },

    // ---------- Navegación ----------
    go(name){
      if (!this.names().includes(name) || name === this.current) return;

      const stage = this.$refs.stage;
      const root  = this.$root;

      // Marca "en movimiento" para simplificar pintura
      root.classList.add('motioning');

      // Pre-mide el destino y fija altura antes de cambiar
      const nextH = this.measure(name);
      if (nextH) stage.style.height = nextH + 'px';

      // Cambia en el siguiente frame (deja aplicar height primero)
      requestAnimationFrame(() => {
        this.current = name;
        this.persist();
        history.replaceState(null, '', `#${name}`);

        // Quita la marca tras la transición
        setTimeout(() => root.classList.remove('motioning'), 220);
      });
    },

    next(){
      if (!this.validate(this.current)) return;
      const i = this.index();
      if (i < this.steps.length - 1) this.go(this.steps[i+1].name);
    },
    prev(){
      const i = this.index();
      if (i > 0) this.go(this.steps[i-1].name);
    },

    // ---------- Persistencia ----------
    persist(){
      if (!this.persistKey) return;
      try { localStorage.setItem(this.persistKey, this.current); } catch {}
    },

    // ---------- Altura dinámica inicial ----------
    resize(){
      const stage = this.$refs.stage;
      const h = this.measure(this.current);
      if (stage && h) stage.style.height = h + 'px';
    },

    // ---------- Validación / Envío ----------
    validate(){ return true; },
    submit(){ alert('Formulario enviado (demo). Integra aquí tu acción Livewire.'); },
  }));
});
</script>
