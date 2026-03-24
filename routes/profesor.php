<?php

use App\Http\Controllers\Profesor\ProfesorPDFController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::view('panel-profesor', 'profesor.dashboard')->middleware('can:profesor.dashboard')->name('profesor.dashboard'); // profesor.dashboard (URL /profesor/dashboard)
    Route::view('perfil-profesor', 'profesor.perfil')->middleware('can:profesor.perfil')->name('profesor.perfil'); // profesor.perfil (URL /profesor/perfil)
    Route::view('horario-profesor', 'profesor.horario')->middleware('can:profesor.horario')->name('profesor.horario'); // profesor.horario (URL /profesor/horario)
    Route::view('calificaciones-profesor', 'profesor.calificaciones')->middleware('can:profesor.calificaciones')->name('profesor.calificaciones'); // profesor.calificaciones (URL /profesor/calificaciones)


    // Horario PDF del profesor
    Route::get('/profesor/pdf/horario/{profesor}', [ProfesorPDFController::class, 'horario_profesor_pdf'])
        ->name('profesor.pdf.horario');
});
