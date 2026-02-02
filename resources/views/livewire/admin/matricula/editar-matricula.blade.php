{{-- resources/views/livewire/admin/inscripcion/editar-inscripcion.blade.php --}}
<div x-data="{
    step: 'generales',
    resumenErrores: { generales: 0, contacto: 0, escolares: 0 },

    is(s) { return this.step === s },

    irA(s) {
        this.step = s;
        this.$nextTick(() => this.$dispatch('catalogos-actualizados'))
    },

    badge(n) { return n > 0 ? n : '' },
}" x-on:ir-a-step.window="irA($event.detail.step)"
    x-on:errores-por-step.window="resumenErrores = $event.detail.summary"
    x-on:catalogos-actualizados.window="
        // Aquí dejo el hook por si necesitas refrescar selects custom.
        // Si no lo usas, no pasa nada.
    "
    class="w-full">
    {{-- ===================== HEADER ===================== --}}
    <div
        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-5 py-4 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Editar Alumno</h1>
                    <p class="text-white/90 text-sm">Actualiza la información del alumno</p>
                </div>

                <div class="text-white/90 text-sm">
                    <span class="font-semibold">ID:</span> {{ $inscripcion_id }}
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 space-y-6">
            {{-- ===================== STEPPER / NAV ===================== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                {{-- Generales --}}
                <button type="button"
                    class="group w-full rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-4 py-3 text-left hover:shadow-sm transition"
                    :class="is('generales') ? 'ring-2 ring-sky-400/60' : ''" @click="irA('generales')">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span
                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-300">
                                {{-- icon --}}
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor"
                                        stroke-width="1.8" />
                                    <path d="M4 20a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" />
                                </svg>
                            </span>
                            <div>
                                <div class="font-semibold text-neutral-900 dark:text-white">Datos generales</div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">Alumno + matrícula/folio
                                </div>
                            </div>
                        </div>

                        <template x-if="resumenErrores.generales > 0">
                            <span
                                class="inline-flex items-center justify-center min-w-[26px] h-6 px-2 rounded-full text-xs font-semibold bg-rose-500 text-white"
                                x-text="badge(resumenErrores.generales)"></span>
                        </template>
                    </div>
                </button>

                {{-- Contacto --}}
                <button type="button"
                    class="group w-full rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-4 py-3 text-left hover:shadow-sm transition"
                    :class="is('contacto') ? 'ring-2 ring-sky-400/60' : ''" @click="irA('contacto')">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span
                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 0 1 18 0Z" stroke="currentColor"
                                        stroke-width="1.8" />
                                    <path d="M12 10a2.5 2.5 0 1 0-2.5-2.5A2.5 2.5 0 0 0 12 10Z" stroke="currentColor"
                                        stroke-width="1.8" />
                                </svg>
                            </span>
                            <div>
                                <div class="font-semibold text-neutral-900 dark:text-white">Datos de contacto</div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">Domicilio + ubicación</div>
                            </div>
                        </div>

                        <template x-if="resumenErrores.contacto > 0">
                            <span
                                class="inline-flex items-center justify-center min-w-[26px] h-6 px-2 rounded-full text-xs font-semibold bg-rose-500 text-white"
                                x-text="badge(resumenErrores.contacto)"></span>
                        </template>
                    </div>
                </button>

                {{-- Escolares --}}
                <button type="button"
                    class="group w-full rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-4 py-3 text-left hover:shadow-sm transition"
                    :class="is('escolares') ? 'ring-2 ring-sky-400/60' : ''" @click="irA('escolares')">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span
                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M4 6.5 12 3l8 3.5v6.2c0 4-3 7.6-8 8.8-5-1.2-8-4.8-8-8.8V6.5Z"
                                        stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                    <path d="M9 12.2 11 14l4-4" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div>
                                <div class="font-semibold text-neutral-900 dark:text-white">Inscripción</div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">Licenciatura + fechas + foto
                                </div>
                            </div>
                        </div>

                        <template x-if="resumenErrores.escolares > 0">
                            <span
                                class="inline-flex items-center justify-center min-w-[26px] h-6 px-2 rounded-full text-xs font-semibold bg-rose-500 text-white"
                                x-text="badge(resumenErrores.escolares)"></span>
                        </template>
                    </div>
                </button>
            </div>

            {{-- ===================== CONTENIDO (PANELES) ===================== --}}
            <div class="relative">
                {{-- Panel: Generales --}}
                <section x-cloak x-show="is('generales')" x-transition.opacity.duration.200ms>
                    <div
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
                        <div
                            class="w-full rounded-t-2xl border-b border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 p-4 text-white">
                            <h2 class="font-semibold">Datos generales</h2>
                            <p class="text-white/90 text-xs">Actualiza los datos del alumno, matrícula y folio</p>
                        </div>

                        <div class="p-4 sm:p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                {{-- Estudiante --}}
                                <flux:field>
                                    <flux:label badge="Requerido">Estudiante</flux:label>
                                    <flux:select wire:model="user_id">
                                        <option value="">Selecciona un estudiante...</option>
                                        @foreach ($usuarios as $u)
                                            <option value="{{ $u->id }}">
                                                {{ $u->id }} — {{ $u->name ?? ($u->username ?? $u->email) }}
                                            </option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="user_id" />
                                </flux:field>

                                {{-- CURP --}}
                                <flux:field>
                                    <flux:label badge="Requerido">CURP</flux:label>
                                    <flux:input wire:model="curp" maxlength="18" placeholder="CURP (18 caracteres)" />
                                    <flux:error name="curp" />
                                </flux:field>

                                {{-- Sexo --}}
                                <flux:field>
                                    <flux:label badge="Requerido">Sexo</flux:label>
                                    <flux:select wire:model="sexo">
                                        <option value="">Selecciona...</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </flux:select>
                                    <flux:error name="sexo" />
                                </flux:field>

                                {{-- Fecha nacimiento --}}
                                <flux:field>
                                    <flux:label badge="Requerido">Fecha de nacimiento</flux:label>
                                    <flux:input type="date" wire:model="fecha_nacimiento" />
                                    <flux:error name="fecha_nacimiento" />
                                </flux:field>

                                {{-- Nombre --}}
                                <flux:field class="lg:col-span-2">
                                    <flux:label badge="Requerido">Nombre(s)</flux:label>
                                    <flux:input wire:model="nombre" placeholder="Nombre(s)" />
                                    <flux:error name="nombre" />
                                </flux:field>

                                {{-- Apellido paterno --}}
                                <flux:field>
                                    <flux:label badge="Requerido">Apellido paterno</flux:label>
                                    <flux:input wire:model="apellido_paterno" placeholder="Apellido paterno" />
                                    <flux:error name="apellido_paterno" />
                                </flux:field>

                                {{-- Apellido materno --}}
                                <flux:field>
                                    <flux:label>Apellido materno</flux:label>
                                    <flux:input wire:model="apellido_materno"
                                        placeholder="Apellido materno (opcional)" />
                                    <flux:error name="apellido_materno" />
                                </flux:field>

                                {{-- Matrícula --}}
                                <flux:field>
                                    <flux:label badge="Requerido">Matrícula</flux:label>
                                    <flux:input wire:model="matricula" placeholder="Matrícula" />
                                    <flux:error name="matricula" />
                                </flux:field>

                                {{-- Folio --}}
                                <flux:field>
                                    <flux:label>Folio</flux:label>
                                    <flux:input wire:model="folio" placeholder="Folio (opcional)" />
                                    <flux:error name="folio" />
                                </flux:field>
                            </div>

                            {{-- Navegación --}}
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                    * Los campos con “Requerido” deben completarse.
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        class="inline-flex items-center rounded-xl border border-neutral-200 dark:border-neutral-800 px-4 py-2 text-sm hover:bg-neutral-50 dark:hover:bg-neutral-800"
                                        @click="irA('contacto')">
                                        Siguiente
                                        <svg class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            aria-hidden="true">
                                            <path d="M9 18 15 12 9 6" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Panel: Contacto --}}
                <section x-cloak x-show="is('contacto')" x-transition.opacity.duration.200ms>
                    <div
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
                        <div
                            class="w-full rounded-t-2xl border-b border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-emerald-500 via-teal-600 to-sky-600 p-4 text-white">
                            <h2 class="font-semibold">Datos de contacto</h2>
                            <p class="text-white/90 text-xs">Domicilio, teléfonos y ubicación</p>
                        </div>

                        <div class="p-4 sm:p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                {{-- Calle --}}
                                <flux:field class="lg:col-span-2">
                                    <flux:label>Calle</flux:label>
                                    <flux:input wire:model="calle" placeholder="Calle" />
                                    <flux:error name="calle" />
                                </flux:field>

                                {{-- No. Exterior --}}
                                <flux:field>
                                    <flux:label>No. exterior</flux:label>
                                    <flux:input wire:model="numero_exterior" placeholder="No. ext." />
                                    <flux:error name="numero_exterior" />
                                </flux:field>

                                {{-- No. Interior --}}
                                <flux:field>
                                    <flux:label>No. interior</flux:label>
                                    <flux:input wire:model="numero_interior" placeholder="No. int." />
                                    <flux:error name="numero_interior" />
                                </flux:field>

                                {{-- Colonia --}}
                                <flux:field>
                                    <flux:label>Colonia</flux:label>
                                    <flux:input wire:model="colonia" placeholder="Colonia" />
                                    <flux:error name="colonia" />
                                </flux:field>

                                {{-- Municipio --}}
                                <flux:field>
                                    <flux:label>Municipio</flux:label>
                                    <flux:input wire:model="municipio" placeholder="Municipio" />
                                    <flux:error name="municipio" />
                                </flux:field>

                                {{-- Código postal --}}
                                <flux:field>
                                    <flux:label>Código postal</flux:label>
                                    <flux:input wire:model="codigo_postal" placeholder="C.P." />
                                    <flux:error name="codigo_postal" />
                                </flux:field>

                                {{-- Celular --}}
                                <flux:field>
                                    <flux:label>Celular</flux:label>
                                    <flux:input wire:model="celular" placeholder="Celular" />
                                    <flux:error name="celular" />
                                </flux:field>

                                {{-- Teléfono --}}
                                <flux:field>
                                    <flux:label>Teléfono</flux:label>
                                    <flux:input wire:model="telefono" placeholder="Teléfono (opcional)" />
                                    <flux:error name="telefono" />
                                </flux:field>

                                {{-- Bachillerato procedente --}}
                                <flux:field class="lg:col-span-2">
                                    <flux:label>Bachillerato procedente</flux:label>
                                    <flux:input wire:model="bachillerato_procedente"
                                        placeholder="Bachillerato procedente" />
                                    <flux:error name="bachillerato_procedente" />
                                </flux:field>

                                {{-- País --}}
                                <flux:field>
                                    <flux:label>País</flux:label>
                                    <flux:select wire:model="pais_id">
                                        <option value="">Selecciona país...</option>
                                        @foreach ($countries as $c)
                                            <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="pais_id" />
                                </flux:field>

                                {{-- Estado --}}
                                <flux:field>
                                    <flux:label>Estado</flux:label>
                                    <flux:select wire:model="estado_id">
                                        <option value="">Selecciona estado...</option>
                                        @foreach ($states as $s)
                                            <option value="{{ $s['id'] }}">{{ $s['name'] }}</option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="estado_id" />
                                </flux:field>

                                {{-- Ciudad --}}
                                <flux:field>
                                    <flux:label>Ciudad</flux:label>
                                    <flux:select wire:model="ciudad_id">
                                        <option value="">Selecciona ciudad...</option>
                                        @foreach ($cities as $ci)
                                            <option value="{{ $ci['id'] }}">{{ $ci['name'] }}</option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="ciudad_id" />
                                </flux:field>
                            </div>

                            {{-- Navegación --}}
                            <div class="flex items-center justify-between gap-3">
                                <button type="button"
                                    class="inline-flex items-center rounded-xl border border-neutral-200 dark:border-neutral-800 px-4 py-2 text-sm hover:bg-neutral-50 dark:hover:bg-neutral-800"
                                    @click="irA('generales')">
                                    <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Atrás
                                </button>

                                <button type="button"
                                    class="inline-flex items-center rounded-xl border border-neutral-200 dark:border-neutral-800 px-4 py-2 text-sm hover:bg-neutral-50 dark:hover:bg-neutral-800"
                                    @click="irA('escolares')">
                                    Siguiente
                                    <svg class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M9 18 15 12 9 6" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Panel: Escolares / Inscripción --}}
                <section x-cloak x-show="is('escolares')" x-transition.opacity.duration.200ms>
                    <div
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
                        <div
                            class="w-full rounded-t-2xl border-b border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-indigo-500 via-blue-600 to-sky-600 p-4 text-white">
                            <h2 class="font-semibold">Inscripción</h2>
                            <p class="text-white/90 text-xs">Licenciatura, generación, cuatrimestre, fecha y foto</p>
                        </div>

                        <div class="p-4 sm:p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                {{-- Licenciatura --}}
                                <flux:field class="lg:col-span-2">
                                    <flux:label badge="Requerido">Licenciatura</flux:label>
                                    <flux:select wire:model="licenciatura_id">
                                        <option value="">Selecciona una licenciatura...</option>
                                        @foreach ($licenciaturas as $lic)
                                            <option value="{{ $lic->id }}">
                                                {{ $lic->nombre ?? ($lic->nombre_corto ?? 'Licenciatura #' . $lic->id) }}
                                            </option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="licenciatura_id" />
                                </flux:field>

                                {{-- Generación --}}
                                <flux:field>
                                    <flux:label badge="Requerido">Generación</flux:label>
                                    <flux:select wire:model="generacion_id">
                                        <option value="">Selecciona una generación...</option>
                                        @foreach ($generaciones as $g)
                                            <option value="{{ $g->id }}">
                                                {{ $g->generacion ?? 'Generación #' . $g->id }}</option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="generacion_id" />
                                </flux:field>

                                {{-- Cuatrimestre --}}
                                <flux:field>
                                    <flux:label badge="Requerido">Cuatrimestre</flux:label>
                                    <flux:select wire:model="cuatrimestre_id">
                                        <option value="">Selecciona un cuatrimestre...</option>
                                        @foreach ($cuatrimestres as $c)
                                            <option value="{{ $c->id }}">
                                                {{ $c->nombre_cuatrimestre ?? 'Cuatrimestre #' . $c->id }}</option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="cuatrimestre_id" />
                                </flux:field>

                                {{-- Fecha inscripción --}}
                                <flux:field>
                                    <flux:label badge="Requerido">Fecha de inscripción</flux:label>
                                    <flux:input type="date" wire:model="fecha_inscripcion" />
                                    <flux:error name="fecha_inscripcion" />
                                </flux:field>

                                {{-- Status --}}
                                <flux:field>
                                    <flux:label>Estatus</flux:label>
                                    <flux:select wire:model="status">
                                        <option value="1">Activa</option>
                                        <option value="0">Inactiva</option>
                                    </flux:select>
                                    <flux:error name="status" />
                                </flux:field>

                                {{-- Foto (preview actual / nueva) --}}
                                <div
                                    class="lg:col-span-2 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="font-semibold text-neutral-900 dark:text-white">Foto del alumno
                                            </div>
                                            <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                                Si subes una nueva, se reemplaza la actual.
                                            </div>
                                        </div>

                                        <div wire:loading wire:target="foto"
                                            class="text-xs text-neutral-500 dark:text-neutral-400">
                                            Cargando foto…
                                        </div>
                                    </div>

                                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- Preview actual --}}
                                        <div
                                            class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-3">
                                            <div
                                                class="text-xs font-semibold text-neutral-700 dark:text-neutral-200 mb-2">
                                                Actual</div>

                                            @if ($foto_actual)
                                                <div
                                                    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                                                    <img src="{{ asset('storage/' . $foto_actual) }}"
                                                        alt="Foto actual" class="w-full h-44 object-cover" />
                                                </div>
                                            @else
                                                <div
                                                    class="flex items-center justify-center h-44 rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700 text-sm text-neutral-500 dark:text-neutral-400">
                                                    Sin foto registrada
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Preview nueva --}}
                                        <div
                                            class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 p-3">
                                            <div
                                                class="text-xs font-semibold text-neutral-700 dark:text-neutral-200 mb-2">
                                                Nueva</div>

                                            @if ($foto)
                                                <div
                                                    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                                                    <img src="{{ $foto->temporaryUrl() }}" alt="Foto nueva"
                                                        class="w-full h-44 object-cover" />
                                                </div>
                                            @else
                                                <div
                                                    class="flex items-center justify-center h-44 rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700 text-sm text-neutral-500 dark:text-neutral-400">
                                                    Aún no seleccionas una foto
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <flux:field>
                                            <flux:label>Subir nueva foto</flux:label>
                                            <flux:input type="file" wire:model="foto" accept="image/*" />
                                            <flux:error name="foto" />
                                        </flux:field>
                                    </div>
                                </div>
                            </div>

                            {{-- Navegación + acciones --}}
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <button type="button"
                                    class="inline-flex items-center rounded-xl border border-neutral-200 dark:border-neutral-800 px-4 py-2 text-sm hover:bg-neutral-50 dark:hover:bg-neutral-800"
                                    @click="irA('contacto')">
                                    <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M15 18 9 12l6-6" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Atrás
                                </button>

                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ url()->previous() }}"
                                        class="inline-flex items-center rounded-xl border border-neutral-200 dark:border-neutral-800 px-4 py-2 text-sm hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                        Cancelar
                                    </a>

                                    <button type="button" wire:click="actualizarInscripcion"
                                        wire:loading.attr="disabled" wire:target="actualizarInscripcion,foto"
                                        class="inline-flex items-center rounded-xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:opacity-95 disabled:opacity-60 disabled:cursor-not-allowed">
                                        <span wire:loading.remove
                                            wire:target="actualizarInscripcion,foto">Actualizar</span>
                                        <span wire:loading wire:target="actualizarInscripcion,foto">Guardando…</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Nota --}}
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                * Si hay errores, el sistema te regresa automáticamente al step correspondiente.
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- ===================== MENSAJES / TOAST HOOK ===================== --}}
            <div x-data="{ show: false, msg: '' }"
                x-on:inscripcion-actualizada.window="
                    show = true;
                    msg = 'Inscripción actualizada correctamente.';
                    setTimeout(() => show = false, 3000);
                "
                x-show="show" x-transition.opacity.duration.200ms x-cloak class="fixed bottom-4 right-4 z-50">
                <div
                    class="rounded-2xl border border-emerald-200 dark:border-emerald-900 bg-white dark:bg-neutral-900 shadow-lg px-4 py-3">
                    <div class="flex items-start gap-3">
                        <span
                            class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <div>
                            <div class="font-semibold text-neutral-900 dark:text-white" x-text="msg"></div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">Los cambios ya fueron
                                guardados.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
