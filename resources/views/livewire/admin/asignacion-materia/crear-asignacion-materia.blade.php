<div class="flex w-full flex-1 flex-col gap-6">

    {{-- Header --}}
    <div
        class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight text-neutral-900 dark:text-white">
                        Asignación de Materias
                    </h2>
                </div>

                {{-- Search --}}
                <div class="w-full sm:w-[420px]">
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M10 4a6 6 0 104.472 10.03l3.749 3.75 1.414-1.415-3.75-3.749A6 6 0 0010 4zm0 2a4 4 0 110 8 4 4 0 010-8z" />
                            </svg>
                        </span>
                        <flux:input type="text" wire:model.live.debounce.350ms="search"
                            placeholder="Buscar materia (nombre, clave o slug)…" />
                    </div>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="grid gap-3 sm:grid-cols-12">
                <div class="sm:col-span-4">
                    <label class="text-xs font-semibold text-neutral-600 dark:text-neutral-300">Filtrar por
                        Licenciatura</label>
                    <flux:select wire:model.live="filtrar_licenciatura" class="mt-2">
                        <flux:select.option value="">--Todas--</flux:select.option>
                        @foreach ($licenciaturas as $l)
                            <flux:select.option value="{{ $l->id }}">{{ $l->nombre }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="sm:col-span-4">
                    <label class="text-xs font-semibold text-neutral-600 dark:text-neutral-300">Filtrar por
                        Cuatrimestre</label>
                    <flux:select wire:model.live="filtrar_cuatrimestre" class="mt-2">
                        <flux:select.option value="">--Todos--</flux:select.option>
                        @foreach ($cuatrimestres as $c)
                            <flux:select.option value="{{ $c->id }}">
                                {{ $c->nombre_cuatrimestre ?? 'Cuatrimestre ' . $c->no_cuatrimestre }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="sm:col-span-2">
                    <label class="text-xs font-semibold text-neutral-600 dark:text-neutral-300">Por página</label>
                    <flux:select wire:model.live="por_pagina" class="mt-2">
                        <flux:select.option value="10">10</flux:select.option>
                        <flux:select.option value="25">25</flux:select.option>
                        <flux:select.option value="50">50</flux:select.option>
                        <flux:select.option value="100">100</flux:select.option>
                    </flux:select>
                </div>

                <div class="sm:col-span-2 flex items-end">
                    <flux:button type="button" wire:click="limpiarFiltros" variant="primary"
                        class="w-full bg-indigo-500 hover:bg-indigo-600 focus:ring-indigo-400"
                        wire:loading.attr="disabled">
                        Limpiar
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    {{-- Loader overlay --}}
    <div class="relative">
        <div wire:loading
            class="absolute inset-0 z-10 grid place-items-center rounded-2xl bg-white/60 backdrop-blur-sm dark:bg-neutral-950/50">
            <div
                class="flex items-center gap-3 rounded-2xl border border-neutral-200 bg-white px-4 py-2 shadow dark:border-neutral-800 dark:bg-neutral-900">
                <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4"
                        stroke-linecap="round" />
                </svg>
                <span class="text-sm text-neutral-700 dark:text-neutral-200">Procesando…</span>
            </div>
        </div>

        @php
            $colors = $colorMap ?? [];
        @endphp

        {{-- Licenciaturas --}}
        <div class="space-y-6">
            @forelse($matriz as $licId => $grupo)
                <div
                    class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">

                    {{-- header licenciatura --}}
                    <div
                        class="flex items-center justify-between gap-3 px-5 py-4 bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500">
                        <div>
                            <h3 class="text-base font-semibold text-white">
                                {{ $grupo['lic']->nombre ?? 'Licenciatura' }}
                            </h3>
                        </div>

                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-white">
                            {{ collect($grupo['cuatrimestres'])->sum(fn($c) => count($c['materias'])) }} materias
                        </span>
                    </div>

                    {{-- Desktop --}}
                    <div class="hidden md:block">
                        <table class="min-w-full">
                            <thead
                                class="bg-neutral-50 text-left text-xs font-semibold uppercase tracking-wider dark:bg-neutral-950 dark:text-neutral-300">
                                <tr>
                                    <th class="px-4 py-3 w-[52%]">Materia</th>
                                    <th class="px-4 py-3 w-[18%]">Clave</th>
                                    <th class="px-4 py-3 w-[30%]">Profesor</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                                @foreach ($grupo['cuatrimestres'] as $cuatId => $bloque)
                                    <tr class="bg-neutral-50/80 dark:bg-neutral-950/60">
                                        <td colspan="3" class="px-4 py-3">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span
                                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-amber-50 text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">
                                                    {{ $bloque['cuat']->nombre_cuatrimestre ?? 'Cuatrimestre ' . ($bloque['cuat']->no_cuatrimestre ?? '') }}
                                                </span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    {{ count($bloque['materias']) }} materias
                                                </span>
                                            </div>
                                        </td>
                                    </tr>

                                    @foreach ($bloque['materias'] as $m)
                                        @php
                                            $key = $licId . '_' . $cuatId . '_' . $m->id;
                                            $entangleKey = "profesorSeleccionado.$key";
                                        @endphp

                                        <tr wire:key="row-{{ $key }}"
                                            class="hover:bg-neutral-50 dark:hover:bg-neutral-950/60 transition">
                                            <td class="px-4 ">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-sm font-semibold text-neutral-900 dark:text-white">
                                                        {{ $m->nombre }}
                                                    </span>
                                                    <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                        {{ $m->slug ?? '' }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="px-4  text-sm text-neutral-700 dark:text-neutral-200">
                                                {{ $m->clave ?? '—' }}
                                            </td>

                                            <td class="px-4 ">
                                                <div class="rounded-2xl p-2 transition" x-data="{
                                                    selected: @entangle($entangleKey),
                                                    colors: @js($colors),
                                                    dot: '#9ca3af',
                                                    selectStyle: '',
                                                    cellStyle: '',
                                                    contrast(hex) {
                                                        if (!hex) return '#111827';
                                                        hex = hex.replace('#', '');
                                                        if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
                                                        if (hex.length !== 6) return '#111827';
                                                        const r = parseInt(hex.slice(0, 2), 16),
                                                            g = parseInt(hex.slice(2, 4), 16),
                                                            b = parseInt(hex.slice(4, 6), 16);
                                                        const l = 0.299 * r + 0.587 * g + 0.114 * b;
                                                        return (l < 140) ? '#ffffff' : '#111827';
                                                    },
                                                    rgba(hex, a = 0.10) {
                                                        if (!hex) return '';
                                                        hex = hex.replace('#', '');
                                                        if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
                                                        if (hex.length !== 6) return '';
                                                        const r = parseInt(hex.slice(0, 2), 16),
                                                            g = parseInt(hex.slice(2, 4), 16),
                                                            b = parseInt(hex.slice(4, 6), 16);
                                                        return `rgba(${r},${g},${b},${a})`;
                                                    },
                                                    apply() {
                                                        const id = this.selected ? Number(this.selected) : null;
                                                        const bg = id ? (this.colors[id] ?? null) : null;
                                                
                                                        if (bg) {
                                                            const txt = this.contrast(bg);
                                                            this.selectStyle = `background-color:${bg};color:${txt};border-color:${bg};`;
                                                            this.cellStyle = `background-color:${this.rgba(bg, 0.12)}; border:1px solid ${this.rgba(bg, 0.35)};`;
                                                            this.dot = bg;
                                                        } else {
                                                            this.selectStyle = '';
                                                            this.cellStyle = '';
                                                            this.dot = '#9ca3af';
                                                        }
                                                    }
                                                }"
                                                    x-init="apply()" x-effect="apply()" :style="cellStyle">

                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-3 top-1/2 -translate-y-1/2 h-2.5 w-2.5 rounded-full ring-2 ring-white/70 dark:ring-neutral-900/60"
                                                            :style="`background-color:${dot}`"></span>

                                                        <select
                                                            wire:model.live="profesorSeleccionado.{{ $key }}"
                                                            wire:change="guardarProfesor('{{ $key }}', $event.target.value)"
                                                            :style="selectStyle"
                                                            class="w-full rounded-2xl border bg-white pl-8 pr-3 py-2.5 text-sm transition
                                                                   focus:ring-2 focus:ring-sky-400 focus:border-sky-400
                                                                   dark:bg-neutral-950 dark:border-neutral-800 dark:text-white">
                                                            <option value="">Sin profesor</option>
                                                            @foreach ($profesores as $p)
                                                                <option value="{{ $p->id }}">
                                                                    {{ trim(($p->nombre ?? '') . ' ' . ($p->apellido_paterno ?? '') . ' ' . ($p->apellido_materno ?? '')) ?: 'Profesor #' . $p->id }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <p class="mt-1 text-[11px] text-neutral-500 dark:text-neutral-400">
                                                        Se guarda al cambiar
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile --}}
                    <div class="md:hidden divide-y divide-neutral-100 dark:divide-neutral-800">
                        @foreach ($grupo['cuatrimestres'] as $cuatId => $bloque)
                            <div class="px-4 bg-neutral-50/80 dark:bg-neutral-950/60">
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-amber-50 text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">
                                    {{ $bloque['cuat']->nombre_cuatrimestre ?? 'Cuatrimestre ' . ($bloque['cuat']->no_cuatrimestre ?? '') }}
                                </span>
                            </div>

                            @foreach ($bloque['materias'] as $m)
                                @php
                                    $key = $licId . '_' . $cuatId . '_' . $m->id;
                                    $entangleKey = "profesorSeleccionado.$key";
                                @endphp

                                <div wire:key="mrow-{{ $key }}" class="p-4">
                                    <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                                        {{ $m->nombre }}
                                    </p>
                                    <p class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ $m->clave ?? '—' }} · {{ $m->slug ?? '' }}
                                    </p>

                                    <div class="mt-3">
                                        <label class="text-xs font-semibold text-neutral-700 dark:text-neutral-300">
                                            Profesor
                                        </label>

                                        <div class="rounded-2xl p-2 mt-2 transition" x-data="{
                                            selected: @entangle($entangleKey),
                                            colors: @js($colors),
                                            dot: '#9ca3af',
                                            selectStyle: '',
                                            cellStyle: '',
                                            contrast(hex) {
                                                if (!hex) return '#111827';
                                                hex = hex.replace('#', '');
                                                if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
                                                if (hex.length !== 6) return '#111827';
                                                const r = parseInt(hex.slice(0, 2), 16),
                                                    g = parseInt(hex.slice(2, 4), 16),
                                                    b = parseInt(hex.slice(4, 6), 16);
                                                const l = 0.299 * r + 0.587 * g + 0.114 * b;
                                                return (l < 140) ? '#ffffff' : '#111827';
                                            },
                                            rgba(hex, a = 0.10) {
                                                if (!hex) return '';
                                                hex = hex.replace('#', '');
                                                if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
                                                if (hex.length !== 6) return '';
                                                const r = parseInt(hex.slice(0, 2), 16),
                                                    g = parseInt(hex.slice(2, 4), 16),
                                                    b = parseInt(hex.slice(4, 6), 16);
                                                return `rgba(${r},${g},${b},${a})`;
                                            },
                                            apply() {
                                                const id = this.selected ? Number(this.selected) : null;
                                                const bg = id ? (this.colors[id] ?? null) : null;
                                        
                                                if (bg) {
                                                    const txt = this.contrast(bg);
                                                    this.selectStyle = `background-color:${bg};color:${txt};border-color:${bg};`;
                                                    this.cellStyle = `background-color:${this.rgba(bg, 0.12)}; border:1px solid ${this.rgba(bg, 0.35)};`;
                                                    this.dot = bg;
                                                } else {
                                                    this.selectStyle = '';
                                                    this.cellStyle = '';
                                                    this.dot = '#9ca3af';
                                                }
                                            }
                                        }"
                                            x-init="apply()" x-effect="apply()" :style="cellStyle">

                                            <div class="relative">
                                                <span
                                                    class="absolute left-3 top-1/2 -translate-y-1/2 h-2.5 w-2.5 rounded-full ring-2 ring-white/70 dark:ring-neutral-900/60"
                                                    :style="`background-color:${dot}`"></span>

                                                <select wire:model.live="profesorSeleccionado.{{ $key }}"
                                                    wire:change="guardarProfesor('{{ $key }}', $event.target.value)"
                                                    :style="selectStyle"
                                                    class="w-full rounded-2xl border bg-white pl-8 pr-3 py-2.5 text-sm transition
                                                           dark:bg-neutral-950 dark:border-neutral-800 dark:text-white">
                                                    <option value="">Sin profesor</option>
                                                    @foreach ($profesores as $p)
                                                        <option value="{{ $p->id }}">
                                                            {{ trim(($p->nombre ?? '') . ' ' . ($p->apellido_paterno ?? '') . ' ' . ($p->apellido_materno ?? '')) ?: 'Profesor #' . $p->id }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <p class="mt-1 text-[11px] text-neutral-500 dark:text-neutral-400">
                                                Se guarda al cambiar
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                </div>
            @empty
                <div
                    class="rounded-2xl border border-dashed border-neutral-300 bg-white p-10 text-center dark:border-neutral-700 dark:bg-neutral-900">
                    <p class="text-sm font-semibold text-neutral-900 dark:text-white">No se encontraron materias</p>
                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">Ajusta filtros o revisa datos.</p>
                </div>
            @endforelse

            @if (isset($paginacion) && $paginacion->hasPages())
                <div class="mt-6">
                    <div
                        class="rounded-2xl border border-neutral-200 bg-white px-4 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        {{ $paginacion->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Toast --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', (e) => {
                const {
                    type,
                    message
                } = e;
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        timer: 2200,
                        showConfirmButton: false,
                        icon: type ?? 'success',
                        title: message ?? 'Listo'
                    });
                }
            });
        });
    </script>

</div>
