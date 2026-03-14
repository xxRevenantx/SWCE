<?php

use App\Http\Controllers\Estudiante\CalificacionesController;
use App\Http\Controllers\Estudiante\DashboardEstudianteController;
use App\Http\Controllers\Estudiante\EstudiantePDFController;
use App\Http\Controllers\Estudiante\HorarioController;
use App\Http\Controllers\Estudiante\PerfilController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('panel-estudiante', [DashboardEstudianteController::class, 'dashboard_estudiante'])->middleware('can:estudiante.dashboard')->name('estudiante.dashboard'); // estudiante.dashboard (URL /estudiante/dashboard)

    Route::get('mi-perfil', [PerfilController::class, 'mi_perfil'])->middleware('can:estudiante.perfil')->name('estudiante.perfil'); // estudiante.perfil (URL /estudiante/perfil)

    Route::get('mi-horario', [HorarioController::class, 'mi_horario'])->middleware('can:estudiante.horario')->name('estudiante.horario'); // estudiante.horario (URL /estudiante/horario)

    Route::get('mis-calificaciones', [CalificacionesController::class, 'mis_calificaciones'])->middleware('can:estudiante.calificaciones')->name('estudiante.calificaciones'); // estudiante.calificaciones (URL /estudiante/calificaciones)

    // Boleta de calificaciones
    Route::get('mis-calificaciones/mi-boleta/{cuatrimestre}', [EstudiantePDFController::class, 'mi_boleta'])->middleware('can:estudiante.pdf.mi-boleta')->name('estudiante.pdf.mi-boleta');


    Route::get('mi-perfil/mi-expediente', [EstudiantePDFController::class, 'mi_expediente'])->middleware('can:estudiante.pdf.mi-expediente')->name('estudiante.pdf.mi-expediente');


    Route::get('mi-horario/ver-horario', [EstudiantePDFController::class, 'mi_horario'])->middleware('can:estudiante.pdf.horario')->name('estudiante.pdf.horario');
});
