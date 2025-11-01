<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Http\Requests\StoreInscripcionRequest;
use App\Http\Requests\UpdateInscripcionRequest;

class InscripcionController extends Controller
{

    public function index()
    {
        return view('admin.inscripcion.index');
    }

}
