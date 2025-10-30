<?php

namespace App\Http\Controllers;

use App\Models\Generacion;
use App\Http\Requests\StoreGeneracionRequest;
use App\Http\Requests\UpdateGeneracionRequest;

class GeneracionController extends Controller
{
    public function index()
    {
        return view('admin.generacion.index');
    }
}
