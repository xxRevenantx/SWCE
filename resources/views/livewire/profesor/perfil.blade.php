<div class="space-y-6">
    {{-- Encabezado --}}
    <section
        class="relative overflow-hidden rounded-[32px] border border-white/60 bg-white/80 shadow-[0_20px_60px_-25px_rgba(15,23,42,0.28)] backdrop-blur-xl dark:border-white/10 dark:bg-neutral-900/80">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.16),transparent_28%),radial-gradient(circle_at_top_right,rgba(16,185,129,0.16),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(168,85,247,0.14),transparent_28%)]">
        </div>

        <div class="absolute -top-20 right-0 h-72 w-72 rounded-full bg-sky-400/10 blur-3xl"></div>
        <div class="absolute -bottom-20 left-0 h-72 w-72 rounded-full bg-emerald-400/10 blur-3xl"></div>

        <div class="relative px-6 py-8 sm:px-8 sm:py-10">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4 sm:gap-5">
                    <div class="relative">
                        <div
                            class="h-24 w-24 overflow-hidden rounded-3xl border-4 border-white/70 bg-white shadow-xl dark:border-white/10 dark:bg-neutral-800 sm:h-28 sm:w-28">
                            <img src="{{ $this->fotoPreview }}" alt="Foto del profesor"
                                class="h-full w-full object-cover">
                        </div>

                        <span
                            class="absolute -bottom-1 -right-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl border-4 border-white bg-white text-sm shadow-md dark:border-neutral-900 dark:bg-neutral-800">
                            👨‍🏫
                        </span>
                    </div>

                    <div class="min-w-0">
                        <div
                            class="mb-2 inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-300">
                            Perfil del profesor
                        </div>

                        <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                            {{ $this->nombreCompleto ?: 'Sin nombre registrado' }}
                        </h1>

                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 sm:text-base">
                            Información general del docente dentro del sistema SWCE.
                        </p>

                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <span
                                class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset
                                {{ $estado === 'true'
                                    ? 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20'
                                    : 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20' }}">
                                <span
                                    class="h-2 w-2 rounded-full {{ $estado === 'true' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ $this->estadoTexto }}
                            </span>

                            @if ($perfil)
                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 dark:bg-white/5 dark:text-slate-200 dark:ring-white/10">
                                    {{ $perfil }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div
                        class="rounded-2xl border border-slate-200/80 bg-white/80 px-4 py-3 shadow-sm dark:border-white/10 dark:bg-white/5">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                            Correo
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-100 break-all">
                            {{ $email ?: 'No registrado' }}
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-slate-200/80 bg-white/80 px-4 py-3 shadow-sm dark:border-white/10 dark:bg-white/5">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                            Teléfono
                        </p>
                        <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-100">
                            {{ $telefono ?: 'No registrado' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tarjetas resumen --}}
    <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article
            class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-white/10 dark:bg-neutral-900">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                Nombre
            </p>
            <p class="mt-3 text-base font-extrabold text-slate-900 dark:text-white">
                {{ $nombre ?: 'No registrado' }}
            </p>
        </article>

        <article
            class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-white/10 dark:bg-neutral-900">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                Apellido paterno
            </p>
            <p class="mt-3 text-base font-extrabold text-slate-900 dark:text-white">
                {{ $apellido_paterno ?: 'No registrado' }}
            </p>
        </article>

        <article
            class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-white/10 dark:bg-neutral-900">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                Apellido materno
            </p>
            <p class="mt-3 text-base font-extrabold text-slate-900 dark:text-white">
                {{ $apellido_materno ?: 'No registrado' }}
            </p>
        </article>

        <article
            class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-white/10 dark:bg-neutral-900">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                CURP
            </p>
            <p class="mt-3 text-base font-extrabold text-slate-900 dark:text-white break-all">
                {{ $curp ?: 'No registrado' }}
            </p>
        </article>
    </section>

    {{-- Información detallada --}}
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        {{-- Datos personales --}}
        <article
            class="xl:col-span-2 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-black tracking-tight text-slate-900 dark:text-white">
                        Datos del profesor
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Información principal registrada en el sistema.
                    </p>
                </div>

                <div
                    class="h-11 w-11 rounded-2xl bg-sky-100 text-sky-700 grid place-items-center dark:bg-sky-500/10 dark:text-sky-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6.75V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v1.5m7.5 0h1.5A2.25 2.25 0 0119.5 9v8.25A2.25 2.25 0 0117.25 19.5H6.75A2.25 2.25 0 014.5 17.25V9a2.25 2.25 0 012.25-2.25h1.5m7.5 0h-7.5" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                        Nombre completo
                    </label>
                    <div
                        class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 dark:border-white/10 dark:bg-white/5 dark:text-slate-100">
                        {{ $this->nombreCompleto ?: 'No registrado' }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                        CURP
                    </label>
                    <div
                        class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 dark:border-white/10 dark:bg-white/5 dark:text-slate-100">
                        {{ $curp ?: 'No registrado' }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                        Teléfono
                    </label>
                    <div
                        class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 dark:border-white/10 dark:bg-white/5 dark:text-slate-100">
                        {{ $telefono ?: 'No registrado' }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                        Correo electrónico
                    </label>
                    <div
                        class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 dark:border-white/10 dark:bg-white/5 dark:text-slate-100 break-all">
                        {{ $email ?: 'No registrado' }}
                    </div>
                </div>
            </div>
        </article>

        {{-- Panel lateral --}}
        <article
            class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="flex items-center gap-3">
                <div class="grid h-12 w-12 place-items-center rounded-2xl shadow-inner"
                    style="background-color: {{ $color ?: '#64748b' }}20; color: {{ $color ?: '#64748b' }};">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 6.75h9m-9 3h9m-9 3h4.5m3.75 6.75H8.25A2.25 2.25 0 016 17.25V6.75A2.25 2.25 0 018.25 4.5h7.5A2.25 2.25 0 0118 6.75v10.5A2.25 2.25 0 0115.75 19.5z" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-white">
                        Perfil laboral
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Descripción y estado actual.
                    </p>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                        Perfil
                    </label>
                    <div
                        class="min-h-[88px] rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                        {{ $perfil ?: 'No se ha registrado una descripción del perfil.' }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                        Color asignado
                    </label>
                    <div
                        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-white/5">
                        <span class="h-6 w-6 rounded-full border border-black/10 dark:border-white/10"
                            style="background-color: {{ $color ?: '#64748b' }};"></span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                            {{ $color ?: 'Sin color' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                        Estado
                    </label>
                    <div
                        class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-white/5">
                        <span
                            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset
                            {{ $estado === 'true'
                                ? 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20'
                                : 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20' }}">
                            <span
                                class="h-2 w-2 rounded-full {{ $estado === 'true' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $this->estadoTexto }}
                        </span>
                    </div>
                </div>
            </div>
        </article>
    </section>
</div>
