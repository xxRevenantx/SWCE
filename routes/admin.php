<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LicenciaturaController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    // Dashboard del Admin
    // Route::view('/dashboard', 'dashboard')->name('dashboard');
        Route::view('/dashboard', 'admin.dashboard')->middleware(['verified', 'can:admin.dashboard'])->name('dashboard');
                                    //directorio donde se cuenta el dashboard => admin.dashboard
    // Rutas del Admin
    Route::resource('usuarios', UserController::class)->names('usuarios');
    Route::resource('licenciaturas', LicenciaturaController::class)->names('licenciaturas');
});
