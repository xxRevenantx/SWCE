<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Licenciatura;
use App\Http\Requests\StoreLicenciaturaRequest;
use App\Http\Requests\UpdateLicenciaturaRequest;

class LicenciaturaController extends Controller
{

    public function index()
    {
        return view('admin.licenciatura.index');
    }

}
