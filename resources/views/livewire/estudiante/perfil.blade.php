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
                        class="rounded-3xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm dark:border-neutral-700 dark:from-neutral-800 dark:to-neutral-900">
                        <div class="flex flex-col items-center text-center">
                            <img src="{{ $this->fotoPreview }}" alt="Foto del estudiante"
                                class="h-32 w-32 rounded-3xl object-cover shadow-md ring-4 ring-white">

                            <h2 class="mt-4 text-lg font-bold text-slate-800 dark:text-white">
                                {{ $this->nombreCompleto ?: 'Sin nombre registrado' }}
                            </h2>

                            <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">
                                {{ $licenciatura ?: 'Sin licenciatura' }}
                            </p>

                            <div class="mt-4 grid w-full grid-cols-2 gap-3 text-left">
                                <div class="rounded-2xl bg-slate-100 px-3 py-2 dark:bg-neutral-800">
                                    <p
                                        class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                        Matrícula
                                    </p>
                                    <p class="mt-1 truncate text-sm font-semibold text-slate-700 dark:text-neutral-200">
                                        {{ $matricula ?: '---' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-slate-100 px-3 py-2 dark:bg-neutral-800">
                                    <p
                                        class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                        Estado
                                    </p>
                                    <p
                                        class="mt-1 text-sm font-semibold {{ $estado_inscripcion == '1' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $estado_inscripcion == '1' ? 'Activo' : 'Inactivo' }}
                                    </p>
                                </div>
                            </div>

                            <div class="my-3 w-full">
                                <a target="_blank" href="{{ route('estudiante.pdf.mi-expediente') }}"
                                    class="inline-flex h-9 w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 px-3 text-xs font-bold text-white hover:from-sky-600 hover:via-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    MI EXPEDIENTE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 xl:col-span-3">
                    {{-- Datos del alumno --}}
                    <div
                        class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                        <div class="mb-5">
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Datos del alumno</h3>
                            <p class="text-sm text-slate-500 dark:text-neutral-400">
                                Información general registrada en el sistema.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                    Nombre
                                </label>
                                <input type="text" value="{{ $nombre }}" readonly
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                    Apellido paterno
                                </label>
                                <input type="text" value="{{ $apellido_paterno }}" readonly
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                    Apellido materno
                                </label>
                                <input type="text" value="{{ $apellido_materno }}" readonly
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                    CURP
                                </label>
                                <input type="text" value="{{ $curp }}" readonly
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm uppercase text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                    Fecha de nacimiento
                                </label>
                                <input type="text" value="{{ $fecha_nacimiento }}" readonly
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                    Sexo
                                </label>
                                <input type="text" value="{{ $sexo }}" readonly
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            </div>
                        </div>
                    </div>

                    {{-- Datos escolares --}}
                    <div
                        class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                        <div class="mb-5">
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Datos escolares</h3>
                            <p class="text-sm text-slate-500 dark:text-neutral-400">
                                Información académica principal del estudiante.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                    Matrícula
                                </label>
                                <input type="text" value="{{ $matricula }}" readonly
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                    Folio
                                </label>
                                <input type="text" value="{{ $folio }}" readonly
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                                    Licenciatura
                                </label>
                                <input type="text" value="{{ $licenciatura }}" readonly
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Datos de contacto --}}
            <div
                class="mt-3 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="mb-5">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Datos de contacto</h3>
                    <p class="text-sm text-slate-500 dark:text-neutral-400">Información registrada del estudiante.</p>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                    <div class="xl:col-span-2">
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Correo electrónico
                        </label>
                        <input type="email" value="{{ $email }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Celular
                        </label>
                        <input type="text" value="{{ $celular }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Teléfono
                        </label>
                        <input type="text" value="{{ $telefono }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div class="xl:col-span-2">
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Calle
                        </label>
                        <input type="text" value="{{ $calle }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Número exterior
                        </label>
                        <input type="text" value="{{ $numero_exterior }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Número interior
                        </label>
                        <input type="text" value="{{ $numero_interior }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Colonia
                        </label>
                        <input type="text" value="{{ $colonia }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Municipio
                        </label>
                        <input type="text" value="{{ $municipio }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Código postal
                        </label>
                        <input type="text" value="{{ $codigo_postal }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div class="xl:col-span-3">
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Bachillerato procedente
                        </label>
                        <input type="text" value="{{ $bachillerato_procedente }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            País
                        </label>
                        <input type="text" value="{{ $pais }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Estado
                        </label>
                        <input type="text" value="{{ $estado }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Ciudad
                        </label>
                        <input type="text" value="{{ $ciudad }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>
                </div>
            </div>

            {{-- Inscripción actual --}}
            <div
                class="mt-3 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="mb-5">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Inscripción actual</h3>
                    <p class="text-sm text-slate-500 dark:text-neutral-400">Datos de la inscripción vigente del
                        estudiante.</p>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Licenciatura
                        </label>
                        <input type="text" value="{{ $licenciatura }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Generación
                        </label>
                        <input type="text" value="{{ $generacion }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Cuatrimestre
                        </label>
                        <input type="text" value="{{ $cuatrimestre }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-neutral-400">
                            Fecha de inscripción
                        </label>
                        <input type="text" value="{{ $fecha_inscripcion }}" readonly
                            class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Aviso --}}
    <div
        class="mt-3 rounded-3xl border border-indigo-200 bg-indigo-50 px-6 py-8 text-center shadow-sm dark:border-indigo-900 dark:bg-indigo-950/40">
        <h3 class="text-2xl font-semibold text-indigo-600 dark:text-indigo-300">
            Aviso de privacidad simplificado del CUM
        </h3>

        <p class="mx-auto mt-6 max-w-5xl text-[16px] leading-8 text-indigo-500 dark:text-indigo-200/90">
            La recolección de datos personales se lleva a cabo a través de
            https://centrouniversitariomoctezuma.com, cuyo administrador y responsable del
            tratamiento es el Centro Universitario Moctezuma (CUM). Los datos personales
            recabados son utilizados para la identificación, validación, creación de perfil y
            asignación de matrícula para el seguimiento académico del CUM.
        </p>

        <div class="my-8 border-t border-indigo-300 dark:border-indigo-800"></div>

        <p class="text-[16px] text-indigo-500 dark:text-indigo-200/90">
            Para cualquier aclaración, pasar a la dirección del CUM.
        </p>
    </div>
</div>
