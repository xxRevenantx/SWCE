<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Calificacion;
use App\Http\Requests\StoreCalificacionRequest;
use App\Http\Requests\UpdateCalificacionRequest;

class CalificacionController extends Controller
{

    public function calificaciones()
    {
        return view('admin.calificacion.index');
    }

}
