<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::view('panel-profesor', 'profesor.dashboard')->middleware('can:profesor.dashboard')->name('profesor.dashboard'); // profesor.dashboard (URL /profesor/dashboard)
    Route::view('perfil-profesor', 'profesor.perfil')->middleware('can:profesor.perfil')->name('profesor.perfil'); // profesor.perfil (URL /profesor/perfil)
    Route::view('horario-profesor', 'profesor.horario')->middleware('can:profesor.horario')->name('profesor.horario'); // profesor.horario (URL /profesor/horario)

});
