<div>
   <!-- resources/views/livewire/horarios/asignacion.blade.php -->
<section class="w-full">
  <!-- Encabezado -->
  <div class="sticky top-0 z-10">
    <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-700/60 bg-gradient-to-r from-[#E4F6FF] to-[#F2EFFF] dark:from-[#0b1220] dark:to-[#121a2a] shadow-lg p-5">
      <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">Asignación de Horarios</h1>
      <p class="text-sm text-neutral-600 dark:text-neutral-300">
        Asigna el horario para la licenciatura, generación y cuatrimestre
      </p>
    </div>
  </div>

  <!-- Filtros -->
  <div class="mt-6 rounded-2xl border bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-700 shadow-sm p-5">
         <div class="flex items-center gap-3 mb-4">
           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
            </svg>

      <span class="text-base font-semibold text-neutral-800 dark:text-neutral-100">Filtrar por:</span>
         </div>
 <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

      <flux:select wire:model="licenciatura_id" placeholder="Selecciona una licenciatura...">
        <flux:select.option value="1">Licenciatura en Diseño Gráfico</flux:select.option>
    </flux:select>

    <flux:select wire:model="generacion_id" placeholder="Selecciona una generación...">
        <flux:select.option value="1">Generación 2023</flux:select.option>
    </flux:select>

    <flux:select wire:model="cuatrimestre_id" placeholder="Selecciona un cuatrimestre...">
        <flux:select.option value="1">Cuatrimestre 1</flux:select.option>
    </flux:select>



             <flux:button  type="button"
                class="btn-azul">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
            </svg>

                    Limpiar filtros
                    </flux:button>


    </div>

  </div>

  <!-- Tabla de horario -->
  <div class="mt-6 rounded-2xl border bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-700 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-[960px] w-full border-collapse">
        <thead>
          <tr class="text-left text-sm">
            <th class="w-40 px-4 py-3 font-semibold  dark:text-neutral-200">HORA</th>
            <th class="px-4 py-3 font-semibold  dark:text-neutral-200">LUNES</th>
            <th class="px-4 py-3 font-semibold  dark:text-neutral-200">MARTES</th>
            <th class="px-4 py-3 font-semibold  dark:text-neutral-200">MIÉRCOLES</th>
            <th class="px-4 py-3 font-semibold  dark:text-neutral-200">JUEVES</th>
            <th class="px-4 py-3 font-semibold  dark:text-neutral-200">VIERNES</th>
          </tr>
        </thead>

        <tbody class="text-sm">

        </tbody>
      </table>
    </div>
  </div>

  <!-- Resumen de materias por profesor -->
  <div class="mt-6 rounded-2xl border bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-700 shadow-sm">
    <div class="p-5">
      <h3 class="text-base font-semibold text-neutral-800 dark:text-neutral-100">
        Materias del Profesor y Horas Totales
      </h3>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-[720px] w-full border-t border-neutral-200 dark:border-neutral-700 text-sm">
        <thead class="bg-neutral-50 dark:bg-neutral-800/60">
          <tr>
            <th class="w-12 px-4 py-2 text-left font-semibold">#</th>
            <th class="px-4 py-2 text-left font-semibold">PROFESOR</th>
            <th class="px-4 py-2 text-left font-semibold">MATERIAS</th>
            <th class="w-40 px-4 py-2 text-left font-semibold">TOTAL DE HORAS</th>
          </tr>
        </thead>
        <tbody>
          <!-- Fila vacía inicial / estados -->
          <tr>
            <td colspan="4" class="px-4 py-10">
              <div class="text-center text-neutral-500 dark:text-neutral-400">
                No hay datos para mostrar.
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="px-5 pb-5">
      <div class="mt-4 rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700 p-4 text-center">
        <p class="text-lg font-semibold text-sky-700 dark:text-sky-300">
          Total global de horas: 0
        </p>
      </div>
    </div>
  </div>
</section>

</div>
