<?php

use App\Http\Controllers\Admin\CalificacionController;
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
    Route::get('usuarios', [UserController::class, 'usuarios'])->middleware('can:admin.usuarios')->name('admin.usuarios');
    Route::get('licenciaturas', [LicenciaturaController::class, 'licencenciaturas'])->middleware('can:admin.licenciaturas')->name('admin.licenciaturas');
    Route::get('cuatrimestres', [CuatrimestreController::class, 'cuatrimestres'])->middleware('can:admin.cuatrimestres')->name('admin.cuatrimestres');

    // GENERACIONES
    Route::get('generaciones', [GeneracionController::class, 'generaciones'])->middleware('can:admin.generaciones')->name('admin.generaciones');
    Route::get('asignacion_generaciones', [GeneracionController::class, 'asignacion'])->middleware('can:admin.asignacion_generaciones')->name('admin.asignacion_generaciones');

    // PROFESORES

    Route::get('profesores', [ProfesorController::class, 'profesores'])->middleware('can:admin.profesores')->name('admin.profesores');


    //MATERIA
    Route::get('materias', [MateriaController::class, 'materia'])->middleware('can:admin.materias')->name('admin.materias');

    Route::get('asignacion_materias', [MateriaController::class, 'asignacion'])->middleware('can:admin.asignacion_materias')->name('admin.asignacion_materias');


    //HORARIO
    Route::get('horarios', [HorarioController::class, 'horarios'])->middleware('can:admin.horarios')->name('admin.horarios');

    // CALIFICACIONES
    Route::get('calificaciones', [CalificacionController::class, 'calificaciones'])->middleware('can:admin.calificaciones')->name('admin.calificaciones');


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

    // Boleta de calificaciones
    Route::get('calificaciones/boleta/{id}/{cuatrimestre}', [PDFController::class, 'boletaCalificacion'])->middleware('can:admin.pdf.boleta_calificacion')->name('admin.pdf.boletaCalificacion');

    // Horario
    Route::get(
        'admin/horarios/pdf/licenciatura/{licenciatura:slug}/generacion/{generacion:generacion}/cuatrimestre/{cuatrimestre:slug}',
        [PDFController::class, 'horario']
    )
        ->middleware('can:admin.pdf.horario')
        ->name('admin.pdf.horario');



    // CALIFICACIONES GENERALES
    Route::get(
        'calificaciones/generales/licenciatura/{licenciatura}/generacion/{generacion}/cuatrimestre/{cuatrimestre}',
        [PDFController::class, 'calificacionesGenerales']
    )->middleware('can:admin.pdf.calificaciones')
        ->name('admin.pdf.calificaciones');


    // Lista de matrícula
    Route::get('matricula/lista/{filtrar_licenciatura?}/{filtrar_generacion?}/{filtrar_cuatrimestre?}/{search?}', [PDFController::class, 'listaMatricula'])->middleware('can:admin.pdf.lista_matricula')->name('admin.pdf.listaMatricula');
});
