<?php

use App\Http\Controllers\Admin\HorarioController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LicenciaturaController;
use App\Http\Controllers\Admin\MateriaController;
use App\Http\Controllers\Admin\ProfesorController;
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

    // GENERACIONES
    Route::get('generaciones', [GeneracionController::class, 'generaciones'])->middleware('can:admin.generaciones')->name('generaciones');
    Route::get('asignacion_generaciones', [GeneracionController::class, 'asignacion'])->middleware('can:admin.asignacion_generaciones')->name('asignacion_generaciones');


    Route::resource('profesores', ProfesorController::class)->middleware('can:admin.profesores')->names('profesores');
    //MATERIA
    Route::get('materias', [MateriaController::class, 'materia'])->middleware('can:admin.materias')->name('materias');

    Route::get('asignacion_materias', [MateriaController::class, 'asignacion'])->middleware('can:admin.asignacion_materias')->name('asignacion_materias');


    //HORARIO
    Route::resource('horarios', HorarioController::class)->middleware('can:admin.horarios')->names('horarios');


    //Inscripción
    Route::resource('inscripciones', InscripcionController::class)->middleware('can:admin.inscripciones')->names('inscripciones');
});
