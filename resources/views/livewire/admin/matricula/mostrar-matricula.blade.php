<div x-data="{
    destroyAlumno(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Todos los datos del alumno se eliminarán permanentemente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563EB',
            cancelButtonColor: '#EF4444',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar'
        }).then((r) => r.isConfirmed && @this.call('eliminarAlumno', id))
    },
}" class="space-y-5">
    {{-- HEADER --}}
    <div
        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-5 py-4 text-white">
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Matrícula</h1>
            <p class="text-white/90 text-sm">Gestión de alumnos</p>
        </div>

        <div class="p-4 sm:p-6 space-y-5">
            {{-- FILTROS --}}
            <div class="flex items-center gap-3">
                <svg class="h-7 w-7 text-neutral-700 dark:text-neutral-200" viewBox="0 0 24 24" fill="none"
                    aria-hidden="true">
                    <path d="M3 5h18l-7 8v5l-4 2v-7L3 5z" stroke="currentColor" stroke-width="2"
                        stroke-linejoin="round" />
                </svg>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Filtrar por:</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Licenciatura --}}
                <div>
                    <flux:label>Licenciatura</flux:label>
                    <flux:select wire:model.live="filtrar_licenciatura"
                        class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <flux:select.option value="">--Selecciona una licenciatura--</flux:select.option>
                        @foreach ($licenciaturas as $l)
                            <flux:select.option value="{{ $l->id }}">{{ $l->nombre }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Generación --}}
                <div>
                    <flux:label>Generación</flux:label>
                    <flux:select wire:model.live="filtrar_generacion"
                        class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <flux:select.option value="">--Selecciona una generación--</flux:select.option>
                        @foreach ($generaciones as $g)
                            <flux:select.option value="{{ $g->id }}">{{ $g->generacion }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Cuatrimestre --}}
                <div>
                    <flux:label>Cuatrimestre</flux:label>
                    <flux:select wire:model.live="filtrar_cuatrimestre"
                        class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <flux:select.option value="">--Selecciona un cuatrimestre--</flux:select.option>
                        @foreach ($cuatrimestres as $c)
                            <flux:select.option value="{{ $c->id }}">{{ $c->no_cuatrimestre }}°
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            {{-- BUSCADOR + LIMPIAR --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-end">
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-2">
                        Buscar Estudiante
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-neutral-500">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor"
                                    stroke-width="2" />
                                <path d="M16.5 16.5 21 21" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>

                        <input wire:model.live.debounce.400ms="search" type="text"
                            placeholder="Buscar estudiante (Nombre, Apellido Paterno, Apellido Materno, CURP, Matrícula)"
                            class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 pl-12 pr-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Filtros</span>
                    <button type="button" wire:click="limpiarFiltros"
                        class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 5h18l-7 8v5l-4 2v-7L3 5z" stroke="currentColor" stroke-width="2"
                                stroke-linejoin="round" />
                        </svg>
                        Limpiar filtros
                    </button>
                </div>
            </div>

            {{-- BOTÓN LISTA PDF --}}
            <div>
                <x-button target="_blank"
                    href="{{ route('admin.pdf.listaMatricula', ['filtrar_licenciatura' => $filtrar_licenciatura, 'filtrar_generacion' => $filtrar_generacion, 'filtrar_cuatrimestre' => $filtrar_cuatrimestre, 'search' => $search]) }}"
                    type="button"
                    class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Lista PDF
                </x-button>
            </div>

            {{-- RESUMEN GENERAL --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">
                    Total general de registros:
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 ring-1 ring-indigo-200 dark:ring-indigo-800">
                        {{ $totalGeneral }}
                    </span>
                </div>

                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                    Aquí el total respeta los filtros y la búsqueda.
                </div>
            </div>

            {{-- TABLA --}}
            <div class="relative overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-800">
                {{-- LOADER SOLO PARA LA TABLA --}}
                <div wire:loading
                    wire:target="search, filtrar_licenciatura, filtrar_generacion, filtrar_cuatrimestre, limpiarFiltros, exportarPdf, gotoPage, nextPage, previousPage, eliminarAlumno"
                    class="absolute inset-0 z-20 grid place-items-center bg-white/60 dark:bg-neutral-900/60 backdrop-blur-sm">
                    <div
                        class="rounded-2xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 shadow-xl px-6 py-5 flex items-center gap-3">
                        <svg class="h-6 w-6 animate-spin text-neutral-700 dark:text-neutral-200" viewBox="0 0 24 24"
                            fill="none" aria-hidden="true">
                            <path d="M12 2v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            <path d="M12 18v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                opacity=".4" />
                            <path d="M4.93 4.93l2.83 2.83" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                            <path d="M16.24 16.24l2.83 2.83" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" opacity=".4" />
                            <path d="M2 12h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            <path d="M18 12h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                opacity=".4" />
                            <path d="M4.93 19.07l2.83-2.83" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" opacity=".4" />
                            <path d="M16.24 7.76l2.83-2.83" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                        <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">Cargando…</span>
                    </div>
                </div>

                <div class="overflow-x-auto bg-white dark:bg-neutral-900">
                    <table class="min-w-full text-sm">
                        <thead class="bg-neutral-100 dark:bg-neutral-800/60 text-neutral-700 dark:text-neutral-200">
                            <tr class="text-left">
                                <th class="px-4 py-3 font-semibold text-center text-white">#</th>
                                <th class="px-4 py-3 font-semibold text-center text-white">FOTO</th>
                                <th class="px-4 py-3 font-semibold text-center text-white">MATRÍCULA</th>
                                <th class="px-4 py-3 font-semibold text-center text-white">FOLIO</th>
                                <th class="px-4 py-3 font-semibold text-center text-white">CURP</th>
                                <th class="px-4 py-3 font-semibold text-center text-white">NOMBRE COMPLETO</th>
                                <th class="px-4 py-3 font-semibold text-center text-white">GÉNERO</th>
                                <th class="px-4 py-3 font-semibold text-center text-white">CUATRIMESTRE</th>
                                <th class="px-4 py-3 font-semibold text-center text-white">GENERACIÓN</th>
                                <th class="px-4 py-3 font-semibold text-center text-white">STATUS</th>
                                <th class="px-4 py-3 font-semibold text-center text-white">ACCIONES</th>
                            </tr>
                        </thead>

                        <tbody x-data="{
                            openRow: null,
                            toggle(id) {
                                this.openRow = this.openRow === id ? null : id
                            }
                        }"
                            class="divide-y divide-neutral-200 dark:divide-neutral-800 text-neutral-800 dark:text-neutral-100 text-center">

                            @php $licActualId = null; @endphp

                            @forelse ($registros as $i => $row)
                                @php
                                    $alumno = $row->alumno;
                                    $escolares = $alumno?->datosEscolares;
                                    $documentacion = $alumno?->documentacion;

                                    $nombre = trim(
                                        ($alumno?->nombre ?? '') .
                                            ' ' .
                                            ($alumno?->apellido_paterno ?? '') .
                                            ' ' .
                                            ($alumno?->apellido_materno ?? ''),
                                    );

                                    $curp = $alumno?->curp ?? '—';
                                    $sexo = $alumno?->sexo ?? '—';

                                    $matricula = $escolares?->matricula ?? '—';
                                    $folio = $escolares?->folio ?? '—';
                                    $foto = $escolares?->foto ?? null;

                                    $cuatriTxt = $row->cuatrimestre
                                        ? $row->cuatrimestre->no_cuatrimestre . '° CUATRIMESTRE'
                                        : '—';

                                    $genTxt = $row->generacion?->generacion ?? '—';
                                    $activo = (bool) ($row->status ?? true);

                                    $licId = $row->licenciatura_id ?? 0;
                                    $licNombre = $row->licenciatura?->nombre ?? 'Sin licenciatura';

                                    $rowKey = 'row-' . ($row->id ?? $registros->firstItem() + $i);

                                    $email = $alumno?->user?->email ?? '—';
                                    $username = $alumno?->user?->username ?? '—';

                                    $fechaNac = $alumno?->fecha_nacimiento
                                        ? \Illuminate\Support\Carbon::parse($alumno->fecha_nacimiento)->format('d/m/Y')
                                        : '—';

                                    $edad = $alumno?->fecha_nacimiento
                                        ? \Illuminate\Support\Carbon::parse($alumno->fecha_nacimiento)->age
                                        : '—';

                                    $tieneCurpDoc = !empty($documentacion?->url_curp);
                                    $tieneActaDoc = !empty($documentacion?->url_acta_nacimiento);
                                    $tieneCertificadoDoc = !empty($documentacion?->url_certificado_estudios);

                                    $totalDocs = collect([$tieneCurpDoc, $tieneActaDoc, $tieneCertificadoDoc])
                                        ->filter()
                                        ->count();
                                @endphp

                                {{-- Header por licenciatura --}}
                                @if ($licActualId !== $licId)
                                    @php $licActualId = $licId; @endphp

                                    <tr>
                                        <td colspan="11" class="px-4 py-3">
                                            <div
                                                class="rounded-2xl border border-neutral-200 dark:border-neutral-800 overflow-hidden">
                                                <div
                                                    class="px-4 py-3 text-white bg-gradient-to-r from-emerald-500 via-teal-500 to-sky-600">
                                                    <div class="flex items-center justify-between gap-3">
                                                        <div class="flex items-center gap-3">
                                                            <span
                                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/25">
                                                                <svg class="h-5 w-5" viewBox="0 0 24 24"
                                                                    fill="none" aria-hidden="true">
                                                                    <path d="M4 19V8l8-4 8 4v11" stroke="currentColor"
                                                                        stroke-width="2" stroke-linejoin="round" />
                                                                    <path d="M6 10h12" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round" />
                                                                    <path d="M8 19v-7h8v7" stroke="currentColor"
                                                                        stroke-width="2" stroke-linejoin="round" />
                                                                </svg>
                                                            </span>
                                                            <div class="leading-tight">
                                                                <div class="text-xs text-white/85">LICENCIATURA</div>
                                                                <div class="font-black tracking-tight text-lg">
                                                                    {{ $licNombre }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @php
                                                            $st = $statsPorLic[$licId] ?? [
                                                                'hombres' => 0,
                                                                'mujeres' => 0,
                                                                'activos' => 0,
                                                                'bajas' => 0,
                                                            ];
                                                        @endphp

                                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                                            <span
                                                                class="inline-flex items-center rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300 px-3 py-1 text-xs font-semibold ring-1 ring-sky-200 dark:ring-sky-800">
                                                                H: {{ $st['hombres'] }}
                                                            </span>
                                                            <span
                                                                class="inline-flex items-center rounded-full bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-300 px-3 py-1 text-xs font-semibold ring-1 ring-pink-200 dark:ring-pink-800">
                                                                M: {{ $st['mujeres'] }}
                                                            </span>
                                                            <span
                                                                class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 px-3 py-1 text-xs font-semibold ring-1 ring-emerald-200 dark:ring-emerald-800">
                                                                Activos: {{ $st['activos'] }}
                                                            </span>
                                                            <span
                                                                class="inline-flex items-center rounded-full bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300 px-3 py-1 text-xs font-semibold ring-1 ring-rose-200 dark:ring-rose-800">
                                                                Bajas: {{ $st['bajas'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                {{-- FILA PRINCIPAL --}}
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/40">
                                    <td class="px-4 py-3">{{ $registros->firstItem() + $i }}</td>

                                    <td class="px-4 py-3">
                                        <div
                                            class="h-8 w-8 rounded-full bg-neutral-200 dark:bg-neutral-700 overflow-hidden">
                                            @if (!empty($foto))
                                                <img src="{{ asset('storage/' . $foto) }}" alt="Foto"
                                                    class="h-full w-full object-cover">
                                            @else
                                                <img src="{{ asset('imagenes_publicas/user.png') }}" alt="Foto"
                                                    class="h-full w-full object-cover">
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">{{ $matricula }}</td>
                                    <td class="px-4 py-3">{{ $folio }}</td>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $curp }}</td>
                                    <td class="px-4 py-3">{{ $nombre !== '' ? $nombre : '—' }}</td>
                                    <td class="px-4 py-3">{{ $sexo !== '—' ? strtoupper($sexo) : '—' }}</td>
                                    <td class="px-4 py-3">{{ $cuatriTxt }}</td>
                                    <td class="px-4 py-3">{{ $genTxt }}</td>

                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $activo ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300' }}">
                                            {{ $activo ? 'Activo' : 'Baja' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            {{-- BOTÓN DETALLES --}}
                                            <button type="button" @click="toggle('{{ $rowKey }}')"
                                                class="inline-flex h-9 items-center justify-center gap-2 rounded-xl px-3 text-xs font-bold text-white
                                                       bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 hover:from-sky-600 hover:via-blue-700 hover:to-indigo-700
                                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="h-4 w-4 transition-transform duration-200"
                                                    :class="openRow === '{{ $rowKey }}' ? 'rotate-180' : ''"
                                                    viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <span
                                                    x-text="openRow === '{{ $rowKey }}' ? 'Ocultar' : 'Detalles'"></span>
                                            </button>

                                            {{-- Editar --}}
                                            <flux:button
                                                href="{{ route('admin.matricula.editar.alumno', $row->alumno->id) }}"
                                                variant="primary"
                                                class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white">
                                                <flux:icon.square-pen class="w-3.5 h-3.5" />
                                            </flux:button>

                                            {{-- Eliminar --}}
                                            <flux:button variant="danger"
                                                class="cursor-pointer bg-rose-600 hover:bg-rose-700 text-white p-1"
                                                @click="destroyAlumno({{ $row->alumno->id }})">
                                                <flux:icon.trash-2 class="w-3.5 h-3.5" />
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- COLLAPSE --}}
                                <tr x-cloak x-show="openRow === '{{ $rowKey }}'"
                                    class="bg-neutral-50/60 dark:bg-neutral-800/20">
                                    <td colspan="11" class="px-4 pb-5">
                                        <div x-show="openRow === '{{ $rowKey }}'"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-2"
                                            class="mt-2 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
                                            {{-- Header --}}
                                            <div
                                                class="px-4 py-3 text-white bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div class="flex items-center gap-3">
                                                        <span
                                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/25">
                                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                                aria-hidden="true">
                                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" />
                                                                <path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </span>

                                                        <div class="leading-tight text-left">
                                                            <div class="text-xs text-white/85">DETALLES DEL ALUMNO
                                                            </div>
                                                            <div class="font-black tracking-tight text-base">
                                                                {{ $nombre !== '' ? $nombre : '—' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                                        <span
                                                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold bg-white/15 ring-1 ring-white/25">
                                                            {{ $matricula !== '—' ? 'Matrícula: ' . $matricula : 'Sin matrícula' }}
                                                        </span>

                                                        <span
                                                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold
                                                            {{ $activo ? 'bg-emerald-500/25 ring-1 ring-emerald-200/30' : 'bg-rose-500/25 ring-1 ring-rose-200/30' }}">
                                                            {{ $activo ? 'Activo' : 'Baja' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Contenido --}}
                                            <div class="p-4 sm:p-5">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
                                                    {{-- Foto grande + básicos --}}
                                                    <div
                                                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4">
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="h-14 w-14 rounded-2xl bg-neutral-200 dark:bg-neutral-700 overflow-hidden ring-1 ring-neutral-300 dark:ring-neutral-700">
                                                                @if (!empty($foto))
                                                                    <img src="{{ asset('storage/' . $foto) }}"
                                                                        alt="Foto"
                                                                        class="h-full w-full object-cover">
                                                                @endif
                                                            </div>

                                                            <div class="min-w-0">
                                                                <div
                                                                    class="text-xs text-neutral-500 dark:text-neutral-400">
                                                                    CURP
                                                                </div>
                                                                <div
                                                                    class="font-mono text-xs break-all text-neutral-900 dark:text-neutral-100">
                                                                    {{ $curp }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-3 flex flex-wrap gap-2">
                                                            <span
                                                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200 ring-1 ring-neutral-200 dark:ring-neutral-700">
                                                                Sexo: {{ $sexo !== '—' ? strtoupper($sexo) : '—' }}
                                                            </span>

                                                            <span
                                                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200 ring-1 ring-neutral-200 dark:ring-neutral-700">
                                                                Edad: {{ $edad }}
                                                            </span>
                                                        </div>

                                                        <div class="mt-3 flex flex-wrap gap-2">
                                                            <a target="_blank"
                                                                href="{{ route('admin.pdf.expedienteAlumno', $row->id) }}"
                                                                class="inline-flex h-9 items-center justify-center gap-2 rounded-xl px-3 text-xs font-bold text-white
                                                                       bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 hover:from-sky-600 hover:via-blue-700 hover:to-indigo-700
                                                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                Ver expediente completo
                                                            </a>

                                                            <button type="button"
                                                                x-on:click="$dispatch('abrir-modal-documentos')"
                                                                wire:click="$dispatch('abrir-modal-documentos-livewire', { id: {{ $row->id }} })"
                                                                class="inline-flex h-9 items-center justify-center gap-2 rounded-xl px-3 text-xs font-bold text-white
                                                                bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 hover:from-sky-600 hover:via-blue-700 hover:to-indigo-700
                                                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                Documentación
                                                            </button>

                                                            {{-- CHECK DE DOCUMENTOS --}}
                                                            <div
                                                                class="inline-flex items-center gap-2 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 py-2">
                                                                <div class="flex items-center gap-2">
                                                                    <span
                                                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full {{ $tieneCurpDoc ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300' }}">
                                                                        {{ $tieneCurpDoc ? '✓' : '✕' }}
                                                                    </span>
                                                                    <span
                                                                        class="text-xs font-semibold text-neutral-700 dark:text-neutral-200">
                                                                        CURP
                                                                    </span>
                                                                </div>

                                                                <div class="flex items-center gap-2">
                                                                    <span
                                                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full {{ $tieneActaDoc ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300' }}">
                                                                        {{ $tieneActaDoc ? '✓' : '✕' }}
                                                                    </span>
                                                                    <span
                                                                        class="text-xs font-semibold text-neutral-700 dark:text-neutral-200">
                                                                        Acta
                                                                    </span>
                                                                </div>

                                                                <div class="flex items-center gap-2">
                                                                    <span
                                                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full {{ $tieneCertificadoDoc ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300' }}">
                                                                        {{ $tieneCertificadoDoc ? '✓' : '✕' }}
                                                                    </span>
                                                                    <span
                                                                        class="text-xs font-semibold text-neutral-700 dark:text-neutral-200">
                                                                        Certificado
                                                                    </span>
                                                                </div>

                                                                <span
                                                                    class="ml-1 inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                                                                    {{ $totalDocs }}/3
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Escolares --}}
                                                    <div
                                                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4">
                                                        <div
                                                            class="text-xs font-bold text-neutral-500 dark:text-neutral-400">
                                                            DATOS ESCOLARES
                                                        </div>

                                                        <div class="mt-3 space-y-2 text-sm">
                                                            <div class="flex items-center justify-between gap-3">
                                                                <span
                                                                    class="text-neutral-500 dark:text-neutral-400">Matrícula</span>
                                                                <span
                                                                    class="font-semibold text-neutral-900 dark:text-neutral-100">{{ $matricula }}</span>
                                                            </div>
                                                            <div class="flex items-center justify-between gap-3">
                                                                <span
                                                                    class="text-neutral-500 dark:text-neutral-400">Folio</span>
                                                                <span
                                                                    class="font-semibold text-neutral-900 dark:text-neutral-100">{{ $folio }}</span>
                                                            </div>
                                                            <div class="flex items-center justify-between gap-3">
                                                                <span
                                                                    class="text-neutral-500 dark:text-neutral-400">Cuatrimestre</span>
                                                                <span
                                                                    class="font-semibold text-neutral-900 dark:text-neutral-100">{{ $cuatriTxt }}</span>
                                                            </div>
                                                            <div class="flex items-center justify-between gap-3">
                                                                <span
                                                                    class="text-neutral-500 dark:text-neutral-400">Generación</span>
                                                                <span
                                                                    class="font-semibold text-neutral-900 dark:text-neutral-100">{{ $genTxt }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Cuenta / Extra --}}
                                                    <div
                                                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4">
                                                        <div
                                                            class="text-xs font-bold text-neutral-500 dark:text-neutral-400">
                                                            CUENTA Y EXTRA
                                                        </div>

                                                        <div class="mt-3 space-y-2 text-sm">
                                                            <div class="flex items-center justify-between gap-3">
                                                                <span
                                                                    class="text-neutral-500 dark:text-neutral-400">Licenciatura</span>
                                                                <span
                                                                    class="font-semibold text-neutral-900 dark:text-neutral-100">{{ $licNombre }}</span>
                                                            </div>
                                                            <div class="flex items-center justify-between gap-3">
                                                                <span
                                                                    class="text-neutral-500 dark:text-neutral-400">Fecha
                                                                    nac.</span>
                                                                <span
                                                                    class="font-semibold text-neutral-900 dark:text-neutral-100">{{ $fechaNac }}</span>
                                                            </div>
                                                            <div class="flex items-center justify-between gap-3">
                                                                <span
                                                                    class="text-neutral-500 dark:text-neutral-400">Email</span>
                                                                <span
                                                                    class="font-semibold text-neutral-900 dark:text-neutral-100 break-all">{{ $email }}</span>
                                                            </div>
                                                            <div class="flex items-center justify-between gap-3">
                                                                <span
                                                                    class="text-neutral-500 dark:text-neutral-400">Usuario</span>
                                                                <span
                                                                    class="font-semibold text-neutral-900 dark:text-neutral-100">{{ $username }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                                                    <button type="button" @click="toggle('{{ $rowKey }}')"
                                                        class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 text-neutral-800 dark:text-neutral-100 ring-1 ring-neutral-200 dark:ring-neutral-700">
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                            aria-hidden="true">
                                                            <path d="M6 18L18 6M6 6l12 12" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" />
                                                        </svg>
                                                        Cerrar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11"
                                        class="px-6 py-10 text-center text-neutral-500 dark:text-neutral-400">
                                        No se encontraron estudiantes con los filtros actuales.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                @if ($registros->hasPages())
                    <div
                        class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                        {{ $registros->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-4">
        <livewire:admin.documentos.cargar-documentos />
    </div>
</div>
