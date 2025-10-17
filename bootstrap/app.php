<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // RUTAS ADMIN
            Route::middleware(['web', 'auth'])
                ->prefix('admin')             // prefijo en la URL → /admin/dashboard
                ->name('admin.')              // prefijo en el nombre → admin.dashboard
                ->group(base_path('routes/admin.php'));

            // RUTAS PROFESOR
            Route::middleware(['web', 'auth'])
                ->prefix('profesor')
                ->name('profesor.')
                ->group(base_path('routes/profesor.php'));

            // RUTAS ESTUDIANTE
            Route::middleware(['web', 'auth'])
                ->prefix('estudiante')
                ->name('estudiante.')
                ->group(base_path('routes/estudiante.php'));
        }

    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
