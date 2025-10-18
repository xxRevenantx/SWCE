<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="bg-muted relative hidden h-full flex-col pt-5 pb-10 px-10  text-white lg:flex dark:border-e dark:border-neutral-800">
               <div class="absolute inset-0 ">
                    <img
                        src="{{ asset('storage/banner.jpg') }}"
                        class="h-full w-full object-cover  shadow-sm dark:bg-neutral-950 dark:shadow-xs"
                    />
                </div>
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                    {{-- <span class="flex h-10 w-10 items-center justify-center rounded-md">
                        <x-app-logo-icon class="me-2 h-7 fill-current text-white" />
                    </span> --}}
                    {{-- {{ config('app.name', 'SWCE') }} --}}
                        <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="w-80 object-contain">

                    <div class="ms-1 grid flex-1 text-start text-sm">
                        <span class="mb-0.5 truncate leading-tight font-semibold">Sistema Web de Control Escolar para el Centro Universitario Moctezuma A.C.</span>
                    </div>

                </a>

                @php
                    [$frase, $autor] = \Illuminate\Support\Str::of(\App\Support\MyInspiring::random())
                        ->explode('-')->map(fn($p)=>trim($p))->pad(2, null)->all();
                @endphp



                <div class="relative z-20 mt-auto text-white">
                    <blockquote class="space-y-2">
                        <flux:heading class="text-white" size="lg">&ldquo;{{ trim($frase) }}&rdquo;</flux:heading>
                        <footer><flux:heading class="text-white">{{ trim($autor) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>

                        <img
                        src="{{ asset('storage/logo.png') }}"
                        class="h-20 w-auto object-cover  dark:bg-neutral-950 dark:shadow-xs"
    />

                        {{-- <span class="flex h-9 w-9 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                        </span> --}}

                        <span class="sr-only">{{ config('app.name', 'SWCE') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
