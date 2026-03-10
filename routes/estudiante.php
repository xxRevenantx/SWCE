<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Estudiante\EstudiantePDFController;
use App\Http\Controllers\LicenciaturaController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::view('panel-estudiante', 'estudiante.dashboard')->middleware('can:estudiante.dashboard')->name('estudiante.dashboard'); // estudiante.dashboard (URL /estudiante/dashboard)

    Route::view('perfil', 'estudiante.perfil')->middleware('can:estudiante.perfil')->name('estudiante.perfil'); // estudiante.perfil (URL /estudiante/perfil)

    Route::view('horario', 'estudiante.horario')->middleware('can:estudiante.horario')->name('estudiante.horario'); // estudiante.horario (URL /estudiante/horario)


    Route::get('perfil/mi-expediente', [EstudiantePDFController::class, 'mi_expediente'])->middleware('can:estudiante.pdf.mi-expediente')->name('estudiante.pdf.mi-expediente');



});
