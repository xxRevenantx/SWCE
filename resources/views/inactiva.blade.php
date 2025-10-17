<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>dado de baja</title>
  <meta name="theme-color" content="#0b0b0c" />
  <meta name="color-scheme" content="dark" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ asset('storage/letra.png') }}" type="image/png">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @fluxAppearance
  <style>
    :root { color-scheme: dark; }
    body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans"; }
    .bg-dark-gradient{
      background-image:
        radial-gradient(28rem 28rem at 10% -10%, rgba(244,63,94,.16), transparent 60%),
        radial-gradient(32rem 32rem at 110% 110%, rgba(129,140,248,.18), transparent 60%),
        linear-gradient(180deg, #0b0b0c, #111114 60%, #0b0b0c 100%);
    }
    @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    .floaty { animation: floaty 6s ease-in-out infinite; }
  </style>
</head>

<body class="min-h-screen bg-dark-gradient text-zinc-100 antialiased selection:bg-rose-400/25">
<section class="relative py-10 sm:py-16 lg:py-24">
  <!-- Glows -->
  <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
    <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-rose-500/15 blur-3xl"></div>
    <div class="absolute -bottom-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
  </div>

  <div class="relative z-10 mx-auto w-full max-w-7xl px-4 md:px-6 lg:px-8">
    <!-- Header -->
    <header class="mb-10 flex items-center justify-center lg:justify-between">
      <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
        <img src="{{ asset('storage/default.png') }}" alt="Logo" class="h-12 w-auto sm:h-14" loading="eager" decoding="async">
        <span class="sr-only">Ir al inicio</span>
      </a>
      <div class="hidden lg:flex items-center gap-2 text-sm text-zinc-300">
        <span class="inline-flex items-center gap-2 rounded-full bg-rose-500/15 px-3 py-1.5 text-rose-200 ring-1 ring-rose-500/30">
          <!-- Stop icon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 6h12v12H6z"/></svg>
          dado de baja
        </span>
      </div>
    </header>

    <!-- Grid -->
    <div class="mx-auto grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-14">
      <!-- Texto / Acciones -->
      <div class="flex items-center">
        <div class="w-full rounded-3xl border border-zinc-700/60 bg-zinc-900/60 p-6 backdrop-blur-xl shadow-xl sm:p-8 lg:p-10">
          <!-- Badge móvil -->
          <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-rose-500/15 px-3 py-1.5 text-xs font-medium text-rose-200 ring-1 ring-rose-500/30 lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M6 6h12v12H6z"/></svg>
            dado de baja
          </div>

          <h1 class="text-balance text-center text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-left lg:text-5xl">
            Acceso deshabilitado <span class="text-rose-300">por baja</span>
          </h1>

          <p class="mt-4 text-pretty text-center text-base leading-relaxed text-zinc-300 sm:text-lg lg:text-left">
            Tu cuenta de <span class="font-semibold text-zinc-100"></span> ha sido <span class="font-semibold text-zinc-100">dada de baja</span> por el área administrativa.
            Por ahora no puedes ingresar al sistema.
          </p>
{{--
          @if($motivo)
          <div class="mt-6 rounded-2xl border border-rose-500/30 bg-rose-500/10 p-4">
            <p class="text-sm">
              <span class="font-semibold text-rose-200">Motivo registrado:</span>
              <span class="text-rose-100/90">{{ $motivo }}</span>
            </p>
          </div>
          @endif --}}

          <dl class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div class="rounded-2xl border border-zinc-700/60 bg-zinc-900/60 p-4">
              <dt class="text-xs uppercase tracking-wide text-zinc-400">Usuario</dt>
              <dd class="mt-1 text-sm font-medium">{{ $user->name ?? 'Usuario' }}</dd>
            </div>
            <div class="rounded-2xl border border-zinc-700/60 bg-zinc-900/60 p-4">
              <dt class="text-xs uppercase tracking-wide text-zinc-400">Rol</dt>
              <dd class="mt-1 text-sm font-medium"></dd>
            </div>
          </dl>

          <!-- Acciones -->
          <div class="mt-6 grid gap-3 sm:grid-cols-2">
            <!-- Solicitar revisión -->
            <form method="POST" action="#" class="contents">
              @csrf
              <div class="rounded-2xl border border-zinc-700/60 bg-zinc-900/60 p-4">
                <label for="mensaje" class="text-sm text-zinc-300">¿Deseas solicitar revisión?</label>
                <textarea id="mensaje" name="mensaje" rows="3"
                  class="mt-2 w-full rounded-xl border-zinc-700 bg-zinc-950/50 p-3 text-sm text-zinc-100 focus:border-zinc-500 focus:ring-zinc-500"
                  placeholder="Explica brevemente tu situación (opcional)"></textarea>
                <button type="submit"
                  class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-600/20 ring-1 ring-sky-400/30 hover:bg-sky-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                  <!-- Paper plane -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="m2 21 21-9L2 3v7l15 2-15 2v7z"/></svg>
                  Enviar solicitud
                </button>
              </div>
            </form>

            <!-- Cerrar sesión -->
            <form method="POST" action="{{ route('logout') }}" class="contents">
              @csrf
              <div class="rounded-2xl border border-zinc-700/60 bg-zinc-900/60 p-4">
                <p class="text-sm text-zinc-300">También puedes cerrar sesión de forma segura.</p>
                <button type="submit"
                  class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-zinc-800 px-4 py-2.5 text-sm font-semibold text-zinc-100 ring-1 ring-zinc-700 hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-700">
                  <!-- Power -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13 3h-2v10h2z"/><path d="M17.83 5.17A7 7 0 1 1 6.17 16.83l1.41-1.41a5 5 0 1 0 7.07-7.07z"/></svg>
                  Cerrar sesión
                </button>
              </div>
            </form>
          </div>

          <!-- Soporte -->
          <div class="mt-6 grid gap-3 sm:grid-cols-2">
            <a href="#"
               class="inline-flex items-center justify-center gap-2 rounded-2xl border border-zinc-700/60 bg-zinc-900/60 px-4 py-3 text-sm font-medium hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-700">
              <!-- Lifebuoy -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2zm7.07 6.93-2.12 2.12A4.98 4.98 0 0 1 16 12c0 .9.24 1.75.65 2.48l2.12 2.12A8 8 0 0 1 4.8 7.2l2.12 2.12A4.98 4.98 0 0 1 12 8c.9 0 1.75.24 2.48.65l2.12-2.12A7.96 7.96 0 0 1 19.07 8.93zM12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>
              Contactar soporte
            </a>
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 rounded-2xl border border-zinc-700/60 bg-zinc-900/60 px-4 py-3 text-sm font-medium hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-700">
              <!-- Home -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="m12 3 9 8h-3v10H6V11H3z"/></svg>
              Volver al inicio
            </a>
          </div>

        </div>
      </div>

      <!-- Ilustración -->
      <div class="flex items-center justify-center">
        <figure class="floaty w-full max-w-xl rounded-3xl border border-zinc-700/60 bg-zinc-900/60 p-4 backdrop-blur-xl shadow-xl">
          <img
            src="https://pagedone.io/asset/uploads/1718004199.png"
            alt="Cuenta deshabilitada"
            class="h-auto w-full object-contain"
            loading="lazy" decoding="async">
        </figure>
      </div>
    </div>

    <!-- Footer -->
    <footer class="mx-auto mt-12 max-w-6xl text-center text-sm text-zinc-500">
      © {{ date('Y') }} Sistema Web de Control Escolar — Centro Universitario Moctezuma A.C.
    </footer>
  </div>
</section>
</body>
</html>
