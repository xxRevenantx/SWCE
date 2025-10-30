<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mantenimiento</title>
  <meta name="theme-color" content="#0b0b0c" />
  <meta name="color-scheme" content="dark" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="icon" href="{{ asset('imagenes_publicas/logo-letra.png') }}" type="image/png">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @fluxAppearance

  <style>
    :root { color-scheme: dark; }
    body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans"; }

    /* Fondo oscuro con degradados suaves */
    .bg-dark-gradient{
      background-image:
        radial-gradient(28rem 28rem at 10% -10%, rgba(56,189,248,.18), transparent 60%),
        radial-gradient(32rem 32rem at 110% 110%, rgba(129,140,248,.18), transparent 60%),
        linear-gradient(180deg, #0b0b0c, #111114 60%, #0b0b0c 100%);
    }

    /* Barra indeterminada */
    @keyframes progress {
      0%   { transform: translateX(-100%); }
      50%  { transform: translateX(0%); }
      100% { transform: translateX(100%); }
    }
    .indeterminate { position: relative; overflow: hidden; }
    .indeterminate::before{
      content:""; position:absolute; inset:0; width:40%;
      background: linear-gradient(90deg, rgba(0,0,0,0), rgba(255,255,255,.18), rgba(0,0,0,0));
      animation: progress 1.8s ease-in-out infinite;
    }

    /* Flotación sutil para la ilustración */
    @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    .floaty { animation: floaty 6s ease-in-out infinite; }
  </style>
</head>

<body class="min-h-screen bg-dark-gradient text-zinc-100 antialiased selection:bg-sky-400/25">
  <section class="relative py-10 sm:py-16 lg:py-24">
    <!-- Glow decorativo -->
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
      <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-sky-500/20 blur-3xl"></div>
      <div class="absolute -bottom-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 md:px-6 lg:px-8">
      <!-- Header -->
      <header class="mb-10 flex items-center justify-center lg:justify-between">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
          <img src="{{ asset('imagenes_publicas/logo.png') }}" alt="Logo" class="h-15 w-auto sm:h-14" loading="eager" decoding="async">
          <span class="sr-only">Ir al inicio</span>
        </a>
        <div class="hidden lg:flex items-center gap-2 text-sm text-zinc-300">
          <span class="inline-flex items-center gap-2 rounded-full bg-amber-500/15 px-3 py-1.5 text-amber-200 ring-1 ring-amber-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M11 7h2v6h-2V7zm0 8h2v2h-2v-2z"/><path d="M12 2 1 21h22L12 2zM4.47 19 12 5.99 19.53 19H4.47z"/>
            </svg>
            Mantenimiento programado
          </span>
        </div>
      </header>

      <!-- Grid -->
      <div class="mx-auto grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-14">
        <!-- Columna texto -->
        <div class="flex items-center">
          <div class="w-full rounded-3xl border border-zinc-700/60 bg-zinc-900/60 p-6 backdrop-blur-xl shadow-xl sm:p-8 lg:p-10">
            <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-amber-500/15 px-3 py-1.5 text-xs font-medium text-amber-200 ring-1 ring-amber-500/30 lg:hidden">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M11 7h2v6h-2V7zm0 8h2v2h-2v-2z"/><path d="M12 2 1 21h22L12 2zM4.47 19 12 5.99 19.53 19H4.47z"/>
              </svg>
              Mantenimiento programado
            </div>

            <h1 class="text-balance text-center text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-left lg:text-5xl">
              ¡Gracias por tu paciencia! <span class="text-sky-400">Volvemos pronto</span>
            </h1>

            <p class="mt-4 text-pretty text-center text-base leading-relaxed text-zinc-300 sm:text-lg lg:text-left">
              Estamos mejorando la plataforma para brindarte una experiencia más rápida y segura.
              Este proceso tomará un corto tiempo. Puedes intentar recargar la página
              o contactarnos si necesitas ayuda inmediata.
            </p>

            <!-- Barra de progreso -->
            <div class="mt-6">
              <div class="h-2 w-full rounded-full bg-zinc-700 indeterminate"></div>
              <p class="mt-2 text-xs text-zinc-400">Progreso aproximado…</p>
            </div>




          </div>
        </div>

        <!-- Columna imagen -->
        <div class="flex items-center justify-center">
          <figure class="floaty w-full max-w-xl rounded-3xl border border-zinc-700/60 bg-zinc-900/60 p-4 backdrop-blur-xl shadow-xl">
            <img
              src="https://pagedone.io/asset/uploads/1718004199.png"
              alt="Ilustración de mantenimiento de sistema"
              class="h-auto w-full object-contain"
              loading="lazy" decoding="async">
          </figure>
        </div>
      </div>

      <!-- Footer -->
      <footer class="mx-auto mt-12 max-w-6xl text-center text-sm text-zinc-500">
        © {{ date('Y') }} Sistema Web de Control Escolar para el Centro Universitario Moctezuma A.C. — Todos los derechos reservados
      </footer>
    </div>
  </section>


</body>
</html>
