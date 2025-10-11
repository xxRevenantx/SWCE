<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {


    Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');


    Route::resource('usuarios', UserController::class)->middleware('can:admin.usuarios')->names('admin.usuarios');

});

