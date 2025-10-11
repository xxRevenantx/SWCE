<div class="w-full mx-auto ">

  <!-- BARRA SUPERIOR -->
  <div class="w-full flex flex-wrap items-center justify-between gap-4 rounded-2xl p-4 sm:p-5 bg-white/90 dark:bg-neutral-800/90 shadow-lg border border-neutral-200 dark:border-neutral-700 mb-4 relative overflow-visible">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500"></div>

    <!-- Fecha -->
    <div class="flex items-center gap-2 w-full sm:w-auto justify-center lg:justify-start text-neutral-700 dark:text-neutral-100">
      <div class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
        <flux:icon.calendar />
      </div>
      <span class="font-medium">{{ now()->translatedFormat('d \d\e F \d\e Y') }}</span>
    </div>

    <!-- Widgets -->
    <div class="w-full sm:w-auto flex flex-col lg:flex-row items-center gap-3 mt-2 sm:mt-0">



      <!-- Chips -->
      <div class="inline-flex items-center gap-2">
        <div class="rounded-xl px-3 py-2 border border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-700/40 text-sm text-neutral-800 dark:text-neutral-100">
          Ciclo escolar
          <flux:badge color="indigo" class="ml-2">{{ $dashboard->ciclo_escolar ?? "0" }}</flux:badge>
        </div>

      </div>

      <!-- Avatar -->
    @if(auth()->user()->photo)
      <div class="relative w-10 h-10 hidden lg:block">
        @if(auth()->user()->photo && file_exists(storage_path('app/public/profile-photos/' . auth()->user()->photo)))
        <div class="w-full h-full rounded-full overflow-hidden border-4 border-white shadow ring-1 ring-neutral-200 dark:ring-neutral-700">
          <img src="{{ asset('storage/profile-photos/' . auth()->user()->photo) }}" alt="Avatar" class="w-full h-full object-cover">
        </div>
        @else
        <flux:avatar circle badge badge:circle badge:color="green" :initials="auth()->user()->initials()" :name="auth()->user()->username" />
        @endif
        <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-neutral-800 rounded-full shadow-md"></span>
      </div>
      <div class="w-full text-center lg:hidden">
        <span class="block font-semibold text-neutral-800 dark:text-neutral-100">{{ auth()->user()->username }}</span>
      </div>
    @else
      <flux:avatar circle badge badge:circle badge:color="green" class="hidden lg:block" :initials="auth()->user()->initials()" :name="auth()->user()->username" />
      <div class="w-full text-center lg:hidden">
        <span class="block font-semibold text-neutral-800 dark:text-neutral-100">{{ auth()->user()->username }}</span>
      </div>
    @endif
    </div>
  </div>

  </div>
