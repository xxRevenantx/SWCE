 <div>
     <div
         class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
         <div class="bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-6 py-6 text-white sm:px-8">
             <h1 class="text-2xl font-bold">Mi perfil</h1>
             <p class="mt-1 text-sm text-blue-100">
                 Consulta tu información personal, escolar, de contacto e inscripción.
             </p>
         </div>

         <div class="p-6 sm:p-8">


             <div class="grid grid-cols-1 gap-6 xl:grid-cols-4">
                 <div class="xl:col-span-1">
                     <div
                         class="rounded-3xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm">
                         <div class="flex flex-col items-center text-center">
                             <img src="{{ $this->fotoPreview }}" alt="Foto del estudiante"
                                 class="h-32 w-32 rounded-3xl object-cover shadow-md ring-4 ring-white">

                             <h2 class="mt-4 text-lg font-bold text-slate-800">
                                 {{ $this->nombreCompleto ?: 'Sin nombre registrado' }}
                             </h2>

                             <p class="mt-1 text-sm text-slate-500">
                                 {{ $licenciatura ?: 'Sin licenciatura' }}
                             </p>

                             <div class="mt-4 grid w-full grid-cols-2 gap-3 text-left">
                                 <div class="rounded-2xl bg-slate-100 px-3 py-2">
                                     <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                         Matrícula
                                     </p>
                                     <p class="mt-1 truncate text-sm font-semibold text-slate-700">
                                         {{ $matricula ?: '---' }}
                                     </p>
                                 </div>

                                 <div class="rounded-2xl bg-slate-100 px-3 py-2">
                                     <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                         Estado
                                     </p>
                                     <p
                                         class="mt-1 text-sm font-semibold {{ $estado_inscripcion == '1' ? 'text-emerald-600' : 'text-rose-600' }}">
                                         {{ $estado_inscripcion == '1' ? 'Activo' : 'Inactivo' }}
                                     </p>
                                 </div>
                             </div>

                         </div>
                     </div>
                 </div>

                 <div class="space-y-6 xl:col-span-3">
                     {{-- Datos del alumno --}}
                     <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                         <div class="mb-5 flex items-center justify-between">
                             <div>
                                 <h3 class="text-lg font-bold text-slate-800">Datos del alumno</h3>
                                 <p class="text-sm text-slate-500">Información general registrada en el sistema.
                                 </p>
                             </div>
                         </div>

                         <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                             <div>
                                 <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                     Nombre
                                 </label>
                                 <input type="text" value="{{ $nombre }}" disabled
                                     class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                             </div>

                             <div>
                                 <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                     Apellido paterno
                                 </label>
                                 <input type="text" value="{{ $apellido_paterno }}" disabled
                                     class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                             </div>

                             <div>
                                 <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                     Apellido materno
                                 </label>
                                 <input type="text" value="{{ $apellido_materno }}" disabled
                                     class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                             </div>

                             <div>
                                 <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                     CURP
                                 </label>
                                 <input type="text" value="{{ $curp }}" disabled
                                     class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm uppercase text-slate-700">
                             </div>

                             <div>
                                 <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                     Fecha de nacimiento
                                 </label>
                                 <input type="text" value="{{ $fecha_nacimiento }}" disabled
                                     class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                             </div>

                             <div>
                                 <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                     Sexo
                                 </label>
                                 <input type="text" value="{{ $sexo }}" disabled
                                     class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                             </div>
                         </div>
                     </div>

                     {{-- Datos escolares --}}
                     <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                         <div class="mb-5">
                             <h3 class="text-lg font-bold text-slate-800">Datos escolares</h3>
                             <p class="text-sm text-slate-500">Información académica principal del estudiante.
                             </p>
                         </div>

                         <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                             <div>
                                 <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                     Matrícula
                                 </label>
                                 <input type="text" value="{{ $matricula }}" disabled
                                     class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                             </div>

                             <div>
                                 <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                     Folio
                                 </label>
                                 <input type="text" value="{{ $folio }}" disabled
                                     class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                             </div>

                             <div>
                                 <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                     Licenciatura
                                 </label>
                                 <input type="text" value="{{ $licenciatura }}" disabled
                                     class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                             </div>
                         </div>
                     </div>
                 </div>
             </div>

             {{-- Datos de contacto --}}
             <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm mt-3">
                 <div class="mb-5">
                     <h3 class="text-lg font-bold text-slate-800">Datos de contacto</h3>
                     <p class="text-sm text-slate-500">Esta información sí puede actualizarse.</p>
                 </div>

                 <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                     <div class="xl:col-span-2">
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Correo electrónico
                         </label>
                         <input type="email" wire:model.defer="email"
                             class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                         @error('email')
                             <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                         @enderror
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Celular
                         </label>
                         <input type="text" wire:model.defer="celular"
                             class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Teléfono
                         </label>
                         <input type="text" wire:model.defer="telefono"
                             class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                     </div>

                     <div class="xl:col-span-2">
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Calle
                         </label>
                         <input type="text" wire:model.defer="calle"
                             class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Número exterior
                         </label>
                         <input type="text" wire:model.defer="numero_exterior"
                             class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Número interior
                         </label>
                         <input type="text" wire:model.defer="numero_interior"
                             class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Colonia
                         </label>
                         <input type="text" wire:model.defer="colonia"
                             class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Municipio
                         </label>
                         <input type="text" wire:model.defer="municipio"
                             class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Código postal
                         </label>
                         <input type="text" wire:model.defer="codigo_postal"
                             class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                     </div>

                     <div class="xl:col-span-3">
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Bachillerato procedente
                         </label>
                         <input type="text" wire:model.defer="bachillerato_procedente"
                             class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             País
                         </label>
                         <input type="text" value="{{ $pais }}" disabled
                             class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Estado
                         </label>
                         <input type="text" value="{{ $estado }}" disabled
                             class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Ciudad
                         </label>
                         <input type="text" value="{{ $ciudad }}" disabled
                             class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                     </div>
                 </div>
             </div>

             {{-- Inscripción actual --}}
             <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm mt-3">
                 <div class="mb-5">
                     <h3 class="text-lg font-bold text-slate-800">Inscripción actual</h3>
                     <p class="text-sm text-slate-500">Datos de la inscripción vigente del estudiante.</p>
                 </div>

                 <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Licenciatura
                         </label>
                         <input type="text" value="{{ $licenciatura }}" disabled
                             class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Generación
                         </label>
                         <input type="text" value="{{ $generacion }}" disabled
                             class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Cuatrimestre
                         </label>
                         <input type="text" value="{{ $cuatrimestre }}" disabled
                             class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                     </div>

                     <div>
                         <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                             Fecha de inscripción
                         </label>
                         <input type="text" value="{{ $fecha_inscripcion }}" disabled
                             class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700">
                     </div>
                 </div>
             </div>
         </div>
     </div>

     {{-- Aviso --}}
     <div class="rounded-3xl border border-indigo-200 bg-indigo-50 px-6 py-8 text-center shadow-sm">
         <h3 class="text-2xl font-semibold text-indigo-600">
             Aviso de privacidad simplificado del CUM
         </h3>

         <p class="mx-auto mt-6 max-w-5xl text-[16px] leading-8 text-indigo-500">
             La recolección de datos personales se lleva a cabo a través de
             https://centrouniversitariomoctezuma.com, cuyo administrador y responsable del
             tratamiento es el Centro Universitario Moctezuma (CUM). Los datos personales
             recabados son utilizados para la identificación, validación, creación de perfil y
             asignación de matrícula para el seguimiento académico del CUM.
         </p>

         <div class="my-8 border-t border-indigo-300"></div>

         <p class="text-[16px] text-indigo-500">
             Para cualquier aclaración, pasar a la dirección del CUM.
         </p>
     </div>
 </div>
