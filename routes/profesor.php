<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::view('panel-profesor', 'profesor.dashboard')->middleware('can:profesor.dashboard')->name('profesor.dashboard'); // profesor.dashboard (URL /profesor/dashboard)
    // ...más rutas del profesor
});

