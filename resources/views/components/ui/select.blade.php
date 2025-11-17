@props(['name', 'label' => null, 'placeholder' => 'Selecciona una opción...'])
<div
    {{ $attributes->except('wire:model') }}
    x-data="{
        open: false,
        search: '',
        value: null,
        labelText: '',

        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => this.$refs.search?.focus());
            }
        },

        choose(v, l) {
            this.value = v;
            this.labelText = l;
            this.open = false;
        },

        matches(text) {
            if (!this.search) return true;
            return text.toLowerCase().includes(this.search.toLowerCase());
        }
    }"
    x-on:click.outside="open = false"
    class="w-full"
>
    {{-- label opcional --}}
    @if ($label)
        <label class="mb-1 block text-sm font-medium text-neutral-200">
            {{ $label }}
        </label>
    @endif

    {{-- input oculto (para formulario / Livewire) --}}
    <input
        type="hidden"
        name="{{ $name }}"
        x-model="value"
        {{ $attributes->whereStartsWith('wire:model') }}
    />

    {{-- botón principal --}}
    <button
        type="button"
        x-on:click="toggle()"
        class="flex w-full items-center justify-between rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2.5 text-sm text-neutral-50 shadow-sm hover:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
    >
        <span x-show="!labelText" class="text-neutral-400">
            {{ $placeholder }}
        </span>

        <span x-show="labelText" x-text="labelText"></span>

        <svg class="ms-2 h-4 w-4 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
        </svg>
    </button>

    {{-- dropdown --}}
    <div
        x-show="open"
        x-transition.origin.top
        class="relative z-40 mt-2 w-full"
        style="display: none;"
    >
        <div class="max-h-72 overflow-hidden rounded-xl border border-neutral-700 bg-neutral-900 shadow-xl">
            {{-- buscador --}}
            <div class="border-b border-neutral-800 px-3 py-2">
                <div class="flex items-center gap-2 rounded-lg bg-neutral-800 px-2 py-1.5">
                    <svg class="h-4 w-4 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.25 5.25a7.5 7.5 0 0 0 11.4 11.4Z"/>
                    </svg>
                    <input
                        x-ref="search"
                        x-model="search"
                        type="text"
                        placeholder="Buscar..."
                        class="w-full border-0 bg-transparent text-sm text-neutral-100 placeholder-neutral-400 focus:ring-0"
                    >
                </div>
            </div>

            {{-- aquí van las opciones (foreach/slot) --}}
            <ul class="max-h-56 space-y-0 overflow-y-auto py-1">
                {{ $slot }}
            </ul>
        </div>
    </div>
</div>
