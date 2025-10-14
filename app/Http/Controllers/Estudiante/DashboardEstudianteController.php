<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;

use App\Models\DashboardEstudiante;
use App\Http\Requests\StoreDashboardEstudianteRequest;
use App\Http\Requests\UpdateDashboardEstudianteRequest;

class DashboardEstudianteController extends Controller
{

    public function index()
    {
        return view('estudiante.dashboard');
    }


}
