<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Http\Requests\StoreCalificacionRequest;
use App\Http\Requests\UpdateCalificacionRequest;

class CalificacionController extends Controller
{

    public function index()
    {
        return view('admin.calificacion.index');
    }

}
