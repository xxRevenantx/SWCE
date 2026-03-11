<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function mi_horario()
    {
        return view('estudiante.horario');
    }
}
