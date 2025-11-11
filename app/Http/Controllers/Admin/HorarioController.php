<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHorarioRequest;
use App\Http\Requests\UpdateHorarioRequest;

class HorarioController extends Controller
{

    public function index()
    {
        return view('admin.horario.index');
    }


}
