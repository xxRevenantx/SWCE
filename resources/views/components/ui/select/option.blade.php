@props([])

@php
    // Si no se pasa label en el componente, usamos el contenido del slot
    $text = $label ?? trim($slot);
@endphp

<li>
    <button
        type="button"
        {{-- al hacer click, avisamos al padre qué valor y etiqueta se seleccionan --}}
        x-on:click="choose('{{ $value }}', '{{ $text }}')"

        {{-- se muestra solo si coincide con la búsqueda (función matches del padre) --}}
        x-show="matches('{{ strtolower($text) }}')"

        class="flex w-full items-center justify-between px-3 py-2 text-left text-sm text-neutral-100 hover:bg-neutral-800"
        :class="{ 'bg-neutral-800': value === '{{ $value }}' }"
    >
        <span>{{ $text }}</span>

        {{-- check cuando está seleccionado --}}
        <svg
            x-show="value === '{{ $value }}'"
            class="h-4 w-4 text-indigo-400"
            xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
    </button>
</li>
