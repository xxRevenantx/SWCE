<?php

use App\Http\Controllers\Admin\HorarioController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LicenciaturaController;
use App\Http\Controllers\Admin\MateriaController;
use App\Http\Controllers\Admin\PDFController;
use App\Http\Controllers\Admin\ProfesorController;
use App\Http\Controllers\CuatrimestreController;
use App\Http\Controllers\GeneracionController;
use App\Http\Controllers\InscripcionController;
use Illuminate\Support\Facades\Route;



// routes/admin.php
Route::middleware(['auth'])->group(function () {



    Route::view('panel-administrador', 'admin.dashboard')->middleware('can:admin.dashboard')->name('admin.dashboard'); // admin.dashboard (URL /admin/dashboard)
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
    Route::get('inscripciones', [InscripcionController::class, 'inscripcion'])->middleware('can:admin.inscripciones')->name('admin.inscripciones');

    // Matrícula
    Route::get('matricula', [InscripcionController::class, 'matricula'])->middleware('can:admin.matricula')->name('admin.matricula');

    Route::get('matricula/editar-alumno/{id}', [InscripcionController::class, 'editarAlumno'])->middleware('can:admin.matricula.editar_alumno')->name('admin.matricula.editar.alumno');

    // PDF

    // Expediente de alumno
    Route::get('matricula/expediente-alumno/{id}', [PDFController::class, 'expedienteAlumno'])->middleware('can:admin.pdf.expediente_alumno')->name('admin.pdf.expedienteAlumno');

    // Credencial de profesor
    Route::get('profesores/credencial/{id}', [PDFController::class, 'credencialProfesor'])->middleware('can:admin.pdf.credencial_profesor')->name('admin.profesores.credencial');

    // Lista de matrícula
    Route::get('matricula/lista/{filtrar_licenciatura?}/{filtrar_generacion?}/{filtrar_cuatrimestre?}/{search?}', [PDFController::class, 'listaMatricula'])->middleware('can:admin.pdf.lista_matricula')->name('admin.pdf.listaMatricula');
});
