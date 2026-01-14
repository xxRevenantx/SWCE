<?php

namespace App\Http\Controllers;

use App\Models\Generacion;
use App\Http\Requests\StoreGeneracionRequest;
use App\Http\Requests\UpdateGeneracionRequest;

class GeneracionController extends Controller
{

    // GENERACIONES
    public function generaciones()
    {
        return view('admin.generacion.index');
    }

    // ASIGNACION DE GENERACIONES
    public function asignacion()
    {
        return view('admin.asignar_generacion.index');
    }
}
