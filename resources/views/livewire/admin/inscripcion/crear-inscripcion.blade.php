<div
  x-data="tabsSlide({
    initial: (localStorage.getItem('alumnoTabs') || 'generales'),
    persistKey: 'alumnoTabs'
  })"
  x-id="['tab']"
  class="w-full "
  x-init="init()"
>

<style>
  /* Oculta scrollbar del tablist */
  .no-scrollbar::-webkit-scrollbar{display:none}
  .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}

  /* Evita parpadeos al inicializar Alpine */
  [x-cloak]{ display: none !important; }
</style>

{{-- Encabezado + Tablist --}}
<div class="sticky top-0 z-10 mb-4 w-full ">
    <div >
        <div class="flex items-center justify-between py-3">
            <h1 class="text-lg font-bold  dark:text-neutral-900 p-4  w-full rounded-2xl bg-gradient-to-r from-[#E4F6FF] to-[#F2EFFF] backdrop-blur border-b border-neutral-200 dark:border-neutral-800 ">
                INSCRIPCIÓN DE ESTUDIANTES <br>|
                <span class="text-sm font-normal text-neutral-600 dark:text-neutral-900">Formulario para registrar nuevos estudiantes</span>
            </h1>
        </div>

        <div
            class="relative overflow-x-auto no-scrollbar"
            role="tablist"
            aria-label="Secciones del formulario"
            x-on:keydown.right.prevent="focusNext()"
            x-on:keydown.left.prevent="focusPrev()"
        >
            <div class="inline-flex ">

                {{-- TAB: Generales --}}
                <button
                    :id="tabId('generales')"
                    :aria-controls="panelId('generales')"
                    :aria-selected="is('generales')"
                    x-on:click="set('generales')"
                    x-on:keydown.enter.prevent="set('generales')"
                    role="tab"
                    :tabindex="is('generales') ? 0 : -1"
                    class="group relative whitespace-nowrap rounded-xl px-3.5 py-2 text-sm font-medium
                                 text-neutral-600 hover:text-neutral-900 dark:text-neutral-300 dark:hover:text-white
                   focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500"
          >
            <span class="flex items-center gap-2">
              <svg class="h-4 w-4 opacity-70" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.33 0-8 1.94-8 4.33V21h16v-2.67C20 15.94 16.33 14 12 14Z"/></svg>
              Datos generales
            </span>
            <span class="absolute inset-x-2 -bottom-[3px] h-[3px] rounded-full bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 transition-opacity duration-200"
                  :class="is('generales') ? 'opacity-100' : 'opacity-0'"></span>
          </button>

          {{-- TAB: Contacto --}}
          <button
            :id="tabId('contacto')"
            :aria-controls="panelId('contacto')"
            :aria-selected="is('contacto')"
            x-on:click="set('contacto')"
            role="tab"
            :tabindex="is('contacto') ? 0 : -1"
            class="group relative whitespace-nowrap rounded-xl px-3.5 py-2 text-sm font-medium
                   text-neutral-600 hover:text-neutral-900 dark:text-neutral-300 dark:hover:text-white
                   focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500"
          >
            <span class="flex items-center gap-2">
              <svg class="h-4 w-4 opacity-70" viewBox="0 0 24 24" fill="currentColor"><path d="M21 8V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v1l9 5Z"/><path d="M3 9v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9l-9 5Z"/></svg>
              Datos de contacto
            </span>
            <span class="absolute inset-x-2 -bottom-[3px] h-[3px] rounded-full bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 transition-opacity duration-200"
                  :class="is('contacto') ? 'opacity-100' : 'opacity-0'"></span>
          </button>

          {{-- TAB: Escolares --}}
          <button
            :id="tabId('escolares')"
            :aria-controls="panelId('escolares')"
            :aria-selected="is('escolares')"
            x-on:click="set('escolares')"
            role="tab"
            :tabindex="is('escolares') ? 0 : -1"
            class="group relative whitespace-nowrap rounded-xl px-3.5 py-2 text-sm font-medium
                   text-neutral-600 hover:text-neutral-900 dark:text-neutral-300 dark:hover:text-white
                   focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500"
          >
            <span class="flex items-center gap-2">
              <svg class="h-4 w-4 opacity-70" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3 1 9l11 6 9-4.91V17h2V9z"/><path d="M11 12 3.12 7.69 3 12l8 4 8-4 .02-4.31z" opacity=".25"/></svg>
              Datos escolares
            </span>
            <span class="absolute inset-x-2 -bottom-[3px] h-[3px] rounded-full bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 transition-opacity duration-200"
                  :class="is('escolares') ? 'opacity-100' : 'opacity-0'"></span>
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- STAGE: contenedor que ajusta la altura y superpone paneles --}}
  <div class="px-4 sm:px-6">
    <div
      class="relative overflow-hidden"
      x-ref="stage"
      style="height:auto; transition: height .28s ease;"
    >
      {{-- PANEL: Generales --}}
      <section
        x-show="is('generales')"
        x-transition:enter="transition transform ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-6"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition transform ease-in duration-250"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-6"
        x-on:transitionend.window="resize()"
        role="tabpanel"
        :id="panelId('generales')"
        :aria-labelledby="tabId('generales')"
        data-panel="generales"
        class="absolute inset-0 will-change-transform"
      >
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
          <div class="border-b border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white rounded-t-2xl">
            <h2 class="font-semibold">Datos generales</h2>
          </div>
          <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <label class="block">
              <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Usuario <sup class="text-red-500">*</sup></span>
              <input type="text" class="mt-1 w-full rounded-xl border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 focus:ring-sky-500 focus:border-sky-500" placeholder="—Selecciona un usuario—">
            </label>
            <label class="block">
              <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Matrícula <sup class="text-red-500">*</sup></span>
              <input type="text" class="mt-1 w-full rounded-xl border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 focus:ring-sky-500 focus:border-sky-500" placeholder="Matrícula">
            </label>
            <label class="block">
              <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">CURP <sup class="text-red-500">*</sup></span>
              <input type="text" class="mt-1 w-full rounded-xl border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 uppercase tracking-wider focus:ring-sky-500 focus:border-sky-500" placeholder="CURP">
            </label>
          </div>
        </div>
      </section>

      {{-- PANEL: Contacto --}}
      <section
        x-show="is('contacto')"
        x-transition:enter="transition transform ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-6"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition transform ease-in duration-250"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-6"
        x-on:transitionend.window="resize()"
        role="tabpanel"
        :id="panelId('contacto')"
        :aria-labelledby="tabId('contacto')"
        data-panel="contacto"
        class="absolute inset-0 will-change-transform"
      >
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
          <div class="border-b border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white rounded-t-2xl">
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
        </div>
      </section>

      {{-- PANEL: Escolares --}}
      <section
        x-show="is('escolares')"
        x-transition:enter="transition transform ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-6"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition transform ease-in duration-250"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-6"
        x-on:transitionend.window="resize()"
        role="tabpanel"
        :id="panelId('escolares')"
        :aria-labelledby="tabId('escolares')"
        data-panel="escolares"
        class="absolute inset-0 will-change-transform"
      >
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
          <div class="border-b border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white rounded-t-2xl">
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
                    class="w-full rounded-xl bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 px-4 py-2 text-sm font-medium shadow hover:opacity-90">
                    Guardar
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div> {{-- /stage --}}
  </div> {{-- /px --}}
</div>

{{-- Alpine logic --}}
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('tabsSlide', ({ initial = 'generales', persistKey = null } = {}) => ({
      current: initial,
      persistKey,
      order: ['generales','contacto','escolares'],

      init() {
        // Leer hash si existe
        const hash = location.hash?.replace('#','');
        if (hash && this.order.includes(hash)) this.current = hash;
        this.$nextTick(() => this.resize());
      },

      set(name) {
        if (name === this.current) return;
        this.current = name;
        this.persist();
        history.replaceState(null, '', `#${name}`);
        this.$nextTick(() => this.resize());
      },

      is(name) { return this.current === name; },

      panelId(name) { return `${this.$id('tab')}-panel-${name}`; },
      tabId(name) { return `${this.$id('tab')}-tab-${name}`; },

      focusNext() {
        const i = (this.order.indexOf(this.current) + 1) % this.order.length;
        this.set(this.order[i]);
      },
      focusPrev() {
        const i = (this.order.indexOf(this.current) - 1 + this.order.length) % this.order.length;
        this.set(this.order[i]);
      },

      persist() {
        if (!this.persistKey) return;
        try { localStorage.setItem(this.persistKey, this.current); } catch {}
      },

      resize() {
        // Ajusta la altura del contenedor al panel visible
        const visible = this.$root.querySelector(`section[data-panel="${this.current}"]`);
        const stage = this.$refs.stage;
        if (!visible || !stage) return;
        // medimos el alto del contenido interno (card)
        const card = visible.firstElementChild;
        const h = (card?.scrollHeight || visible.scrollHeight || 0);
        stage.style.height = h + 'px';
      }
    }));
  });
</script>
