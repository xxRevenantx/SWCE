<?php

// app/Http/Controllers/DashboardRouter.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRouter extends Controller
{

    public function __invoke(Request $request)
    {
        $u = $request->user();

        if (! $u) {
            return redirect()->route('login');
        }

        // permitir avanzar solo si el campo `status` del usuario es true
        if ($u->status == "false") {
            return response()->view('inactiva', ['message' => 'Cuenta inactiva.'], 403);
        }

        return match (true) {
            $u->hasRole('Admin')      => redirect()->route('admin.dashboard'),
            $u->hasRole('Profesor')   => redirect()->route('profesor.dashboard'),
            $u->hasRole('Estudiante') => redirect()->route('estudiante.dashboard'),
            default                   => redirect()->route('#'),
        };
    }
}
