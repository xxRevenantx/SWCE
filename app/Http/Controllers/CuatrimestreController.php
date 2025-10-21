<?php

namespace App\Http\Controllers;

use App\Models\Cuatrimestre;
use App\Http\Requests\StoreCuatrimestreRequest;
use App\Http\Requests\UpdateCuatrimestreRequest;

class CuatrimestreController extends Controller
{
     public function index()
    {
        return view('admin.cuatrimestre.index');
    }
}
