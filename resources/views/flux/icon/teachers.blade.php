@pure

{{-- Icono: Teacher (persona frente a pizarrón) --}}

@props([
    'variant' => 'outline',
])

@php
if ($variant === 'solid') {
    throw new \Exception('The "solid" variant is not supported in Lucide.');
}

$classes = Flux::classes('shrink-0')
    ->add(match($variant) {
        'outline' => '[:where(&)]:size-6',
        'solid' => '[:where(&)]:size-6',
        'mini' => '[:where(&)]:size-5',
        'micro' => '[:where(&)]:size-4',
    });

$strokeWidth = match ($variant) {
    'outline' => 2,
    'mini' => 2.25,
    'micro' => 2.5,
};
@endphp

<svg
    {{ $attributes->class($classes) }}
    data-flux-icon
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="{{ $strokeWidth }}"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
    data-slot="icon"
>
    <!-- Pizarrón -->
    <rect x="3" y="4" width="18" height="12" rx="2" ry="2" />

    <!-- Líneas del pizarrón -->
    <line x1="13" y1="9" x2="18" y2="9" />
    <line x1="13" y1="12" x2="18" y2="12" />

    <!-- Cabeza del docente -->
    <circle cx="7" cy="10" r="2" />

    <!-- Brazo señalando (puntero hacia el pizarrón) -->
    <path d="M8.5 10.5 L11 8.8" />

    <!-- Cuerpo y postura (ligero apoyo hacia la derecha) -->
    <path d="M4.5 18v-1.5A3.5 3.5 0 0 1 8 13h2" />
</svg>
