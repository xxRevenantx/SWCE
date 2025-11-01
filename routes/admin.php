<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LicenciaturaController;
use App\Http\Controllers\CuatrimestreController;
use App\Http\Controllers\GeneracionController;
use App\Http\Controllers\InscripcionController;
use Illuminate\Support\Facades\Route;



// routes/admin.php
Route::middleware(['auth'])->group(function () {



    Route::view('panel-administrador', 'admin.dashboard')->middleware('can:admin.dashboard')->name('admin.dashboard'); // admin.dashboard (URL /admin/dashboard)
    // ...más rutas del admin
        // Rutas del Admin
    Route::resource('usuarios', UserController::class)->middleware('can:admin.usuarios')->names('usuarios');
    Route::resource('licenciaturas', LicenciaturaController::class)->middleware('can:admin.licenciaturas')->names('licenciaturas');
    Route::resource('cuatrimestres', CuatrimestreController::class)->middleware('can:admin.cuatrimestres')->names('cuatrimestres');
    Route::resource('generaciones', GeneracionController::class)->middleware('can:admin.generaciones')->names('generaciones');


    //Inscripción
    Route::resource('inscripciones', InscripcionController::class)->middleware('can:admin.inscripciones')->names('inscripciones');

});
