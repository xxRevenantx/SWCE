<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profesor;
use App\Http\Requests\StoreProfesorRequest;
use App\Http\Requests\UpdateProfesorRequest;

class ProfesorController extends Controller
{

    public function index()
    {
        return view('admin.profesor.index');
    }


}
