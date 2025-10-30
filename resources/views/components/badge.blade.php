@props([
  // color base tailwind (solo nombre, sin el número): emerald, red, gray, indigo, etc.
  'color' => 'emerald',
  // tamaño: xs | sm
  'size'  => 'xs',
  // variante: filled | outline
  'variant' => 'outline',
])

@php
  // tamaños
  $sizes = [
    'xs' => 'px-2.5 py-0.5 text-xs',
    'sm' => 'px-3 py-0.5 text-sm',
  ];

  // clases por variante (usando el color recibido)
  $outline = "border border-{$color}-300/60 bg-{$color}-50 text-{$color}-700 dark:bg-{$color}-900/20 dark:text-{$color}-300 dark:border-{$color}-700/50";
  $filled  = "border border-{$color}-600 bg-{$color}-600 text-white dark:bg-{$color}-500 dark:border-{$color}-500";

  $variantClass = $variant === 'filled' ? $filled : $outline;
@endphp

<span {{ $attributes->class([
    "inline-flex items-center rounded-full font-medium",
    $sizes[$size] ?? $sizes['xs'],
    $variantClass,
]) }}>
  {{ $slot }}
</span>
