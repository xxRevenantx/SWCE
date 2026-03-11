<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalificacionesController extends Controller
{
    public function mis_calificaciones()
    {
        return view('estudiante.calificaciones');
    }
}
