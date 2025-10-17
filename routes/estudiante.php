<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LicenciaturaController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {


    // Route::view('estudiante/dashboard', 'estudiante.dashboard')->middleware('can:estudiante.dashboard')->middleware(['auth', 'verified'])->name('estudiante.dashboard');
    Route::view('/dashboard', 'estudiante.dashboard')->middleware(['verified', 'can:estudiante.dashboard'])->name('dashboard');

});

