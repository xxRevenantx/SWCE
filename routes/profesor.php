<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LicenciaturaController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {


 Route::view('/dashboard', 'profesor.dashboard')->middleware(['verified', 'can:profesor.dashboard'])->name('dashboard');
                                    //directorio donde se cuenta el dashboard => profesor.dashboard

});

