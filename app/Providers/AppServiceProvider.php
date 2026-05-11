<?php

namespace App\Providers;

use App\Models\AsignarGeneracion;
use App\Observers\AsignarGeneracionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Se registra el observer para asignar automáticamente el orden.
        AsignarGeneracion::observe(AsignarGeneracionObserver::class);
    }
}
