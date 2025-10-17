<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LicenciaturaController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
   Route::view('panel-estudiante', 'estudiante.dashboard')->middleware('can:estudiante.dashboard')->name('estudiante.dashboard'); // estudiante.dashboard (URL /estudiante/dashboard)


});

