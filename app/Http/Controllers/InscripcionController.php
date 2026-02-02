<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Http\Requests\StoreInscripcionRequest;
use App\Http\Requests\UpdateInscripcionRequest;

class InscripcionController extends Controller
{

    public function inscripcion()
    {
        return view('admin.inscripcion.index');
    }

    public function matricula()
    {
        return view('admin.matricula.index');
    }

    public function editarAlumno($id)
    {
        return view('admin.matricula.editar-alumno', compact('id'));
    }
}
