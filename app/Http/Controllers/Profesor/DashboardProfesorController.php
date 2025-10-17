<?php

namespace App\Http\Controllers\Profesor;


use App\Http\Controllers\Controller;

use App\Models\DashboardProfesor;
use App\Http\Requests\StoreDashboardProfesorRequest;
use App\Http\Requests\UpdateDashboardProfesorRequest;

class DashboardProfesorController extends Controller
{


    public function index()
    {
        return view('profesor.dashboard');
    }


}
